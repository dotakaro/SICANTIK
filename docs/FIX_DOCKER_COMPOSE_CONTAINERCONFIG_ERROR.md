# Fix Docker Compose 'ContainerConfig' Error

## Masalah

Error `KeyError: 'ContainerConfig'` saat menjalankan `docker-compose up` setelah rebuild image.

## Penyebab

Error ini biasanya terjadi karena:
1. Docker image tidak memiliki metadata yang lengkap
2. Docker Compose versi lama tidak kompatibel dengan image yang baru
3. Ada masalah dengan volume mounting dari container lama

## Solusi Cepat

### Opsi 1: Menggunakan Script Otomatis

```bash
cd /opt/sicantik
./scripts/fix-docker-compose-error.sh
```

### Opsi 2: Manual Steps

#### Step 1: Stop dan Remove Container Lama

```bash
docker-compose stop odoo_sicantik
docker-compose rm -f odoo_sicantik
```

#### Step 2: Remove Image (Optional)

```bash
docker rmi sicantik-odoo:18.4
```

#### Step 3: Rebuild Image

```bash
docker-compose build --no-cache odoo_sicantik
```

#### Step 4: Start dengan --remove-orphans

```bash
docker-compose up -d --remove-orphans odoo_sicantik
```

### Opsi 3: Force Recreate

```bash
docker-compose up -d --force-recreate --remove-orphans odoo_sicantik
```

## Solusi Alternatif (Jika Masih Error)

### Upgrade Docker Compose

Jika menggunakan Docker Compose versi lama (< 1.29), upgrade ke versi terbaru:

```bash
# Cek versi
docker-compose --version

# Upgrade (jika menggunakan pip)
pip3 install --upgrade docker-compose

# Atau gunakan Docker Compose V2 (recommended)
# Docker Compose V2 sudah built-in di Docker Desktop dan Docker Engine 20.10+
docker compose version  # Perhatikan: tanpa dash
```

### Gunakan Docker Compose V2

Jika Docker Compose V2 tersedia, gunakan perintah tanpa dash:

```bash
docker compose build --no-cache odoo_sicantik
docker compose up -d --remove-orphans odoo_sicantik
```

### Clean Up Lengkap

Jika semua solusi di atas tidak berhasil:

```bash
# Stop semua container
docker-compose down

# Remove semua volume (HATI-HATI: ini akan menghapus data!)
# docker-compose down -v  # JANGAN jalankan jika tidak yakin!

# Remove image
docker rmi sicantik-odoo:18.4

# Rebuild dari scratch
docker-compose build --no-cache odoo_sicantik

# Start fresh
docker-compose up -d --remove-orphans
```

## Verifikasi

Setelah fix, verifikasi container berjalan:

```bash
# Cek status
docker-compose ps

# Cek logs
docker-compose logs -f odoo_sicantik

# Cek container details
docker inspect odoo_sicantik
```

## Catatan Penting

- `--remove-orphans` akan menghapus container yang tidak lagi didefinisikan di `docker-compose.yml`
- `--force-recreate` akan memaksa recreate container meskipun tidak ada perubahan
- `--no-cache` akan rebuild image dari scratch tanpa menggunakan cache

## Troubleshooting Lanjutan

Jika masih error, cek:

1. **Docker Version**:
   ```bash
   docker --version
   docker-compose --version
   ```

2. **Image Metadata**:
   ```bash
   docker inspect sicantik-odoo:18.4 | grep -A 10 ContainerConfig
   ```

3. **Docker System Info**:
   ```bash
   docker system df
   docker system prune  # Hati-hati: ini akan menghapus unused resources
   ```

## Referensi

- [Docker Compose Documentation](https://docs.docker.com/compose/)
- [Docker Compose V2 Migration](https://docs.docker.com/compose/cli-command/)

