#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Script untuk menghapus template WhatsApp SICANTIK yang sudah ada
Jalankan script ini sebelum upgrade modul sicantik_whatsapp
"""

import sys
import os

# Add Odoo to path
sys.path.insert(0, '/usr/lib/python3/dist-packages')

import odoo
from odoo import api, SUPERUSER_ID

def cleanup_templates():
    """Hapus template WhatsApp SICANTIK yang sudah ada"""
    
    # Initialize Odoo
    odoo.tools.config.parse_config(['-c', '/etc/odoo/odoo.conf'])
    registry = odoo.registry(odoo.tools.config['db_name'])
    
    with registry.cursor() as cr:
        env = api.Environment(cr, SUPERUSER_ID, {})
        
        template_names = [
            'izin_selesai_diproses',
            'dokumen_baru_untuk_tandatangan',
            'dokumen_perlu_approval',
            'update_status_perizinan',
            'reminder_dokumen_pending',
            'peringatan_masa_berlaku_izin',
            'perpanjangan_izin_disetujui'
        ]
        
        print('='*80)
        print('🧹 CLEANUP: Menghapus template WhatsApp SICANTIK yang sudah ada')
        print('='*80)
        
        # Cari template yang sudah ada
        existing_templates = env['whatsapp.template'].search([
            ('template_name', 'in', template_names)
        ])
        
        if not existing_templates:
            print('✅ Tidak ada template yang perlu dihapus')
            return
        
        print(f'📋 Ditemukan {len(existing_templates)} template yang akan dihapus')
        
        # Hapus variabel terlebih dahulu
        for template in existing_templates:
            print(f'  Menghapus variabel untuk: {template.name}')
            template.variable_ids.unlink()
        
        # Hapus template
        template_names_list = [t.name for t in existing_templates]
        existing_templates.unlink()
        
        print(f'✅ Berhasil menghapus {len(template_names_list)} template')
        for name in template_names_list:
            print(f'  - {name}')
        
        print('='*80)
        print('✅ Cleanup selesai! Silakan upgrade modul sicantik_whatsapp sekarang.')

if __name__ == '__main__':
    cleanup_templates()

