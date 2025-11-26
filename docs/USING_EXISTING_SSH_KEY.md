# Menggunakan SSH Key yang Sudah Ada di GitHub Secrets

## 🎯 Situasi

Anda sudah punya `SSH_PRIVATE_KEY` di GitHub Secrets, tapi tidak yakin SSH key mana yang digunakan.

## ⚠️ Catatan Penting

**GitHub Secrets tidak bisa dilihat kembali setelah di-set** (untuk security). Jadi Anda perlu mencari private key yang sesuai di laptop Anda.

## 🔍 Langkah-Langkah

### 1. Lihat SSH Keys yang Tersedia di Laptop

Jalankan script helper:

```bash
./scripts/deploy/show-ssh-key.sh
```

Script ini akan menampilkan semua SSH public keys yang ada di laptop Anda.

### 2. Identifikasi Private Key yang Sesuai

Setelah melihat public keys, cari private key yang sesuai:

```bash
# Contoh: jika public key adalah ~/.ssh/id_ed25519.pub
# Maka private key-nya adalah ~/.ssh/id_ed25519

# Lihat private key (HATI-HATI, jangan share!)
cat ~/.ssh/id_ed25519
```

### 3. Verifikasi Public Key Sudah di Server

Pastikan public key yang sesuai sudah ditambahkan ke server production:

```bash
# Login ke server production
ssh user@your-server.com

# Cek authorized_keys
cat ~/.ssh/authorized_keys
```

Public key yang ada di `authorized_keys` harus sama dengan public key dari private key yang ada di GitHub Secrets.

### 4. Opsi: Gunakan Key yang Sudah Ada

Jika Anda menemukan private key yang sesuai:

```bash
# Jalankan setup script
./scripts/deploy/setup-ssh-deploy.sh

# Ketika ditanya "Apakah Anda sudah punya SSH key di GitHub Secrets?"
# Jawab: y

# Pilih opsi: a (gunakan SSH key yang sudah ada)
# Masukkan path ke private key: ~/.ssh/id_ed25519
```

Script akan menampilkan:
- Public key (untuk verifikasi di server)
- Private key (untuk verifikasi di GitHub Secrets)

### 5. Opsi: Generate Key Baru

Jika private key tidak ditemukan atau hilang:

```bash
# Jalankan setup script
./scripts/deploy/setup-ssh-deploy.sh

# Ketika ditanya "Apakah Anda sudah punya SSH key di GitHub Secrets?"
# Jawab: y

# Pilih opsi: b (generate key baru)
```

**PENTING:** Jika generate key baru, Anda perlu:
1. Update `SSH_PRIVATE_KEY` di GitHub Secrets dengan private key baru
2. Tambahkan public key baru ke server production (`~/.ssh/authorized_keys`)

## 🔐 Verifikasi Setup

### Test SSH Connection dari Laptop

```bash
# Gunakan private key yang sesuai
ssh -i ~/.ssh/id_ed25519 user@your-server.com
```

Jika berhasil masuk tanpa password, berarti key sudah benar.

### Verifikasi GitHub Secrets

1. Buka: https://github.com/dotakaro/SICANTIK/settings/secrets/actions
2. Pastikan `SSH_PRIVATE_KEY` sudah ada
3. **Catatan:** Anda tidak bisa melihat isinya, tapi bisa update jika perlu

### Test GitHub Actions Deployment

1. Buat commit kecil:
   ```bash
   echo "# Test" >> TEST.md
   git add TEST.md
   git commit -m "test: test deployment"
   ```

2. Cek GitHub Actions: https://github.com/dotakaro/SICANTIK/actions

3. Jika deployment gagal dengan error SSH, berarti private key tidak sesuai.

## 🆘 Troubleshooting

### Problem: SSH Connection Gagal dari GitHub Actions

**Kemungkinan penyebab:**
1. Private key di GitHub Secrets tidak sesuai dengan public key di server
2. Public key belum ditambahkan ke server
3. SSH user atau host salah

**Solusi:**
1. Generate key baru dan update GitHub Secrets
2. Pastikan public key sudah di server: `cat ~/.ssh/authorized_keys`
3. Test SSH connection manual dari laptop

### Problem: Tidak Tahu Private Key Mana yang Digunakan

**Solusi:**
1. Cek semua SSH keys di laptop: `ls -la ~/.ssh/`
2. Test satu per satu:
   ```bash
   ssh -i ~/.ssh/key1 user@server
   ssh -i ~/.ssh/key2 user@server
   ```
3. Key yang berhasil masuk adalah yang benar

### Problem: Private Key Hilang

**Solusi:**
1. Generate key baru: `./scripts/deploy/setup-ssh-deploy.sh`
2. Update GitHub Secrets dengan private key baru
3. Tambahkan public key baru ke server

## 📚 Referensi

- `DEPLOYMENT.md` - Panduan lengkap deployment
- `docs/SSH_KEY_EXPLANATION.md` - Penjelasan SSH key
- `scripts/deploy/setup-ssh-deploy.sh` - Script setup SSH
- `scripts/deploy/show-ssh-key.sh` - Script lihat SSH keys

