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
        # wa_template_uid adalah Char field, jadi kita simpan sebagai string untuk konsistensi
        existing_tmpl_by_id = {}
        for t in existing_tmpls:
            if t.wa_template_uid:
                # Simpan sebagai string (karena wa_template_uid adalah Char field)
                existing_tmpl_by_id[str(t.wa_template_uid)] = t
        
        # Index by template_name + lang_code (untuk fallback jika wa_template_uid belum ada atau berbeda)
        # Ini penting untuk template yang sudah ada di Odoo tapi belum di-submit ke Meta (masih draft)
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
        
        _logger.info(
            f'📊 Template yang sudah ada di Odoo: {len(existing_tmpls)} total, '
            f'{len(existing_tmpl_by_id)} dengan wa_template_uid, '
            f'{len(existing_tmpl_by_name)} dengan template_name+lang_code'
        )
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
                
                # Convert template_id ke string untuk konsistensi (wa_template_uid adalah Char field)
                template_id_str = str(template_id)
                
                # Cari berdasarkan wa_template_uid terlebih dahulu (prioritas utama)
                existing_tmpl = existing_tmpl_by_id.get(template_id_str)
                
                # Jika tidak ditemukan berdasarkan wa_template_uid, cari berdasarkan template_name + lang_code
                # Ini untuk menangani kasus:
                # 1. Template sudah di-submit ke Meta tapi wa_template_uid berbeda (rare case)
                # 2. Template sudah di-submit ke Meta tapi wa_template_uid belum tersimpan di Odoo
                # 3. Template masih draft di Odoo (belum di-submit) - SKIP update
                if not existing_tmpl and template_name and lang_code:
                    normalized_name = str(template_name).lower().strip()
                    normalized_lang = str(lang_code).lower().strip()
                    existing_tmpl_candidate = existing_tmpl_by_name.get((normalized_name, normalized_lang))
                    
                    if existing_tmpl_candidate:
                        if existing_tmpl_candidate.wa_template_uid:
                            # Template sudah di-submit sebelumnya (punya wa_template_uid)
                            # Update dengan data dari Meta, termasuk wa_template_uid baru jika berbeda
                            existing_tmpl = existing_tmpl_candidate
                            _logger.info(
                                f'📝 Template ditemukan berdasarkan template_name: "{template_name}" (lang: {lang_code}). '
                                f'Template sudah punya wa_template_uid: {existing_tmpl.wa_template_uid}, '
                                f'update dengan wa_template_uid baru: {template_id_str} dan status dari Meta'
                            )
                        else:
                            # Template masih draft di Odoo (belum di-submit ke Meta)
                            # JANGAN update template ini dengan data dari Meta
                            # Biarkan user submit template ini secara manual melalui tombol "Submit for Approval"
                            _logger.info(
                                f'⏸️ Template "{template_name}" (lang: {lang_code}) masih draft di Odoo '
                                f'(belum punya wa_template_uid). Skip update dari Meta. '
                                f'User harus submit template ini secara manual melalui tombol "Submit for Approval".'
                            )
                            existing_tmpl = None  # Pastikan tidak di-update, tidak di-create
                    else:
                        # Template tidak ditemukan di Odoo - akan di-create baru dari Meta
                        _logger.debug(
                            f'🔍 Template "{template_name}" (lang: {lang_code}) tidak ditemukan di Odoo. '
                            f'Will be created from Meta. '
                            f'Available keys: {list(existing_tmpl_by_name.keys())[:10]}...'
                        )
                
                if existing_tmpl:
                    # Template sudah ada di Odoo dan sudah di-submit ke Meta - UPDATE dengan data dari Meta
                    template_update_count += 1
                    existing_tmpl._update_template_from_response(template)
                    _logger.info(
                        f'✅ Template "{template_name}" di-update dengan status: {template.get("status", "unknown")}'
                    )
                else:
                    # Double-check: Pastikan template benar-benar tidak ada di Odoo sebelum create
                    # Ini untuk menghindari race condition atau masalah pencarian
                    if template_name and lang_code:
                        normalized_name = str(template_name).lower().strip()
                        normalized_lang = str(lang_code).lower().strip()
                        double_check = existing_tmpl_by_name.get((normalized_name, normalized_lang))
                        
                        if double_check:
                            # Template ditemukan di double-check - skip create
                            if not double_check.wa_template_uid:
                                _logger.warning(
                                    f'⚠️ Template "{template_name}" (lang: {lang_code}) ditemukan di double-check '
                                    f'tapi masih draft (tidak punya wa_template_uid). Skip create dari Meta.'
                                )
                            else:
                                # Template ditemukan tapi tidak terdeteksi sebelumnya - update sekarang
                                _logger.warning(
                                    f'⚠️ Template "{template_name}" (lang: {lang_code}) ditemukan di double-check. '
                                    f'Update dengan wa_template_uid: {template_id_str}'
                                )
                                template_update_count += 1
                                double_check._update_template_from_response(template)
                            continue  # Skip create
                    
                    # Template benar-benar tidak ada di Odoo - CREATE baru dari Meta
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
