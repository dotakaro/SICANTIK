# Fix Embedded Git Repository - odoo_source

## 🎯 Masalah

Folder `odoo_source` kosong terkirim ke GitHub karena folder tersebut adalah **embedded git repository** (ada folder `.git` di dalamnya).

Git tidak akan track isi folder yang punya `.git` sendiri, hanya folder kosong sebagai pointer.

## 🔧 Solusi

### Opsi 1: Hapus .git dari odoo_source (Recommended)

Jika `odoo_source` tidak perlu sebagai git repository terpisah:

```bash
# 1. Backup .git folder (optional)
mv odoo_source/.git odoo_source/.git.backup

# 2. Remove dari Git cache
git rm --cached odoo_source

# 3. Add folder dengan isinya (tanpa .git)
git add odoo_source/

# 4. Commit dan push
git commit -m "fix: add odoo_source contents (remove embedded git repo)"
git push
```

### Opsi 2: Gunakan Git Submodule (Jika Perlu Repo Terpisah)

Jika `odoo_source` perlu tetap sebagai repository terpisah:

```bash
# 1. Remove dari Git cache
git rm --cached odoo_source

# 2. Add sebagai submodule
git submodule add <repository-url> odoo_source

# 3. Commit dan push
git commit -m "feat: add odoo_source as submodule"
git push
```

### Opsi 3: Copy Isi Tanpa .git

Jika ingin tetap ada `.git` di `odoo_source` tapi juga track isinya:

```bash
# 1. Copy isi ke folder baru
cp -r odoo_source odoo_source_content

# 2. Remove .git dari copy
rm -rf odoo_source_content/.git

# 3. Remove odoo_source dari Git
git rm --cached odoo_source

# 4. Add folder baru
git add odoo_source_content/

# 5. Rename kembali (optional)
mv odoo_source_content odoo_source

# 6. Commit dan push
git commit -m "fix: add odoo_source contents"
git push
```

## ✅ Verifikasi

Setelah fix:

```bash
# Check files ter-track
git ls-files odoo_source/ | wc -l

# Harus lebih dari 1 (bukan hanya folder kosong)

# Check status
git status odoo_source/
```

## 📝 Catatan

- **Embedded git repository** terjadi ketika ada folder `.git` di dalam folder yang di-track
- Git akan melihatnya sebagai submodule atau folder kosong
- Solusi terbaik: hapus `.git` jika tidak diperlukan, atau gunakan submodule jika diperlukan

