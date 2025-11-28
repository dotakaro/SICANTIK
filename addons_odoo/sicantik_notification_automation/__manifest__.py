# -*- coding: utf-8 -*-

{
    "name": "SICANTIK Notification Automation",
    "version": "1.0.0",
    "category": "SICANTIK",
    "summary": "Sistem Notifikasi Massal dan Otomatis untuk SICANTIK",
    "description": """
SICANTIK Notification Automation
================================

Modul ini menyediakan sistem notifikasi massal dan otomatis berdasarkan pemicu/event tertentu.

Fitur Utama:
------------
* Notifikasi berbasis aturan yang dapat dikonfigurasi
* Pemicu berbasis event (create, write, state change)
* Kampanye notifikasi massal dengan pemrosesan batch
* Manajemen antrian pengiriman dengan mekanisme ulang coba
* Penjadwalan pengiriman (langsung, terjadwal, batch)
* Pencatatan dan pelacakan lengkap untuk audit
* Pembatasan laju pengiriman untuk menghindari spam
* Manajemen daftar hitam nomor telepon

Penggunaan:
-----------
1. Buat Pemicu (Trigger) untuk event yang ingin dimonitor
2. Buat Aturan (Rule) untuk menentukan kapan dan kepada siapa notifikasi dikirim
3. Buat Kampanye (Campaign) untuk pengiriman massal
4. Pantau antrian pengiriman dan log untuk audit

Integrasi:
----------
* Menggunakan sicantik_whatsapp untuk pengiriman pesan
* Terintegrasi dengan sicantik_connector untuk pemicu izin
* Terintegrasi dengan sicantik_tte untuk pemicu dokumen

Cron Jobs:
----------
* Process Notification Queue (setiap 1 menit) - Priority 20
* Process Campaign Batch (setiap 5 menit) - Priority 15
* Cleanup Old Logs (setiap hari) - Priority 5

Semua cron jobs dapat dimonitor melalui:
Settings → Technical → Automation → Scheduled Actions
    """,
    "author": "DPMPTSP Kabupaten Karo",
    "website": "https://perizinan.karokab.go.id",
    "depends": [
        "base",
        "mail",
        "sicantik_connector",
        "sicantik_whatsapp",
    ],
    "data": [
        "security/ir.model.access.csv",
        "data/notification_trigger_data.xml",
        "data/cron_data.xml",
        "views/notification_trigger_views.xml",
        "views/notification_rule_views.xml",
        "views/notification_campaign_views.xml",
        "views/notification_queue_views.xml",
        "views/notification_log_views.xml",
        "views/notification_blacklist_views.xml",
        "views/notification_menus.xml",
        "wizard/notification_test_wizard_views.xml",
        "wizard/notification_preview_wizard_views.xml",
    ],
    "installable": True,
    "application": False,
    "auto_install": False,
    "license": "LGPL-3",
    "images": [
        "static/description/main.png",
    ],
}

