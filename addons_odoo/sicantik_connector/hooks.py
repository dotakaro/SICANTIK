# -*- coding: utf-8 -*-

def pre_init_hook(cr):
    """
    Hook yang dijalankan sebelum install/upgrade modul
    Membuat kolom sicantik_permit_count di res_partner sebelum modul di-load
    """
    import logging
    _logger = logging.getLogger(__name__)
    
    _logger.info('='*80)
    _logger.info('🔧 PRE-INIT HOOK: Membuat kolom sicantik_permit_count di res_partner')
    _logger.info('='*80)
    
    try:
        # Cek apakah kolom sudah ada
        cr.execute("""
            SELECT EXISTS (
                SELECT FROM information_schema.columns 
                WHERE table_schema = 'public' 
                AND table_name = 'res_partner' 
                AND column_name = 'sicantik_permit_count'
            );
        """)
        
        column_exists = cr.fetchone()[0]
        
        if column_exists:
            _logger.info('✅ Kolom sicantik_permit_count sudah ada, tidak perlu dibuat')
        else:
            # Buat kolom dengan SQL langsung (lebih reliable)
            cr.execute("""
                ALTER TABLE "res_partner" 
                ADD COLUMN "sicantik_permit_count" int4;
            """)
            _logger.info('✅ Kolom sicantik_permit_count berhasil dibuat')
            cr.commit()
            
    except Exception as e:
        _logger.error(f'❌ Error saat membuat kolom sicantik_permit_count: {str(e)}')
        # Coba rollback jika ada error
        try:
            cr.rollback()
        except:
            pass
        # Jangan raise exception, biarkan Odoo handle sendiri
    
    _logger.info('='*80)

