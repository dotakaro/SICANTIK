# 🔄 Odoo 18 Cron Job Changes

**Date:** 29 Oktober 2025  
**Issue:** Invalid field errors for cron jobs  
**Status:** ✅ RESOLVED

---

## 🐛 PROBLEM

### **Error 1: numbercall**
```
ValueError: Invalid field 'numbercall' on model 'ir.cron'
```

### **Error 2: repeat_count**
```
ValueError: Invalid field 'repeat_count' on model 'ir.cron'
```

---

## 📊 DEPRECATED FIELDS IN ODOO 18

### **Fields Removed:**
```
❌ numbercall    - Number of calls (deprecated)
❌ doall         - Execute all missed runs (deprecated)
❌ repeat_count  - Repeat count (deprecated)
```

### **Why Removed:**
- Simplified cron configuration
- Cron jobs now run indefinitely by default
- More intuitive behavior
- Less configuration needed

---

## ✅ ODOO 18 CRON CONFIGURATION

### **Required Fields:**
```xml
<record id="cron_job_id" model="ir.cron">
    <field name="name">Job Name</field>
    <field name="model_id" ref="model_name"/>
    <field name="state">code</field>
    <field name="code">model.method_name()</field>
    <field name="interval_number">1</field>
    <field name="interval_type">days</field>
    <field name="active" eval="True"/>
    <field name="priority">10</field>
</record>
```

### **Field Descriptions:**

| Field | Type | Description | Example |
|-------|------|-------------|---------|
| `name` | String | Display name | "SICANTIK: Update Permits" |
| `model_id` | Many2one | Target model reference | `ref="model_sicantik_permit"` |
| `state` | Selection | Execution type | `code` or `object_write` |
| `code` | Text | Python code to execute | `model.cron_update_expired_permits()` |
| `interval_number` | Integer | Frequency number | `1` |
| `interval_type` | Selection | Time unit | `days`, `hours`, `minutes` |
| `active` | Boolean | Enable/disable job | `True` or `False` |
| `priority` | Integer | Execution priority (0-100) | `5`, `10`, `15` |

---

## 🔧 MIGRATION GUIDE

### **From Odoo 17 to Odoo 18:**

**OLD (Odoo 17):**
```xml
<record id="cron_job" model="ir.cron">
    <field name="name">Job Name</field>
    <field name="model_id" ref="model_name"/>
    <field name="state">code</field>
    <field name="code">model.method()</field>
    <field name="interval_number">1</field>
    <field name="interval_type">days</field>
    <field name="numbercall">-1</field>        <!-- ❌ REMOVE -->
    <field name="doall" eval="False"/>         <!-- ❌ REMOVE -->
    <field name="active" eval="True"/>
    <field name="priority">5</field>
</record>
```

**NEW (Odoo 18):**
```xml
<record id="cron_job" model="ir.cron">
    <field name="name">Job Name</field>
    <field name="model_id" ref="model_name"/>
    <field name="state">code</field>
    <field name="code">model.method()</field>
    <field name="interval_number">1</field>
    <field name="interval_type">days</field>
    <!-- numbercall removed -->
    <!-- doall removed -->
    <field name="active" eval="True"/>
    <field name="priority">5</field>
</record>
```

### **Changes:**
1. ✅ Remove `numbercall` field
2. ✅ Remove `doall` field
3. ✅ Remove `repeat_count` field (if used)
4. ✅ Keep all other fields

---

## 📝 SICANTIK CONNECTOR CRON JOBS

### **Current Configuration:**

**1. Update Expired Permits (Daily 00:00)**
```xml
<record id="cron_update_expired_permits" model="ir.cron">
    <field name="name">SICANTIK: Update Expired Permits</field>
    <field name="model_id" ref="model_sicantik_permit"/>
    <field name="state">code</field>
    <field name="code">model.cron_update_expired_permits()</field>
    <field name="interval_number">1</field>
    <field name="interval_type">days</field>
    <field name="active" eval="True"/>
    <field name="priority">5</field>
</record>
```

**2. Sync Expiry Dates (Daily 02:00)**
```xml
<record id="cron_sync_expiry_dates" model="ir.cron">
    <field name="name">SICANTIK: Sync Expiry Dates (Workaround)</field>
    <field name="model_id" ref="model_sicantik_connector"/>
    <field name="state">code</field>
    <field name="code">model.cron_sync_expiry_dates()</field>
    <field name="interval_number">1</field>
    <field name="interval_type">days</field>
    <field name="active" eval="True"/>
    <field name="priority">10</field>
</record>
```

**3. Check Expiring Permits (Daily 09:00)**
```xml
<record id="cron_check_expiring_permits" model="ir.cron">
    <field name="name">SICANTIK: Check Expiring Permits</field>
    <field name="model_id" ref="model_sicantik_permit"/>
    <field name="state">code</field>
    <field name="code">model.cron_check_expiring_permits()</field>
    <field name="interval_number">1</field>
    <field name="interval_type">days</field>
    <field name="active" eval="True"/>
    <field name="priority">15</field>
</record>
```

---

## 🎯 BEHAVIOR CHANGES

### **Odoo 17 Behavior:**
```
numbercall = -1  → Run indefinitely
numbercall = 5   → Run 5 times then stop
doall = True     → Execute all missed runs
doall = False    → Skip missed runs
```

### **Odoo 18 Behavior:**
```
✅ Always runs indefinitely (no limit)
✅ Automatically skips missed runs
✅ Simpler configuration
✅ More predictable behavior
```

---

## 🔍 VERIFICATION

### **Check Cron Jobs:**

**Method 1: Via UI**
```
1. Login to Odoo
2. Go to Settings
3. Activate Developer Mode
4. Go to Technical > Automation > Scheduled Actions
5. Search for "SICANTIK"
6. Verify all 3 cron jobs are listed
```

**Method 2: Via Database**
```bash
docker exec postgres_companion_standalone psql -U odoo -d sicantik_companion_standalone -c "SELECT name, active, interval_number, interval_type, priority FROM ir_cron WHERE name LIKE 'SICANTIK%';"
```

**Method 3: Via Logs**
```bash
docker-compose logs odoo_companion_standalone | grep cron
```

---

## 🚨 TROUBLESHOOTING

### **Problem: Cron not executing**

**Check:**
```sql
-- Check if cron is active
SELECT name, active, nextcall FROM ir_cron WHERE name LIKE 'SICANTIK%';

-- Check last execution
SELECT name, lastcall FROM ir_cron WHERE name LIKE 'SICANTIK%';
```

**Solution:**
```
1. Verify 'active' = True
2. Check 'nextcall' timestamp
3. Verify method exists in model
4. Check Odoo logs for errors
```

### **Problem: "Invalid field" error**

**Solution:**
```
1. Remove deprecated fields:
   - numbercall
   - doall
   - repeat_count
2. Restart Odoo
3. Reinstall module
```

---

## 💡 BEST PRACTICES

### **Cron Configuration:**
1. ✅ Use descriptive names
2. ✅ Set appropriate priorities (lower = higher priority)
3. ✅ Use reasonable intervals (avoid too frequent)
4. ✅ Add error handling in methods
5. ✅ Log execution results

### **Priority Guidelines:**
```
Priority 0-5:   Critical jobs (system maintenance)
Priority 10-20: Important jobs (data sync)
Priority 30-50: Normal jobs (notifications)
Priority 60+:   Low priority jobs (cleanup)
```

### **Interval Guidelines:**
```
Minutes: Real-time updates (use sparingly)
Hours:   Frequent updates (monitoring)
Days:    Regular maintenance (most common)
Weeks:   Periodic tasks (reports)
Months:  Rare tasks (archiving)
```

---

## 📚 REFERENCES

### **Official Documentation:**
- Odoo 18 Cron Documentation: https://www.odoo.com/documentation/18.0/developer/reference/backend/orm.html#cron-jobs
- Migration Guide: https://www.odoo.com/documentation/18.0/developer/reference/upgrades.html

### **Related Files:**
- `addons_odoo/sicantik_connector/data/cron_data.xml`
- `addons_odoo/sicantik_connector/models/sicantik_permit.py`
- `addons_odoo/sicantik_connector/models/sicantik_connector.py`

---

## 🎊 SUMMARY

### **Changes Made:**
✅ Removed `numbercall` field  
✅ Removed `doall` field  
✅ Removed `repeat_count` field  
✅ Updated all 3 cron jobs  
✅ Verified Odoo restart successful  

### **Result:**
✅ No errors in logs  
✅ Cron jobs configured correctly  
✅ Module ready for installation  
✅ Odoo 18 compatible  

### **Status:**
```
Configuration: ✅ CORRECT
Compatibility: ✅ ODOO 18
Errors:        ✅ NONE
Ready:         ✅ YES
```

---

**Last Updated:** 29 Oktober 2025  
**Odoo Version:** 18.0  
**Status:** ✅ RESOLVED

**🔄 Cron jobs sekarang fully compatible dengan Odoo 18! 🚀**

