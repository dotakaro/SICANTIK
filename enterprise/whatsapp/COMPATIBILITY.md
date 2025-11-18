# Compatibility Layer untuk Odoo 18.0 CE dan 18.4+

## Masalah

Enterprise addons 18.4 menggunakan `models.Constraint()` yang tidak tersedia di Odoo 18.0 CE, menyebabkan error:
```
AttributeError: module 'odoo.models' has no attribute 'Constraint'
```

## Solusi

Compatibility layer yang otomatis mengkonversi `models.Constraint()` menjadi `_sql_constraints` di Odoo 18.0 CE, tanpa mengubah kode enterprise addons.

## Cara Kerja

1. **File `compatibility.py`**:
   - Mengecek apakah `models.Constraint` tersedia (Odoo 18.4+)
   - Jika tidak tersedia (Odoo 18.0 CE), membuat wrapper class `Constraint`
   - Monkey patch `BaseModel.__init_subclass__` untuk auto-convert `Constraint` instances ke `_sql_constraints`

2. **Import di `models/__init__.py`**:
   - Import `compatibility` module sebelum model lain di-import
   - Memastikan `Constraint` class tersedia sebelum model didefinisikan

3. **Auto-conversion**:
   - Saat model class dibuat, `__init_subclass__` akan:
     - Mencari semua attribute yang merupakan instance `Constraint`
     - Mengkonversi ke format `_sql_constraints` (tuple: name, definition, message)
     - Menambahkan ke `_sql_constraints` list
     - Menghapus instance `Constraint` dari class (sudah di-convert)

## Kompatibilitas

- ✅ **Odoo 18.0 CE**: Menggunakan `_sql_constraints` (format lama)
- ✅ **Odoo 18.4+**: Menggunakan native `models.Constraint` (format baru)
- ✅ **Tidak mengubah kode enterprise**: Hanya menambahkan compatibility layer

## File yang Diubah

1. `enterprise/whatsapp/compatibility.py` - Compatibility layer (baru)
2. `enterprise/whatsapp/models/__init__.py` - Import compatibility module

## Testing

Setelah compatibility layer diaktifkan:
- Registry loaded tanpa error
- Module whatsapp bisa diinstall tanpa error
- Constraints bekerja dengan benar di kedua versi Odoo

## Catatan

- Compatibility layer hanya aktif di Odoo 18.0 CE
- Di Odoo 18.4+, menggunakan native `Constraint` class
- Tidak ada perubahan pada kode enterprise addons

