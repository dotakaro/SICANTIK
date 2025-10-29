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
    
    # Computed Fields
    can_sign = fields.Boolean(
        string='Dapat Ditandatangani',
        compute='_compute_can_sign'
    )
    can_download = fields.Boolean(
        string='Dapat Diunduh',
        compute='_compute_can_download'
    )
    
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
    
    @api.depends('document_number', 'file_hash')
    def _compute_verification_url(self):
        """Compute URL untuk verifikasi publik"""
        for record in self:
            if record.document_number and record.state in ['signed', 'verified']:
                base_url = self.env['ir.config_parameter'].sudo().get_param('web.base.url')
                record.verification_url = f'{base_url}/verify/{record.document_number}'
            else:
                record.verification_url = False
    
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
    
    @api.model
    def create(self, vals):
        """Override create untuk generate document number"""
        if vals.get('document_number', 'New') == 'New':
            vals['document_number'] = self.env['ir.sequence'].next_by_code('sicantik.document') or 'New'
        return super().create(vals)
    
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
            # Get MinIO connector
            minio_connector = self.env['minio.connector'].search([], limit=1)
            if not minio_connector:
                raise UserError('Konfigurasi MinIO tidak ditemukan')
            
            # Generate unique object name
            timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
            object_name = f'{self.permit_id.permit_number}/{timestamp}_{filename}'
            
            # Calculate file hash
            file_hash = hashlib.sha256(file_data).hexdigest()
            
            # Upload to MinIO
            result = minio_connector.upload_file(
                bucket_name=self.minio_bucket,
                object_name=object_name,
                file_data=file_data,
                content_type='application/pdf'
            )
            
            if result['success']:
                # Update record
                self.write({
                    'original_filename': filename,
                    'file_size': len(file_data),
                    'file_hash': file_hash,
                    'minio_object_name': object_name,
                    'state': 'uploaded',
                    'upload_date': fields.Datetime.now()
                })
                
                _logger.info(f'Document {self.document_number} uploaded to MinIO: {object_name}')
                
                return {
                    'success': True,
                    'message': f'Dokumen berhasil diupload ke MinIO',
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
        """Tandatangani dokumen dengan BSRE"""
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
            
            # Sign with BSRE
            sign_result = bsre_config.sign_document(
                document_data=file_data,
                document_name=self.original_filename
            )
            
            if sign_result['success']:
                # Upload signed document back to MinIO
                signed_object_name = self.minio_object_name.replace('.pdf', '_signed.pdf')
                upload_result = minio_connector.upload_file(
                    bucket_name=self.minio_bucket,
                    object_name=signed_object_name,
                    file_data=sign_result['signed_data'],
                    content_type='application/pdf'
                )
                
                if upload_result['success']:
                    # Generate QR Code
                    self._generate_qr_code()
                    
                    # Update record
                    self.write({
                        'state': 'signed',
                        'signature_date': fields.Datetime.now(),
                        'signer_id': self.env.user.id,
                        'signature_method': 'bsre',
                        'bsre_request_id': sign_result.get('request_id'),
                        'bsre_signature_id': sign_result.get('signature_id'),
                        'bsre_certificate': sign_result.get('certificate'),
                        'minio_object_name': signed_object_name,
                    })
                    
                    _logger.info(f'Document {self.document_number} signed with BSRE')
                    
                    return {
                        'type': 'ir.actions.client',
                        'tag': 'display_notification',
                        'params': {
                            'title': 'Berhasil',
                            'message': 'Dokumen berhasil ditandatangani dengan BSRE',
                            'type': 'success',
                            'sticky': False,
                        }
                    }
                else:
                    raise UserError('Gagal upload dokumen tertandatangani ke MinIO')
            else:
                raise UserError(f'Gagal tanda tangan BSRE: {sign_result.get("message")}')
                
        except Exception as e:
            _logger.error(f'Error signing document {self.document_number}: {str(e)}')
            raise UserError(f'Error tanda tangan dokumen: {str(e)}')
    
    def _generate_qr_code(self):
        """Generate QR code untuk verifikasi dokumen"""
        self.ensure_one()
        
        try:
            import qrcode
            from io import BytesIO
            
            # Prepare QR data
            qr_data = {
                'document_number': self.document_number,
                'permit_number': self.permit_number,
                'file_hash': self.file_hash,
                'signature_date': self.signature_date.isoformat() if self.signature_date else None,
                'verification_url': self.verification_url,
            }
            
            # Convert to string
            import json
            qr_string = json.dumps(qr_data)
            
            # Generate QR code
            qr = qrcode.QRCode(
                version=1,
                error_correction=qrcode.constants.ERROR_CORRECT_H,
                box_size=10,
                border=4,
            )
            qr.add_data(qr_string)
            qr.make(fit=True)
            
            # Create image
            img = qr.make_image(fill_color="black", back_color="white")
            
            # Convert to binary
            buffer = BytesIO()
            img.save(buffer, format='PNG')
            qr_image_data = base64.b64encode(buffer.getvalue())
            
            # Update record
            self.write({
                'qr_code_data': qr_string,
                'qr_code_image': qr_image_data,
            })
            
            _logger.info(f'QR code generated for document {self.document_number}')
            
        except Exception as e:
            _logger.error(f'Error generating QR code: {str(e)}')
            # Don't raise error, just log it
    
    def action_embed_qr_code(self):
        """Embed QR code ke dalam PDF"""
        self.ensure_one()
        
        if not self.qr_code_image:
            raise UserError('QR code belum digenerate')
        
        if self.qr_code_embedded:
            raise UserError('QR code sudah diembed ke dokumen')
        
        try:
            # TODO: Implement PDF manipulation to embed QR code
            # This will use PyPDF2 and reportlab
            
            self.write({'qr_code_embedded': True})
            
            return {
                'type': 'ir.actions.client',
                'tag': 'display_notification',
                'params': {
                    'title': 'Berhasil',
                    'message': 'QR code berhasil diembed ke dokumen',
                    'type': 'success',
                    'sticky': False,
                }
            }
            
        except Exception as e:
            _logger.error(f'Error embedding QR code: {str(e)}')
            raise UserError(f'Error embed QR code: {str(e)}')
    
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

