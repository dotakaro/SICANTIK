# -*- coding: utf-8 -*-

from odoo import models, Command
import logging

_logger = logging.getLogger(__name__)


class WhatsappTemplate(models.Model):
    _inherit = 'whatsapp.template'

    def _update_template_from_response(self, remote_template_vals):
        """
        Override untuk memastikan wa_template_uid dan template_name juga di-update saat sync dari Meta.
        
        Perbaikan:
        - Tambahkan wa_template_uid ke update_fields agar template yang belum di-submit ke Meta bisa di-link
        - Tambahkan template_name ke update_fields agar template_name di Odoo sesuai dengan Meta
        """
        self.ensure_one()
        # Tambahkan wa_template_uid dan template_name ke update_fields
        update_fields = ('body', 'header_type', 'header_text', 'footer_text', 'lang_code', 'template_type', 'status', 'quality', 'wa_template_uid', 'template_name')
        template_vals = self._get_template_vals_from_response(remote_template_vals, self.wa_account_id)
        update_vals = {field: template_vals[field] for field in update_fields}
        
        # Convert wa_template_uid ke string untuk konsistensi (wa_template_uid adalah Char field)
        # _get_template_vals_from_response mengembalikan int, tapi kita perlu string
        if 'wa_template_uid' in update_vals and update_vals['wa_template_uid']:
            update_vals['wa_template_uid'] = str(update_vals['wa_template_uid'])
        
        # Log status sebelum dan sesudah update untuk debugging
        old_status = self.status
        new_status = update_vals.get('status', 'unknown')
        _logger.info(
            f'🔄 Update template "{self.template_name or self.name}": '
            f'status {old_status} → {new_status}, '
            f'wa_template_uid {self.wa_template_uid or "None"} → {update_vals.get("wa_template_uid", "None")}'
        )

        # variables should be preserved instead of overwritten to keep odoo-specific data like fields
        variable_ids = []
        existing_template_variables = {(variable_id.name, variable_id.line_type): variable_id.id for variable_id in self.variable_ids}
        for variable_vals in template_vals['variable_ids']:
            if not existing_template_variables.pop((variable_vals['name'], variable_vals['line_type']), False):
                variable_ids.append(Command.create(variable_vals))
        variable_ids.extend([Command.delete(to_remove) for to_remove in existing_template_variables.values()])
        update_vals['variable_ids'] = variable_ids

        for button in template_vals['button_ids']:
            button['variable_ids'] = [Command.create(var) for var in button['variable_ids']]
            additional_button_vals = self._get_additional_button_values(button)
            button.update(additional_button_vals)

        update_vals['button_ids'] = [Command.clear()] + [Command.create(button) for button in template_vals['button_ids']]
        if not self.header_attachment_ids or self.header_type != template_vals['header_type']:
            new_attachment_commands = [Command.create(attachment) for attachment in template_vals['header_attachment_ids']]
            update_vals['header_attachment_ids'] = [Command.clear()] + new_attachment_commands

        self.write(update_vals)
        
        # Verifikasi status setelah write
        self.refresh()
        if self.status != new_status:
            _logger.warning(
                f'⚠️ Status tidak ter-update untuk template "{self.template_name or self.name}": '
                f'diharapkan {new_status}, tapi masih {self.status}'
            )

