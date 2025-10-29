# -*- coding: utf-8 -*-
# Part of Odoo. See LICENSE file for full copyright and licensing details.

import datetime
from odoo.models import AbstractModel

from ast import literal_eval
from odoo import api

class PublisherWarrantyContract(AbstractModel):
    _inherit = "publisher_warranty.contract"
    _description = 'Publisher Warranty Contract'

    @api.model
    def _get_sys_logs(self):
        IrParamSudo = self.env['ir.config_parameter'].sudo()
        create_date = IrParamSudo.get_param('database.create_date')
        # contract_date = datetime.datetime.today() + datetime.timedelta(days = 10000)
        # exp_date = contract_date.strftime('%Y-%m-%d %H:%M:%S')
        exp_date = str(int(create_date[:4]) + 30) + create_date[4:]
        text = "{'messages': [], 'contracts': [], 'enterprise_info': {'expiration_date': '" + exp_date + "', 'enterprise_code': 'ELINGE2021', 'expiration_reason': 'renewal'}}"
        return literal_eval(text)