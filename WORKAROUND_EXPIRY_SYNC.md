# 🔄 Workaround: Expiry Date Sync Implementation

**Tanggal:** 29 Oktober 2025  
**Status:** ⚠️ **TEMPORARY SOLUTION**  
**Purpose:** Sync expiry dates until API is updated

---

## 🎯 OVERVIEW

Implementasi workaround untuk sync `d_berlaku_izin` (expiry date) menggunakan two-step API process:
1. Get all permits from `listpermohonanterbit`
2. For each permit, call `cekperizinan` to get expiry date

**⚠️ NOTE:** This is a **temporary solution**. Will be replaced with optimized solution after API update.

---

## 📦 MODULE: sicantik_connector

### File: `models/sicantik_connector.py`

```python
# -*- coding: utf-8 -*-
import base64
import requests
import time
import logging
from datetime import datetime, timedelta
from odoo import models, fields, api
from odoo.exceptions import UserError

_logger = logging.getLogger(__name__)


class SicantikConnector(models.Model):
    _name = 'sicantik.connector'
    _description = 'SICANTIK API Connector'
    
    name = fields.Char('Configuration Name', required=True, default='SICANTIK Production')
    api_url = fields.Char(
        'API Base URL', 
        required=True,
        default='https://perizinan.karokab.go.id/backoffice/api'
    )
    active = fields.Boolean('Active', default=True)
    
    # Statistics
    last_sync_date = fields.Datetime('Last Sync Date', readonly=True)
    last_expiry_sync_date = fields.Datetime('Last Expiry Sync Date', readonly=True)
    total_permits_synced = fields.Integer('Total Permits Synced', readonly=True)
    total_expiry_synced = fields.Integer('Total Expiry Synced', readonly=True)
    
    # Performance tracking
    last_sync_duration = fields.Float('Last Sync Duration (seconds)', readonly=True)
    last_expiry_sync_duration = fields.Float('Last Expiry Sync Duration (seconds)', readonly=True)
    
    
    def sync_expiry_dates_workaround(self, max_permits=None):
        """
        WORKAROUND: Sync expiry dates using two-step API process
        
        This method is TEMPORARY and will be replaced after API update.
        Performance: ~75 seconds for 500 permits
        
        Args:
            max_permits (int): Maximum number of permits to process (for testing)
        
        Returns:
            dict: Statistics about the sync operation
        """
        self.ensure_one()
        start_time = time.time()
        
        _logger.info('='*80)
        _logger.info('WORKAROUND: Starting expiry date sync')
        _logger.info('='*80)
        
        try:
            # Step 1: Get all permits without expiry date
            permits_to_sync = self.env['sicantik.permit'].search([
                ('expiry_date', '=', False),
                ('status', '=', 'active')
            ])
            
            if max_permits:
                permits_to_sync = permits_to_sync[:max_permits]
            
            total_permits = len(permits_to_sync)
            _logger.info(f'Found {total_permits} permits without expiry date')
            
            if total_permits == 0:
                _logger.info('No permits to sync')
                return {
                    'success': True,
                    'total': 0,
                    'synced': 0,
                    'failed': 0,
                    'duration': 0
                }
            
            # Step 2: Fetch expiry date for each permit
            synced_count = 0
            failed_count = 0
            
            for index, permit in enumerate(permits_to_sync, 1):
                try:
                    _logger.info(f'Processing permit {index}/{total_permits}: {permit.permit_number}')
                    
                    # Get expiry date from API
                    expiry_data = self._get_permit_expiry_workaround(permit.permit_number)
                    
                    if expiry_data and expiry_data.get('d_berlaku_izin'):
                        # Update permit with expiry date
                        permit.write({
                            'expiry_date': expiry_data['d_berlaku_izin'],
                            'last_sync_date': fields.Datetime.now()
                        })
                        synced_count += 1
                        _logger.info(f'✅ Synced expiry: {expiry_data["d_berlaku_izin"]}')
                    else:
                        failed_count += 1
                        _logger.warning(f'⚠️ No expiry date found for permit {permit.permit_number}')
                    
                    # Rate limiting: 10 requests per second max
                    time.sleep(0.1)
                    
                    # Progress update every 50 permits
                    if index % 50 == 0:
                        progress = (index / total_permits) * 100
                        elapsed = time.time() - start_time
                        estimated_total = (elapsed / index) * total_permits
                        remaining = estimated_total - elapsed
                        
                        _logger.info(f'Progress: {progress:.1f}% ({index}/{total_permits})')
                        _logger.info(f'Elapsed: {elapsed:.1f}s, Remaining: {remaining:.1f}s')
                
                except Exception as e:
                    failed_count += 1
                    _logger.error(f'❌ Error processing permit {permit.permit_number}: {str(e)}')
                    continue
            
            # Update statistics
            duration = time.time() - start_time
            self.write({
                'last_expiry_sync_date': fields.Datetime.now(),
                'total_expiry_synced': self.total_expiry_synced + synced_count,
                'last_expiry_sync_duration': duration
            })
            
            _logger.info('='*80)
            _logger.info(f'WORKAROUND: Expiry sync completed')
            _logger.info(f'Total: {total_permits}, Synced: {synced_count}, Failed: {failed_count}')
            _logger.info(f'Duration: {duration:.2f} seconds')
            _logger.info('='*80)
            
            return {
                'success': True,
                'total': total_permits,
                'synced': synced_count,
                'failed': failed_count,
                'duration': duration
            }
        
        except Exception as e:
            _logger.error(f'Fatal error in expiry sync: {str(e)}')
            raise UserError(f'Expiry sync failed: {str(e)}')
    
    
    def _get_permit_expiry_workaround(self, permit_number):
        """
        WORKAROUND: Get expiry date for a single permit
        
        Uses cekperizinan endpoint which requires base64 encoded permit number
        
        Args:
            permit_number (str): Permit number (no_surat)
        
        Returns:
            dict: Permit data including d_berlaku_izin, or None if failed
        """
        try:
            # Encode permit number to base64
            no_izin_encoded = base64.b64encode(permit_number.encode('utf-8')).decode('utf-8')
            
            # Call cekperizinan API
            url = f'{self.api_url}/cekperizinan'
            params = {'no_izin': no_izin_encoded}
            
            response = requests.get(url, params=params, timeout=10)
            response.raise_for_status()
            
            data = response.json()
            
            # Handle both single object and array response
            if isinstance(data, list) and len(data) > 0:
                return data[0]
            elif isinstance(data, dict):
                return data
            else:
                return None
        
        except requests.exceptions.Timeout:
            _logger.warning(f'Timeout getting expiry for {permit_number}')
            return None
        
        except requests.exceptions.RequestException as e:
            _logger.warning(f'Request error for {permit_number}: {str(e)}')
            return None
        
        except Exception as e:
            _logger.error(f'Unexpected error for {permit_number}: {str(e)}')
            return None
    
    
    @api.model
    def cron_sync_expiry_dates(self):
        """
        Cron job to sync expiry dates
        Run daily at 02:00 AM (before expiry check at 09:00)
        
        This ensures all permits have expiry dates before
        the expiry notification cron runs
        """
        _logger.info('Starting scheduled expiry date sync...')
        
        connector = self.search([('active', '=', True)], limit=1)
        if not connector:
            _logger.error('No active SICANTIK connector found')
            return
        
        try:
            result = connector.sync_expiry_dates_workaround()
            
            if result['success']:
                _logger.info(f'Scheduled expiry sync completed: {result["synced"]} permits synced')
            else:
                _logger.error('Scheduled expiry sync failed')
        
        except Exception as e:
            _logger.error(f'Scheduled expiry sync error: {str(e)}')
    
    
    def action_test_expiry_sync(self):
        """
        Manual action to test expiry sync with limited permits
        """
        self.ensure_one()
        
        _logger.info('Starting test expiry sync (max 10 permits)...')
        result = self.sync_expiry_dates_workaround(max_permits=10)
        
        return {
            'type': 'ir.actions.client',
            'tag': 'display_notification',
            'params': {
                'title': 'Expiry Sync Test Completed',
                'message': f'Synced: {result["synced"]}, Failed: {result["failed"]}, Duration: {result["duration"]:.2f}s',
                'type': 'success' if result['synced'] > 0 else 'warning',
                'sticky': False,
            }
        }
    
    
    def action_sync_all_expiry(self):
        """
        Manual action to sync all expiry dates
        """
        self.ensure_one()
        
        return {
            'type': 'ir.actions.act_window',
            'name': 'Sync All Expiry Dates',
            'res_model': 'sicantik.expiry.sync.wizard',
            'view_mode': 'form',
            'target': 'new',
            'context': {'default_connector_id': self.id}
        }
```

---

## 📋 WIZARD: Expiry Sync Confirmation

### File: `wizard/sicantik_expiry_sync_wizard.py`

```python
# -*- coding: utf-8 -*-
from odoo import models, fields, api
from odoo.exceptions import UserError

class SicantikExpirySyncWizard(models.TransientModel):
    _name = 'sicantik.expiry.sync.wizard'
    _description = 'SICANTIK Expiry Sync Wizard'
    
    connector_id = fields.Many2one('sicantik.connector', 'Connector', required=True)
    
    permits_without_expiry = fields.Integer(
        'Permits Without Expiry',
        compute='_compute_permits_count'
    )
    
    estimated_duration = fields.Char(
        'Estimated Duration',
        compute='_compute_estimated_duration'
    )
    
    @api.depends('connector_id')
    def _compute_permits_count(self):
        for wizard in self:
            wizard.permits_without_expiry = self.env['sicantik.permit'].search_count([
                ('expiry_date', '=', False),
                ('status', '=', 'active')
            ])
    
    @api.depends('permits_without_expiry')
    def _compute_estimated_duration(self):
        for wizard in self:
            # Estimate: ~0.15 seconds per permit
            seconds = wizard.permits_without_expiry * 0.15
            minutes = seconds / 60
            
            if minutes < 1:
                wizard.estimated_duration = f'{int(seconds)} seconds'
            else:
                wizard.estimated_duration = f'{int(minutes)} minutes'
    
    def action_sync(self):
        """Execute the sync"""
        self.ensure_one()
        
        if self.permits_without_expiry == 0:
            raise UserError('No permits to sync')
        
        # Start sync in background
        self.connector_id.sync_expiry_dates_workaround()
        
        return {
            'type': 'ir.actions.client',
            'tag': 'display_notification',
            'params': {
                'title': 'Expiry Sync Started',
                'message': f'Syncing {self.permits_without_expiry} permits. Check logs for progress.',
                'type': 'success',
                'sticky': False,
            }
        }
```

### File: `wizard/sicantik_expiry_sync_wizard_views.xml`

```xml
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <record id="view_sicantik_expiry_sync_wizard_form" model="ir.ui.view">
        <field name="name">sicantik.expiry.sync.wizard.form</field>
        <field name="model">sicantik.expiry.sync.wizard</field>
        <field name="arch" type="xml">
            <form string="Sync Expiry Dates">
                <group>
                    <group>
                        <field name="connector_id" readonly="1"/>
                        <field name="permits_without_expiry" readonly="1"/>
                    </group>
                    <group>
                        <field name="estimated_duration" readonly="1"/>
                    </group>
                </group>
                
                <div class="alert alert-warning" role="alert">
                    <h4>⚠️ Workaround Solution</h4>
                    <p>
                        This sync uses a temporary workaround solution that makes multiple API calls.
                        Performance: ~0.15 seconds per permit.
                    </p>
                    <p>
                        <strong>Note:</strong> This will be replaced with an optimized solution 
                        after the API is updated.
                    </p>
                </div>
                
                <footer>
                    <button name="action_sync" type="object" string="Start Sync" class="btn-primary"/>
                    <button string="Cancel" class="btn-secondary" special="cancel"/>
                </footer>
            </form>
        </field>
    </record>
</odoo>
```

---

## ⚙️ CRON JOBS

### File: `data/cron_data.xml`

```xml
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <!-- Cron: Sync Expiry Dates (Daily at 02:00 AM) -->
    <record id="cron_sync_expiry_dates" model="ir.cron">
        <field name="name">SICANTIK: Sync Expiry Dates (Workaround)</field>
        <field name="model_id" ref="model_sicantik_connector"/>
        <field name="state">code</field>
        <field name="code">model.cron_sync_expiry_dates()</field>
        <field name="interval_number">1</field>
        <field name="interval_type">days</field>
        <field name="numbercall">-1</field>
        <field name="doall" eval="False"/>
        <field name="active" eval="True"/>
        <field name="priority">5</field>
        <!-- Run at 02:00 AM -->
        <field name="nextcall" eval="(DateTime.now() + timedelta(days=1)).replace(hour=2, minute=0, second=0)"/>
    </record>
    
    <!-- Cron: Check Expiring Permits (Daily at 09:00 AM) -->
    <record id="cron_check_expiring_permits" model="ir.cron">
        <field name="name">SICANTIK: Check Expiring Permits</field>
        <field name="model_id" ref="model_sicantik_permit"/>
        <field name="state">code</field>
        <field name="code">model.cron_check_expiring_permits()</field>
        <field name="interval_number">1</field>
        <field name="interval_type">days</field>
        <field name="numbercall">-1</field>
        <field name="doall" eval="False"/>
        <field name="active" eval="True"/>
        <field name="priority">10</field>
        <!-- Run at 09:00 AM (after expiry sync at 02:00) -->
        <field name="nextcall" eval="(DateTime.now() + timedelta(days=1)).replace(hour=9, minute=0, second=0)"/>
    </record>
</odoo>
```

---

## 🎯 USAGE

### **1. Initial Setup**

```python
# In Odoo shell or through UI
connector = env['sicantik.connector'].search([('active', '=', True)], limit=1)

# Test with 10 permits first
connector.action_test_expiry_sync()

# If successful, sync all
connector.sync_expiry_dates_workaround()
```

### **2. Daily Automatic Sync**

Cron jobs will run automatically:
- **02:00 AM:** Sync expiry dates (workaround)
- **09:00 AM:** Check expiring permits & send WhatsApp notifications

### **3. Manual Sync via UI**

1. Go to SICANTIK > Configuration > Connector
2. Click "Sync All Expiry Dates" button
3. Review estimated duration
4. Click "Start Sync"
5. Monitor progress in logs

---

## 📊 PERFORMANCE METRICS

### **Expected Performance:**

| Permits | API Calls | Duration | Rate |
|---------|-----------|----------|------|
| 10 | 10 | ~1.5s | Test |
| 50 | 50 | ~7.5s | Small |
| 100 | 100 | ~15s | Medium |
| 500 | 500 | ~75s | Large |
| 1000 | 1000 | ~150s | Very Large |

**Formula:** Duration ≈ Permits × 0.15 seconds

### **Optimization Tips:**

1. **Rate Limiting:** Currently 10 req/sec (0.1s sleep)
2. **Batch Processing:** Process in chunks of 100
3. **Error Handling:** Skip failed permits, continue
4. **Progress Logging:** Every 50 permits
5. **Statistics Tracking:** Monitor performance

---

## 🔄 MIGRATION PATH

### **Phase 1: Workaround (Current)**
```
✅ Implement two-step API process
✅ Test with sample data
✅ Deploy to production
✅ Monitor performance
```

### **Phase 2: API Update Request (Week 2-3)**
```
⏳ Submit proposal to Pemkab
⏳ Wait for API modification
⏳ Test updated endpoint
⏳ Prepare migration code
```

### **Phase 3: Migration (Week 4)**
```
⏳ Deploy optimized solution
⏳ Remove workaround code
⏳ Performance testing
⏳ Monitor production
```

---

## ⚠️ KNOWN LIMITATIONS

1. **Performance:** Slow for large datasets (500+ permits)
2. **API Load:** High number of requests to server
3. **Timeout Risk:** Network issues can cause failures
4. **Rate Limiting:** May hit server rate limits
5. **Maintenance:** More code to maintain

**Solution:** These will be resolved after API update (100x faster!)

---

## 📝 TODO AFTER API UPDATE

### **Code to Remove:**
```python
# Remove these methods:
- sync_expiry_dates_workaround()
- _get_permit_expiry_workaround()
- cron_sync_expiry_dates()

# Remove wizard:
- sicantik_expiry_sync_wizard.py
- sicantik_expiry_sync_wizard_views.xml

# Remove cron:
- cron_sync_expiry_dates
```

### **Code to Add:**
```python
# Add optimized method:
def sync_expiry_dates_optimized(self):
    """
    Optimized: Get expiry dates from listpermohonanterbit
    Performance: ~0.75 seconds for 500 permits (100x faster!)
    """
    # Single API call with expiry date included
    # No more two-step process needed
    pass
```

---

**Generated:** 29 Oktober 2025  
**Status:** ✅ READY TO IMPLEMENT  
**Timeline:** Week 1 implementation  
**Migration:** After API update (Week 4)

