# Panduan Instalasi KKMB Connect (Langkah demi Langkah)

Panduan ini ditujukan agar mudah diikuti, termasuk oleh pengguna non-teknis yang dibantu developer/hosting.

## 1. Kebutuhan Server

- **PHP 8.2 atau lebih baru**
- Ekstensi PHP: `bcmath, ctype, curl, dom, fileinfo, gd, mbstring, openssl, pdo_mysql, tokenizer, xml, zip`
- **MySQL 8 / MariaDB 10.4+**
- **Composer** (untuk memasang dependency PHP)
- (Opsional) **Node.js 18+** — hanya bila ingin meng-compile aset Tailwind sendiri. Tanpa ini pun aplikasi tetap tampil premium karena memakai Tailwind CDN.

## 2. Ambil Kode

Bisa lewat Git atau upload manual:

```bash
git clone <url-repo-anda> kkmb-connect
cd kkmb-connect
```

## 3. Pasang Dependency PHP

```bash
composer install --no-dev --optimize-autoloader
```

## 4. Siapkan File Environment

```bash
cp .env.example .env
php artisan key:generate
```

Buka `.env`, lalu isi minimal:
- `APP_URL` = alamat domain Anda (mis. `https://kkmbconnect.id`)
- `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` sesuai database yang dibuat
- (Opsional) `FONNTE_TOKEN`, `MAILKETING_API_TOKEN`, data bank untuk pembayaran

> Jika token Fonnte/Mailketing dikosongkan, aplikasi tetap berjalan. Notifikasi in-app tetap aktif; WhatsApp/email hanya dilewati dengan aman.

## 5. Buat Database

Buat database kosong (mis. `kkmb_connect`) melalui panel hosting atau:

```sql
CREATE DATABASE kkmb_connect CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

## 6. Migrasi & Data Awal

```bash
php artisan migrate --seed
```

Ini membuat semua tabel dan mengisi: 5 role, akun admin & pengurus demo, serta 3 paket langganan.

## 7. Hubungkan Storage (untuk upload foto/logo/bukti)

```bash
php artisan storage:link
```

## 8. (Opsional) Compile Aset Produksi

Hanya jika ingin memakai Vite alih-alih Tailwind CDN:

```bash
npm install
npm run build
```

## 9. Cache Konfigurasi (produksi)

```bash
php artisan config:cache
php artisan route:cache
```

## 10. Jalankan / Akses

- Lokal: `php artisan serve` lalu buka `http://127.0.0.1:8000`
- Produksi: arahkan **document root web server ke folder `public/`**

Login admin: `/admin` — `admin@kkmbconnect.id` / `password` (**segera ganti**).

## 11. Layanan Latar Belakang

- **Queue** (untuk notifikasi): jalankan `php artisan queue:work` (via Supervisor di VPS) atau fallback cron.
- **Scheduler**: tambahkan cron `* * * * * php /path/artisan schedule:run >> /dev/null 2>&1`.

## 12. Permission Folder

Pastikan folder berikut writable oleh web server:

```bash
chmod -R 775 storage bootstrap/cache
```

Selesai. Lanjutkan ke panduan deploy sesuai hosting Anda (FastPanel / cPanel / VPS).

---
Dibangun oleh **Java Maya Studio** — https://wa.me/6285722224391
