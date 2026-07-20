# Deploy ke cPanel

Panduan deploy KKMB Connect ke hosting ber-**cPanel** (shared hosting umum).

## Prasyarat
- Akses cPanel dengan **PHP 8.2+** (via MultiPHP / PHP Selector).
- Akses Terminal cPanel atau SSH (bila tersedia). Bila tidak ada Terminal, sebagian langkah artisan dapat dijalankan lewat **Cron sekali jalan**.

## Langkah

### 1. Siapkan PHP & Ekstensi
- **MultiPHP Manager** → set domain ke PHP 8.2+.
- **Select PHP Version / Extensions** → aktifkan: `bcmath, ctype, curl, dom, fileinfo, gd, mbstring, openssl, pdo_mysql, tokenizer, xml, zip`.

### 2. Upload Kode
Opsi A — **Git Version Control** (Menu: Git™ Version Control) → Clone repo.
Opsi B — Upload ZIP ke home directory lalu **Extract** via File Manager.

Letakkan kode di luar `public_html` bila memungkinkan (mis. `/home/USER/kkmb-connect`), agar hanya folder `public` yang terekspos.

### 3. Arahkan public_html ke folder public
Dua cara umum:
- **Symlink**: hapus/backup `public_html`, lalu buat symlink `public_html -> /home/USER/kkmb-connect/public` (via Terminal).
- **Atau** pindahkan isi `public/` ke `public_html/` dan sesuaikan path di `public_html/index.php` agar menunjuk ke `../kkmb-connect/vendor/autoload.php` dan `../kkmb-connect/bootstrap/app.php`.

### 4. Buat Database
- **MySQL® Databases** → buat database + user + beri privilege. Catat kredensialnya.

### 5. Composer & Environment
Via Terminal cPanel:
```bash
cd ~/kkmb-connect
composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate
```
Bila Composer tidak tersedia di server, jalankan `composer install` di lokal lalu upload folder `vendor/` sekalian.

Edit `.env`: `APP_URL`, `DB_*`, `APP_ENV=production`, `APP_DEBUG=false`, integrasi bila ada.

### 6. Migrasi, Seed, Storage
```bash
php artisan migrate --seed
php artisan storage:link
php artisan config:cache
```
> Jika `storage:link` diblokir shared hosting, buat symlink manual `public/storage -> ../storage/app/public` via File Manager, atau set `FILESYSTEM_DISK` sesuai kebijakan hosting.

### 7. Permission
Set `storage/` dan `bootstrap/cache/` ke 775 via File Manager (Permissions).

### 8. Queue & Scheduler via Cron (khas cPanel, tanpa Supervisor)
Menu **Cron Jobs**:
```
* * * * * php /home/USER/kkmb-connect/artisan schedule:run >> /dev/null 2>&1
* * * * * php /home/USER/kkmb-connect/artisan queue:work --stop-when-empty >> /dev/null 2>&1
```

### 9. SSL
Aktifkan **AutoSSL / Let's Encrypt** dari cPanel.

## Update Versi Baru
Tarik perubahan (Git) atau upload ulang, lalu:
```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
```

---
Dibangun oleh **Java Maya Studio** — https://wa.me/6285722224391
