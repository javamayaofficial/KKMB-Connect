# Deploy ke FastPanel (Prioritas Utama) — dari GitHub

Panduan deploy KKMB Connect ke server ber-**FastPanel**, sumber kode dari GitHub.

## Prasyarat
- Server dengan FastPanel terpasang.
- Domain sudah diarahkan ke server.
- Repository GitHub berisi kode KKMB Connect.

## Langkah

### 1. Buat Site & Database di FastPanel
1. **Sites → Create Site**, isi domain (mis. `kkmbconnect.id`).
2. Pilih **PHP 8.2** (atau lebih baru) pada site tersebut.
3. **Databases → Create Database**: catat nama DB, user, dan password.

### 2. Ambil Kode dari GitHub
Masuk ke server via SSH (atau Terminal FastPanel), lalu ke folder site:

```bash
cd /var/www/USER/data/www/kkmbconnect.id
git clone https://github.com/USERNAME/kkmb-connect.git .
```

> Jika folder sudah berisi file default, kosongkan dulu atau clone ke subfolder lalu pindahkan.

### 3. Set Document Root ke /public
Di FastPanel, buka pengaturan site → **Document Root** → arahkan ke:
```
/var/www/USER/data/www/kkmbconnect.id/public
```
Ini wajib agar Laravel aman (folder root tidak terekspos).

### 4. Install Dependency & Environment
```bash
composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate
```
Edit `.env`: isi `APP_URL`, `DB_*` (sesuai langkah 1), dan integrasi (Fonnte/Mailketing/bank) bila sudah ada.
Set `APP_ENV=production` dan `APP_DEBUG=false`.

### 5. Migrasi, Seed, Storage
```bash
php artisan migrate --seed
php artisan storage:link
php artisan config:cache
php artisan route:cache
```

### 6. Permission
```bash
chmod -R 775 storage bootstrap/cache
```

### 7. Queue (Supervisor) & Scheduler (Cron)
FastPanel umumnya menyediakan pengelola proses. Tambahkan worker:
```bash
php artisan queue:work --sleep=3 --tries=3
```
Jika Supervisor tidak tersedia, gunakan **fallback cron** (lihat catatan bawah).

Tambahkan **Cron** untuk scheduler (menu Cron di FastPanel):
```
* * * * * php /var/www/USER/data/www/kkmbconnect.id/artisan schedule:run >> /dev/null 2>&1
```

### 8. SSL
Aktifkan **Let's Encrypt** dari FastPanel untuk domain (HTTPS). Aplikasi memaksa HTTPS di produksi.

## Update / Publish Versi Baru
```bash
cd /var/www/USER/data/www/kkmbconnect.id
git pull
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
```

## Fallback bila Queue Worker tidak bisa berjalan terus-menerus
Tambahkan cron tiap menit:
```
* * * * * php /path/artisan queue:work --stop-when-empty >> /dev/null 2>&1
```
Notifikasi tetap terkirim, hanya sedikit tertunda.

---
Dibangun oleh **Java Maya Studio** — https://wa.me/6285722224391
