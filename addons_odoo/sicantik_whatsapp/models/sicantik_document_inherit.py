# -*- coding: utf-8 -*-

from odoo import models, fields, api, _
from odoo.exceptions import UserError
from datetime import datetime, timedelta
import logging

_logger = logging.getLogger(__name__)


class SicantikDocument(models.Model):
    """
    Inherit model sicantik.document untuk menambahkan trigger notifikasi WhatsApp
    """
    _inherit = 'sicantik.document'
    
    def write(self, vals):
        """Override untuk trigger notifikasi WhatsApp saat status dokumen berubah"""
        result = super().write(vals)
        
        # Cek jika dokumen baru diupload dan status menjadi 'pending_signature'
        if vals.get('state') == 'pending_signature':
            self._kirim_notifikasi_dokumen_baru()
        
        # Cek jika dokumen perlu approval
        if vals.get('state') == 'pending_signature' and vals.get('requires_approval'):
            self._kirim_notifikasi_perlu_approval()
        
        return result
    
    def _kirim_notifikasi_dokumen_baru(self):
        """
        Kirim notifikasi WhatsApp ke staff saat dokumen baru masuk untuk ditandatangani
        Template: document_pending
        
        Menggunakan dispatcher multi-provider untuk routing otomatis.
        """
        for record in self:
            # Cari staff yang bertanggung jawab (bisa dari workflow atau default)
            # Untuk sementara, kita kirim ke semua user internal dengan nomor WhatsApp
            # Field mobile ada di res.partner, bukan res.users, jadi kita filter manual
            all_staff = self.env['res.users'].search([
                ('share', '=', False),
                ('active', '=', True)
            ])
            
            # Filter manual untuk user yang punya phone di partner_id
            # Di Odoo 18.4, hanya ada field 'phone', tidak ada 'mobile'
            def _has_phone(user):
                if not user.partner_id:
                    return False
                # Gunakan method _get_mobile_or_phone() jika ada, atau langsung akses phone
                if hasattr(user.partner_id, '_get_mobile_or_phone'):
                    try:
                        return bool(user.partner_id._get_mobile_or_phone())
                    except:
                        pass
                # Fallback: akses phone langsung dengan safe access
                return bool(getattr(user.partner_id, 'phone', False))
            
            staff_users = all_staff.filtered(_has_phone)[:5]  # Limit untuk menghindari spam
            
            if not staff_users:
                _logger.warning('Tidak ada staff dengan nomor WhatsApp untuk notifikasi dokumen baru')
                return
            
            # Hitung jumlah dokumen pending
            jumlah_pending = self.env['sicantik.document'].search_count([
                ('state', '=', 'pending_signature')
            ])
            
            # Gunakan dispatcher untuk routing otomatis
            dispatcher = self.env['sicantik.whatsapp.dispatcher']
            
            # Prepare context values
            permit_type_name = record.permit_type_id.name if record.permit_type_id else ''
            applicant_name = record.permit_id.applicant_name if record.permit_id else ''
            pendaftaran_id = record.permit_id.registration_id if record.permit_id else record.document_number or ''
            link_dashboard = 'https://sicantik.dotakaro.com/dashboard'
            
            context_values = {
                'jumlah': str(jumlah_pending),
                'jenis_izin': permit_type_name,
                'nama_pemohon': applicant_name,
                'pendaftaran_id': pendaftaran_id,
                'link_dashboard': link_dashboard,
            }
            
            # Kirim notifikasi ke setiap staff
            for user in staff_users[:5]:  # Limit 5 staff untuk menghindari spam
                try:
                    if not user.partner_id:
                        continue
                    
                    # Kirim via dispatcher
                    result = dispatcher.send_template_message(
                        template_key='document_pending',
                        partner_id=user.partner_id.id,
                        context_values=context_values
                    )
                    
                    if result.get('success'):
                        _logger.info(
                            f'✅ Notifikasi dokumen baru dikirim ke {user.name} '
                            f'via {result.get("provider", "unknown")}'
                        )
                    else:
                        _logger.error(
                            f'❌ Gagal kirim notifikasi ke {user.name}: '
                            f'{result.get("error", "Unknown error")}'
                        )
                    
                except Exception as e:
                    _logger.error(f'❌ Error mengirim notifikasi ke {user.name}: {str(e)}', exc_info=True)
    
    def _kirim_notifikasi_perlu_approval(self):
        """
        Kirim notifikasi WhatsApp ke pejabat saat dokumen perlu approval
        Template: approval_required
        
        Menggunakan dispatcher multi-provider untuk routing otomatis.
        """
        for record in self:
            # Cari pejabat berwenang dari workflow (reverse lookup)
            workflow = self.env['signature.workflow'].search([
                ('document_id', '=', record.id),
                ('state', '=', 'pending')
            ], limit=1)
            
            if not workflow or not workflow.approver_id:
                _logger.debug(f'Tidak ada workflow atau approver untuk dokumen {record.document_number}')
                return
            
            approver = workflow.approver_id
            
            # Ambil partner dari approver
            approver_partner = None
            if hasattr(approver, 'partner_id') and approver.partner_id:
                approver_partner = approver.partner_id
            elif hasattr(approver, 'name'):
                # Jika approver bukan res.users, coba cari partner berdasarkan nama
                approver_partner = self.env['res.partner'].search([
                    ('name', '=', approver.name)
                ], limit=1)
            
            if not approver_partner:
                _logger.warning(f'Pejabat {approver.name} tidak memiliki partner')
                return
            
            # Cek apakah partner memiliki nomor WhatsApp
            mobile = approver_partner._get_mobile_or_phone() if approver_partner else False
            if not mobile:
                _logger.warning(f'Pejabat {approver.name} tidak memiliki nomor WhatsApp')
                return
            
            try:
                # Gunakan dispatcher untuk routing otomatis
                dispatcher = self.env['sicantik.whatsapp.dispatcher']
                
                # Ambil data dokumen untuk variabel template
                permit_type_name = record.permit_type_id.name if record.permit_type_id else 'Tidak diketahui'
                applicant_name = record.permit_id.applicant_name if record.permit_id else 'Tidak diketahui'
                permit_number = record.permit_id.permit_number if record.permit_id and hasattr(record.permit_id, 'permit_number') else record.document_number or 'Tidak ada'
                approval_link = f'https://sicantik.dotakaro.com/approval/{workflow.id}'
                
                # Prepare context values
                context_values = {
                    'nama_pejabat': approver.name or 'Pejabat Berwenang',
                    'jenis_izin': permit_type_name,
                    'nama_pemohon': applicant_name,
                    'permit_number': permit_number,
                    'approval_link': approval_link,
                }
                
                # Kirim via dispatcher
                result = dispatcher.send_template_message(
                    template_key='approval_required',
                    partner_id=approver_partner.id,
                    context_values=context_values
                )
                
                if result.get('success'):
                    _logger.info(
                        f'✅ Notifikasi approval dikirim ke {approver.name} '
                        f'via {result.get("provider", "unknown")}'
                    )
                else:
                    _logger.error(
                        f'❌ Gagal kirim notifikasi approval: {result.get("error", "Unknown error")}'
                    )
                
            except Exception as e:
                _logger.error(f'❌ Error mengirim notifikasi approval: {str(e)}', exc_info=True)
    
    @api.model
    def cron_reminder_dokumen_pending(self):
        """
        Cron job untuk reminder dokumen yang pending lebih dari 24 jam
        Dijalankan setiap hari jam 10:00
        """
        _logger.info('='*80)
        _logger.info('⏰ CRON: Reminder dokumen pending')
        _logger.info('='*80)
        
        # Cari dokumen yang pending lebih dari 24 jam
        threshold_time = datetime.now() - timedelta(hours=24)
        
        dokumen_pending = self.search([
            ('state', '=', 'pending_signature'),
            ('write_date', '<', threshold_time)
        ])
        
        if not dokumen_pending:
            _logger.info('Tidak ada dokumen pending lebih dari 24 jam')
            return
        
        template = self.env['whatsapp.template'].search([
            ('template_name', '=', 'reminder_dokumen_pending'),
            ('status', '=', 'approved'),
            ('active', '=', True)
        ], limit=1)
        
        if not template:
            _logger.warning('Template WhatsApp "reminder_dokumen_pending" tidak ditemukan')
            return
        
        # Kirim reminder ke staff
        # Field mobile ada di res.partner, bukan res.users, jadi kita filter manual
        all_staff = self.env['res.users'].search([
            ('share', '=', False),
            ('active', '=', True)
        ])
        
        # Filter manual untuk user yang punya phone di partner_id
        # Di Odoo 18.4, hanya ada field 'phone', tidak ada 'mobile'
        def _has_phone(user):
            if not user.partner_id:
                return False
            # Gunakan method _get_mobile_or_phone() jika ada, atau langsung akses phone
            if hasattr(user.partner_id, '_get_mobile_or_phone'):
                try:
                    return bool(user.partner_id._get_mobile_or_phone())
                except:
                    pass
            # Fallback: akses phone langsung dengan safe access
            return bool(getattr(user.partner_id, 'phone', False))
        
        staff_users = all_staff.filtered(_has_phone)[:3]  # Limit untuk menghindari spam
        
        # Gunakan dispatcher untuk routing otomatis
        dispatcher = self.env['sicantik.whatsapp.dispatcher']
        
        # Ambil sample dokumen untuk context
        sample_doc = dokumen_pending[0] if dokumen_pending else None
        if not sample_doc:
            return
        
        # Prepare context values
        permit_type_name = sample_doc.permit_type_id.name if sample_doc.permit_type_id else ''
        applicant_name = sample_doc.permit_id.applicant_name if sample_doc.permit_id else ''
        waktu_pending = (datetime.now() - sample_doc.write_date).total_seconds() / 3600  # Jam
        waktu_pending_str = f'{int(waktu_pending)} jam' if waktu_pending < 24 else f'{int(waktu_pending / 24)} hari'
        link_dashboard = 'https://sicantik.dotakaro.com/dashboard'
        
        context_values = {
            'jumlah': str(len(dokumen_pending)),
            'jenis_izin': permit_type_name,
            'nama_pemohon': applicant_name,
            'waktu_pending': waktu_pending_str,
            'link_dashboard': link_dashboard,
        }
        
        for user in staff_users[:3]:  # Limit 3 staff
            try:
                if not user.partner_id:
                    continue
                
                # Kirim via dispatcher
                result = dispatcher.send_template_message(
                    template_key='reminder',
                    partner_id=user.partner_id.id,
                    context_values=context_values
                )
                
                if result.get('success'):
                    _logger.info(
                        f'✅ Reminder dikirim ke {user.name} '
                        f'via {result.get("provider", "unknown")}'
                    )
                else:
                    _logger.error(
                        f'❌ Gagal kirim reminder ke {user.name}: '
                        f'{result.get("error", "Unknown error")}'
                    )
                
            except Exception as e:
                _logger.error(f'❌ Error mengirim reminder: {str(e)}', exc_info=True)
        
        _logger.info(f'✅ Total reminder dikirim: {len(staff_users[:3])}')
        _logger.info('='*80)

