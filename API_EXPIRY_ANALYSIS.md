# 📅 SICANTIK API - Expiry Date Analysis

**Tanggal:** 29 Oktober 2025  
**Status:** ⚠️ **PARTIAL SUPPORT**

---

## 🔍 HASIL INVESTIGASI

### ❓ **PERTANYAAN:**
> "untuk pengecekan expiry apakah sudah ada endpointnya?"

### ✅ **JAWABAN:**
**YA, tapi dengan keterbatasan.**

---

## 📊 TEMUAN DETAIL

### 1. **Field `d_berlaku_izin` ADA di Database**

```sql
-- Table: tmpermohonan
Field: d_berlaku_izin
Type: date
Description: Tanggal berlaku izin (expiry date)
```

### 2. **Endpoint yang INCLUDE `d_berlaku_izin`**

#### ✅ **Endpoint: `cekperizinan`** (TERSEDIA)
```
GET /backoffice/api/cekperizinan?no_izin={base64_encoded_no_surat}
```

**Response Fields:**
```json
{
  "pendaftaran_id": "string",
  "n_pemohon": "string",
  "n_perizinan": "string",
  "no_surat": "string",
  "d_terima_berkas": "YYYY-MM-DD",
  "d_berlaku_izin": "YYYY-MM-DD",  // ✅ EXPIRY DATE
  "a_pemohon": "string",
  "telp_pemohon": "string",
  "tgl_surat": "YYYY-MM-DD",
  "n_perusahaan": "string",
  "npwp": "string",
  "a_perusahaan": "string",
  "d_tgl_berdiri": "YYYY-MM-DD",
  "i_telp_perusahaan": "string",
  "n_kecamatan": "string",
  "n_kabupaten": "string",
  "n_propinsi": "string",
  "n_kelurahan": "string"
}
```

**Keuntungan:**
- ✅ Include `d_berlaku_izin` (expiry date)
- ✅ Data lengkap (pemohon, perusahaan, lokasi)
- ✅ Sudah production ready

**Kelemahan:**
- ❌ Requires `no_izin` (base64 encoded)
- ❌ Hanya bisa cek 1 izin per request
- ❌ Tidak bisa list semua izin dengan expiry date

---

### 3. **Endpoint yang TIDAK INCLUDE `d_berlaku_izin`**

#### ❌ **Endpoint: `listpermohonanterbit`** (TIDAK ADA)
```
GET /backoffice/api/listpermohonanterbit?limit=10&offset=0
```

**Response Fields:**
```json
{
  "pendaftaran_id": "string",
  "n_pemohon": "string",
  "n_perizinan": "string",
  "no_surat": "string"
  // ❌ TIDAK ADA d_berlaku_izin
}
```

**Problem:**
- ❌ Tidak ada `d_berlaku_izin`
- ❌ Tidak bisa detect izin yang akan expired
- ❌ Harus call `cekperizinan` untuk setiap izin (inefficient)

---

## 💡 SOLUSI & STRATEGI

### **Opsi 1: Modify Existing Endpoint** ⭐ RECOMMENDED
Koordinasi dengan tim IT Pemkab Karo untuk **menambahkan field `d_berlaku_izin`** ke endpoint `listpermohonanterbit`.

**Query Modification:**
```sql
-- CURRENT (tidak ada d_berlaku_izin)
SELECT
a.pendaftaran_id,
c.n_pemohon,
i.n_perizinan,
tmsk.no_surat
FROM tmpermohonan AS a
...

-- PROPOSED (tambah d_berlaku_izin)
SELECT
a.pendaftaran_id,
c.n_pemohon,
i.n_perizinan,
tmsk.no_surat,
a.d_berlaku_izin,  -- ✅ ADD THIS
a.d_terima_berkas
FROM tmpermohonan AS a
...
```

**Keuntungan:**
- ✅ Minimal code change (1 line)
- ✅ Backward compatible
- ✅ Efficient (1 query untuk semua data)
- ✅ No breaking changes

**Timeline:**
- Development: 30 menit
- Testing: 1 jam
- Deployment: Same day

---

### **Opsi 2: Create New Endpoint** 🆕
Buat endpoint baru khusus untuk expiry checking.

**Proposed Endpoint:**
```
GET /backoffice/api/listpermohonanexpiry?days={n}&limit={n}&offset={n}
```

**Parameters:**
- `days` (optional): Filter izin yang akan expired dalam N hari (default: 90)
- `limit` (required): Pagination limit
- `offset` (required): Pagination offset

**Response:**
```json
[
  {
    "pendaftaran_id": "string",
    "n_pemohon": "string",
    "n_perizinan": "string",
    "no_surat": "string",
    "d_berlaku_izin": "YYYY-MM-DD",
    "days_remaining": 45,
    "status": "warning",  // "ok", "warning", "critical", "expired"
    "telp_pemohon": "string"
  }
]
```

**Implementation:**
```php
public function listpermohonanexpiry_get()
{
    $days = $this->get('days') ?: 90;
    $limit = $this->get('limit');
    $offset = $this->get('offset');
    
    $target_date = date('Y-m-d', strtotime("+{$days} days"));
    
    $query = "SELECT
        a.pendaftaran_id,
        c.n_pemohon,
        i.n_perizinan,
        tmsk.no_surat,
        a.d_berlaku_izin,
        DATEDIFF(a.d_berlaku_izin, CURDATE()) as days_remaining,
        c.telp_pemohon,
        CASE
            WHEN DATEDIFF(a.d_berlaku_izin, CURDATE()) > 60 THEN 'ok'
            WHEN DATEDIFF(a.d_berlaku_izin, CURDATE()) BETWEEN 31 AND 60 THEN 'warning'
            WHEN DATEDIFF(a.d_berlaku_izin, CURDATE()) BETWEEN 1 AND 30 THEN 'critical'
            ELSE 'expired'
        END as status
    FROM tmpermohonan AS a
    INNER JOIN tmpemohon_tmpermohonan AS b ON a.id = b.tmpermohonan_id
    INNER JOIN tmpemohon AS c ON c.id = b.tmpemohon_id
    INNER JOIN tmpermohonan_trperizinan AS h ON h.tmpermohonan_id = a.id
    INNER JOIN trperizinan AS i ON i.id = h.trperizinan_id
    INNER JOIN tmpermohonan_tmsk ON tmpermohonan_tmsk.tmpermohonan_id = a.id
    INNER JOIN tmsk ON tmsk.id = tmpermohonan_tmsk.tmsk_id
    WHERE
        a.c_izin_selesai = 1
        AND a.d_berlaku_izin IS NOT NULL
        AND a.d_berlaku_izin <= '{$target_date}'
    ORDER BY a.d_berlaku_izin ASC
    LIMIT {$offset}, {$limit}";
    
    $result = $this->db->query($query)->result_array();
    $this->response($result, 200);
}
```

**Keuntungan:**
- ✅ Dedicated endpoint for expiry
- ✅ Built-in filtering by days
- ✅ Status categorization
- ✅ Sorted by expiry date

**Kelemahan:**
- ❌ New endpoint (more code)
- ❌ Need testing & deployment
- ❌ More maintenance

---

### **Opsi 3: Workaround - Two-Step Process** ⚠️ TEMPORARY
Gunakan kombinasi 2 endpoints yang ada.

**Process:**
```
1. Call listpermohonanterbit (get all permits)
   ↓
2. For each permit:
   - Extract no_surat
   - Encode to base64
   - Call cekperizinan?no_izin={base64}
   - Get d_berlaku_izin
   ↓
3. Filter permits by expiry date
```

**Implementation (Python/Odoo):**
```python
import base64
import requests
from datetime import datetime, timedelta

def get_expiring_permits(days=90):
    """Get permits expiring within N days"""
    base_url = "https://perizinan.karokab.go.id/backoffice/api"
    
    # Step 1: Get all permits
    permits = []
    offset = 0
    limit = 100
    
    while True:
        response = requests.get(
            f"{base_url}/listpermohonanterbit",
            params={'limit': limit, 'offset': offset}
        )
        data = response.json()
        
        if not data:
            break
            
        permits.extend(data)
        offset += limit
    
    # Step 2: Get expiry date for each permit
    expiring_permits = []
    target_date = datetime.now() + timedelta(days=days)
    
    for permit in permits:
        try:
            # Encode no_surat to base64
            no_izin = base64.b64encode(
                permit['no_surat'].encode()
            ).decode()
            
            # Get full permit details
            detail_response = requests.get(
                f"{base_url}/cekperizinan",
                params={'no_izin': no_izin}
            )
            detail = detail_response.json()
            
            # Check expiry date
            if detail.get('d_berlaku_izin'):
                expiry_date = datetime.strptime(
                    detail['d_berlaku_izin'], 
                    '%Y-%m-%d'
                )
                
                if expiry_date <= target_date:
                    days_remaining = (expiry_date - datetime.now()).days
                    permit['d_berlaku_izin'] = detail['d_berlaku_izin']
                    permit['days_remaining'] = days_remaining
                    permit['telp_pemohon'] = detail.get('telp_pemohon')
                    expiring_permits.append(permit)
        
        except Exception as e:
            print(f"Error processing permit {permit['no_surat']}: {e}")
            continue
    
    return expiring_permits
```

**Keuntungan:**
- ✅ Bisa digunakan sekarang (no API changes)
- ✅ Flexible filtering
- ✅ No coordination needed

**Kelemahan:**
- ❌ Very inefficient (N+1 queries)
- ❌ Slow performance (100 permits = 101 API calls)
- ❌ High server load
- ❌ Rate limiting issues
- ❌ Not scalable

**Performance Analysis:**
```
Scenario: 500 active permits
- listpermohonanterbit: 5 calls (100 permits each)
- cekperizinan: 500 calls (1 per permit)
- Total: 505 API calls
- Time: ~5-10 minutes (depending on network)
```

---

## 🎯 REKOMENDASI

### **SHORT TERM (Immediate - Week 1)**
✅ **Use Opsi 3 (Workaround)** untuk development & testing

**Implementation:**
```python
# In sicantik_connector module
class SicantikConnector(models.Model):
    _name = 'sicantik.connector'
    
    def sync_expiring_permits(self, days=90):
        """
        Sync permits expiring within N days
        Uses two-step process (temporary workaround)
        """
        expiring_permits = self._get_expiring_permits_workaround(days)
        
        for permit_data in expiring_permits:
            permit = self.env['sicantik.permit'].search([
                ('registration_id', '=', permit_data['pendaftaran_id'])
            ], limit=1)
            
            if permit:
                permit.write({
                    'expiry_date': permit_data['d_berlaku_izin'],
                    'days_remaining': permit_data['days_remaining']
                })
    
    def _get_expiring_permits_workaround(self, days):
        """Workaround using two API calls"""
        # Implementation as shown above
        pass
```

**Cron Job:**
```xml
<!-- Run daily at 09:00 -->
<record id="cron_sync_expiring_permits" model="ir.cron">
    <field name="name">Sync Expiring Permits</field>
    <field name="model_id" ref="model_sicantik_connector"/>
    <field name="state">code</field>
    <field name="code">model.sync_expiring_permits(90)</field>
    <field name="interval_number">1</field>
    <field name="interval_type">days</field>
    <field name="numbercall">-1</field>
    <field name="doall" eval="False"/>
</record>
```

---

### **LONG TERM (Recommended - Week 2-3)**
⭐ **Request Opsi 1 (Modify Endpoint)** dari tim IT Pemkab Karo

**Proposal Email Template:**
```
Subject: Request Penambahan Field d_berlaku_izin pada API listpermohonanterbit

Kepada Yth,
Tim IT SICANTIK Pemkab Karo

Terkait pengembangan sistem companion untuk WhatsApp notification,
kami membutuhkan field 'd_berlaku_izin' pada endpoint listpermohonanterbit.

Current Response:
{
  "pendaftaran_id": "...",
  "n_pemohon": "...",
  "n_perizinan": "...",
  "no_surat": "..."
}

Proposed Response (tambah 2 fields):
{
  "pendaftaran_id": "...",
  "n_pemohon": "...",
  "n_perizinan": "...",
  "no_surat": "...",
  "d_berlaku_izin": "YYYY-MM-DD",  // NEW
  "d_terima_berkas": "YYYY-MM-DD"  // NEW (optional)
}

Benefit:
- Automated expiry notifications via WhatsApp
- Proactive reminder untuk perpanjangan izin
- Meningkatkan compliance & revenue

Code Change (minimal):
- 1 line di query SELECT (tambah a.d_berlaku_izin)
- Backward compatible (tidak breaking existing integrations)

Timeline:
- Development: 30 menit
- Testing: 1 jam
- Deployment: Same day

Terima kasih.
```

---

## 📊 COMPARISON TABLE

| Aspect | Opsi 1: Modify | Opsi 2: New Endpoint | Opsi 3: Workaround |
|--------|---------------|---------------------|-------------------|
| **Effort** | Low (30 min) | Medium (4 hours) | None (use now) |
| **Performance** | ⭐⭐⭐⭐⭐ Excellent | ⭐⭐⭐⭐⭐ Excellent | ⭐ Poor |
| **API Calls** | 1 per page | 1 per page | N+1 (very high) |
| **Scalability** | ✅ Excellent | ✅ Excellent | ❌ Poor |
| **Maintenance** | ✅ Low | ⚠️ Medium | ⚠️ High |
| **Coordination** | ⚠️ Required | ⚠️ Required | ✅ None |
| **Timeline** | 1 day | 1 week | Immediate |
| **Recommended** | ⭐ **YES** | ⚠️ If Opsi 1 rejected | ⚠️ Temporary only |

---

## 🔄 MIGRATION PATH

### **Phase 1: Development (Week 1)**
```
Use Opsi 3 (Workaround)
- Implement two-step process
- Test with small dataset
- Validate expiry logic
- Build WhatsApp notifications
```

### **Phase 2: Optimization (Week 2-3)**
```
Request Opsi 1 (Modify Endpoint)
- Submit proposal to Pemkab
- Wait for approval & implementation
- Test new endpoint
- Prepare migration code
```

### **Phase 3: Migration (Week 4)**
```
Switch to Modified Endpoint
- Update connector code
- Remove workaround logic
- Performance testing
- Monitor production
```

---

## 💻 CODE EXAMPLES

### **Current Implementation (Workaround)**
```python
# models/sicantik_connector.py
import base64
import requests
from datetime import datetime, timedelta

class SicantikConnector(models.Model):
    _name = 'sicantik.connector'
    
    def _get_expiring_permits_workaround(self, days=90):
        """
        Workaround: Two-step process
        WARNING: Inefficient, use only temporarily
        """
        base_url = self.config_id.api_url
        permits = []
        offset = 0
        limit = 100
        
        # Step 1: Get all permits
        while True:
            response = requests.get(
                f"{base_url}/listpermohonanterbit",
                params={'limit': limit, 'offset': offset},
                timeout=30
            )
            data = response.json()
            
            if not data:
                break
            
            permits.extend(data)
            offset += limit
            
            # Prevent infinite loop
            if len(permits) >= 1000:
                break
        
        # Step 2: Get expiry for each
        expiring = []
        target_date = datetime.now() + timedelta(days=days)
        
        for permit in permits:
            try:
                # Encode no_surat
                no_izin = base64.b64encode(
                    permit['no_surat'].encode()
                ).decode()
                
                # Get details
                detail_response = requests.get(
                    f"{base_url}/cekperizinan",
                    params={'no_izin': no_izin},
                    timeout=10
                )
                
                if detail_response.status_code == 200:
                    detail = detail_response.json()
                    
                    if detail.get('d_berlaku_izin'):
                        expiry_date = datetime.strptime(
                            detail['d_berlaku_izin'],
                            '%Y-%m-%d'
                        )
                        
                        if expiry_date <= target_date:
                            days_remaining = (expiry_date - datetime.now()).days
                            permit['d_berlaku_izin'] = detail['d_berlaku_izin']
                            permit['days_remaining'] = days_remaining
                            permit['telp_pemohon'] = detail.get('telp_pemohon')
                            expiring.append(permit)
                
                # Rate limiting
                time.sleep(0.1)  # 10 requests/second max
                
            except Exception as e:
                _logger.error(f"Error getting expiry for {permit['no_surat']}: {e}")
                continue
        
        return expiring
```

### **Future Implementation (After API Update)**
```python
# models/sicantik_connector.py
class SicantikConnector(models.Model):
    _name = 'sicantik.connector'
    
    def _get_expiring_permits_optimized(self, days=90):
        """
        Optimized: Single API call with expiry date
        Use after API endpoint is updated
        """
        base_url = self.config_id.api_url
        permits = []
        offset = 0
        limit = 100
        target_date = datetime.now() + timedelta(days=days)
        
        while True:
            response = requests.get(
                f"{base_url}/listpermohonanterbit",
                params={'limit': limit, 'offset': offset},
                timeout=30
            )
            data = response.json()
            
            if not data:
                break
            
            # Filter by expiry date
            for permit in data:
                if permit.get('d_berlaku_izin'):
                    expiry_date = datetime.strptime(
                        permit['d_berlaku_izin'],
                        '%Y-%m-%d'
                    )
                    
                    if expiry_date <= target_date:
                        days_remaining = (expiry_date - datetime.now()).days
                        permit['days_remaining'] = days_remaining
                        permits.append(permit)
            
            offset += limit
        
        return permits
```

---

## 📈 PERFORMANCE IMPACT

### **Workaround Performance:**
```
Scenario: 500 active permits, check 90-day expiry

API Calls:
- listpermohonanterbit: 5 calls (100 each)
- cekperizinan: 500 calls (1 each)
- Total: 505 calls

Time Estimate:
- Network latency: 100ms per call
- Processing: 50ms per call
- Total: 505 × 150ms = 75 seconds

Server Load:
- High (505 requests)
- Not scalable
```

### **Optimized Performance (After Update):**
```
Scenario: Same 500 permits

API Calls:
- listpermohonanterbit: 5 calls (100 each)
- Total: 5 calls

Time Estimate:
- Network latency: 100ms per call
- Processing: 50ms per call
- Total: 5 × 150ms = 0.75 seconds

Server Load:
- Low (5 requests)
- Highly scalable

Improvement: 100x faster! 🚀
```

---

## ✅ ACTION ITEMS

### **Immediate (Week 1):**
- [x] ✅ Analyze API endpoints
- [x] ✅ Confirm d_berlaku_izin exists
- [x] ✅ Document workaround solution
- [ ] ⏳ Implement workaround in Odoo
- [ ] ⏳ Test with sample data

### **Short Term (Week 2-3):**
- [ ] ⏳ Prepare proposal for Pemkab
- [ ] ⏳ Submit API modification request
- [ ] ⏳ Follow up with IT team
- [ ] ⏳ Test modified endpoint (when ready)

### **Long Term (Week 4+):**
- [ ] ⏳ Migrate to optimized solution
- [ ] ⏳ Remove workaround code
- [ ] ⏳ Performance testing
- [ ] ⏳ Production deployment

---

**Generated:** 29 Oktober 2025  
**Status:** ⚠️ WORKAROUND AVAILABLE, API UPDATE RECOMMENDED  
**Next Action:** Implement workaround & request API modification

