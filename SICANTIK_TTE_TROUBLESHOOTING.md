# 🔧 SICANTIK TTE - TROUBLESHOOTING GUIDE

**Date:** 24 November 2025  
**Module:** SICANTIK TTE - Digital Signature  
**Version:** 1.0.0  
**Status:** Active Development

---

## 📋 PENDAHULUAN

Panduan ini membantu menyelesaikan masalah umum yang mungkin terjadi saat menginstal atau menggunakan modul SICANTIK TTE (Tanda Tangan Elektronik).

---

## 🚨 MASALAH UMUM

### 1. Error Indentasi pada Modul

**Symptom:**
```
IndentationError: expected an indented block after class definition
```

**Solusi:**
1. Jalankan script troubleshooting:
```bash
./scripts/odoo/troubleshoot-odoo.sh
```

2. Periksa sintaks Python:
```bash
docker exec -it odoo_sicantik bash
cd /mnt/extra-addons/sicantik_tte
python3 -m py_compile wizard/*.py models/*.py
```

3. Hapus cache Python:
```bash
docker exec odoo_sicantik find /mnt/extra-addons -name "*.pyc" -delete
docker exec odoo_sicantik find /mnt/extra-addons -name "__pycache__" -exec rm -rf {} +
```

### 2. Error Model Access

**Symptom:**
```
No matching record found for external id 'model_document_upload_wizard' in field 'Model'
Missing required value for the field 'Model' (model_id).
```

**Solusi:**
1. Pastikan model didefinisikan dengan benar di file Python:
   - Model wizard harus didefinisikan dengan `models.TransientModel`
   - Nama model harus konsisten antara Python dan XML

2. Periksa file `ir.model.access.csv`:
   - Nama model dalam `model_id:id` harus sesuai dengan definisi di Python
   - Format: `model_[module_name]` (contoh: `model_sicantik_document_upload_wizard`)

3. Pastikan model diimpor dengan benar:
   - Tambahkan model ke file `models/__init__.py`
   - Restart Odoo untuk reload model

### 3. Error View Definition

**Symptom:**
```
Error while validating view: View definition mismatch for wizard
```

**Solusi:**
1. Pastikan nama model di view sesuai dengan definisi Python:
```xml
<field name="model">sicantik.document_upload_wizard</field>
```

2. Periksa nama field di view:
```xml
<field name="document_file" filename="document_filename"/>
```

3. Pastikan action menggunakan model yang benar:
```xml
<field name="res_model">sicantik.document_upload_wizard</field>
```

### 4. Error Session

**Symptom:**
```
FileNotFoundError: [Errno 2] No such file or directory: '/var/lib/odoo/sessions/...'
```

**Solusi:**
Error ini normal dan tidak mengganggu fungsi utama. Odoo akan otomatis membuat session folder saat diperlukan.

Jika error ini terus berlanjut:
1. Periksa permission folder `/var/lib/odoo/`:
```bash
docker exec odoo_sicantik ls -la /var/lib/odoo/
```

2. Pastikan user odoo memiliki permission:
```bash
docker exec odoo_sicantik chown -R odoo:odoo /var/lib/odoo/
```

---

## 🛠️ TROUBLESHOOTING LANGKAH DEMI LANGKAH

### Langkah 1: Verifikasi Instalasi

```bash
# 1. Periksa status container
docker-compose ps

# 2. Periksa log Odoo
docker logs odoo_sicantik | tail -20

# 3. Verifikasi modul terload
docker exec odoo_sicantik ls -la /mnt/extra-addons/sicantik_tte/
```

### Langkah 2: Debug Model

```bash
# Akses container
docker exec -it odoo_sicantik bash

# Periksa model di database
python3 -c "
import odoo
odoo.tools.config['addons_path'] = ['/opt/odoo/addons', '/mnt/extra-addons', '/mnt/enterprise-addons']
import odoo.registry
registry = odoo.registry.Registry('sicantik')
with registry.cursor() as cr:
    env = odoo.api.Environment(cr, 1, {})
    models = env['ir.model'].search([('model', 'like', 'sicantik%')])
    for model in models:
        print(f'{model.model}: {model.name}')
"
```

### Langkah 3: Perbaiki Konfigurasi

1. Restart Odoo:
```bash
docker-compose restart odoo_sicantik
```

2. Update modul:
```bash
docker exec odoo_sicantik python3 odoo-bin -d sicantik -u sicantik_tte --stop-after-init
```

3. Reinstall modul:
```bash
docker exec odoo_sicantik python3 odoo-bin -d sicantik -i sicantik_tte --stop-after-init
```

---

## 🔧 ADVANCED TROUBLESHOOTING

### 1. Debug Mode

Aktifkan debug mode di `odoo.conf`:
```ini
log_level = debug
log_handler = :DEBUG
dev_mode = reload,qweb,werkzeug,xml
```

### 2. Python Shell Debug

Akses Python shell di container:
```bash
docker exec -it odoo_sicantik python3
```

Import dan test model:
```python
import odoo
odoo.tools.config['addons_path'] = ['/opt/odoo/addons', '/mnt/extra-addons', '/mnt/enterprise-addons']
import odoo.registry
registry = odoo.registry.Registry('sicantik')
with registry.cursor() as cr:
    env = odoo.api.Environment(cr, 1, {})
    # Test model
    Model = env['sicantik.document_upload_wizard']
    print(Model._name)
```

### 3. SQL Query Debug

Langsung ke database:
```bash
docker exec -it postgres psql -U odoo -d sicantik
```

Cek model dan fields:
```sql
SELECT model, name FROM ir_model WHERE model LIKE 'sicantik%';
SELECT * FROM ir_model_fields WHERE model = 'sicantik.document_upload_wizard';
```

---

## 📋 CHECKLIST INSTALASI

### Sebelum Install
- [ ] Odoo 18.4 Enterprise running
- [ ] Python dependencies terinstall (PyPDF2, qrcode, dll)
- [ ] MinIO server running
- [ ] BSRE API configured

### Saat Install
- [ ] Update Apps List
- [ ] Remove "Apps" filter
- [ ] Cari module "SICANTIK TTE"
- [ ] Klik "Install"
- [ ] Tunggu proses selesai

### Setelah Install
- [ ] Verifikasi menu SICANTIK muncul
- [ ] Test BSRE Configuration
- [ ] Test Document Upload
- [ ] Test QR Code Generation
- [ ] Test Signature Workflow

---

## 🆘 BANTUAN

### Commands Berguna
```bash
# Restart service
docker-compose restart odoo_sicantik

# View logs
docker logs -f odoo_sicantik

# Update module
docker exec odoo_sicantik python3 odoo-bin -d [db] -u [module] --stop-after-init

# Reinstall module
docker exec odoo_sicantik python3 odoo-bin -d [db] -i [module] --stop-after-init
```

### Debug Workflow
1. **Document Upload**:
   - Periksa file di MinIO
   - Verifikasi QR Code generated
   - Test embed QR ke PDF

2. **BSRE Integration**:
   - Test API connection
   - Verifikasi signing process
   - Check error logs

3. **Verification**:
   - Test QR Code scan
   - Verify signature validity
   - Check document status

---

## 📝 CATATAN PENTING

1. **Backup Database**: Sebelum melakukan perubahan besar, selalu backup database
2. **Version Control**: Keep track perubahan di Git
3. **Testing**: Test semua fitur sebelum production
4. **Documentation**: Update dokumentasi untuk setiap perubahan

---

**Last Updated:** 24 November 2025  
**Version:** 1.0.0  
**Status:** ✅ ACTIVE DEVELOPMENT

**🚀 SICANTIK TTE - Digital Signature Siap Digunakan! 🚀**