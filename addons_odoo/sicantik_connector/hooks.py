# -*- coding: utf-8 -*-

def pre_init_hook(cr):
    """
    Hook yang dijalankan sebelum install/upgrade modul
    Membuat kolom sicantik_permit_count di res_partner sebelum modul di-load
    """
    from odoo.tools.sql import column_exists, create_column
    
    if not column_exists(cr, "res_partner", "sicantik_permit_count"):
        create_column(cr, "res_partner", "sicantik_permit_count", "int4")

