# KKMB Connect

**Super App & Digital Ecosystem Koperasi Kesejahteraan Mahasiswa Bandung (KKMB).**
Tagline: *Satu Jaringan, Ribuan Peluang.*

Platform resmi yang memusatkan data alumni, mempermudah pencarian relasi & bisnis, mengelola event, dan mendigitalkan keanggotaan koperasi — mobile-first, siap dipakai puluhan ribu anggota.

---

## Fitur Utama (MVP yang sudah hidup end-to-end)

- **Autentikasi multi-peran** — registrasi, login, lupa/reset password, dengan status keanggotaan (pending → active).
- **Approval / verifikasi anggota** oleh pengurus (admin panel) + notifikasi otomatis.
- **Profil Alumni + Kartu Keanggotaan Digital + QR Member**.
- **Directory Alumni** dengan pencarian & filter (profesi, bidang usaha, kota, angkatan).
- **Directory Bisnis Alumni** + Kelola Bisnis Saya + persetujuan admin + *featured listing*.
- **Event Management** — buat event (admin), pendaftaran, tiket QR, dan **QR Check-in** oleh pengurus.
- **Feed Komunitas & Posting Artikel** dengan moderasi.
- **Dashboard** — statistik jaringan, sebaran alumni, rekomendasi **"Temukan Relasi"** (berbasis aturan).
- **Notifikasi Terpadu** — in-app + WhatsApp (Fonnte) + Email (Mailketing) dengan fallback aman.
- **Langganan (Manual Transfer / QRIS manual)** — pilih paket, upload bukti, verifikasi admin.
- **Admin Panel (Filament)** — anggota, bisnis, event, feed, pembayaran, statistik.
- **REST API `/api/v1` (Sanctum)** sebagai fondasi aplikasi mobile Android/iOS.
- **PWA** (installable), **Dark Mode**, responsif mobile.

Fitur yang sengaja **ditunda** ke fase berikut (demi stabilitas MVP): marketplace transaksional, job portal, mentoring matching, chat, video meeting, koperasi digital penuh (simpanan/pinjaman/SHU), AI Assistant penuh, payment gateway (Midtrans), aplikasi native. Lihat bagian audit di akhir pesan pembangunan.

---

## Stack Teknologi

- **Backend:** PHP 8.2+, Laravel 11
- **Database:** MySQL / MariaDB
- **Admin panel:** Filament v3
- **Frontend pengguna:** Blade + Livewire + Tailwind (PWA, mobile-first)
- **Auth:** Laravel session (web) + Sanctum (API) + spatie/laravel-permission (RBAC)
- **Queue & cache:** driver `database` (tanpa Redis — kompatibel FastPanel/cPanel)

> Catatan tampilan: layout memuat Tailwind via CDN agar tampil premium **tanpa langkah build** di shared hosting. Untuk optimasi produksi, tersedia setup Vite (`package.json`, `vite.config.js`, `tailwind.config.js`) bila ingin meng-compile aset sendiri.

---

## Setup Lokal Singkat

```bash
composer install
cp .env.example .env
php artisan key:generate
# atur DB_* di .env, lalu:
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Akun demo hasil seeder:
- Super Admin: `admin@kkmbconnect.id` / `password`
- Pengurus: `pengurus@kkmbconnect.id` / `password`

Admin panel: `/admin` · Aplikasi pengguna: `/`

> **Wajib** ganti password akun demo setelah instalasi.

---

## Konfigurasi Database

Isi di `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kkmb_connect
DB_USERNAME=kkmb_user
DB_PASSWORD=rahasia
```

Lalu `php artisan migrate --seed`.

---

## Konfigurasi Integrasi

Semua integrasi diatur lewat `.env` dan dibaca oleh `config/integrations.php`. Provider bisa diganti tanpa mengubah kode inti (service layer di `app/Services/`).

### WhatsApp — Fonnte (utama)
```
WHATSAPP_PROVIDER=fonnte
FONNTE_TOKEN=xxxxxxxx
FONNTE_DEVICE=optional
```
Alternatif resmi: OneSender, StarSender (tambahkan class provider yang mengimplementasikan `App\Services\Notification\WhatsAppProvider`).
Trigger utama: pendaftaran diterima, verifikasi disetujui, pendaftaran event, status pembayaran.
**Fallback:** bila token kosong/gagal, pesan dicatat di log dan notifikasi in-app tetap tersimpan — aplikasi tidak error.

### Email — Mailketing (utama)
```
EMAIL_PROVIDER=mailketing
MAILKETING_API_TOKEN=xxxxxxxx
MAIL_FROM_ADDRESS=no-reply@domainanda.com
MAIL_FROM_NAME="KKMB Connect"
```
Alternatif resmi: kirim.email.
**Fallback:** bila token kosong, sistem memakai mailer bawaan Laravel (default `MAIL_MAILER=log`) sehingga tidak crash.

### Payment — Manual Transfer (utama, sesuai kebutuhan MVP)
```
PAYMENT_PROVIDER=manual_transfer
BANK_NAME="Bank BCA"
BANK_ACCOUNT_NO="1234567890"
BANK_ACCOUNT_NAME="Koperasi KKMB"
QRIS_IMAGE_PATH="images/qris.png"
```
**Fallback:** QRIS manual (unggah gambar QRIS ke `storage/app/public/images/qris.png`).
Gateway **Midtrans** (alternatif Xendit/Duitku) disiapkan sebagai **fase 2** — env sudah tersedia namun tidak diaktifkan agar MVP stabil.

---

## Deploy

Panduan deploy dipisah agar jelas:
- `docs/deploy-fastpanel.md` — GitHub → FastPanel (prioritas utama)
- `docs/deploy-cpanel.md` — cPanel
- `docs/deploy-vps-manual.md` — VPS manual via SSH
- `docs/installasi.md` — panduan instalasi langkah demi langkah untuk pengguna non-teknis

---

## Arah Pengembangan Android & iOS

Backend + REST API (`/api/v1`) berjalan di server (FastPanel/cPanel/VPS). Aplikasi mobile menjadi **client terpisah** yang memanggil API yang sama:

1. **MVP sekarang:** PWA installable (Add to Home Screen) — sudah berjalan.
2. **Fase 3 native:** bungkus dengan Capacitor / Flutter / React Native; autentikasi memakai token Sanctum (`POST /api/v1/auth/login` → simpan token → kirim `Authorization: Bearer`).
3. Endpoint inti sudah tersedia: auth, directory, event + registrasi, dashboard, rekomendasi, notifikasi.
4. Play Store & App Store: siapkan ikon, splash, privacy policy, dan build rilis dari proyek mobile terpisah. Backend tidak perlu diubah.

Pemisahan tanggung jawab: **backend = data, logika, auth, API**; **mobile = UI native + konsumsi API**.

---

## Struktur Proyek (ringkas)

```
kkmb-connect/
├── app/
│   ├── Models/               # entitas & relasi
│   ├── Http/Controllers/     # Auth, App (pengguna), Api/V1 (REST)
│   ├── Livewire/             # directory alumni & bisnis (pencarian live)
│   ├── Filament/             # admin panel (Resources + Widgets)
│   ├── Services/             # Notification (Fonnte/Mailketing), Payment (manual)
│   ├── Support/              # RecommendationService (Temukan Relasi)
│   └── Providers/            # binding provider + panel Filament (footer kredit)
├── database/migrations|seeders|factories/
├── resources/views/          # layout, komponen (app-footer), auth, app, livewire
├── routes/web.php|api.php|console.php
├── public/                   # index.php, manifest.json, sw.js, images/
├── config/                   # integrations.php + config Laravel inti
└── docs/                     # instalasi + 3 panduan deploy
```

---

## Keamanan & Operasional

- Ganti semua password demo & set `APP_DEBUG=false` di produksi.
- Jalankan queue worker (Supervisor) atau fallback cron `queue:work --stop-when-empty`.
- Jadwalkan `schedule:run` (cron) untuk menandai langganan kedaluwarsa.
- Backup database berkala.

---

*Dibungkus dengan ❤ untuk komunitas KKMB.*

---
Dibangun oleh **Java Maya Studio** — https://wa.me/6285722224391
