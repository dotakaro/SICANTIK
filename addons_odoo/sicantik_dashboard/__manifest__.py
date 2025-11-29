# -*- coding: utf-8 -*-
{
    'name': 'SICANTIK Dashboard',
    'version': '1.0.0',
    'category': 'SICANTIK',
    'summary': 'Dashboard untuk statistik izin, dokumen TTE, dan WhatsApp',
    'description': """
SICANTIK Dashboard Module
==========================

Modul dashboard profesional untuk aplikasi SICANTIK yang menampilkan:
* Statistik izin (per kategori, per tahun, expired, akan expired)
* Statistik dokumen TTE yang ditandatangani
* Statistik notifikasi WhatsApp (kontak, opt-in, pesan terkirim/gagal)
* Visualisasi data dengan charts dan grafik interaktif menggunakan Owl JS

Fitur:
------
* Dashboard real-time dengan auto-refresh setiap 5 menit
* Statistik lengkap untuk izin, dokumen, dan WhatsApp
* Charts interaktif (bar, pie, line charts)
* Responsive design untuk mobile dan desktop
* Caching untuk performa optimal

Dependencies:
-------------
* sicantik_connector: Untuk data izin dan permit
* sicantik_tte: Untuk data dokumen TTE
* sicantik_whatsapp: Untuk data WhatsApp

Author: SICANTIK Development Team
License: LGPL-3
    """,
    'author': 'SICANTIK Development Team',
    'website': 'https://perizinan.karokab.go.id',
    'license': 'LGPL-3',
    'depends': [
        'base',
        'web',
        'sicantik_connector',    # Untuk data izin dan permit
        'sicantik_tte',          # Untuk data dokumen TTE
        'sicantik_whatsapp',     # Untuk data WhatsApp
    ],
    'data': [
        'security/ir.model.access.csv',
        'views/dashboard_views.xml',
        'data/dashboard_menu_data.xml',
    ],
    'assets': {
        'web.assets_backend': [
            'sicantik_dashboard/static/src/dashboard/dashboard.js',
            'sicantik_dashboard/static/src/dashboard/dashboard.xml',
            'sicantik_dashboard/static/src/dashboard/dashboard.scss',
            'sicantik_dashboard/static/src/dashboard/registry.js',
        ],
    },
    'installable': True,
    'application': False,
    'auto_install': False,
}

