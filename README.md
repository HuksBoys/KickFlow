# ⚽ KickFlow

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.5-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white" alt="Alpine.js">
  <img src="https://img.shields.io/badge/Gemini_AI-API-4285F4?style=for-the-badge&logo=google&logoColor=white" alt="Gemini AI">
</p>

<p align="center">
  <strong>Platform Pemesanan Lapangan Futsal Modern dengan Kecerdasan Buatan</strong><br>
  Solusi lengkap untuk manajemen booking lapangan futsal, dilengkapi asisten AI berbasis Gemini.
</p>

---

## 📋 Daftar Isi

- [Fitur Utama](#-fitur-utama)
- [Tech Stack](#-tech-stack)
- [Panduan Instalasi](#-panduan-instalasi)
- [Konfigurasi .env](#-konfigurasi-env)
- [Deployment ke Vercel](#-deployment-ke-vercel)
- [Struktur Direktori](#-struktur-direktori)
- [Panduan Penggunaan](#-panduan-penggunaan)
- [Log Perubahan](#-log-perubahan--pembaruan-proyek)

---

## ✨ Fitur Utama

### 👤 Manajemen Pengguna
- Registrasi & login pengguna (Customer dan Admin)
- Autentikasi berbasis peran (Role-Based Access Control / RBAC)
- Proteksi ketat hak akses rute admin & dashboard melalui middleware `EnsureUserIsAdmin` (403 Forbidden untuk non-admin)
- Manajemen profil pengguna

### 🏟️ Manajemen Lapangan
- Tampilan daftar lapangan futsal yang tersedia (Indoor & Outdoor)
- Tambah lapangan baru dengan upload foto, penentuan tarif per jam, deskripsi & fasilitas (Admin)
- Detail lapangan lengkap (foto, harga per jam, fasilitas)
- Status ketersediaan lapangan secara real-time dan toggle status aktif/nonaktif

### 📅 Sistem Booking
- Pemesanan lapangan dengan pemilihan tanggal & jam
- Konfirmasi dan pembatalan booking
- Riwayat pemesanan untuk setiap pengguna
- Manajemen jadwal booking oleh Admin

### 💳 Pembayaran & Kasir
- Proses pembayaran booking lapangan
- Modul Kasir (Point of Sale) untuk konfirmasi pesanan pending dan penerimaan bayar lunas langsung
- Penyelesaian sewa lapangan dan pembatalan otomatis dengan status refund
- Cetak struk nota resmi pemesanan lapangan (`window.print()`)
- Riwayat transaksi pembayaran

### 🤖 Asisten AI (Gemini)
- Chat interaktif dengan asisten AI berbasis Google Gemini
- Rekomendasi lapangan berdasarkan preferensi pengguna
- Bantuan informasi seputar booking dan fasilitas

### 📊 Dashboard Admin
- Proteksi akses eksklusif Administrator (Customer dan tamu ditolak)
- Statistik booking harian dan pendapatan platform real-time
- Manajemen lapangan (CRUD lengkap)
- Akses cepat ke antrean Kasir & Konfirmasi pesanan
- Laporan 10 aktivitas transaksi terbaru platform

---

## 🛠️ Tech Stack

| Komponen | Teknologi | Versi |
| :--- | :--- | :--- |
| Backend Framework | Laravel | 13.x |
| Bahasa Pemrograman | PHP | 8.5 |
| Database | MySQL | 8.0 |
| Frontend Styling | Tailwind CSS | 3.x |
| Frontend JS | Alpine.js | 3.x |
| AI Integration | Google Gemini API | Latest |
| Build Tool | Vite | Latest |
| Autentikasi | Laravel Breeze / Sanctum | - |

---

## 🚀 Panduan Instalasi

### Prasyarat

Pastikan sistem Anda sudah terinstal:
- **PHP** >= 8.5
- **Composer** >= 2.x
- **Node.js** >= 18.x & **npm** >= 9.x
- **MySQL** >= 8.0
- **Git**

### Langkah-Langkah Instalasi

**1. Clone Repository**

```bash
git clone https://github.com/username/KickFlow.git
cd KickFlow
```

**2. Install Dependensi PHP & JavaScript**

```bash
composer install
npm install
```

**3. Setup File Konfigurasi (.env)**

```bash
cp .env.example .env
```

Buka file `.env` dan isi variabel berikut:

```env
DB_DATABASE=DB_KickFlow
DB_USERNAME=root
DB_PASSWORD=your_database_password

GEMINI_API_KEY=your_gemini_api_key_here
```

**4. Generate Application Key**

```bash
php artisan key:generate
```

**5. Jalankan Migrasi Database & Seeder**

```bash
php artisan migrate --seed
```

**6. Build Asset Frontend**

```bash
npm run build
```

**7. Jalankan Server Lokal**

```bash
php artisan serve
```

Aplikasi akan berjalan di: **http://127.0.0.1:8000**

---

## ⚙️ Konfigurasi .env

Berikut adalah semua variabel lingkungan yang diperlukan oleh KickFlow:

```env
APP_NAME=KickFlow
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_LEVEL=debug

# Konfigurasi Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=DB_KickFlow
DB_USERNAME=root
DB_PASSWORD=your_database_password

# Konfigurasi Session & Cache
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

# Konfigurasi Mail (opsional)
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@kickflow.com"
MAIL_FROM_NAME="${APP_NAME}"

# Google Gemini AI API Key
GEMINI_API_KEY=your_gemini_api_key_here
```

> **Catatan:** Dapatkan `GEMINI_API_KEY` Anda di [Google AI Studio](https://aistudio.google.com/app/apikey).

---

## ☁️ Deployment ke Vercel

Aplikasi KickFlow siap di-deploy ke **Vercel Serverless Platform** menggunakan runtime komunitas `vercel-php@0.9.0` (PHP 8.5) dan Vite asset pipeline.

### Arsitektur Serverless Vercel untuk KickFlow:
1. **Entry Point Serverless (`api/index.php`)**: Mempersiapkan subdirektori `/tmp/storage/...` dan `/tmp/bootstrap/cache` secara otomatis saat cold start agar Blade view compilation, session, dan logging tidak mengalami error file system read-only, serta menormalkan `SCRIPT_NAME` untuk routing Laravel.
2. **Konfigurasi `vercel.json`**: Menjalankan `npm run build` otomatis untuk aset frontend Vite/Tailwind, menetapkan `outputDirectory: "public"`, mengarahkan rute statis `/build/(.*)` ke `/build/$1`, dan mem-forward semua rute aplikasi `/(.*)` langsung ke runtime PHP `api/index.php` (tanpa `handle: filesystem` agar `public/index.php` tidak tertangkap sebagai file unduhan statis).
3. **Filter `.vercelignore`**: Mencegah folder lokal `vendor/`, `node_modules/`, dan file kredensial `.env` ter-upload langsung ke bundle serverless, menjaga ukuran fungsi di bawah batas limit Vercel.

### Langkah-Langkah Deploy:

#### 1. Persiapkan Cloud MySQL Database
Karena serverless functions bersifat ephemeral dan read-only, Anda memerlukan MySQL server cloud eksternal (contoh layanan gratis/tier terjangkau: **TiDB Serverless**, **Aiven MySQL**, **Railway**, atau **Clever Cloud**).

Jalankan migrasi dan seeder awal dari komputer lokal ke database cloud Anda:
```bash
DB_HOST=your-remote-host.com DB_PORT=3306 DB_DATABASE=your_db DB_USERNAME=your_user DB_PASSWORD=your_pass php artisan migrate --seed --force
```

#### 2. Push Kode ke Repository (GitHub/GitLab)
```bash
git add .
git commit -m "fix(vercel): resolve index.php static download issue by refining routing"
git push origin master
```

#### 3. Import / Pengaturan di Vercel Dashboard
1. Masuk ke [Vercel Dashboard](https://vercel.com/dashboard) dan buka project **KickFlow**.
2. **PENTING (Pengaturan Framework & Build)**:
   - Masuk ke **Settings** -> **General** -> **Build & Development Settings**.
   - Pada bagian **Framework Preset**, ubah dari **Vite** menjadi **Other** (agar Vercel tidak mencari folder `dist`).
   - Pada bagian **Output Directory**, biarkan sesuai `vercel.json` (terkonfigurasi otomatis ke `public`).
3. Di bagian **Environment Variables** (Settings -> Environment Variables), pastikan telah diisi:
   - `APP_NAME` : `KickFlow`
   - `APP_ENV` : `production`
   - `APP_DEBUG` : `false`
   - `APP_KEY` : `base64:Emdso5jnrv6Sqf7pxzFF3bflfC09tmKXuNAwj/3Wk2Y=` *(atau gunakan hasil `php artisan key:generate --show`)*
   - `APP_URL` : `https://your-project.vercel.app` *(sesuaikan domain Vercel Anda)*
   - `DB_CONNECTION` : `mysql`
   - `DB_HOST` : Host cloud database Anda
   - `DB_PORT` : `3306`
   - `DB_DATABASE` : Nama database Anda
   - `DB_USERNAME` : Username database
   - `DB_PASSWORD` : Password database
   - `GEMINI_API_KEY` : API Key Google Gemini AI Anda
4. Lakukan **Redeploy** (Deployments -> titik tiga (...) pada deployment terbaru -> **Redeploy**).

---

## 📁 Struktur Direktori

```
KickFlow/
├── app/
│   ├── Http/
│   │   ├── Controllers/         # Controller aplikasi
│   │   │   ├── Admin/           # Controller khusus Admin
│   │   │   └── Customer/        # Controller khusus Customer
│   │   ├── Middleware/          # Middleware autentikasi & otorisasi
│   │   └── Requests/            # Form Request validation
│   ├── Models/                  # Model Eloquent
│   ├── Services/                # Service classes (AI, Payment, dll)
│   └── Policies/                # Authorization policies
├── database/
│   ├── migrations/              # File migrasi database
│   ├── seeders/                 # Database seeders
│   └── factories/               # Model factories untuk testing
├── resources/
│   ├── views/                   # Template Blade
│   │   ├── admin/               # Halaman dashboard admin
│   │   ├── customer/            # Halaman untuk customer
│   │   ├── components/          # Komponen Blade reusable
│   │   └── layouts/             # Layout utama aplikasi
│   ├── css/                     # File CSS (Tailwind)
│   └── js/                      # File JavaScript (Alpine.js)
├── routes/
│   ├── web.php                  # Rute web utama
│   └── api.php                  # Rute API
├── tests/
│   ├── Feature/                 # Feature tests
│   └── Unit/                    # Unit tests
├── public/                      # Asset publik & entry point
├── storage/                     # File storage (upload, logs)
├── .env.example                 # Template konfigurasi environment
├── AGENTS.md                    # Panduan untuk AI agents
└── README.md                    # Dokumentasi proyek ini
```

---

## 📖 Panduan Penggunaan

### Login sebagai Admin

1. Buka browser dan akses `http://127.0.0.1:8000`
2. Klik **"Masuk"** di pojok kanan atas
3. Gunakan kredensial admin default (setelah seeder dijalankan):
   - **Email:** `admin@kickflow.com`
   - **Password:** `password`
4. Anda akan diarahkan ke **Dashboard Admin**

**Fitur Admin:**
- **Manajemen Lapangan (Tambah & Kelola Lapangan):** Tambah lapangan baru dengan upload foto, penentuan tarif per jam, tipe (indoor/outdoor), fasilitas, ubah data, dan toggle status aktif/nonaktif.
- **Kasir & Konfirmasi Pesanan (Point of Sale):** Antrean kasir terpadu untuk memverifikasi pesanan pending, konfirmasi reservasi, terima pembayaran lunas, penyelesaian sewa, pembatalan jadwal, filter multi-status, serta cetak struk nota resmi.
- **Statistik & Monitoring:** Pantau booking hari ini, estimasi pendapatan bulanan, pesanan yang perlu tindakan cepat, dan 10 aktivitas transaksi terbaru.

---

### Login sebagai Customer

1. Buka browser dan akses `http://127.0.0.1:8000`
2. Klik **"Daftar"** untuk membuat akun baru, atau **"Masuk"** jika sudah punya akun
3. Isi formulir pendaftaran dan verifikasi email
4. Setelah login, Anda akan melihat halaman utama customer

**Cara Booking Lapangan:**
1. Pilih menu **"Lapangan"** untuk melihat daftar lapangan yang tersedia
2. Klik lapangan yang ingin dipesan untuk melihat detailnya
3. Pilih **tanggal** dan **jam** yang diinginkan
4. Klik **"Pesan Sekarang"** dan konfirmasi pemesanan
5. Lakukan pembayaran sesuai instruksi yang diberikan
6. Tunggu konfirmasi dari admin

---

### Menggunakan Asisten AI (Chat Gemini)

1. Login sebagai Customer atau Admin
2. Klik ikon **"Chat AI"** atau menu **"Asisten"** di navigasi
3. Ketik pertanyaan Anda, misalnya:
   - *"Lapangan mana yang tersedia besok jam 3 sore?"*
   - *"Berapa harga sewa lapangan A per jam?"*
   - *"Bantu saya booking lapangan untuk 10 orang"*
4. Asisten AI akan merespons dan memberikan rekomendasi yang relevan

---

## 📜 Log Perubahan & Pembaruan Proyek

| Waktu / Versi | Sub-Agent | Fitur / Komponen | Deskripsi Perubahan | File yang Dibuat/Diubah |
| :--- | :--- | :--- | :--- | :--- |
| 2026-10-27 19:33 / v0.1.0 | [Lead-Agent] | Inisialisasi Proyek | Setup proyek KickFlow, konfigurasi Laravel Boost, pembuatan README.md master | README.md, AGENTS.md |
| 2026-10-27 19:40 / v0.2.0 | [DB-Agent] | Database Schema & Models | Instalasi spatie/laravel-permission, pembuatan migrasi untuk tables: fields, schedules, bookings, chat_logs. Penambahan kolom role & phone pada tabel users. Constraint UNIQUE(field_id, booking_date, start_time) untuk mencegah double booking | `database/migrations/*_create_fields_table.php`, `*_create_schedules_table.php`, `*_create_bookings_table.php`, `*_create_chat_logs_table.php`, `*_create_permission_tables.php` |
| 2026-10-27 19:40 / v0.2.1 | [DB-Agent] | Eloquent Models & Relationships | Pembuatan model Field, Schedule, Booking, ChatLog dengan relasi lengkap. Modifikasi User model dengan HasRoles trait dari Spatie | `app/Models/Field.php`, `app/Models/Schedule.php`, `app/Models/Booking.php`, `app/Models/ChatLog.php`, `app/Models/User.php` |
| 2026-10-27 19:40 / v0.2.2 | [DB-Agent] | Factories & Seeders | Pembuatan factories realistis untuk semua model. Seeder untuk 2 user (admin@kickflow.com, budi@gmail.com) dan 5 lapangan futsal Indonesia | `database/factories/FieldFactory.php`, `ScheduleFactory.php`, `BookingFactory.php`, `ChatLogFactory.php`, `database/seeders/UserSeeder.php`, `FieldSeeder.php`, `DatabaseSeeder.php` |
| 2026-10-27 19:55 / v0.3.0 | [AI-Agent] | Generative AI Service & Function Calling | Implementasi `KickFlowAiService` terintegrasi dengan Google Gemini 2.0 Flash API (Function Calling tools: `check_field_availability`, `get_available_fields`, `get_booking_info`) serta sistem fallback cerdas offline/lokal | `app/Services/KickFlowAiService.php`, `config/services.php` |
| 2026-10-27 19:56 / v0.3.1 | [AI-Agent] | API & Web Controllers | Pembuatan `Api\ChatController` (/api/chat), `BookingController`, `FieldController`, `DashboardController`, `Auth\LoginController`, `Auth\RegisterController`, `StoreBookingRequest`, `StoreChatRequest`, dan `BookingPolicy` | `app/Http/Controllers/Api/ChatController.php`, `app/Http/Controllers/BookingController.php`, `app/Http/Controllers/FieldController.php`, `app/Http/Controllers/DashboardController.php`, `app/Http/Controllers/Auth/LoginController.php`, `app/Http/Controllers/Auth/RegisterController.php`, `app/Http/Requests/*`, `app/Policies/BookingPolicy.php`, `routes/web.php` |
| 2026-10-27 20:00 / v0.4.0 | [UI-Agent] | Antarmuka Pengguna (UI) Blade & Tailwind CSS | Pembuatan layout responsif `layouts/app.blade.php`, landing page `welcome.blade.php`, katalog lapangan `fields/index.blade.php`, detail lapangan `fields/show.blade.php`, formulir pemesanan `bookings/create.blade.php`, riwayat booking `bookings/index.blade.php`, rincian tiket `bookings/show.blade.php`, dashboard admin `dashboard.blade.php`, serta autentikasi `auth/login.blade.php` & `auth/register.blade.php` dengan tema hijau futsal | `resources/views/layouts/app.blade.php`, `resources/views/welcome.blade.php`, `resources/views/fields/*`, `resources/views/bookings/*`, `resources/views/dashboard.blade.php`, `resources/views/auth/*` |
| 2026-10-27 20:03 / v0.4.1 | [UI-Agent] | Floating Chatbot Widget Interaktif | Pembuatan widget chatbot melayang (floating) menggunakan Alpine.js yang terintegrasi secara asinkron dengan endpoint `/api/chat`, dilengkapi typing indicator, auto-scroll, saran pertanyaan cepat, dan riwayat percakapan | `resources/views/layouts/app.blade.php`, `resources/js/app.js`, `resources/css/app.css` |
| 2026-10-27 20:05 / v0.4.2 | [Lead-Agent] | Verifikasi Integrasi & Automated Testing | Verifikasi akhir integrasi seluruh modul, asset bundling via Vite (`npm run build`), penulisan automated test suite mencakup pencegahan double-booking, flow reservasi, dan API AI Chatbot (6 passed, 21 assertions) | `tests/Feature/KickFlowBookingAndChatTest.php`, `tests/Feature/ExampleTest.php`, `public/build/*` |
| 2026-10-27 20:25 / v0.5.0 | [UI-Agent] | Transformasi Tema Desain Catppuccin | Redesain visual menyeluruh seluruh antarmuka (UI) dan Floating Chatbot Widget ke gaya warna modern "Catppuccin Mocha" (Base `#1e1e2e`, Mantle `#181825`, Crust `#11111b`, Surface0 `#313244`, Green `#a6e3a1`, Mauve `#cba6f7`, Text `#cdd6f4`) | `resources/css/app.css`, `resources/views/layouts/app.blade.php`, `resources/views/welcome.blade.php`, `resources/views/fields/*`, `resources/views/bookings/*`, `resources/views/dashboard.blade.php`, `resources/views/auth/*` |
| 2026-10-27 21:15 / v0.6.0 | [AI-Agent] | Aksi Perubahan & Mutasi AI Chatbot (Write Tools) | Implementasi tool mutasi data Gemini Function Calling: `create_booking` (reservasi langsung via pesan percakapan dengan validasi anti double-booking real-time) dan `cancel_booking` (pembatalan booking valid dengan otorisasi pengguna), didukung automated test suite lengkap (8 passed, 31 assertions) | `app/Services/KickFlowAiService.php`, `resources/views/layouts/app.blade.php`, `tests/Feature/KickFlowBookingAndChatTest.php` |
| 2026-10-29 18:40 / v0.7.0 | [DevOps-Agent] | Konfigurasi Deployment Vercel Serverless | Konfigurasi arsitektur deployment serverless Vercel: runtime `vercel-php@0.9.0` (PHP 8.5), Vite asset build pipeline, handling auto-create direktori penyimpanan `/tmp/storage/...` & `/tmp/bootstrap/cache`, rules `.vercelignore`, dan panduan integrasi cloud MySQL | `vercel.json`, `api/index.php`, `.vercelignore`, `README.md` |
| 2026-10-29 19:30 / v0.7.1 | [DevOps-Agent] | Perbaikan Unduhan Statis Vercel & Normalisasi Rute | Perbaikan masalah file index.php terunduh mentah: menghapus handle: filesystem, menambahkan normalisasi SCRIPT_NAME di api/index.php, dan rute eksplisit aset /build/(.*) | `vercel.json`, `api/index.php`, `README.md` |
| 2026-10-29 19:40 / v0.7.2 | [DevOps-Agent] | Resolusi STATIC_BUILD_NO_OUT_DIR (dist) | Menetapkan outputDirectory: "public" di vercel.json untuk mengatasi pencarian folder "dist" oleh preset build Vite Vercel, serta panduan penggantian Framework Preset ke "Other" | `vercel.json`, `README.md` |
| 2026-10-30 15:10 / v0.8.0 | [Admin-Agent] | Fitur Tambah Lapangan & Kasir (Konfirmasi Pesanan) | Implementasi modul admin menyeluruh: Manajemen Lapangan (tambah lapangan baru, upload foto, edit, hapus, toggle status) dan Modul Kasir (konfirmasi pesanan pending, terima pembayaran lunas, penyelesaian booking, pembatalan, cetak struk nota, filter status multi-kriteria), didukung middleware `EnsureUserIsAdmin` serta automated test suite lengkap (27 passed, 94 assertions) | `app/Http/Controllers/Admin/FieldController.php`, `app/Http/Controllers/Admin/CashierController.php`, `app/Http/Middleware/EnsureUserIsAdmin.php`, `app/Http/Requests/Admin/*`, `resources/views/admin/*`, `resources/views/layouts/app.blade.php`, `resources/views/dashboard.blade.php`, `tests/Feature/AdminFieldManagementTest.php`, `tests/Feature/AdminCashierTest.php`, `README.md` |
| 2026-10-31 18:30 / v0.8.1 | [Security-Agent] | Pengamanan Akses Dashboard Admin & Otorisasi RBAC | Pengetatan otorisasi rute `/dashboard` dengan middleware `admin` (`EnsureUserIsAdmin`) untuk mencegah customer mengakses statistik dan data reservasi admin (HTTP 403 Forbidden), penyembunyian tautan navigasi Dashboard Admin pada footer untuk non-admin di `layouts/app.blade.php`, serta penambahan 3 automated feature tests pengujian akses dashboard (30 passed, 99 assertions) | `routes/web.php`, `resources/views/layouts/app.blade.php`, `tests/Feature/AdminFieldManagementTest.php`, `README.md` |
| 2026-10-31 18:45 / v0.8.2 | [Bugfix-Agent] | Resolusi Tampilan Blank Detail Riwayat Pesanan Customer | Memperbaiki error 500 fatal `Call to undefined method Controller::authorize()` ketika customer membuka tiket detail booking `/bookings/{id}` dengan menambahkan trait `AuthorizesRequests` ke base `Controller` dan menggunakan `Gate::authorize()` pada `BookingController`, dilengkapi 3 automated feature tests verifikasi hak akses tiket pemesanan (33 passed, 103 assertions) | `app/Http/Controllers/BookingController.php`, `app/Http/Controllers/Controller.php`, `tests/Feature/KickFlowBookingAndChatTest.php`, `README.md` |

---

<p align="center">
  Dibuat dengan ❤️ menggunakan <a href="https://laravel.com">Laravel</a> & <a href="https://ai.google.dev/">Google Gemini AI</a>
</p>
