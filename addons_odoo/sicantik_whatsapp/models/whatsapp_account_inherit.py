# -*- coding: utf-8 -*-

from odoo import models, _
from odoo.exceptions import ValidationError
from odoo.addons.whatsapp.tools.whatsapp_api import WhatsAppApi
from odoo.addons.whatsapp.tools.whatsapp_exception import WhatsAppError
import logging

_logger = logging.getLogger(__name__)


class WhatsappAccount(models.Model):
    _inherit = 'whatsapp.account'

    def button_sync_whatsapp_account_templates(self):
        """
        Override untuk memperbaiki sync template Meta agar update bukan replace.
        
        Perbaikan:
        - Cari template berdasarkan template_name + lang_code jika wa_template_uid tidak ditemukan
        - Update template yang sudah ada di Odoo dengan data dari Meta
        - Hanya create template baru jika benar-benar tidak ada di Odoo
        """
        self.ensure_one()
        try:
            response = WhatsAppApi(self)._get_all_template(fetch_all=True)
            wa_phone_number = WhatsAppApi(self)._get_phone_number(self.phone_uid)
            if wa_phone_number:
                self.phone_number = wa_phone_number
        except WhatsAppError as err:
            raise ValidationError(str(err)) from err

        WhatsappTemplate = self.env['whatsapp.template']
        existing_tmpls = WhatsappTemplate.with_context(active_test=False).search([('wa_account_id', '=', self.id)])
        # Index by wa_template_uid (ID dari Meta) - prioritas utama
        existing_tmpl_by_id = {int(t.wa_template_uid): t for t in existing_tmpls if t.wa_template_uid}
        # Index by template_name + lang_code (untuk fallback jika wa_template_uid belum ada atau berbeda)
        existing_tmpl_by_name = {(t.template_name, t.lang_code): t for t in existing_tmpls if t.template_name and t.lang_code}
        template_update_count = 0
        template_create_count = 0
        if response.get('data'):
            create_vals = []
            for template in response['data']:
                # Extract template_name dan lang_code dari response Meta
                template_name = template.get('name', '')
                lang_code = template.get('language', '')
                template_id = template.get('id')
                
                # Convert template_id ke integer (Meta selalu return string, tapi kita simpan sebagai int)
                template_id_int = None
                if template_id:
                    try:
                        template_id_int = int(template_id)
                    except (ValueError, TypeError):
                        _logger.warning(f'⚠️ Invalid template_id dari Meta: {template_id}')
                        continue
                
                if not template_id_int:
                    _logger.warning(f'⚠️ Template dari Meta tidak punya ID: {template}')
                    continue
                
                # Cari berdasarkan wa_template_uid terlebih dahulu (prioritas utama)
                existing_tmpl = existing_tmpl_by_id.get(template_id_int)
                
                # Jika tidak ditemukan berdasarkan wa_template_uid, cari berdasarkan template_name + lang_code
                # Ini untuk template yang sudah ada di Odoo tapi belum di-submit ke Meta (belum punya wa_template_uid)
                if not existing_tmpl and template_name and lang_code:
                    existing_tmpl = existing_tmpl_by_name.get((template_name, lang_code))
                    if existing_tmpl:
                        _logger.info(
                            f'📝 Template ditemukan berdasarkan template_name: "{template_name}" (lang: {lang_code}). '
                            f'Update dengan wa_template_uid: {template_id_int} dan status dari Meta'
                        )
                
                if existing_tmpl:
                    # Template sudah ada di Odoo - UPDATE dengan data dari Meta
                    template_update_count += 1
                    existing_tmpl._update_template_from_response(template)
                    _logger.info(
                        f'✅ Template "{template_name}" di-update dengan status: {template.get("status", "unknown")}'
                    )
                else:
                    # Template tidak ada di Odoo - CREATE baru dari Meta
                    template_create_count += 1
                    create_vals.append(WhatsappTemplate._create_template_from_response(template, self))
                    _logger.info(
                        f'➕ Template baru "{template_name}" akan di-create dari Meta'
                    )
            
            # Create semua template baru sekaligus (batch create)
            if create_vals:
                WhatsappTemplate.create(create_vals)
        return {
            'type': 'ir.actions.client',
            'tag': 'display_notification',
            'params': {
                'title': _("Templates synchronized!"),
                'type': 'success',
                'message': _("%(create_count)s were created, %(update_count)s were updated",
                    create_count=template_create_count, update_count=template_update_count),
                'next': {'type': 'ir.actions.act_window_close'},
            }
        }
