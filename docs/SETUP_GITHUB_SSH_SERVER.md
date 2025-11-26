# Setup SSH Key untuk GitHub di Server Production

## 🎯 Masalah

Error `Permission denied (publickey)` terjadi karena server production belum memiliki SSH key yang terdaftar di GitHub.

## 🔧 Solusi

### Opsi 1: Generate SSH Key Baru di Server (Recommended)

**Di server production:**

```bash
# 1. Generate SSH key baru
ssh-keygen -t ed25519 -C "server-production@sicantik" -f ~/.ssh/id_ed25519_github

# Tekan Enter untuk semua prompt (atau set passphrase jika diperlukan)

# 2. Tampilkan public key
cat ~/.ssh/id_ed25519_github.pub
```

**Copy public key yang ditampilkan**, lalu:

1. Buka: https://github.com/settings/keys
2. Klik **"New SSH key"**
3. Title: `SICANTIK Production Server`
4. Key: Paste public key dari server
5. Klik **"Add SSH key"**

**Kembali ke server:**

```bash
# 3. Test koneksi ke GitHub
ssh -T git@github.com

# Jika berhasil, akan muncul:
# Hi dotakaro! You've successfully authenticated, but GitHub does not provide shell access.
```

**4. Clone repository:**

```bash
# Clone dengan SSH
git clone git@github.com:dotakaro/SICANTIK.git /opt/sicantik

# Atau jika masih error, gunakan SSH key spesifik:
GIT_SSH_COMMAND="ssh -i ~/.ssh/id_ed25519_github" git clone git@github.com:dotakaro/SICANTIK.git /opt/sicantik
```

### Opsi 2: Gunakan HTTPS dengan Personal Access Token

Jika SSH key tidak memungkinkan, gunakan HTTPS:

```bash
# Clone dengan HTTPS
git clone https://github.com/dotakaro/SICANTIK.git /opt/sicantik

# Atau dengan Personal Access Token:
# git clone https://YOUR_TOKEN@github.com/dotakaro/SICANTIK.git /opt/sicantik
```

**Cara membuat Personal Access Token:**
1. Buka: https://github.com/settings/tokens
2. Klik **"Generate new token (classic)"**
3. Note: `SICANTIK Production Server`
4. Select scopes: `repo` (full control of private repositories)
5. Generate token
6. Copy token (hanya muncul sekali!)

### Opsi 3: Copy SSH Key dari Laptop (Jika Sama)

Jika Anda ingin menggunakan SSH key yang sama dengan laptop:

**Di laptop:**

```bash
# Tampilkan public key
cat ~/.ssh/id_ed25519.pub
```

**Copy public key**, lalu tambahkan ke GitHub (sama seperti Opsi 1).

**Di server production:**

```bash
# Copy private key dari laptop ke server (via scp)
# Di laptop:
scp ~/.ssh/id_ed25519 root@your-server.com:~/.ssh/id_ed25519
scp ~/.ssh/id_ed25519.pub root@your-server.com:~/.ssh/id_ed25519.pub

# Di server:
chmod 600 ~/.ssh/id_ed25519
chmod 644 ~/.ssh/id_ed25519.pub
```

## 🔐 Konfigurasi SSH untuk Multiple Keys

Jika Anda punya multiple SSH keys, konfigurasi SSH config:

**Di server production:**

```bash
# Edit SSH config
nano ~/.ssh/config
```

Tambahkan:

```
Host github.com
    HostName github.com
    User git
    IdentityFile ~/.ssh/id_ed25519_github
    IdentitiesOnly yes
```

Simpan dan test:

```bash
ssh -T git@github.com
```

## ✅ Verifikasi Setup

Setelah setup SSH key:

```bash
# Test koneksi
ssh -T git@github.com

# Clone repository
git clone git@github.com:dotakaro/SICANTIK.git /opt/sicantik

# Check repository
cd /opt/sicantik
git remote -v
```

## 🆘 Troubleshooting

### Problem: "Permission denied (publickey)"

**Solusi:**
1. Pastikan public key sudah ditambahkan ke GitHub
2. Pastikan private key permission benar: `chmod 600 ~/.ssh/id_ed25519_github`
3. Test dengan verbose: `ssh -vT git@github.com`

### Problem: "Host key verification failed"

**Solusi:**
```bash
# Remove old GitHub host key
ssh-keygen -R github.com

# Add GitHub host key lagi
ssh-keyscan github.com >> ~/.ssh/known_hosts
```

### Problem: Multiple SSH keys conflict

**Solusi:**
Gunakan SSH config seperti di atas, atau gunakan `GIT_SSH_COMMAND`:

```bash
GIT_SSH_COMMAND="ssh -i ~/.ssh/id_ed25519_github" git clone git@github.com:dotakaro/SICANTIK.git /opt/sicantik
```

## 📚 Referensi

- GitHub SSH Setup: https://docs.github.com/en/authentication/connecting-to-github-with-ssh
- Personal Access Token: https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/creating-a-personal-access-token

