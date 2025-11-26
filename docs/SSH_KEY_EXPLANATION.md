# Penjelasan SSH Key untuk Deployment

## 🎯 Jawaban Singkat

**SSH Private Key yang diminta GitHub Secrets adalah dari LAPTOP ANDA**, bukan dari server production.

## 📋 Penjelasan Lengkap

### Arsitektur Deployment

```
Laptop Anda → GitHub Actions → Server Production
     ↓              ↓                    ↓
  Generate      Gunakan Private    Terima Public
  SSH Key Pair     Key dari          Key di
                   GitHub Secrets    authorized_keys
```

### Flow SSH Key untuk Deployment

1. **Di Laptop Anda** (Development Machine):
   - Jalankan script: `./scripts/deploy/setup-ssh-deploy.sh`
   - Script akan generate SSH key pair baru di: `~/.ssh/github_actions_deploy`
   - **Public Key** → Copy dan tambahkan ke server production
   - **Private Key** → Copy dan tambahkan ke GitHub Secrets

2. **Di GitHub Secrets**:
   - Private key dari laptop Anda disimpan sebagai `SSH_PRIVATE_KEY`
   - GitHub Actions akan menggunakan private key ini untuk connect ke server

3. **Di Server Production**:
   - Public key dari laptop Anda ditambahkan ke `~/.ssh/authorized_keys`
   - Server akan menerima koneksi dari GitHub Actions menggunakan private key yang sesuai

### Mengapa Bukan SSH Key Production?

**SSH Key Production** biasanya adalah:
- Key yang sudah ada di server untuk akses ke server tersebut
- Key yang digunakan untuk login ke server dari berbagai tempat
- Bisa jadi key yang sudah digunakan untuk keperluan lain

**SSH Key untuk GitHub Actions** adalah:
- Key khusus yang di-generate untuk deployment otomatis
- Key yang hanya digunakan oleh GitHub Actions
- Key yang lebih aman karena dedicated untuk satu tujuan

### Lokasi File SSH Key

Setelah menjalankan `setup-ssh-deploy.sh`, SSH key akan ada di:

**Di Laptop Anda:**
```bash
~/.ssh/github_actions_deploy      # Private key (untuk GitHub Secrets)
~/.ssh/github_actions_deploy.pub # Public key (untuk server production)
```

**Tidak ada file di server production** - hanya public key yang ditambahkan ke `authorized_keys`.

## 🔐 Langkah-Langkah Setup

### 1. Generate SSH Key di Laptop

```bash
# Di laptop Anda
./scripts/deploy/setup-ssh-deploy.sh
```

Script akan menampilkan:
- **Public Key** → Copy ini untuk ditambahkan ke server
- **Private Key** → Copy ini untuk GitHub Secrets

### 2. Tambahkan Public Key ke Server Production

```bash
# Login ke server production
ssh user@your-server.com

# Tambahkan public key
mkdir -p ~/.ssh
echo "PASTE_PUBLIC_KEY_DARI_LAPTOP" >> ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys
chmod 700 ~/.ssh
```

### 3. Tambahkan Private Key ke GitHub Secrets

1. Buka: https://github.com/dotakaro/SICANTIK/settings/secrets/actions
2. Klik **New repository secret**
3. Name: `SSH_PRIVATE_KEY`
4. Value: Copy seluruh isi file `~/.ssh/github_actions_deploy` dari laptop Anda
   ```bash
   # Di laptop Anda, untuk melihat private key:
   cat ~/.ssh/github_actions_deploy
   ```

### 4. Test Connection

```bash
# Di laptop Anda, test koneksi ke server
ssh -i ~/.ssh/github_actions_deploy user@your-server.com
```

Jika berhasil, Anda akan masuk ke server tanpa password prompt.

## ⚠️ Catatan Penting

1. **Jangan commit SSH key ke Git!**
   - Private key hanya untuk GitHub Secrets
   - Public key bisa di-commit (tidak masalah), tapi lebih baik tidak

2. **Jangan share private key!**
   - Private key adalah rahasia
   - Hanya simpan di GitHub Secrets
   - Jangan kirim via email atau chat

3. **Jika sudah ada SSH key di server:**
   - Tidak masalah, kita hanya menambahkan public key baru
   - Server bisa menerima multiple public keys di `authorized_keys`

4. **Jika ingin menggunakan SSH key yang sudah ada:**
   - Bisa, tapi tidak direkomendasikan untuk security
   - Lebih baik generate key baru khusus untuk GitHub Actions

## 🔍 Verifikasi Setup

Setelah setup, verifikasi dengan:

```bash
# 1. Cek SSH key ada di laptop
ls -la ~/.ssh/github_actions_deploy*

# 2. Test koneksi ke server
ssh -i ~/.ssh/github_actions_deploy user@your-server.com

# 3. Cek GitHub Secrets sudah ditambahkan
# Buka: https://github.com/dotakaro/SICANTIK/settings/secrets/actions
# Pastikan SSH_PRIVATE_KEY sudah ada
```

## 📚 Referensi

- `DEPLOYMENT.md` - Panduan lengkap deployment
- `scripts/deploy/setup-ssh-deploy.sh` - Script setup SSH
- GitHub Actions SSH: https://docs.github.com/en/actions/deployment/security-hardening-your-deployments

