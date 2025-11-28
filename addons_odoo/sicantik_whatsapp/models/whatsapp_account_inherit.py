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
        # Cari template dengan wa_account_id yang sama ATAU template tanpa wa_account_id (dari XML/data)
        # Template dari XML mungkin belum punya wa_account_id, jadi kita cari juga yang NULL
        existing_tmpls = WhatsappTemplate.with_context(active_test=False).search([
            '|', ('wa_account_id', '=', self.id), ('wa_account_id', '=', False)
        ])
        
        # Index by template_name + lang_code (prioritas utama)
        # Logika sederhana: jika template_name sama → UPDATE, jika tidak → CREATE baru
        # Normalize template_name (lowercase, strip whitespace) untuk menghindari masalah case sensitivity
        existing_tmpl_by_name = {}
        for t in existing_tmpls:
            if t.template_name and t.lang_code:
                # Normalize: lowercase dan strip whitespace
                normalized_name = str(t.template_name).lower().strip()
                normalized_lang = str(t.lang_code).lower().strip()
                key = (normalized_name, normalized_lang)
                # Jika sudah ada, prioritaskan yang punya wa_template_uid
                if key not in existing_tmpl_by_name or t.wa_template_uid:
                    existing_tmpl_by_name[key] = t
        
        # Index by wa_template_uid untuk fallback (jika template_name berbeda tapi wa_template_uid sama)
        existing_tmpl_by_id = {}
        for t in existing_tmpls:
            if t.wa_template_uid:
                # Simpan sebagai string (karena wa_template_uid adalah Char field)
                existing_tmpl_by_id[str(t.wa_template_uid)] = t
        
        _logger.info(
            f'📊 Template yang sudah ada di Odoo: {len(existing_tmpls)} total, '
            f'{len(existing_tmpl_by_id)} dengan wa_template_uid, '
            f'{len(existing_tmpl_by_name)} dengan template_name+lang_code'
        )
        
        # Simpan semua template_name dari Meta untuk tracking
        # Template yang TIDAK ada di list ini berarti TIDAK ada di Meta dan TIDAK BOLEH di-update
        meta_template_names = set()
        
        template_update_count = 0
        template_create_count = 0
        if response.get('data'):
            create_vals = []
            for template in response['data']:
                # Extract template_name dan lang_code dari response Meta
                template_name = template.get('name', '')
                lang_code = template.get('language', '')
                template_id = template.get('id')
                
                if not template_id:
                    _logger.warning(f'⚠️ Template dari Meta tidak punya ID: {template}')
                    continue
                
                # Tambahkan ke set template yang ada di Meta
                if template_name and lang_code:
                    normalized_name = str(template_name).lower().strip()
                    normalized_lang = str(lang_code).lower().strip()
                    meta_template_names.add((normalized_name, normalized_lang))
                
                # Convert template_id ke string untuk konsistensi (wa_template_uid adalah Char field)
                template_id_str = str(template_id)
                
                # LOGIKA SEDERHANA:
                # 1. Cari berdasarkan template_name + lang_code (prioritas utama)
                # 2. Jika ditemukan → UPDATE dengan data dari Meta (HANYA jika template ini ada di Meta)
                # 3. Jika tidak ditemukan → CREATE baru dari Meta
                # 4. Template di Odoo yang tidak ada di Meta → biarkan as is (tetap draft)
                existing_tmpl = None
                if template_name and lang_code:
                    normalized_name = str(template_name).lower().strip()
                    normalized_lang = str(lang_code).lower().strip()
                    existing_tmpl = existing_tmpl_by_name.get((normalized_name, normalized_lang))
                    
                    if existing_tmpl:
                        # Template ditemukan di Odoo berdasarkan template_name + lang_code
                        # PENTING: Hanya update jika template ini BENAR-BENAR ada di Meta
                        # Template ini sudah ada di meta_template_names (ditambahkan di atas)
                        # Jadi kita bisa langsung UPDATE dengan data dari Meta
                        _logger.info(
                            f'📝 Template ditemukan di Odoo: "{template_name}" (lang: {lang_code}). '
                            f'Template ini ada di Meta, akan di-update dengan wa_template_uid: {template_id_str} dan status dari Meta'
                        )
                    else:
                        # Template tidak ditemukan di Odoo - akan di-create baru dari Meta
                        _logger.debug(
                            f'🔍 Template "{template_name}" (lang: {lang_code}) tidak ditemukan di Odoo. '
                            f'Will be created from Meta.'
                        )
                else:
                    _logger.warning(
                        f'⚠️ Template dari Meta tidak punya template_name atau lang_code: {template}'
                    )
                    continue  # Skip template yang tidak punya nama atau bahasa
                
                if existing_tmpl:
                    # Template sudah ada di Odoo dan BENAR-BENAR ada di Meta - UPDATE dengan data dari Meta
                    # PENTING: Hanya template yang ada di response Meta yang akan di-update
                    template_update_count += 1
                    existing_tmpl._update_template_from_response(template)
                    # Pastikan wa_account_id di-set jika template dari XML belum punya wa_account_id
                    if not existing_tmpl.wa_account_id:
                        existing_tmpl.wa_account_id = self.id
                        _logger.info(
                            f'🔗 Template "{template_name}" di-link dengan wa_account_id: {self.id}'
                        )
                    _logger.info(
                        f'✅ Template "{template_name}" di-update dengan status: {template.get("status", "unknown")}'
                    )
                else:
                    # Template tidak ditemukan di Odoo - CREATE baru dari Meta
                    template_create_count += 1
                    create_vals.append(WhatsappTemplate._create_template_from_response(template, self))
                    _logger.info(
                        f'➕ Template baru "{template_name}" akan di-create dari Meta'
                    )
            
            # Create semua template baru sekaligus (batch create)
            if create_vals:
                WhatsappTemplate.create(create_vals)
            
            # PENTING: Template di Odoo yang TIDAK ada di Meta TIDAK BOLEH di-update
            # Hanya template yang ada di meta_template_names yang boleh di-update
            # Template yang tidak ada di Meta akan tetap draft (tidak diubah)
            _logger.info(
                f'📋 Template yang ada di Meta: {len(meta_template_names)} template. '
                f'Template di Odoo yang tidak ada di Meta akan tetap draft (tidak diubah).'
            )
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
