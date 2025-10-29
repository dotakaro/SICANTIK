# 🏢 Odoo Enterprise Activation Guide

**Date:** 29 Oktober 2025  
**Odoo Version:** 18.0  
**Status:** Enterprise modules available, activation needed

---

## 📊 CURRENT STATUS

### **Enterprise Folder** ✅
```bash
✅ Location: ./enterprise (1,313 modules)
✅ Mounted: /mnt/enterprise-addons
✅ Detected by Odoo
✅ Watching active
```

### **Addons Path** ✅
```
addons_path = /mnt/extra-addons,/mnt/enterprise-addons,/usr/lib/python3/dist-packages/odoo/addons
```

### **Verification:**
```bash
$ docker exec odoo_companion_standalone ls /mnt/enterprise-addons/ | wc -l
1313  # modules available

$ docker-compose logs odoo_companion_standalone | grep enterprise
✅ Watching addons folder /mnt/enterprise-addons
```

---

## 🔑 ENTERPRISE ACTIVATION OPTIONS

### **Option 1: Odoo.com Account (Recommended)**

**Steps:**
1. Login to Odoo at http://localhost:8065
2. Go to **Settings** (⚙️ icon)
3. Scroll to **About** section
4. Click **"Activate the Enterprise Edition"**
5. Enter your **Odoo.com credentials**
6. Enter **subscription code** or **contract number**
7. Click **Activate**

**Requirements:**
- Valid Odoo.com account
- Active enterprise subscription
- Internet connection

---

### **Option 2: License File (Offline)**

**Steps:**

1. **Get License File:**
   - Download from Odoo.com account
   - Or request from Odoo partner
   - File format: `odoo_enterprise.lic` or similar

2. **Copy to Container:**
```bash
# Copy license file to Odoo data directory
docker cp odoo_enterprise.lic odoo_companion_standalone:/var/lib/odoo/

# Or mount as volume (add to docker-compose.yml)
volumes:
  - ./odoo_enterprise.lic:/var/lib/odoo/odoo_enterprise.lic:ro
```

3. **Restart Odoo:**
```bash
docker-compose restart odoo_companion_standalone
```

4. **Verify Activation:**
   - Login to Odoo
   - Go to Settings
   - Check if "Enterprise Edition" badge appears

---

### **Option 3: Development/Testing (30 Days Trial)**

**Steps:**

1. **Access Odoo:**
   - Open http://localhost:8065
   - Login as admin

2. **Install Enterprise Module:**
   - Go to **Apps**
   - Remove filters
   - Search for any enterprise module (e.g., "Accounting")
   - Click **Install**

3. **Trial Activation:**
   - Odoo will show trial activation dialog
   - Click **"Start Trial"**
   - Enter email address
   - Confirm activation

**Note:** Trial is valid for 30 days

---

### **Option 4: Partner License**

If you have Odoo partner license:

1. **Contact Your Partner:**
   - Request license file
   - Or subscription activation code

2. **Follow Option 1 or 2** above

---

## 🔍 VERIFICATION

### **Check Enterprise Status:**

**Method 1: Via UI**
```
1. Login to Odoo
2. Go to Settings
3. Look for "Enterprise Edition" badge
4. Check "About" section for license info
```

**Method 2: Via Database**
```bash
# Check if enterprise modules are installed
docker exec postgres_companion_standalone psql -U odoo -d sicantik_companion_standalone -c "SELECT name, state FROM ir_module_module WHERE name LIKE '%enterprise%' OR name LIKE 'web_enterprise';"
```

**Method 3: Via Logs**
```bash
# Check for enterprise activation messages
docker-compose logs odoo_companion_standalone | grep -i "enterprise\|license"
```

---

## 📦 AVAILABLE ENTERPRISE MODULES

### **Key Enterprise Modules:**
```
✅ account_accountant      - Advanced Accounting
✅ web_enterprise          - Enterprise UI
✅ web_studio              - Studio (App Builder)
✅ helpdesk                - Helpdesk
✅ project_enterprise      - Advanced Project
✅ sale_subscription       - Subscriptions
✅ mrp_workorder          - Manufacturing
✅ quality_control         - Quality
✅ documents               - Documents Management
✅ sign                    - eSignature
✅ voip                    - VoIP Integration
✅ social                  - Social Marketing
✅ marketing_automation    - Marketing Automation
... and 1,300+ more modules
```

### **Check Available Modules:**
```bash
# List all enterprise modules
docker exec odoo_companion_standalone ls /mnt/enterprise-addons/

# Count modules
docker exec odoo_companion_standalone ls /mnt/enterprise-addons/ | wc -l
```

---

## 🚨 TROUBLESHOOTING

### **Problem: Enterprise modules not visible**

**Solution:**
```bash
# 1. Verify enterprise folder mounted
docker exec odoo_companion_standalone ls /mnt/enterprise-addons/

# 2. Check addons_path
docker exec odoo_companion_standalone cat /etc/odoo/odoo.conf | grep addons_path

# 3. Restart Odoo
docker-compose restart odoo_companion_standalone

# 4. Update Apps List in UI
# Go to Apps > Update Apps List
```

### **Problem: License expired**

**Solution:**
```
1. Contact Odoo support or partner
2. Renew subscription
3. Get new license file
4. Follow activation steps above
```

### **Problem: "Invalid license" error**

**Solution:**
```bash
# 1. Remove old license
docker exec odoo_companion_standalone rm /var/lib/odoo/*.lic

# 2. Copy new license
docker cp new_license.lic odoo_companion_standalone:/var/lib/odoo/

# 3. Restart Odoo
docker-compose restart odoo_companion_standalone
```

---

## 💡 RECOMMENDATIONS

### **For Development:**
1. ✅ Use 30-day trial
2. ✅ Test enterprise features
3. ✅ Evaluate modules needed
4. ✅ Plan production license

### **For Production:**
1. ✅ Purchase enterprise subscription
2. ✅ Get official license file
3. ✅ Activate via Odoo.com
4. ✅ Keep license file backed up

### **For Testing:**
1. ✅ Use trial activation
2. ✅ Test all required modules
3. ✅ Document features used
4. ✅ Prepare for purchase

---

## 📝 CURRENT CONFIGURATION

### **Docker Compose:**
```yaml
odoo_companion_standalone:
  volumes:
    - ./addons_odoo:/mnt/extra-addons          # ✅ Custom
    - ./enterprise:/mnt/enterprise-addons      # ✅ Enterprise
    - ./config_odoo:/etc/odoo                  # ✅ Config
```

### **Odoo Config:**
```ini
addons_path = /mnt/extra-addons,/mnt/enterprise-addons,/usr/lib/python3/dist-packages/odoo/addons
```

### **Status:**
```
✅ Enterprise folder: Available (1,313 modules)
✅ Mounted correctly: /mnt/enterprise-addons
✅ Detected by Odoo: Yes
⏳ Activated: Needs activation
```

---

## 🎯 NEXT STEPS

### **To Activate Enterprise:**

1. **Choose activation method:**
   - Option 1: Odoo.com account (if you have subscription)
   - Option 2: License file (if you have .lic file)
   - Option 3: Trial (for testing, 30 days)

2. **Follow steps** for chosen method above

3. **Verify activation:**
   - Check for "Enterprise Edition" badge
   - Try installing enterprise module
   - Verify in Settings > About

4. **Install enterprise modules** as needed

---

## 📞 SUPPORT

### **Odoo Official:**
- Website: https://www.odoo.com
- Support: https://www.odoo.com/help
- Documentation: https://www.odoo.com/documentation/18.0/

### **License Questions:**
- Email: sales@odoo.com
- Phone: Check Odoo.com for regional contacts

### **Partner Support:**
- Contact your Odoo implementation partner
- Request license activation assistance

---

## 🎊 SUMMARY

### **What's Working:**
✅ Enterprise modules available (1,313 modules)  
✅ Correctly mounted in container  
✅ Detected by Odoo  
✅ Addons path configured  

### **What's Needed:**
⏳ Enterprise activation (license or trial)  
⏳ Install enterprise modules  
⏳ Verify functionality  

### **How to Activate:**
1. Login to Odoo: http://localhost:8065
2. Go to Settings
3. Click "Activate Enterprise Edition"
4. Choose activation method
5. Follow prompts

---

**Last Updated:** 29 Oktober 2025  
**Status:** ✅ READY FOR ACTIVATION  
**Action:** Choose activation method and proceed

**🏢 Enterprise modules siap digunakan setelah aktivasi! 🚀**

