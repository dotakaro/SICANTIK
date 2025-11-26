# Fix SSH Config untuk GitHub

## ✅ Status

Test dengan `ssh -T -i ~/.ssh/id_ed25519_github git@github.com` **berhasil**, berarti:
- ✅ SSH key sudah benar
- ✅ Public key sudah ditambahkan ke GitHub dengan benar
- ❌ SSH config belum dikonfigurasi dengan benar

## 🔧 Solusi

### Di Server Production, jalankan:

```bash
# 1. Buat/update SSH config
cat > ~/.ssh/config << 'EOF'
Host github.com
    HostName github.com
    User git
    IdentityFile ~/.ssh/id_ed25519_github
    IdentitiesOnly yes
EOF

# 2. Set permissions
chmod 600 ~/.ssh/config

# 3. Test tanpa -i flag
ssh -T git@github.com
```

Sekarang `git clone` dan command lainnya akan bekerja tanpa perlu specify `-i` flag.

## ✅ Verifikasi

```bash
# Test SSH connection (tanpa -i)
ssh -T git@github.com

# Harus muncul:
# Hi dotakaro! You've successfully authenticated, but GitHub does not provide shell access.

# Clone repository
git clone git@github.com:dotakaro/SICANTIK.git /opt/sicantik

# Check remote
cd /opt/sicantik
git remote -v
```

## 📝 Penjelasan

**IdentitiesOnly yes** memastikan SSH hanya menggunakan key yang di-specify di config, tidak mencari key lain di `~/.ssh/`.

**IdentityFile** menentukan key mana yang digunakan untuk GitHub.

Setelah ini, semua command git akan otomatis menggunakan key yang benar.

