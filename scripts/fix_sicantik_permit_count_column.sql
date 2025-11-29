-- Script untuk membuat kolom sicantik_permit_count di res_partner
-- Jalankan script ini secara manual jika pre_init_hook tidak bekerja

-- Cek apakah kolom sudah ada
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT FROM information_schema.columns 
        WHERE table_schema = 'public' 
        AND table_name = 'res_partner' 
        AND column_name = 'sicantik_permit_count'
    ) THEN
        -- Buat kolom jika belum ada
        ALTER TABLE "res_partner" 
        ADD COLUMN "sicantik_permit_count" int4;
        
        RAISE NOTICE 'Kolom sicantik_permit_count berhasil dibuat';
    ELSE
        RAISE NOTICE 'Kolom sicantik_permit_count sudah ada';
    END IF;
END $$;

