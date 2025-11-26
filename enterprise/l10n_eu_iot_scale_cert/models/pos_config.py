# Part of Odoo. See LICENSE file for full copyright and licensing details.

from odoo import api, models, release

from odoo.addons.l10n_eu_iot_scale_cert.controllers.checksum import calculate_scale_checksum
from odoo.addons.l10n_eu_iot_scale_cert.controllers.expected_checksum import EXPECTED_CHECKSUM


class PosConfig(models.Model):
    _inherit = 'pos.config'

    def _load_pos_data(self, data):
        response = super()._load_pos_data(data)

        pos_config = self.browse(response[0]["id"])
        is_eu_country = self.env.company.country_id in self.env.ref('base.europe').country_ids
        kg_uom_id = self.env.ref('uom.product_uom_kgm').id
        unit_uom_id = self.env.ref('uom.product_uom_unit').id
        response[0]["_is_eu_country"] = is_eu_country
        response[0]["_kg_uom_id"] = kg_uom_id
        response[0]["_unit_uom_id"] = unit_uom_id
        if is_eu_country and pos_config.iface_electronic_scale:
            response[0]["_scale_checksum"] = calculate_scale_checksum()[0]
            response[0]["_scale_checksum_expected"] = EXPECTED_CHECKSUM
            response[0]["_lne_certification_details"] = pos_config._get_certification_details()
        return response

    @api.model
    def fix_rounding_for_scale_certification(self):
        decimal_precision = self.env.ref('uom.decimal_product_uom')
        if decimal_precision.digits < 3:
            decimal_precision.digits = 3
        if not self.env.user.has_group('uom.group_uom'):
            self.env['res.config.settings'].create({
                'group_uom': True,
            }).execute()

    def _get_certification_details(self):
        self.ensure_one()
        return {
            "pos_name": "Odoo Point of Sale",
            "odoo_version": release.major_version,
            "certificate_number": "LNE-40724",
            "iot_app_version": self.env["ir.module.module"]._get("iot").installed_version,
            "iot_image": self.iface_scale_id.iot_id.version,
        }
