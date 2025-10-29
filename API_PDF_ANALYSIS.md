# 📄 SICANTIK API - Analisis Endpoint PDF

**Tanggal:** 29 Oktober 2025  
**Status:** ⚠️ **ENDPOINT PDF TIDAK TERSEDIA**

---

## 🔍 HASIL INVESTIGASI

### ❌ **KESIMPULAN UTAMA:**
**TIDAK ADA endpoint API untuk download PDF perizinan** di sistem SICANTIK production.

---

## 📊 TEMUAN DETAIL

### 1. Endpoint API yang Tersedia
Berdasarkan analisis controller API (`backoffice/www/modules/api/controllers/api.php`), endpoint yang tersedia adalah:

```
✅ listpermohonanterbit_get()      - Metadata izin terbit
✅ listpermohonanproses_get()      - Metadata izin proses
✅ jenisPerizinanList_get()        - Daftar jenis izin
✅ jumlahPerizinan_get()           - Statistik
✅ syaratPerizinan_get()           - Syarat per jenis
✅ nilaiRetribusi_get()            - Biaya retribusi
❌ TIDAK ADA endpoint untuk PDF
```

### 2. Data yang Dikembalikan API
Endpoint `listpermohonanterbit` hanya mengembalikan **metadata**:

```json
{
  "pendaftaran_id": "0003200001082017",
  "n_pemohon": "Afira Arva",
  "n_perizinan": "IZIN GANGGUAN USAHA (HO)",
  "no_surat": "503.530.570/0110/DPMPPTSP-DS/08/YYY/KK"
}
```

**❌ TIDAK ADA field untuk:**
- Path/URL PDF
- Link download
- File reference

### 3. Struktur Database
Tabel `tmpermohonan` memiliki field `file_ttd`:

```sql
Field: file_ttd
Type: text
Null: YES
Default: NULL
```

**Status:** Field ada tapi **KOSONG** (tidak ada data)

### 4. Penyimpanan File
Lokasi folder upload: `backoffice/assets/upload/`

**Struktur:**
```
backoffice/assets/upload/
├── api.pdf          # File dokumentasi API (bukan izin)
├── logo/            # Logo instansi
├── syarat/          # Dokumen syarat
└── ttd/             # Tanda tangan digital
```

**❌ TIDAK ADA folder khusus untuk PDF izin terbit**

---

## 🤔 ANALISIS MASALAH

### Kenapa Tidak Ada Endpoint PDF?

#### 1. **Kemungkinan Alasan Teknis:**
- PDF di-generate on-demand (tidak disimpan)
- PDF hanya tersedia di internal system
- Security concern (data pribadi)
- Belum diimplementasi

#### 2. **Kemungkinan Workflow:**
```
User Request → System Generate PDF → Print/Download → Discard
(Tidak ada persistent storage)
```

#### 3. **Implikasi:**
- PDF tidak tersimpan permanen di server
- Tidak ada repository PDF
- Setiap kali butuh PDF harus regenerate

---

## 💡 SOLUSI & ALTERNATIF

### **Opsi 1: Request Endpoint Baru** ⭐ RECOMMENDED
Koordinasi dengan tim SICANTIK untuk menambahkan endpoint:

```php
// Endpoint yang dibutuhkan
GET /api/downloadPDF?pendaftaran_id={id}

// Response
{
  "pendaftaran_id": "0003200001082017",
  "pdf_url": "https://perizinan.karokab.go.id/downloads/izin_0003200001082017.pdf",
  "generated_at": "2025-01-15 10:30:00",
  "file_size": 1024000,
  "hash": "sha256_hash_here"
}
```

**Keuntungan:**
- ✅ Clean & proper solution
- ✅ Scalable
- ✅ Secure (bisa add authentication)
- ✅ Trackable (logging)

**Requirements:**
- Koordinasi dengan tim IT Pemkab Karo
- Development time: 1-2 minggu
- Testing & deployment

---

### **Opsi 2: Web Scraping** ⚠️ NOT RECOMMENDED
Scrape halaman detail izin untuk extract PDF:

```python
# Pseudo code
def scrape_pdf(pendaftaran_id):
    url = f"https://perizinan.karokab.go.id/detail/{pendaftaran_id}"
    response = requests.get(url)
    soup = BeautifulSoup(response.text)
    pdf_link = soup.find('a', {'class': 'download-pdf'})
    return pdf_link['href']
```

**Kelemahan:**
- ❌ Fragile (breaks when UI changes)
- ❌ Slow performance
- ❌ High server load
- ❌ Ethical concerns
- ❌ Possible legal issues

---

### **Opsi 3: Manual Upload Workflow** ⚠️ WORKAROUND
Workflow manual dengan staff:

```
1. Staff SICANTIK generate PDF
2. Upload ke sistem companion
3. Link dengan pendaftaran_id
4. Process signature
```

**Keuntungan:**
- ✅ Tidak perlu API baru
- ✅ Kontrol penuh atas file
- ✅ Quality assurance

**Kelemahan:**
- ❌ Manual process
- ❌ Time consuming
- ❌ Human error prone
- ❌ Not scalable

---

### **Opsi 4: Hybrid Approach** ⭐ PRACTICAL
Kombinasi automated + manual:

**Phase 1: Manual (Immediate)**
```
1. API detect izin terbit baru
2. Notifikasi ke staff SICANTIK
3. Staff upload PDF manual
4. System process otomatis
```

**Phase 2: Automated (Future)**
```
1. Endpoint PDF ready
2. Switch to automated
3. Backward compatible
```

**Keuntungan:**
- ✅ Start immediately
- ✅ Gradual improvement
- ✅ Risk mitigation
- ✅ Flexible

---

## 🎯 REKOMENDASI IMPLEMENTASI

### **SHORT TERM (1-2 Bulan):**

#### 1. **Hybrid Workflow**
```python
# Odoo Companion Implementation
class SicantikPermit(models.Model):
    _name = 'sicantik.permit'
    
    pendaftaran_id = fields.Char('Registration ID')
    pdf_file = fields.Binary('PDF File')
    pdf_filename = fields.Char('PDF Filename')
    pdf_source = fields.Selection([
        ('manual', 'Manual Upload'),
        ('api', 'API Download'),
        ('generated', 'System Generated')
    ], default='manual')
    
    # Manual upload
    def action_upload_pdf(self):
        return {
            'type': 'ir.actions.act_window',
            'name': 'Upload PDF',
            'res_model': 'sicantik.pdf.upload.wizard',
            'view_mode': 'form',
            'target': 'new',
        }
```

#### 2. **Notification System**
```python
def cron_notify_new_permits(self):
    """Notify staff when new permits detected"""
    new_permits = self.search([
        ('pdf_file', '=', False),
        ('create_date', '>=', fields.Datetime.now() - timedelta(days=1))
    ])
    
    if new_permits:
        # Send email/notification to staff
        self.env['mail.mail'].create({
            'subject': f'{len(new_permits)} Izin Baru Perlu Upload PDF',
            'body_html': self._prepare_notification_body(new_permits),
            'email_to': 'staff@karokab.go.id'
        }).send()
```

---

### **LONG TERM (3-6 Bulan):**

#### 1. **Koordinasi dengan Pemkab Karo**
**Proposal Endpoint Baru:**

```
Subject: Proposal Penambahan API Endpoint Download PDF

Kepada Yth,
Tim IT SICANTIK Pemkab Karo

Terkait pengembangan sistem companion untuk digital signature,
kami membutuhkan endpoint API untuk download PDF izin yang sudah terbit.

Spesifikasi Endpoint:
- URL: GET /api/downloadPDF
- Parameter: pendaftaran_id
- Response: PDF file atau URL download
- Authentication: API Key (optional)

Benefit:
- Integrasi digital signature (TTE BSRE)
- Verifikasi dokumen online
- Audit trail lengkap
- Mengurangi manual work

Timeline: 1-2 bulan development
Testing: 2 minggu
Deployment: Bertahap

Terima kasih.
```

#### 2. **Alternative: Direct Database Access**
Jika memungkinkan, request akses read-only ke database production:

```python
# Direct DB connection (read-only)
class SicantikDirectDB(models.AbstractModel):
    _name = 'sicantik.direct.db'
    
    def _get_production_connection(self):
        return pymysql.connect(
            host='db.sicantik.internal',
            user='readonly_user',
            password='secure_password',
            database='db_office',
            read_only=True
        )
    
    def get_permit_with_pdf_path(self, pendaftaran_id):
        conn = self._get_production_connection()
        cursor = conn.cursor()
        cursor.execute("""
            SELECT pendaftaran_id, file_ttd, no_surat
            FROM tmpermohonan
            WHERE pendaftaran_id = %s
        """, (pendaftaran_id,))
        return cursor.fetchone()
```

---

## 📋 ACTION ITEMS

### **Immediate (Week 1):**
- [x] ✅ Analyze API endpoints
- [x] ✅ Check database structure
- [x] ✅ Document findings
- [ ] ⏳ Design hybrid workflow
- [ ] ⏳ Create upload wizard

### **Short Term (Month 1-2):**
- [ ] ⏳ Implement manual upload
- [ ] ⏳ Create notification system
- [ ] ⏳ Test workflow with staff
- [ ] ⏳ Prepare proposal for Pemkab

### **Long Term (Month 3-6):**
- [ ] ⏳ Submit proposal to Pemkab
- [ ] ⏳ Coordinate API development
- [ ] ⏳ Test new endpoint
- [ ] ⏳ Migrate to automated

---

## 🔄 WORKFLOW COMPARISON

### **Current Workflow (SICANTIK):**
```
1. Permohonan masuk
2. Proses verifikasi
3. Generate PDF (on-demand)
4. Print/download
5. Serahkan ke pemohon
```

### **Proposed Workflow (With Companion):**
```
1. Permohonan masuk (SICANTIK)
2. Proses verifikasi (SICANTIK)
3. Generate PDF (SICANTIK)
4. ⭐ Upload to Companion (Manual/API)
5. ⭐ Digital Signature (BSRE)
6. ⭐ QR Code Generation
7. ⭐ Store in MinIO
8. ⭐ Public Verification
9. Serahkan ke pemohon
```

---

## 💻 CODE EXAMPLES

### **Upload Wizard**
```python
# models/pdf_upload_wizard.py
class SicantikPdfUploadWizard(models.TransientModel):
    _name = 'sicantik.pdf.upload.wizard'
    _description = 'PDF Upload Wizard'
    
    permit_id = fields.Many2one('sicantik.permit', required=True)
    pdf_file = fields.Binary('PDF File', required=True)
    pdf_filename = fields.Char('Filename')
    
    def action_upload(self):
        self.permit_id.write({
            'pdf_file': self.pdf_file,
            'pdf_filename': self.pdf_filename,
            'pdf_source': 'manual',
            'pdf_uploaded_at': fields.Datetime.now()
        })
        
        # Store in MinIO
        self.permit_id.store_in_minio()
        
        return {'type': 'ir.actions.act_window_close'}
```

### **Batch Upload**
```python
def action_batch_upload(self):
    """Upload multiple PDFs at once"""
    return {
        'type': 'ir.actions.act_window',
        'name': 'Batch Upload PDF',
        'res_model': 'sicantik.pdf.batch.upload',
        'view_mode': 'form',
        'target': 'new',
    }
```

---

## 📊 IMPACT ANALYSIS

### **Without PDF Endpoint:**
- ⚠️ Manual upload required
- ⚠️ Slower process
- ⚠️ Human error possible
- ✅ Still functional
- ✅ Workaround available

### **With PDF Endpoint:**
- ✅ Fully automated
- ✅ Faster process
- ✅ No human error
- ✅ Scalable
- ✅ Professional

---

## 🎯 CONCLUSION

### **Current State:**
❌ No PDF endpoint available in SICANTIK API

### **Recommended Approach:**
⭐ **Hybrid Solution:**
1. Start with manual upload (immediate)
2. Request API endpoint (parallel)
3. Migrate to automated (future)

### **Timeline:**
- **Phase 1 (Manual):** 2 weeks
- **Phase 2 (API Request):** 1-2 months
- **Phase 3 (Automated):** 3-6 months

### **Risk Level:**
🟡 **MEDIUM** - Workaround available, not blocking

---

**Generated:** 29 Oktober 2025  
**Status:** ⚠️ NEEDS COORDINATION WITH PEMKAB  
**Next Action:** Design hybrid workflow & prepare proposal

