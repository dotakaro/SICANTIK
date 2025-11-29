# -*- coding: utf-8 -*-

from odoo import models, fields, api
from odoo.exceptions import ValidationError
from odoo.tools.sql import column_exists, create_column
import re
import logging

_logger = logging.getLogger(__name__)


class ResPartner(models.Model):
    """
    Extend Partner model for WhatsApp integration
    """
    _inherit = 'res.partner'
    
    def _auto_init(self):
        """Create column manually to avoid computation during installation"""
        if not column_exists(self.env.cr, "res_partner", "sicantik_permit_count"):
            create_column(self.env.cr, "res_partner", "sicantik_permit_count", "int4")
        return super()._auto_init()
    
    # WhatsApp Fields
    whatsapp_number = fields.Char(
        string='WhatsApp Number',
        help='WhatsApp number for notifications (format: +62xxx)'
    )
    
    def _get_mobile_or_phone(self):
        """
        Safe method to get mobile or phone number
        Di Odoo 18.4, field 'phone' selalu tersedia di res.partner (contact)
        
        Returns phone (prioritas utama), otherwise mobile, otherwise whatsapp_number, otherwise False
        
        Note: This method uses safe access (getattr) to avoid AttributeError
        if fields don't exist in database or model definition.
        """
        self.ensure_one()
        
        # Prioritas 1: phone (selalu tersedia di Odoo 18.4)
        phone_value = getattr(self, 'phone', None)
        if phone_value:
            return phone_value
        
        # Prioritas 2: mobile (jika tersedia)
        mobile_value = getattr(self, 'mobile', None)
        if mobile_value:
            return mobile_value
        
        # Prioritas 3: whatsapp_number (custom field)
        if hasattr(self, 'whatsapp_number') and self.whatsapp_number:
            return self.whatsapp_number
        
        return False
    
    whatsapp_opt_in = fields.Boolean(
        string='WhatsApp Notifications',
        default=False,
        help='Allow WhatsApp notifications for this contact'
    )
    
    whatsapp_opt_in_date = fields.Datetime(
        string='Opt-in Date',
        readonly=True,
        help='Date when contact opted in for WhatsApp notifications'
    )
    
    # SICANTIK Integration
    sicantik_permit_ids = fields.One2many(
        'sicantik.permit',
        'partner_id',
        string='SICANTIK Permits',
        help='Permits associated with this contact'
    )
    
    sicantik_permit_count = fields.Integer(
        string='Jumlah Izin',
        compute='_compute_sicantik_permit_count',
        store=True,
        help='Jumlah izin terkait dengan pemohon ini'
    )
    
    @api.depends('sicantik_permit_ids')
    def _compute_sicantik_permit_count(self):
        """Hitung jumlah izin terkait"""
        for partner in self:
            partner.sicantik_permit_count = len(partner.sicantik_permit_ids)
    
    @api.constrains('whatsapp_number')
    def _check_whatsapp_number(self):
        """Validate WhatsApp number format"""
        for partner in self:
            if partner.whatsapp_number:
                # Remove spaces and special characters
                number = re.sub(r'[^\d+]', '', partner.whatsapp_number)
                
                # Check format
                if not number.startswith('+'):
                    raise ValidationError(
                        'WhatsApp number must start with country code (e.g., +62 for Indonesia)'
                    )
                
                if len(number) < 10:
                    raise ValidationError(
                        'WhatsApp number is too short'
                    )
                
                # Update with cleaned format
                if number != partner.whatsapp_number:
                    partner.whatsapp_number = number
    
    def action_opt_in_whatsapp(self):
        """Opt-in for WhatsApp notifications"""
        self.ensure_one()
        
        if not self.whatsapp_number:
            raise ValidationError('Please set WhatsApp number first')
        
        self.write({
            'whatsapp_opt_in': True,
            'whatsapp_opt_in_date': fields.Datetime.now()
        })
        
        return {
            'type': 'ir.actions.client',
            'tag': 'display_notification',
            'params': {
                'title': 'WhatsApp Notifications Enabled',
                'message': f'Notifications will be sent to {self.whatsapp_number}',
                'type': 'success',
                'sticky': False,
            }
        }
    
    def action_opt_out_whatsapp(self):
        """Opt-out from WhatsApp notifications"""
        self.ensure_one()
        
        self.write({
            'whatsapp_opt_in': False
        })
        
        return {
            'type': 'ir.actions.client',
            'tag': 'display_notification',
            'params': {
                'title': 'WhatsApp Notifications Disabled',
                'message': 'You will no longer receive WhatsApp notifications',
                'type': 'info',
                'sticky': False,
            }
        }
    
    def action_view_permits(self):
        """View permits for this contact"""
        self.ensure_one()
        
        return {
            'type': 'ir.actions.act_window',
            'name': f'Izin: {self.name}',
            'res_model': 'sicantik.permit',
            'view_mode': 'list,form',
            'domain': [('partner_id', '=', self.id)],
            'context': {'default_partner_id': self.id},
        }
    
    def action_send_opt_in_message(self):
        """
        Kirim pesan opt-in ke pemohon untuk notifikasi WhatsApp
        
        Menggunakan text message langsung via Fonnte/Watzap (bukan template).
        """
        self.ensure_one()
        
        mobile = self._get_mobile_or_phone()
        if not mobile:
            return {
                'type': 'ir.actions.client',
                'tag': 'display_notification',
                'params': {
                    'title': 'Pesan Opt-In',
                    'message': f'Pemohon {self.name} tidak memiliki nomor WhatsApp.',
                    'type': 'warning',
                    'sticky': False,
                }
            }
        
        # Generate link WhatsApp Business Account untuk opt-in dengan pesan pre-filled
        opt_in_manager = self.env['whatsapp.opt.in.manager']
        try:
            opt_in_info = opt_in_manager.generate_whatsapp_opt_in_link(
                message='Ya Saya Setuju Menerima Pesan Notifikasi dari DPMPTSP'
            )
            whatsapp_link = opt_in_info['link']
            wa_phone_number = opt_in_info['phone_number']
            wa_account_name = opt_in_info.get('wa_account_name', 'WhatsApp Business Account')
        except Exception as e:
            _logger.warning(f'⚠️ Tidak dapat generate WhatsApp opt-in link: {str(e)}')
            whatsapp_link = None
            wa_phone_number = None
            wa_account_name = 'WhatsApp Business Account'
        
        # Prepare pesan opt-in
        if whatsapp_link:
            message = f"""Yth. {self.name},

DPMPTSP Kabupaten Karo memberikan layanan notifikasi WhatsApp untuk memudahkan komunikasi terkait perizinan Anda.

Dengan layanan ini, Anda akan menerima:
✅ Notifikasi real-time saat izin selesai diproses
✅ Update status perizinan otomatis
✅ Peringatan masa berlaku izin
✅ Link download dokumen langsung

Untuk mengaktifkan layanan ini, silakan klik link berikut:

🔗 {whatsapp_link}

Setelah Anda klik link di atas, pesan persetujuan akan otomatis terisi: "Ya Saya Setuju Menerima Pesan Notifikasi dari DPMPTSP"
Anda cukup klik tombol "Kirim" untuk mengaktifkan notifikasi WhatsApp.

Setelah Anda mengirim pesan persetujuan, notifikasi akan aktif secara otomatis.

Terima kasih atas perhatiannya.

DPMPTSP Kabupaten Karo
Kabupaten Karo"""
        else:
            # Fallback jika tidak bisa generate link
            message = f"""Yth. {self.name},

DPMPTSP Kabupaten Karo memberikan layanan notifikasi WhatsApp untuk memudahkan komunikasi terkait perizinan Anda.

Dengan layanan ini, Anda akan menerima:
✅ Notifikasi real-time saat izin selesai diproses
✅ Update status perizinan otomatis
✅ Peringatan masa berlaku izin
✅ Link download dokumen langsung

Untuk mengaktifkan layanan ini, silakan hubungi WhatsApp Business Account kami.

Terima kasih atas perhatiannya.

DPMPTSP Kabupaten Karo
Kabupaten Karo"""
        
        try:
            dispatcher = self.env['sicantik.whatsapp.dispatcher']
            
            # Kirim via Fonnte (atau provider default yang mendukung text message)
            result = dispatcher.send_text_message(
                partner_id=self.id,
                message=message,
                provider_type='fonnte'  # Force Fonnte karena mendukung text message
            )
            
            if result.get('success'):
                _logger.info(
                    f'✅ Pesan opt-in dikirim ke {self.name} ({mobile}) '
                    f'via {result.get("provider", "unknown")}'
                )
                return {
                    'type': 'ir.actions.client',
                    'tag': 'display_notification',
                    'params': {
                        'title': 'Pesan Opt-In',
                        'message': f'✅ Pesan opt-in berhasil dikirim ke {self.name} ({mobile}) via {result.get("provider", "unknown")}.\n\nPenerima dapat mengklik link WhatsApp Business Account di pesan untuk langsung mengaktifkan notifikasi WhatsApp. Tidak perlu membalas pesan.',
                        'type': 'success',
                        'sticky': False,
                    }
                }
            else:
                error_msg = result.get('error', 'Unknown error')
                _logger.error(f'❌ Gagal kirim pesan opt-in: {error_msg}')
                return {
                    'type': 'ir.actions.client',
                    'tag': 'display_notification',
                    'params': {
                        'title': 'Pesan Opt-In',
                        'message': f'Gagal mengirim pesan opt-in: {error_msg}',
                        'type': 'danger',
                        'sticky': True,
                    }
                }
                
        except Exception as e:
            _logger.error(f'❌ Error mengirim pesan opt-in: {str(e)}', exc_info=True)
            return {
                'type': 'ir.actions.client',
                'tag': 'display_notification',
                'params': {
                    'title': 'Pesan Opt-In',
                    'message': f'Terjadi error saat mengirim pesan opt-in: {str(e)}',
                    'type': 'danger',
                    'sticky': True,
                }
            }

