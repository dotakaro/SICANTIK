# Troubleshooting GitHub SSH Permission Denied

## 🔍 Masalah

Sudah menambahkan public key ke GitHub tapi masih dapat error `Permission denied (publickey)`.

## 🔧 Langkah-Langkah Troubleshooting

### 1. Jalankan Script Troubleshooting

```bash
# Di server production
./scripts/troubleshoot-github-ssh.sh
```

Script akan:
- Check SSH keys yang tersedia
- Check file permissions
- Check SSH config
- Test connection dengan verbose output
- Tampilkan public keys untuk verifikasi

### 2. Check Manual

#### A. Verifikasi Public Key di GitHub

1. Buka: https://github.com/settings/keys
2. Pastikan public key dari server sudah ada di list
3. Copy public key dari server dan bandingkan dengan yang di GitHub

**Di server:**
```bash
cat ~/.ssh/id_ed25519_github.pub
```

**Pastikan sama persis dengan yang di GitHub!**

#### B. Check File Permissions

```bash
# SSH directory harus 700
chmod 700 ~/.ssh

# Private key harus 600
chmod 600 ~/.ssh/id_ed25519_github

# Public key harus 644
chmod 644 ~/.ssh/id_ed25519_github.pub

# SSH config harus 600
chmod 600 ~/.ssh/config
```

#### C. Check SSH Config

```bash
# Edit SSH config
nano ~/.ssh/config
```

Pastikan ada entry untuk GitHub:

```
Host github.com
    HostName github.com
    User git
    IdentityFile ~/.ssh/id_ed25519_github
    IdentitiesOnly yes
```

Jika tidak ada, tambahkan entry di atas.

#### D. Test dengan Key Spesifik

```bash
# Test dengan key spesifik
ssh -T -i ~/.ssh/id_ed25519_github git@github.com

# Atau dengan verbose untuk debug
ssh -vT -i ~/.ssh/id_ed25519_github git@github.com
```

### 3. Common Issues & Solutions

#### Issue 1: Wrong Key Being Used

**Problem:** SSH menggunakan key yang salah (bukan yang ditambahkan ke GitHub)

**Solution:**
```bash
# Gunakan SSH config untuk force key tertentu
cat > ~/.ssh/config << EOF
Host github.com
    HostName github.com
    User git
    IdentityFile ~/.ssh/id_ed25519_github
    IdentitiesOnly yes
EOF

chmod 600 ~/.ssh/config
```

#### Issue 2: Multiple Keys Conflict

**Problem:** Ada banyak SSH keys dan SSH menggunakan yang salah

**Solution:**
```bash
# Gunakan IdentitiesOnly yes di SSH config
# Ini akan force SSH hanya menggunakan key yang di-specify
```

#### Issue 3: Key Permissions Wrong

**Problem:** File permissions tidak benar

**Solution:**
```bash
# Fix semua permissions
chmod 700 ~/.ssh
chmod 600 ~/.ssh/id_ed25519_github
chmod 644 ~/.ssh/id_ed25519_github.pub
chmod 600 ~/.ssh/config
```

#### Issue 4: Wrong Public Key Added to GitHub

**Problem:** Public key yang ditambahkan ke GitHub bukan dari server production

**Solution:**
```bash
# Tampilkan public key dari server
cat ~/.ssh/id_ed25519_github.pub

# Copy dan tambahkan ke GitHub
# Pastikan sama persis!
```

#### Issue 5: GitHub Host Key Issue

**Problem:** GitHub host key tidak dikenal atau salah

**Solution:**
```bash
# Remove old GitHub host key
ssh-keygen -R github.com

# Re-add GitHub host key
ssh-keyscan github.com >> ~/.ssh/known_hosts
```

### 4. Test Step by Step

```bash
# Step 1: Test dengan verbose
ssh -vT git@github.com

# Step 2: Check key yang digunakan
ssh -vT git@github.com 2>&1 | grep "Offering public key"

# Step 3: Test dengan key spesifik
ssh -T -i ~/.ssh/id_ed25519_github git@github.com

# Step 4: Check SSH agent (jika menggunakan)
ssh-add -l
```

### 5. Alternative: Use HTTPS Instead

Jika SSH masih bermasalah, gunakan HTTPS:

```bash
# Clone dengan HTTPS
git clone https://github.com/dotakaro/SICANTIK.git /opt/sicantik

# Atau update remote jika sudah clone
cd /opt/sicantik
git remote set-url origin https://github.com/dotakaro/SICANTIK.git
```

## ✅ Verifikasi Setup

Setelah fix, verifikasi:

```bash
# 1. Test SSH connection
ssh -T git@github.com

# Harus muncul:
# Hi dotakaro! You've successfully authenticated, but GitHub does not provide shell access.

# 2. Clone repository
git clone git@github.com:dotakaro/SICANTIK.git /opt/sicantik

# 3. Check remote
cd /opt/sicantik
git remote -v
```

## 🆘 Still Having Issues?

1. **Check GitHub account:**
   - Pastikan account `dotakaro` memiliki akses ke repository
   - Check repository settings → Collaborators

2. **Check SSH key di GitHub:**
   - Pastikan key sudah ditambahkan ke account yang benar
   - Pastikan key tidak expired atau disabled

3. **Try different key:**
   - Generate key baru dan tambahkan ke GitHub
   - Test dengan key baru

4. **Use HTTPS:**
   - Jika SSH masih bermasalah, gunakan HTTPS dengan Personal Access Token

## 📚 Referensi

- GitHub SSH Troubleshooting: https://docs.github.com/en/authentication/troubleshooting-ssh
- SSH Config: https://linux.die.net/man/5/ssh_config

