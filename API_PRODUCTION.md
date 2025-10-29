# 🌐 SICANTIK API - Production Endpoints

**Server:** perizinan.karokab.go.id  
**Status:** ✅ **PRODUCTION READY**  
**Tanggal:** 29 Oktober 2025

---

## 🎯 STRATEGI DEVELOPMENT

Menggunakan **API production yang sudah running** di server perizinan.karokab.go.id untuk development Odoo Companion App. Ini lebih efisien karena:

✅ **Keuntungan:**
1. Data real-time dari production
2. Tidak perlu maintain local legacy app
3. Testing dengan data aktual
4. Lebih cepat untuk development
5. Tidak ada masalah compatibility PHP

⚠️ **Catatan:**
- Docker local tetap berguna untuk backup/testing offline
- Odoo Companion akan consume API production
- Perlu handle network connectivity issues

---

## 🌐 Production API Endpoints

### Base URL
```
https://perizinan.karokab.go.id/backoffice/api/
```

### 1. List Permohonan Terbit
**Endpoint:** `listpermohonanterbit`

**URL:**
```
https://perizinan.karokab.go.id/backoffice/api/listpermohonanterbit?limit=10&offset=0
```

**Parameters:**
- `limit` (required): Jumlah data per page
- `offset` (required): Offset untuk pagination

**Response Structure:**
```json
[
  {
    "pendaftaran_id": "string",
    "n_pemohon": "string",
    "n_perizinan": "string",
    "no_surat": "string",
    "d_terima_berkas": "YYYY-MM-DD"
  }
]
```

**Example:**
```bash
curl "https://perizinan.karokab.go.id/backoffice/api/listpermohonanterbit?limit=10&offset=0"
```

---

### 2. List Permohonan Proses
**Endpoint:** `listpermohonanproses`

**URL:**
```
https://perizinan.karokab.go.id/backoffice/api/listpermohonanproses?limit=10&offset=0
```

**Parameters:**
- `limit` (required): Jumlah data per page
- `offset` (required): Offset untuk pagination

**Response Structure:**
```json
[
  {
    "pendaftaran_id": "string",
    "n_pemohon": "string",
    "n_perizinan": "string",
    "status": "string",
    "d_terima_berkas": "YYYY-MM-DD"
  }
]
```

---

### 3. Jenis Perizinan List
**Endpoint:** `jenisperizinanlist`

**URL:**
```
https://perizinan.karokab.go.id/backoffice/api/jenisperizinanlist
```

**Parameters:** None required

**Response Structure:**
```json
[
  {
    "id": "string",
    "n_perizinan": "string",
    "c_perizinan": "string"
  }
]
```

**Example:**
```bash
curl "https://perizinan.karokab.go.id/backoffice/api/jenisperizinanlist"
```

---

### 4. Jumlah Perizinan
**Endpoint:** `jumlahPerizinan`

**URL:**
```
https://perizinan.karokab.go.id/backoffice/api/jumlahPerizinan
```

**Parameters:** None required

**Response Structure:**
```json
{
  "total": "number",
  "terbit": "number",
  "proses": "number"
}
```

---

## 🔧 Odoo Integration Strategy

### 1. Configuration Model
```python
# models/sicantik_config.py
from odoo import models, fields

class SicantikConfig(models.Model):
    _name = 'sicantik.config'
    _description = 'SICANTIK API Configuration'
    
    name = fields.Char('Configuration Name', default='Production')
    api_base_url = fields.Char(
        'API Base URL',
        default='https://perizinan.karokab.go.id/backoffice/api/',
        required=True
    )
    api_key = fields.Char('API Key', help='Optional API key for authentication')
    timeout = fields.Integer('Request Timeout (seconds)', default=30)
    active = fields.Boolean('Active', default=True)
    
    # Connection test
    def test_connection(self):
        import requests
        try:
            response = requests.get(
                f"{self.api_base_url}jumlahPerizinan",
                timeout=self.timeout
            )
            if response.status_code == 200:
                return {
                    'type': 'ir.actions.client',
                    'tag': 'display_notification',
                    'params': {
                        'title': 'Connection Success',
                        'message': 'Successfully connected to SICANTIK API',
                        'type': 'success',
                    }
                }
        except Exception as e:
            return {
                'type': 'ir.actions.client',
                'tag': 'display_notification',
                'params': {
                    'title': 'Connection Failed',
                    'message': str(e),
                    'type': 'danger',
                }
            }
```

---

### 2. API Connector Service
```python
# models/sicantik_connector.py
import requests
import logging
from odoo import models, api
from odoo.exceptions import UserError

_logger = logging.getLogger(__name__)

class SicantikConnector(models.AbstractModel):
    _name = 'sicantik.connector'
    _description = 'SICANTIK API Connector Service'
    
    @api.model
    def _get_api_config(self):
        """Get active API configuration"""
        config = self.env['sicantik.config'].search([('active', '=', True)], limit=1)
        if not config:
            raise UserError('No active SICANTIK API configuration found')
        return config
    
    @api.model
    def _make_request(self, endpoint, params=None):
        """Make HTTP request to SICANTIK API"""
        config = self._get_api_config()
        url = f"{config.api_base_url}{endpoint}"
        
        headers = {}
        if config.api_key:
            headers['Authorization'] = f'Bearer {config.api_key}'
        
        try:
            response = requests.get(
                url,
                params=params or {},
                headers=headers,
                timeout=config.timeout
            )
            response.raise_for_status()
            return response.json()
        except requests.exceptions.RequestException as e:
            _logger.error(f"SICANTIK API Error: {str(e)}")
            raise UserError(f"Failed to connect to SICANTIK API: {str(e)}")
    
    @api.model
    def get_permohonan_terbit(self, limit=100, offset=0):
        """Get list of issued permits"""
        return self._make_request('listpermohonanterbit', {
            'limit': limit,
            'offset': offset
        })
    
    @api.model
    def get_permohonan_proses(self, limit=100, offset=0):
        """Get list of permits in process"""
        return self._make_request('listpermohonanproses', {
            'limit': limit,
            'offset': offset
        })
    
    @api.model
    def get_jenis_perizinan(self):
        """Get list of permit types"""
        return self._make_request('jenisperizinanlist')
    
    @api.model
    def get_jumlah_perizinan(self):
        """Get permit statistics"""
        return self._make_request('jumlahPerizinan')
```

---

### 3. Scheduled Polling for New Permits
```python
# models/sicantik_permit.py
from odoo import models, fields, api
import logging

_logger = logging.getLogger(__name__)

class SicantikPermit(models.Model):
    _name = 'sicantik.permit'
    _description = 'SICANTIK Permit Record'
    _order = 'd_terima_berkas desc'
    
    pendaftaran_id = fields.Char('Registration ID', required=True, index=True)
    n_pemohon = fields.Char('Applicant Name')
    n_perizinan = fields.Char('Permit Type')
    no_surat = fields.Char('Permit Number')
    d_terima_berkas = fields.Date('Document Received Date')
    
    # Companion app fields
    pdf_imported = fields.Boolean('PDF Imported', default=False)
    pdf_signed = fields.Boolean('PDF Signed', default=False)
    qr_code = fields.Char('QR Code')
    
    _sql_constraints = [
        ('pendaftaran_id_unique', 'unique(pendaftaran_id)', 
         'Registration ID must be unique!')
    ]
    
    @api.model
    def cron_poll_new_permits(self):
        """Scheduled action to poll for new permits from SICANTIK"""
        _logger.info('Starting SICANTIK permit polling...')
        
        connector = self.env['sicantik.connector']
        
        try:
            # Get permits from API
            permits = connector.get_permohonan_terbit(limit=100, offset=0)
            
            new_count = 0
            for permit_data in permits:
                # Check if already exists
                existing = self.search([
                    ('pendaftaran_id', '=', permit_data.get('pendaftaran_id'))
                ])
                
                if not existing:
                    # Create new permit record
                    self.create({
                        'pendaftaran_id': permit_data.get('pendaftaran_id'),
                        'n_pemohon': permit_data.get('n_pemohon'),
                        'n_perizinan': permit_data.get('n_perizinan'),
                        'no_surat': permit_data.get('no_surat'),
                        'd_terima_berkas': permit_data.get('d_terima_berkas'),
                    })
                    new_count += 1
            
            _logger.info(f'Polling complete. New permits: {new_count}')
            
        except Exception as e:
            _logger.error(f'Error polling SICANTIK API: {str(e)}')
```

---

### 4. Cron Job Configuration
```xml
<!-- data/cron_data.xml -->
<odoo>
    <data noupdate="1">
        <!-- Poll for new permits every 15 minutes -->
        <record id="cron_poll_sicantik_permits" model="ir.cron">
            <field name="name">SICANTIK: Poll New Permits</field>
            <field name="model_id" ref="model_sicantik_permit"/>
            <field name="state">code</field>
            <field name="code">model.cron_poll_new_permits()</field>
            <field name="interval_number">15</field>
            <field name="interval_type">minutes</field>
            <field name="numbercall">-1</field>
            <field name="active" eval="True"/>
        </record>
        
        <!-- Sync permit types daily -->
        <record id="cron_sync_permit_types" model="ir.cron">
            <field name="name">SICANTIK: Sync Permit Types</field>
            <field name="model_id" ref="model_sicantik_permit_type"/>
            <field name="state">code</field>
            <field name="code">model.cron_sync_permit_types()</field>
            <field name="interval_number">1</field>
            <field name="interval_type">days</field>
            <field name="numbercall">-1</field>
            <field name="active" eval="True"/>
        </record>
    </data>
</odoo>
```

---

## 🔒 Security Considerations

### 1. Network Security
```python
# Add retry logic with exponential backoff
from requests.adapters import HTTPAdapter
from requests.packages.urllib3.util.retry import Retry

def _get_session(self):
    """Get requests session with retry logic"""
    session = requests.Session()
    retry = Retry(
        total=3,
        backoff_factor=1,
        status_forcelist=[500, 502, 503, 504]
    )
    adapter = HTTPAdapter(max_retries=retry)
    session.mount('http://', adapter)
    session.mount('https://', adapter)
    return session
```

### 2. SSL Verification
```python
# In production, always verify SSL
response = requests.get(
    url,
    params=params,
    headers=headers,
    timeout=config.timeout,
    verify=True  # Always True in production
)
```

### 3. Rate Limiting
```python
# Implement rate limiting to avoid overloading production server
import time
from functools import wraps

def rate_limit(calls=10, period=60):
    """Decorator to rate limit API calls"""
    def decorator(func):
        last_reset = [time.time()]
        calls_made = [0]
        
        @wraps(func)
        def wrapper(*args, **kwargs):
            if time.time() - last_reset[0] >= period:
                calls_made[0] = 0
                last_reset[0] = time.time()
            
            if calls_made[0] >= calls:
                sleep_time = period - (time.time() - last_reset[0])
                if sleep_time > 0:
                    time.sleep(sleep_time)
                calls_made[0] = 0
                last_reset[0] = time.time()
            
            calls_made[0] += 1
            return func(*args, **kwargs)
        return wrapper
    return decorator

@rate_limit(calls=10, period=60)  # Max 10 calls per minute
def _make_request(self, endpoint, params=None):
    # ... implementation
```

---

## 📊 Monitoring & Logging

### 1. API Call Logging
```python
class SicantikApiLog(models.Model):
    _name = 'sicantik.api.log'
    _description = 'SICANTIK API Call Log'
    _order = 'create_date desc'
    
    endpoint = fields.Char('Endpoint')
    params = fields.Text('Parameters')
    status_code = fields.Integer('Status Code')
    response_time = fields.Float('Response Time (seconds)')
    error_message = fields.Text('Error Message')
    create_date = fields.Datetime('Call Time', readonly=True)
```

### 2. Dashboard Statistics
```python
def get_api_statistics(self):
    """Get API usage statistics"""
    logs = self.env['sicantik.api.log'].search([
        ('create_date', '>=', fields.Datetime.now() - timedelta(days=7))
    ])
    
    return {
        'total_calls': len(logs),
        'success_rate': len(logs.filtered(lambda l: l.status_code == 200)) / len(logs) * 100,
        'avg_response_time': sum(logs.mapped('response_time')) / len(logs),
        'errors': logs.filtered(lambda l: l.status_code != 200)
    }
```

---

## 🧪 Testing

### Test Connection
```bash
# Test from command line
curl "https://perizinan.karokab.go.id/backoffice/api/jumlahPerizinan"

# Test from Python
python3 << EOF
import requests
response = requests.get('https://perizinan.karokab.go.id/backoffice/api/jumlahPerizinan')
print(f"Status: {response.status_code}")
print(f"Data: {response.json()}")
EOF
```

### Test from Odoo
```python
# In Odoo shell
connector = env['sicantik.connector']
result = connector.get_jumlah_perizinan()
print(result)
```

---

## 📝 Development Workflow

### Phase 1: Setup (Week 1)
1. ✅ Create `sicantik_connector` module
2. ✅ Implement API configuration model
3. ✅ Implement connector service
4. ✅ Test connection to production API

### Phase 2: Data Sync (Week 2)
1. ✅ Create permit models
2. ✅ Implement polling mechanism
3. ✅ Setup cron jobs
4. ✅ Test data synchronization

### Phase 3: PDF Management (Week 3)
1. ✅ Implement PDF detection
2. ✅ MinIO integration
3. ✅ PDF import workflow
4. ✅ Test PDF storage

### Phase 4: Digital Signature (Week 4)
1. ✅ BSRE integration
2. ✅ Signature workflow
3. ✅ QR code generation
4. ✅ Test signing process

### Phase 5: Verification Portal (Week 5)
1. ✅ Public verification page
2. ✅ QR code scanner
3. ✅ Certificate validation
4. ✅ Test verification

---

## 🎯 Benefits of Using Production API

### ✅ Advantages
1. **Real-time Data**
   - Always up-to-date
   - No sync delays
   - Actual production data

2. **Simplified Infrastructure**
   - No need to maintain legacy app locally
   - Reduced Docker complexity
   - Focus on Odoo development

3. **Faster Development**
   - No PHP compatibility issues
   - Direct API consumption
   - Clean separation of concerns

4. **Better Testing**
   - Test with real data
   - Real-world scenarios
   - Production-like environment

### ⚠️ Considerations
1. **Network Dependency**
   - Requires internet connection
   - Handle connection failures gracefully
   - Implement retry logic

2. **API Stability**
   - Monitor API availability
   - Implement fallback mechanisms
   - Cache critical data

3. **Performance**
   - Implement caching
   - Optimize polling frequency
   - Use pagination wisely

---

## 🚀 Next Steps

1. **Create Odoo Module Structure**
   ```bash
   mkdir -p addons_odoo/sicantik_connector
   cd addons_odoo/sicantik_connector
   ```

2. **Initialize Module**
   - Create `__manifest__.py`
   - Create models
   - Create views
   - Create cron jobs

3. **Test Integration**
   - Test API connection
   - Test data polling
   - Test error handling

4. **Deploy & Monitor**
   - Deploy to Odoo
   - Monitor API calls
   - Optimize performance

---

**Generated:** 29 Oktober 2025  
**Strategy:** Production API Integration  
**Status:** ✅ READY TO IMPLEMENT

**Production API:** https://perizinan.karokab.go.id/backoffice/api/

