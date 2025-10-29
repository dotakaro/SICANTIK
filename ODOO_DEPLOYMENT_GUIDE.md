# 🚀 SICANTIK Odoo Companion - Deployment Guide

**Date:** 29 Oktober 2025  
**Version:** 1.0.0  
**Status:** ✅ READY TO DEPLOY

---

## 📋 OVERVIEW

Guide ini menjelaskan cara deploy **SICANTIK Connector Module** di Odoo 18 Enterprise menggunakan Docker.

---

## 🎯 QUICK START (Recommended)

### **Method 1: Using Quick Start Script** ⭐

```bash
# 1. Navigate to project directory
cd /Users/rimba/odoo-dev/SICANTIK

# 2. Run quick start script
./start-odoo-companion.sh

# 3. Wait for services to start (30-60 seconds)

# 4. Open browser
open http://localhost:8065
```

**That's it!** 🎉

---

## 🔧 MANUAL DEPLOYMENT

### **Method 2: Using Docker Compose**

```bash
# 1. Start PostgreSQL first
docker-compose up -d postgres_companion_standalone

# 2. Wait for PostgreSQL (10 seconds)
sleep 10

# 3. Start Odoo
docker-compose up -d odoo_companion_standalone

# 4. Check logs
docker-compose logs -f odoo_companion_standalone
```

---

## 📦 SERVICES INFORMATION

### **Odoo Companion**
- **URL:** http://localhost:8065
- **Container:** odoo_companion_standalone
- **Image:** odoo:18.0
- **Port:** 8065 → 8069

### **PostgreSQL**
- **Host:** localhost
- **Port:** 5435 → 5432
- **Database:** sicantik_companion_standalone
- **User:** odoo
- **Password:** odoo_password_secure

### **Volumes Mapping**
```
./addons_odoo          → /mnt/extra-addons       (Custom modules)
./enterprise           → /mnt/enterprise-addons  (Enterprise modules)
./config_odoo          → /etc/odoo               (Configuration)
./uploads              → /mnt/sicantik_uploads   (SICANTIK uploads)
```

---

## 🔐 DEFAULT CREDENTIALS

### **Odoo Admin**
```
Username: admin
Password: admin_odoo_secure_2025
Database: sicantik_companion_standalone
```

### **PostgreSQL**
```
Host: localhost
Port: 5435
Database: sicantik_companion_standalone
User: odoo
Password: odoo_password_secure
```

---

## 📥 INSTALLING SICANTIK CONNECTOR MODULE

### **Step 1: Access Odoo**
1. Open http://localhost:8065
2. Login with admin credentials
3. Select database: `sicantik_companion_standalone`

### **Step 2: Update Apps List**
1. Go to **Apps** menu (top menu)
2. Click **"Update Apps List"** (top right)
3. Confirm update

### **Step 3: Install Module**
1. Remove **"Apps"** filter (click X on filter)
2. Search for **"SICANTIK"**
3. Find **"SICANTIK Connector"**
4. Click **"Install"**
5. Wait 30-60 seconds

### **Step 4: Verify Installation**
1. Check **SICANTIK** menu appears (top menu)
2. Go to **SICANTIK > Configuration > API Configuration**
3. Verify default configuration exists
4. Click **"Test Connection"**
5. Verify success message

---

## ⚙️ CONFIGURATION

### **Odoo Configuration File**

Location: `./config_odoo/odoo.conf`

```ini
[options]
# Database
db_host = postgres_companion_standalone
db_port = 5432
db_user = odoo
db_password = odoo_password_secure
db_name = sicantik_companion_standalone

# Addons path - CRITICAL!
addons_path = /mnt/extra-addons,/mnt/enterprise-addons,/usr/lib/python3/dist-packages/odoo/addons

# Admin password
admin_passwd = admin_odoo_secure_2025

# Server
http_port = 8069
http_interface = 0.0.0.0

# Development mode
dev_mode = reload,qweb,werkzeug,xml
```

### **Docker Compose Configuration**

Key settings in `docker-compose.yml`:

```yaml
odoo_companion_standalone:
  image: odoo:18.0
  ports:
    - "8065:8069"
  volumes:
    - ./addons_odoo:/mnt/extra-addons           # ⭐ Custom modules
    - ./enterprise:/mnt/enterprise-addons       # ⭐ Enterprise
    - ./config_odoo:/etc/odoo                   # ⭐ Config
  command: ["odoo", "--config=/etc/odoo/odoo.conf", "--dev=all"]
```

---

## 🔍 VERIFICATION CHECKLIST

### **Container Status**
```bash
# Check if containers are running
docker ps | grep -E "odoo_companion|postgres_companion"

# Expected output:
# odoo_companion_standalone    - Up
# postgres_companion_standalone - Up
```

### **Odoo Logs**
```bash
# View Odoo logs
docker-compose logs -f odoo_companion_standalone

# Expected output:
# INFO sicantik_companion_standalone odoo.modules.loading: Modules loaded.
# INFO sicantik_companion_standalone odoo.service.server: HTTP service (werkzeug) running on 0.0.0.0:8069
```

### **Module Verification**
```bash
# Check if module is loaded
docker exec odoo_companion_standalone ls -la /mnt/extra-addons/sicantik_connector

# Expected output:
# drwxr-xr-x  sicantik_connector
# -rw-r--r--  __manifest__.py
# -rw-r--r--  __init__.py
# drwxr-xr-x  models/
# drwxr-xr-x  views/
# ...
```

### **Database Connection**
```bash
# Test PostgreSQL connection
docker exec postgres_companion_standalone psql -U odoo -d sicantik_companion_standalone -c "SELECT version();"

# Expected output:
# PostgreSQL 15.x ...
```

---

## 🛠️ COMMON COMMANDS

### **Start Services**
```bash
# Start all
./start-odoo-companion.sh

# Or manually
docker-compose up -d odoo_companion_standalone postgres_companion_standalone
```

### **Stop Services**
```bash
# Stop all
docker-compose stop odoo_companion_standalone postgres_companion_standalone

# Or force stop
docker-compose down odoo_companion_standalone postgres_companion_standalone
```

### **Restart Services**
```bash
# Restart Odoo only
docker-compose restart odoo_companion_standalone

# Restart all
docker-compose restart odoo_companion_standalone postgres_companion_standalone
```

### **View Logs**
```bash
# Follow logs
docker-compose logs -f odoo_companion_standalone

# Last 100 lines
docker-compose logs --tail=100 odoo_companion_standalone

# All logs
docker-compose logs odoo_companion_standalone
```

### **Access Container Shell**
```bash
# Odoo container
docker exec -it odoo_companion_standalone bash

# PostgreSQL container
docker exec -it postgres_companion_standalone bash
```

### **Database Operations**
```bash
# Backup database
docker exec postgres_companion_standalone pg_dump -U odoo sicantik_companion_standalone > backup.sql

# Restore database
cat backup.sql | docker exec -i postgres_companion_standalone psql -U odoo -d sicantik_companion_standalone

# List databases
docker exec postgres_companion_standalone psql -U odoo -c "\l"
```

---

## 🐛 TROUBLESHOOTING

### **Problem: Module not found**

**Symptoms:**
- Module tidak muncul di Apps list
- Error "Module not found"

**Solution:**
```bash
# 1. Check volume mapping
docker exec odoo_companion_standalone ls -la /mnt/extra-addons/

# 2. Restart Odoo
docker-compose restart odoo_companion_standalone

# 3. Update apps list in Odoo UI
```

### **Problem: Cannot connect to database**

**Symptoms:**
- Error "could not connect to server"
- Database connection failed

**Solution:**
```bash
# 1. Check PostgreSQL is running
docker ps | grep postgres_companion

# 2. Check PostgreSQL logs
docker-compose logs postgres_companion_standalone

# 3. Restart PostgreSQL
docker-compose restart postgres_companion_standalone

# 4. Wait 10 seconds, then restart Odoo
sleep 10
docker-compose restart odoo_companion_standalone
```

### **Problem: Port already in use**

**Symptoms:**
- Error "port is already allocated"
- Cannot start container

**Solution:**
```bash
# 1. Check what's using the port
lsof -i :8065

# 2. Stop the process or change port in docker-compose.yml
# Change: "8065:8069" to "8066:8069"

# 3. Restart services
docker-compose up -d odoo_companion_standalone
```

### **Problem: Permission denied**

**Symptoms:**
- Error "permission denied"
- Cannot read/write files

**Solution:**
```bash
# 1. Fix permissions
chmod -R 755 addons_odoo/
chmod -R 755 config_odoo/

# 2. Restart Odoo
docker-compose restart odoo_companion_standalone
```

### **Problem: Module installation fails**

**Symptoms:**
- Error during module installation
- Module status: "To Install" stuck

**Solution:**
```bash
# 1. Check Odoo logs
docker-compose logs -f odoo_companion_standalone

# 2. Check for Python errors
docker exec odoo_companion_standalone python3 -m py_compile /mnt/extra-addons/sicantik_connector/models/*.py

# 3. Restart Odoo in update mode
docker-compose stop odoo_companion_standalone
docker-compose run --rm odoo_companion_standalone odoo --config=/etc/odoo/odoo.conf --update=sicantik_connector --stop-after-init
docker-compose up -d odoo_companion_standalone
```

---

## 📊 MONITORING

### **Health Check**
```bash
# Check container health
docker ps --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}"

# Check Odoo process
docker exec odoo_companion_standalone ps aux | grep odoo

# Check PostgreSQL process
docker exec postgres_companion_standalone ps aux | grep postgres
```

### **Resource Usage**
```bash
# Check container stats
docker stats odoo_companion_standalone postgres_companion_standalone

# Check disk usage
docker system df

# Check volume size
docker volume ls
du -sh $(docker volume inspect sicantik_odoo_companion_data -f '{{.Mountpoint}}')
```

### **Log Monitoring**
```bash
# Monitor logs in real-time
docker-compose logs -f odoo_companion_standalone | grep -E "ERROR|WARNING|INFO"

# Search for specific errors
docker-compose logs odoo_companion_standalone | grep "ERROR"

# Export logs to file
docker-compose logs odoo_companion_standalone > odoo_logs.txt
```

---

## 🔄 UPDATE & MAINTENANCE

### **Update Module**
```bash
# 1. Make changes to module files
# 2. Restart Odoo
docker-compose restart odoo_companion_standalone

# 3. In Odoo UI, go to Apps
# 4. Find SICANTIK Connector
# 5. Click "Upgrade"
```

### **Update Odoo Image**
```bash
# 1. Pull latest image
docker pull odoo:18.0

# 2. Stop containers
docker-compose stop odoo_companion_standalone

# 3. Remove old container
docker rm odoo_companion_standalone

# 4. Start new container
docker-compose up -d odoo_companion_standalone
```

### **Backup & Restore**
```bash
# Backup everything
./scripts/backup.sh

# Backup database only
docker exec postgres_companion_standalone pg_dump -U odoo sicantik_companion_standalone > backup_$(date +%Y%m%d).sql

# Backup volumes
docker run --rm -v sicantik_odoo_companion_data:/data -v $(pwd):/backup ubuntu tar czf /backup/odoo_data_backup.tar.gz /data

# Restore database
cat backup_20251029.sql | docker exec -i postgres_companion_standalone psql -U odoo -d sicantik_companion_standalone
```

---

## 🚀 PRODUCTION DEPLOYMENT

### **Recommended Changes for Production**

1. **Change Passwords**
```ini
# In config_odoo/odoo.conf
admin_passwd = YOUR_STRONG_PASSWORD_HERE

# In docker-compose.yml
POSTGRES_PASSWORD: YOUR_DB_PASSWORD_HERE
```

2. **Disable Dev Mode**
```ini
# In config_odoo/odoo.conf
# Comment out or remove:
# dev_mode = reload,qweb,werkzeug,xml
```

3. **Configure Workers**
```ini
# In config_odoo/odoo.conf
workers = 4  # 2 * CPU cores + 1
```

4. **Enable SSL**
```yaml
# Use nginx reverse proxy with SSL
# See: nginx/conf.d/odoo.conf
```

5. **Set Database Filter**
```ini
# In config_odoo/odoo.conf
dbfilter = ^sicantik_companion_standalone$
list_db = False
```

---

## 📞 SUPPORT

### **Documentation**
- Module README: `addons_odoo/sicantik_connector/README.md`
- Deployment Checklist: `DEPLOYMENT_CHECKLIST.md`
- Implementation Summary: `IMPLEMENTATION_SUMMARY.md`

### **Logs Location**
- Odoo logs: `docker-compose logs odoo_companion_standalone`
- PostgreSQL logs: `docker-compose logs postgres_companion_standalone`
- Container logs: `/var/log/odoo/` (inside container)

### **Useful Links**
- Odoo Documentation: https://www.odoo.com/documentation/18.0/
- Docker Documentation: https://docs.docker.com/
- PostgreSQL Documentation: https://www.postgresql.org/docs/

---

## ✅ DEPLOYMENT CHECKLIST

- [ ] Docker Desktop running
- [ ] All files in place
- [ ] Permissions correct (755)
- [ ] PostgreSQL started
- [ ] Odoo started
- [ ] Web interface accessible (http://localhost:8065)
- [ ] Login successful
- [ ] Module visible in Apps
- [ ] Module installed successfully
- [ ] Configuration tested
- [ ] API connection tested
- [ ] First sync successful

---

**🎉 Happy Deploying! 🚀**

**Last Updated:** 29 Oktober 2025  
**Version:** 1.0.0  
**Status:** ✅ PRODUCTION READY

