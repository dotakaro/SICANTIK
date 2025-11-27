# -*- coding: utf-8 -*-

from odoo import models, fields, api
from odoo.exceptions import UserError, ValidationError
import base64
import hashlib
import logging
from datetime import datetime

_logger = logging.getLogger(__name__)


class SicantikDocument(models.Model):
    """
    Model untuk menyimpan dokumen perizinan yang akan ditandatangani
    """
    _name = 'sicantik.document'
    _description = 'Dokumen Perizinan SICANTIK'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'create_date desc'
    _rec_name = 'document_number'
    
    # Document Information
    document_number = fields.Char(
        string='Nomor Dokumen',
        required=True,
        copy=False,
        readonly=True,
        default='New',
        tracking=True
    )
    name = fields.Char(
        string='Nama Dokumen',
        required=True,
        tracking=True
    )
    permit_id = fields.Many2one(
        'sicantik.permit',
        string='Izin Terkait',
        required=True,
        ondelete='restrict',
        tracking=True
    )
    permit_number = fields.Char(
        related='permit_id.permit_number',
        string='Nomor Izin',
        store=True
    )
    permit_type_id = fields.Many2one(
        related='permit_id.permit_type_id',
        string='Jenis Izin',
        store=True
    )
    
    # File Information
    original_filename = fields.Char(
        string='Nama File Asli',
        readonly=True
    )
    file_size = fields.Integer(
        string='Ukuran File (bytes)',
        readonly=True
    )
    file_hash = fields.Char(
        string='Hash File (SHA256)',
        readonly=True,
        help='Hash untuk verifikasi integritas dokumen'
    )
    
    # MinIO Storage
    minio_bucket = fields.Char(
        string='MinIO Bucket',
        readonly=True,
        default='sicantik-documents'
    )
    minio_object_name = fields.Char(
        string='MinIO Object Name',
        readonly=True,
        help='Path file di MinIO storage'
    )
    minio_url = fields.Char(
        string='MinIO URL',
        readonly=True,
        compute='_compute_minio_url',
        store=True
    )
    
    # Document Status
    state = fields.Selection([
        ('draft', 'Draft'),
        ('uploaded', 'Terupload'),
        ('pending_signature', 'Menunggu Tanda Tangan'),
        ('signed', 'Tertandatangani'),
        ('verified', 'Terverifikasi'),
        ('rejected', 'Ditolak'),
        ('cancelled', 'Dibatalkan'),
    ], string='Status', default='draft', required=True, tracking=True)
    
    # Signature Information
    signature_date = fields.Datetime(
        string='Tanggal Tanda Tangan',
        readonly=True,
        tracking=True
    )
    signer_id = fields.Many2one(
        'res.users',
        string='Penandatangan',
        readonly=True,
        tracking=True
    )
    signature_method = fields.Selection([
        ('bsre', 'BSRE TTE'),
        ('manual', 'Manual Upload'),
    ], string='Metode Tanda Tangan', readonly=True)
    
    # BSRE Information
    bsre_request_id = fields.Char(
        string='BSRE Request ID',
        readonly=True
    )
    bsre_signature_id = fields.Char(
        string='BSRE Signature ID',
        readonly=True
    )
    bsre_certificate = fields.Text(
        string='BSRE Certificate',
        readonly=True
    )
    bsre_signer_name = fields.Char(
        string='Nama Penandatangan (BSRE)',
        readonly=True,
        help='Nama penandatangan dari sertifikat BSRE'
    )
    bsre_signer_identifier = fields.Char(
        string='Identitas Penandatangan (BSRE)',
        readonly=True,
        help='NIK atau Email penandatangan dari BSRE'
    )
    
    # QR Code
    qr_code_data = fields.Text(
        string='QR Code Data',
        readonly=True,
        help='Data yang diencode dalam QR code'
    )
    qr_code_image = fields.Binary(
        string='QR Code Image',
        readonly=True,
        attachment=True
    )
    qr_code_embedded = fields.Boolean(
        string='QR Code Sudah Diembed',
        default=False,
        readonly=True
    )
    
    # Verification
    verification_url = fields.Char(
        string='URL Verifikasi',
        compute='_compute_verification_url',
        store=True
    )
    download_url = fields.Char(
        string='URL Download',
        compute='_compute_download_url',
        store=True,
        help='URL untuk download dokumen (untuk QR code)'
    )
    verification_count = fields.Integer(
        string='Jumlah Verifikasi',
        default=0,
        readonly=True
    )
    last_verified_date = fields.Datetime(
        string='Terakhir Diverifikasi',
        readonly=True
    )
    
    # Metadata
    uploaded_by = fields.Many2one(
        'res.users',
        string='Diupload Oleh',
        default=lambda self: self.env.user,
        readonly=True
    )
    upload_date = fields.Datetime(
        string='Tanggal Upload',
        default=fields.Datetime.now,
        readonly=True
    )
    notes = fields.Text(
        string='Catatan'
    )
    
    # Phone field for WhatsApp notifications
    mobile = fields.Char(
        string='Mobile',
        compute='_compute_mobile',
        readonly=True,
        store=False,
        help='Mobile number from signer or creator (for WhatsApp notifications)'
    )
    
    # Computed Fields
    can_sign = fields.Boolean(
        string='Dapat Ditandatangani',
        compute='_compute_can_sign'
    )
    can_download = fields.Boolean(
        string='Dapat Diunduh',
        compute='_compute_can_download'
    )
    
    @api.depends('signer_id', 'create_uid')
    def _compute_mobile(self):
        """Compute mobile number from signer or creator using safe accessor"""
        for record in self:
            mobile_number = False
            # Prioritize signer_id, fallback to create_uid
            if record.signer_id:
                # Try signer's partner first, then signer itself (if signer is a user)
                if hasattr(record.signer_id, 'partner_id') and record.signer_id.partner_id:
                    mobile_number = record.signer_id.partner_id._get_mobile_or_phone()
                elif hasattr(record.signer_id, '_get_mobile_or_phone'):
                    mobile_number = record.signer_id._get_mobile_or_phone()
            elif record.create_uid:
                # create_uid is res.users, try partner_id first
                if record.create_uid.partner_id:
                    mobile_number = record.create_uid.partner_id._get_mobile_or_phone()
                elif hasattr(record.create_uid, '_get_mobile_or_phone'):
                    mobile_number = record.create_uid._get_mobile_or_phone()
            record.mobile = mobile_number
    
    @api.depends('minio_bucket', 'minio_object_name')
    def _compute_minio_url(self):
        """Compute MinIO URL untuk download"""
        for record in self:
            if record.minio_bucket and record.minio_object_name:
                # Get MinIO config from environment or config
                minio_endpoint = self.env['ir.config_parameter'].sudo().get_param(
                    'sicantik_tte.minio_endpoint', 'localhost:9000'
                )
                record.minio_url = f'http://{minio_endpoint}/{record.minio_bucket}/{record.minio_object_name}'
            else:
                record.minio_url = False
    
    @api.depends('document_number', 'state')
    def _compute_verification_url(self):
        """Compute URL untuk verifikasi publik"""
        for record in self:
            if record.document_number and record.document_number != 'New' and record.state in ['signed', 'verified']:
                # Gunakan system parameter khusus atau default ke domain production
                base_url = self.env['ir.config_parameter'].sudo().get_param(
                    'sicantik_tte.verification_base_url',
                    default='https://sicantik.dotakaro.com'
                )
                # Pastikan tidak ada trailing slash
                base_url = base_url.rstrip('/')
                record.verification_url = f'{base_url}/sicantik/tte/verify/{record.document_number}'
            else:
                record.verification_url = False
    
    @api.depends('original_filename', 'state')
    def _compute_download_url(self):
        """Compute URL untuk download dokumen (untuk QR code)"""
        for record in self:
            # Note: 'id' tidak bisa di depends, tapi bisa diakses langsung di method
            if record.id and record.state in ['signed', 'verified']:
                # Get base URL dari system parameter atau gunakan default
                base_url = self.env['ir.config_parameter'].sudo().get_param(
                    'sicantik_tte.verification_base_url',
                    default='https://sicantik.dotakaro.com'
                )
                base_url = base_url.rstrip('/')
                # URL download menggunakan route yang sudah ada
                record.download_url = f'{base_url}/web/content/sicantik.document/{record.id}/download?filename={record.original_filename or "document.pdf"}'
            else:
                record.download_url = False
    
    @api.depends('state', 'minio_object_name')
    def _compute_can_sign(self):
        """Check apakah dokumen dapat ditandatangani"""
        for record in self:
            record.can_sign = (
                record.state == 'pending_signature' and
                record.minio_object_name and
                self.env.user.has_group('base.group_system')
            )
    
    @api.depends('state', 'minio_object_name')
    def _compute_can_download(self):
        """Check apakah dokumen dapat diunduh"""
        for record in self:
            record.can_download = (
                record.minio_object_name and
                record.state in ['uploaded', 'pending_signature', 'signed', 'verified']
            )
    
    @api.model_create_multi
    def create(self, vals_list):
        """Override create untuk generate document number"""
        # @api.model_create_multi always receives a list of dicts
        for vals in vals_list:
            if vals.get('document_number', 'New') == 'New':
                # Generate nomor dokumen dari sequence
                # Sequence akan otomatis increment dan tidak akan reset ke 00001
                document_number = self.env['ir.sequence'].next_by_code('sicantik.document') or 'New'
                vals['document_number'] = document_number
                _logger.info(f'[CREATE] Generated document_number: {document_number}')
        
        return super().create(vals_list)
    
    def write(self, vals):
        """Override write untuk mencegah perubahan document_number setelah create"""
        # Mencegah perubahan document_number setelah create
        if 'document_number' in vals:
            for record in self:
                # Jika document_number sudah ada dan bukan 'New', jangan izinkan perubahan
                if record.document_number and record.document_number != 'New' and vals['document_number'] != record.document_number:
                    _logger.warning(f'[WRITE] Attempt to change document_number from {record.document_number} to {vals["document_number"]} - BLOCKED')
                    raise ValidationError(
                        f'Tidak dapat mengubah nomor dokumen yang sudah ada. '
                        f'Nomor dokumen "{record.document_number}" tidak dapat diubah menjadi "{vals["document_number"]}".'
                    )
        
        return super().write(vals)
    
    def action_upload_to_minio(self, file_data, filename):
        """
        Upload file ke MinIO storage
        
        Args:
            file_data (bytes): Binary data file
            filename (str): Nama file
        
        Returns:
            dict: Result with success status and message
        """
        self.ensure_one()
        
        try:
            # Get MinIO connector (prioritize active one)
            minio_connector = self.env['minio.connector'].search([
                ('active', '=', True)
            ], limit=1)
            if not minio_connector:
                # Fallback to any connector if no active one
                minio_connector = self.env['minio.connector'].search([], limit=1)
            if not minio_connector:
                raise UserError('Konfigurasi MinIO tidak ditemukan. Silakan buat konfigurasi MinIO terlebih dahulu.')
            
            # Determine bucket name
            bucket_name = self.minio_bucket or minio_connector.default_bucket or 'sicantik-documents'
            if not bucket_name:
                raise UserError('Nama bucket tidak ditemukan. Silakan set bucket name di dokumen atau konfigurasi MinIO.')
            
            # Generate filename dari nama dokumen (format: Nama Pemohon - Nomor Izin - Jenis Izin.pdf)
            # Jika nama dokumen sudah ada, gunakan sebagai nama file
            if self.name:
                # Bersihkan nama dokumen dari karakter yang tidak valid untuk filename
                safe_filename = self.name.replace('/', '-').replace('\\', '-').replace(':', '-')
                safe_filename = safe_filename.replace('*', '').replace('?', '').replace('"', '').replace('<', '').replace('>', '').replace('|', '')
                # Pastikan extension .pdf
                if not safe_filename.lower().endswith('.pdf'):
                    safe_filename = f'{safe_filename}.pdf'
                final_filename = safe_filename
            else:
                # Fallback ke filename asli jika nama dokumen tidak ada
                final_filename = filename
            
            # Generate unique object name dengan timestamp untuk menghindari konflik
            timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
            object_name = f'{self.permit_id.permit_number}/{timestamp}_{final_filename}'
            
            # Calculate file hash
            file_hash = hashlib.sha256(file_data).hexdigest()
            
            _logger.info(f'Uploading document: bucket={bucket_name}, object={object_name}, filename={final_filename}, size={len(file_data)} bytes')
            
            # CRITICAL: Embed QR code SEBELUM upload ke MinIO
            # QR code harus di-embed sebelum signing karena PDF yang sudah signed tidak boleh dimodifikasi
            _logger.info(f'[UPLOAD] Embed QR code ke PDF sebelum upload ke MinIO...')
            
            # Generate QR code terlebih dahulu
            self._compute_verification_url()
            self._generate_qr_code()
            
            # Embed QR code ke PDF
            pdf_with_qr = file_data
            try:
                if self.qr_code_image:
                    pdf_with_qr = self._embed_qr_code_to_pdf(file_data)
                    _logger.info(f'[UPLOAD] ✅ QR code berhasil di-embed ke PDF')
                else:
                    _logger.warning(f'[UPLOAD] ⚠️ QR code belum digenerate, upload tanpa QR code')
            except Exception as qr_error:
                _logger.error(f'[UPLOAD] ❌ Error embed QR code: {str(qr_error)}, upload tanpa QR code')
                # Continue dengan file asli jika embed QR code gagal
            
            # Upload to MinIO (dengan QR code sudah di-embed)
            result = minio_connector.upload_file(
                bucket_name=bucket_name,
                object_name=object_name,
                file_data=pdf_with_qr,
                content_type='application/pdf'
            )
            
            if result['success']:
                # Update record - gunakan final_filename untuk original_filename
                self.write({
                    'original_filename': final_filename,
                    'file_size': len(pdf_with_qr),
                    'file_hash': hashlib.sha256(pdf_with_qr).hexdigest(),  # Recalculate hash dengan QR code
                    'minio_bucket': bucket_name,  # Ensure bucket name is saved
                    'minio_object_name': object_name,
                    'state': 'uploaded',
                    'upload_date': fields.Datetime.now(),
                    'qr_code_embedded': True  # Mark bahwa QR code sudah di-embed
                })
                
                _logger.info(f'[UPLOAD] ✅ Document {self.document_number} uploaded to MinIO dengan QR code: {object_name}')
                
                return {
                    'success': True,
                    'message': f'Dokumen berhasil diupload ke MinIO dengan QR code',
                    'object_name': object_name
                }
            else:
                raise UserError(f'Upload gagal: {result.get("message")}')
                
        except Exception as e:
            _logger.error(f'Error uploading document {self.document_number}: {str(e)}')
            raise UserError(f'Error upload dokumen: {str(e)}')
    
    def action_download_from_minio(self):
        """Download file dari MinIO storage"""
        self.ensure_one()
        
        if not self.minio_object_name:
            raise UserError('Dokumen belum diupload ke MinIO')
        
        try:
            # Get MinIO connector
            minio_connector = self.env['minio.connector'].search([], limit=1)
            if not minio_connector:
                raise UserError('Konfigurasi MinIO tidak ditemukan')
            
            # Download from MinIO
            result = minio_connector.download_file(
                bucket_name=self.minio_bucket,
                object_name=self.minio_object_name
            )
            
            if result['success']:
                # Return file as attachment
                return {
                    'type': 'ir.actions.act_url',
                    'url': f'/web/content/sicantik.document/{self.id}/download?filename={self.original_filename}',
                    'target': 'self',
                }
            else:
                raise UserError(f'Download gagal: {result.get("message")}')
                
        except Exception as e:
            _logger.error(f'Error downloading document {self.document_number}: {str(e)}')
            raise UserError(f'Error download dokumen: {str(e)}')
    
    def action_request_signature(self):
        """Request tanda tangan digital via BSRE"""
        self.ensure_one()
        
        if self.state != 'uploaded':
            raise UserError('Dokumen harus dalam status "Terupload" untuk diminta tanda tangan')
        
        # Create signature workflow
        workflow = self.env['signature.workflow'].create({
            'document_id': self.id,
            'state': 'pending',
        })
        
        # Update document state
        self.write({'state': 'pending_signature'})
        
        return {
            'type': 'ir.actions.act_window',
            'name': 'Workflow Tanda Tangan',
            'res_model': 'signature.workflow',
            'res_id': workflow.id,
            'view_mode': 'form',
            'target': 'current',
        }
    
    def action_sign_with_bsre(self):
        """Open wizard untuk entry passphrase dan sign dokumen dengan BSRE"""
        self.ensure_one()
        
        if self.state != 'pending_signature':
            raise UserError('Dokumen harus dalam status "Menunggu Tanda Tangan"')
        
        # Get BSRE configuration
        bsre_config = self.env['bsre.config'].search([('active', '=', True)], limit=1)
        if not bsre_config:
            raise UserError('Konfigurasi BSRE tidak ditemukan')
        
        # Open passphrase wizard
        return {
            'name': 'Tandatangani Dokumen',
            'type': 'ir.actions.act_window',
            'res_model': 'sign.passphrase.wizard',
            'view_mode': 'form',
            'target': 'new',
            'context': {
                'default_document_id': self.id,
                'default_bsre_config_id': bsre_config.id,
            }
        }
    
    def action_sign_with_bsre_internal(self, passphrase):
        """
        Internal method untuk sign dokumen dengan BSRE (dipanggil dari wizard)
        
        Args:
            passphrase (str): Passphrase dari user untuk signing
        """
        self.ensure_one()
        
        if self.state != 'pending_signature':
            raise UserError('Dokumen harus dalam status "Menunggu Tanda Tangan"')
        
        try:
            # Get BSRE connector
            bsre_config = self.env['bsre.config'].search([('active', '=', True)], limit=1)
            if not bsre_config:
                raise UserError('Konfigurasi BSRE tidak ditemukan')
            
            # Download file from MinIO
            minio_connector = self.env['minio.connector'].search([], limit=1)
            download_result = minio_connector.download_file(
                bucket_name=self.minio_bucket,
                object_name=self.minio_object_name
            )
            
            if not download_result['success']:
                raise UserError('Gagal download dokumen dari MinIO')
            
            file_data = download_result['data']
            
            # Sign with BSRE (dengan passphrase dari user)
            # Ambil nama jenis izin untuk default reason
            permit_type_name = None
            if self.permit_type_id:
                permit_type_name = self.permit_type_id.name
            
            sign_result = bsre_config.sign_document(
                document_data=file_data,
                document_name=self.original_filename,
                passphrase=passphrase,  # Passphrase dari wizard
                permit_type_name=permit_type_name  # Nama jenis izin untuk default reason
            )
            
            if sign_result['success']:
                # CRITICAL: Gunakan file signed dari BSRE API response
                # File ini sudah ditandatangani secara digital dan TIDAK BOLEH dimodifikasi
                signed_file_data = sign_result['signed_data']
                
                # Validasi bahwa file adalah PDF yang valid
                if not signed_file_data or len(signed_file_data) < 100:
                    raise UserError('File signed dari BSRE API tidak valid atau kosong')
                
                # Validasi bahwa file dimulai dengan PDF header
                if not signed_file_data.startswith(b'%PDF'):
                    _logger.error(f'[SIGN] ❌ File signed tidak dimulai dengan PDF header! File mungkin tidak valid.')
                    _logger.error(f'[SIGN] First 50 bytes: {signed_file_data[:50]}')
                    raise UserError('File signed dari BSRE API bukan PDF yang valid')
                
                _logger.info(f'[SIGN] ✅ File signed dari BSRE API valid: {len(signed_file_data)} bytes, dimulai dengan PDF header')
                
                # Upload signed document back to MinIO dengan nama <nama dokumen>-signed.pdf
                # Format nama: <nama dokumen>-signed.pdf
                if self.name:
                    # Bersihkan nama dokumen dari karakter yang tidak valid
                    safe_name = self.name.replace('/', '-').replace('\\', '-').replace(':', '-')
                    safe_name = safe_name.replace('*', '').replace('?', '').replace('"', '').replace('<', '').replace('>', '').replace('|', '')
                    signed_filename = f'{safe_name}-signed.pdf'
                else:
                    # Fallback ke original filename dengan suffix -signed
                    signed_filename = self.original_filename.replace('.pdf', '-signed.pdf') if self.original_filename else 'document-signed.pdf'
                
                # Generate object name dengan timestamp untuk menghindari konflik
                timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
                signed_object_name = f'{self.permit_id.permit_number}/{timestamp}_{signed_filename}'
                
                upload_result = minio_connector.upload_file(
                    bucket_name=self.minio_bucket,
                    object_name=signed_object_name,
                    file_data=signed_file_data,
                    content_type='application/pdf'
                )
                
                if upload_result['success']:
                    # Update record dengan state signed terlebih dahulu
                    self.write({
                        'state': 'signed',
                        'signature_date': fields.Datetime.now(),
                        'signer_id': self.env.user.id,  # User yang melakukan signing (untuk audit)
                        'signature_method': 'bsre',
                        'bsre_request_id': sign_result.get('request_id'),
                        'bsre_signature_id': sign_result.get('signature_id'),
                        'bsre_certificate': sign_result.get('certificate'),
                        'minio_object_name': signed_object_name,
                    })
                    
                    # Verifikasi dokumen yang baru ditandatangani untuk mendapatkan informasi penandatangan dari BSRE
                    _logger.info(f'[SIGN] Memverifikasi dokumen yang baru ditandatangani untuk mendapatkan informasi penandatangan...')
                    try:
                        verify_result = bsre_config.verify_signature(sign_result['signed_data'])
                        
                        if verify_result.get('success') and verify_result.get('valid'):
                            _logger.info(f'[SIGN] ✅ Verifikasi berhasil, mengambil informasi penandatangan dari BSRE')
                            
                            update_vals = {}
                            
                            # Ambil informasi penandatangan dari response verify
                            signer_info = verify_result.get('signer')
                            if signer_info:
                                if isinstance(signer_info, dict):
                                    signer_name = signer_info.get('name') or signer_info.get('nama') or signer_info.get('pemilik') or ''
                                    signer_identifier = signer_info.get('nik') or signer_info.get('email') or signer_info.get('identifier') or ''
                                elif isinstance(signer_info, str):
                                    signer_name = signer_info
                                    signer_identifier = bsre_config.signing_identifier or ''
                                else:
                                    signer_name = str(signer_info)
                                    signer_identifier = bsre_config.signing_identifier or ''
                                
                                if signer_name:
                                    update_vals['bsre_signer_name'] = signer_name
                                    _logger.info(f'[SIGN] - bsre_signer_name dari verify: {signer_name}')
                                if signer_identifier:
                                    update_vals['bsre_signer_identifier'] = signer_identifier
                                    _logger.info(f'[SIGN] - bsre_signer_identifier dari verify: {signer_identifier}')
                            
                            # Ambil dari certificate jika signer tidak ada
                            if not update_vals.get('bsre_signer_name'):
                                certificate_info = verify_result.get('certificate')
                                if certificate_info and isinstance(certificate_info, dict):
                                    cert_owner = certificate_info.get('owner') or certificate_info.get('name') or certificate_info.get('pemilik') or certificate_info.get('subject') or ''
                                    if cert_owner:
                                        update_vals['bsre_signer_name'] = cert_owner
                                        _logger.info(f'[SIGN] - bsre_signer_name dari certificate: {cert_owner}')
                            
                            # Fallback ke BSRE config jika verify tidak memberikan informasi
                            if not update_vals.get('bsre_signer_name'):
                                if bsre_config.certificate_owner:
                                    update_vals['bsre_signer_name'] = bsre_config.certificate_owner
                                    _logger.info(f'[SIGN] - bsre_signer_name dari config: {bsre_config.certificate_owner}')
                            
                            # Pastikan signing_identifier selalu di-set
                            if not update_vals.get('bsre_signer_identifier') and bsre_config.signing_identifier:
                                update_vals['bsre_signer_identifier'] = bsre_config.signing_identifier
                                _logger.info(f'[SIGN] - bsre_signer_identifier dari config: {bsre_config.signing_identifier}')
                            
                            # Update dokumen dengan informasi penandatangan
                            if update_vals:
                                self.write(update_vals)
                                _logger.info(f'[SIGN] Dokumen di-update dengan informasi penandatangan: {update_vals}')
                        else:
                            _logger.warning(f'[SIGN] ⚠️ Verifikasi gagal atau tanda tangan tidak valid, menggunakan informasi dari config')
                            # Fallback: gunakan informasi dari config
                            if bsre_config.certificate_owner:
                                self.write({'bsre_signer_name': bsre_config.certificate_owner})
                            if bsre_config.signing_identifier:
                                self.write({'bsre_signer_identifier': bsre_config.signing_identifier})
                    except Exception as verify_error:
                        _logger.error(f'[SIGN] ❌ Error saat verifikasi untuk mendapatkan informasi penandatangan: {str(verify_error)}', exc_info=True)
                        # Fallback: gunakan informasi dari config
                        if bsre_config.certificate_owner:
                            self.write({'bsre_signer_name': bsre_config.certificate_owner})
                        if bsre_config.signing_identifier:
                            self.write({'bsre_signer_identifier': bsre_config.signing_identifier})
                    
                    _logger.info(f'[SIGN] Dokumen {self.document_number} berhasil ditandatangani')
                    _logger.info(f'[SIGN] - bsre_signer_name: {self.bsre_signer_name}')
                    _logger.info(f'[SIGN] - bsre_signer_identifier: {self.bsre_signer_identifier}')
                    _logger.info(f'[SIGN] ✅ File signed disimpan dengan nama: {signed_object_name}')
                    _logger.info(f'[SIGN] QR code sudah di-embed sebelum signing, signature digital tetap utuh')
                    _logger.info(f'Document {self.document_number} signed with BSRE')
                    
                    return {
                        'success': True,
                        'message': f'Dokumen {self.document_number} berhasil ditandatangani dengan BSRE dan QR code'
                    }
                else:
                    return {
                        'success': False,
                        'message': 'Gagal upload dokumen tertandatangani ke MinIO'
                    }
            else:
                return {
                    'success': False,
                    'message': f'Gagal tanda tangan BSRE: {sign_result.get("message")}'
                }
                
        except Exception as e:
            _logger.error(f'Error signing document {self.document_number}: {str(e)}')
            return {
                'success': False,
                'message': f'Error tanda tangan dokumen: {str(e)}'
            }
    
    def _generate_qr_code(self):
        """Generate QR code untuk verifikasi dokumen"""
        self.ensure_one()
        
        try:
            import qrcode
            from io import BytesIO
            
            # Pastikan verification_url sudah di-compute
            self._compute_verification_url()
            
            # Gunakan verification_url yang sudah di-compute (lebih konsisten)
            verification_url = self.verification_url
            
            # Fallback jika verification_url belum tersedia
            if not verification_url:
                base_url = self.env['ir.config_parameter'].sudo().get_param(
                    'sicantik_tte.verification_base_url',
                    default='https://sicantik.dotakaro.com'
                )
                base_url = base_url.rstrip('/')
                verification_url = f'{base_url}/sicantik/tte/verify/{self.document_number}'
            
            _logger.info(f'Generating QR code untuk dokumen {self.document_number} dengan verification URL: {verification_url}')
            
            # Generate QR code dengan URL verifikasi (akan mengarah ke halaman verifikasi)
            qr = qrcode.QRCode(
                version=1,
                error_correction=qrcode.constants.ERROR_CORRECT_H,
                box_size=10,
                border=4,
            )
            qr.add_data(verification_url)
            qr.make(fit=True)
            
            # Create image
            img = qr.make_image(fill_color="black", back_color="white")
            
            # Convert to binary
            buffer = BytesIO()
            img.save(buffer, format='PNG')
            qr_image_data = base64.b64encode(buffer.getvalue())
            
            # Update record - simpan verification_url di qr_code_data
            self.write({
                'qr_code_data': verification_url,  # Simpan verification URL untuk reference
                'qr_code_image': qr_image_data,
            })
            
            _logger.info(f'QR code generated for document {self.document_number}: {verification_url}')
            
        except Exception as e:
            _logger.error(f'❌ Error generating QR code untuk dokumen {self.document_number}: {str(e)}', exc_info=True)
            # Don't raise error, just log it
    
    def _embed_qr_code_to_pdf(self, pdf_data):
        """
        Helper method untuk embed QR code ke PDF data
        Mengembalikan PDF data dengan QR code yang sudah di-embed
        
        Args:
            pdf_data (bytes): Binary PDF data
        
        Returns:
            bytes: PDF data dengan QR code yang sudah di-embed
        """
        if not self.qr_code_image:
            _logger.warning(f'QR code belum digenerate, return PDF as-is')
            return pdf_data
        
        try:
            from PyPDF2 import PdfReader, PdfWriter
            from reportlab.pdfgen import canvas
            from reportlab.lib.units import inch
            from reportlab.lib.utils import ImageReader
            from io import BytesIO
            from PIL import Image
            
            # Decode QR code image
            qr_image_bytes = base64.b64decode(self.qr_code_image)
            qr_image = Image.open(BytesIO(qr_image_bytes))
            
            # Convert PIL Image to ImageReader for reportlab
            qr_buffer = BytesIO()
            qr_image.save(qr_buffer, format='PNG')
            qr_buffer.seek(0)
            qr_img_reader = ImageReader(qr_buffer)
            
            # Read existing PDF
            existing_pdf = PdfReader(BytesIO(pdf_data))
            output = PdfWriter()
            
            # Process each page
            for page_num in range(len(existing_pdf.pages)):
                page = existing_pdf.pages[page_num]
                
                # Get page dimensions
                page_width = float(page.mediabox.width)
                page_height = float(page.mediabox.height)
                
                # Create overlay with QR code
                packet = BytesIO()
                can = canvas.Canvas(packet, pagesize=(page_width, page_height))
                
                # QR code size and position (BOTTOM LEFT to avoid BSRE signature)
                qr_size = 1.2 * inch  # 1.2 inch = ~3 cm
                margin = 0.3 * inch   # 0.3 inch margin from edges
                
                # Position: BOTTOM LEFT corner (BSRE signature di kanan)
                x_position = margin
                y_position = margin
                
                # Draw QR code
                can.drawImage(
                    qr_img_reader,
                    x_position,
                    y_position,
                    width=qr_size,
                    height=qr_size,
                    preserveAspectRatio=True,
                    mask='auto'
                )
                
                # Add verification text below QR code
                can.setFont("Helvetica", 7)
                can.setFillColorRGB(0, 0, 0)
                text_y = y_position - 0.15 * inch
                can.drawString(
                    x_position,
                    text_y,
                    f"Scan untuk verifikasi"
                )
                can.drawString(
                    x_position,
                    text_y - 0.12 * inch,
                    f"Doc: {self.document_number}"
                )
                can.save()
                
                # Merge overlay with page
                packet.seek(0)
                overlay_pdf = PdfReader(packet)
                page.merge_page(overlay_pdf.pages[0])
                
                # Add to output
                output.add_page(page)
            
            # Write output PDF
            output_buffer = BytesIO()
            output.write(output_buffer)
            output_buffer.seek(0)
            return output_buffer.read()
            
        except Exception as e:
            _logger.error(f'Error embedding QR code to PDF: {str(e)}', exc_info=True)
            # Return original PDF if embedding fails
            return pdf_data
    
    def action_embed_qr_code(self):
        """
        Embed QR code ke dalam PDF signed document
        QR Code akan ditambahkan di pojok kanan bawah setiap halaman
        """
        self.ensure_one()
        
        if not self.qr_code_image:
            raise UserError('QR code belum digenerate')
        
        if self.qr_code_embedded:
            _logger.info(f'QR code already embedded for document {self.document_number}')
            return True
        
        try:
            from PyPDF2 import PdfReader, PdfWriter
            from reportlab.pdfgen import canvas
            from reportlab.lib.pagesizes import letter
            from reportlab.lib.units import inch
            from reportlab.lib.utils import ImageReader
            from io import BytesIO
            from PIL import Image
            
            # Get MinIO connector
            minio_connector = self.env['minio.connector'].search([], limit=1)
            if not minio_connector:
                raise UserError('Konfigurasi MinIO tidak ditemukan')
            
            # Download signed PDF from MinIO
            download_result = minio_connector.download_file(
                bucket_name=self.minio_bucket,
                object_name=self.minio_object_name
            )
            
            if not download_result['success']:
                raise UserError('Gagal download dokumen dari MinIO')
            
            pdf_data = download_result['data']
            
            # Decode QR code image
            qr_image_bytes = base64.b64decode(self.qr_code_image)
            qr_image = Image.open(BytesIO(qr_image_bytes))
            
            # Convert PIL Image to ImageReader for reportlab
            qr_buffer = BytesIO()
            qr_image.save(qr_buffer, format='PNG')
            qr_buffer.seek(0)
            qr_img_reader = ImageReader(qr_buffer)
            
            # Read existing PDF
            existing_pdf = PdfReader(BytesIO(pdf_data))
            output = PdfWriter()
            
            # Process each page
            for page_num in range(len(existing_pdf.pages)):
                page = existing_pdf.pages[page_num]
                
                # Get page dimensions
                page_width = float(page.mediabox.width)
                page_height = float(page.mediabox.height)
                
                # Create overlay with QR code
                packet = BytesIO()
                can = canvas.Canvas(packet, pagesize=(page_width, page_height))
                
                # QR code size and position (BOTTOM LEFT to avoid BSRE signature)
                qr_size = 1.2 * inch  # 1.2 inch = ~3 cm
                margin = 0.3 * inch   # 0.3 inch margin from edges
                
                # Position: BOTTOM LEFT corner (BSRE signature di kanan)
                x_position = margin
                y_position = margin
                
                # Draw QR code
                can.drawImage(
                    qr_img_reader,
                    x_position,
                    y_position,
                    width=qr_size,
                    height=qr_size,
                    preserveAspectRatio=True,
                    mask='auto'
                )
                
                # Add verification text below QR code
                can.setFont("Helvetica", 7)
                can.setFillColorRGB(0, 0, 0)
                text_y = y_position - 0.15 * inch
                can.drawString(
                    x_position,
                    text_y,
                    f"Scan untuk verifikasi"
                )
                can.drawString(
                    x_position,
                    text_y - 0.12 * inch,
                    f"Doc: {self.document_number}"
                )
                
                can.save()
                
                # Merge overlay with page
                packet.seek(0)
                overlay_pdf = PdfReader(packet)
                page.merge_page(overlay_pdf.pages[0])
                
                # Add to output
                output.add_page(page)
            
            # Write output PDF
            output_buffer = BytesIO()
            output.write(output_buffer)
            output_buffer.seek(0)
            final_pdf_data = output_buffer.read()
            
            # Upload back to MinIO (replace the signed file)
            upload_result = minio_connector.upload_file(
                bucket_name=self.minio_bucket,
                object_name=self.minio_object_name,
                file_data=final_pdf_data,
                content_type='application/pdf'
            )
            
            if upload_result['success']:
                # Update record
                self.write({
                    'qr_code_embedded': True,
                    'file_size': len(final_pdf_data)
                })
                
                _logger.info(f'QR code embedded successfully for document {self.document_number}')
                
                return True
            else:
                raise UserError('Gagal upload dokumen dengan QR code ke MinIO')
            
        except Exception as e:
            _logger.error(f'Error embedding QR code: {str(e)}', exc_info=True)
            raise UserError(f'Error embed QR code: {str(e)}')
    
    def action_open_upload_wizard(self):
        """Buka wizard upload dokumen baru"""
        # Bisa dipanggil dari form view yang sudah ada record atau dari action
        return {
            'type': 'ir.actions.act_window',
            'name': 'Upload Dokumen',
            'res_model': 'document.upload.wizard',
            'view_mode': 'form',
            'target': 'new',
            'context': {}
        }
    
    def action_cancel(self):
        """Cancel dokumen"""
        self.ensure_one()
        
        if self.state == 'signed':
            raise UserError('Dokumen yang sudah ditandatangani tidak dapat dibatalkan')
        
        self.write({'state': 'cancelled'})
        
        return {
            'type': 'ir.actions.client',
            'tag': 'display_notification',
            'params': {
                'title': 'Dibatalkan',
                'message': 'Dokumen telah dibatalkan',
                'type': 'warning',
                'sticky': False,
            }
        }

