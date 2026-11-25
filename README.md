# FixTrack (Fix & Track) &mdash; Inventory & Issue Tracking System

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.4-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.4">
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Tailwind_CSS-v4.0-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Alpine.js-v3-8BC0D0?style=for-the-badge&logo=alpinedotjs&logoColor=white" alt="Alpine.js">
  <img src="https://img.shields.io/badge/Chart.js-v4-FF6384?style=for-the-badge&logo=chartdotjs&logoColor=white" alt="Chart.js">
  <img src="https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge" alt="License">
</p>

---

## 📋 Tentang FixTrack

**FixTrack** adalah sistem pelaporan kerusakan, kehilangan, dan kekurangan barang atau aset perusahaan berbasis web. Dirancang dengan antarmuka **Modern Industrial Dashboard – Dark Mode**, sistem ini difokuskan pada alur kerja yang sederhana, praktis, dan cepat untuk 2 peran utama:

1. **Admin**:
   - Memantau seluruh tiket laporan yang masuk.
   - Mengubah status laporan (`Pending`, `Sedang Diproses`, `Selesai`, `Ditolak`).
   - Menentukan prioritas dan menambahkan catatan penanganan (`handling_notes`).
   - Mengelola master data barang (CRUD inventaris).
   - Mengelola master kategori barang (CRUD kategori).
   - Mengelola akun pengguna (CRUD user).
   - Memantau grafik statistik status laporan sederhana.
   - Menghapus laporan jika diperlukan.
   - Mengunduh rekap laporan dalam format PDF dan Excel.

2. **User (Pelapor)**:
   - Login dan logout.
   - Melihat dashboard pribadi (metrik laporan mandiri & tiket terbaru).
   - Membuat laporan insiden aset dengan unggah foto bukti fisik (Before).
   - Melihat daftar dan riwayat laporan milik sendiri (dilindungi otorisasi sehingga tidak dapat melihat laporan user lain).
   - Memantau perkembangan status laporan, catatan penanganan, dan foto bukti selesai (After).
   - Berinteraksi di ruang diskusi tiket dua arah dengan Admin.
   - Menerima notifikasi in-app ketika status laporan berubah atau ada tanggapan baru.

---

## 🌟 Fitur Baru & Interaktif

1. **Komparasi Foto Before vs After**:
   - Menampilkan perbandingan visual kondisi awal kerusakan (*Before*) dan bukti fisik hasil perbaikan (*After*).
   - Dilengkapi *image lightbox* layar penuh untuk inspeksi detail foto beresolusi tinggi.
   - Admin dapat mengunggah foto bukti selesai langsung dari modal ubah status.

2. **Ruang Diskusi & Komentar Tiket Dua Arah**:
   - Kolom obrolan kronologis di setiap tiket insiden antara Pelapor dan Admin.
   - Pelapor dapat menanyakan estimasi waktu, dan Admin dapat meminta konfirmasi detail lapangan.

3. **Sistem Notifikasi In-App (Real-Time Bell)**:
   - Ikon lonceng di navbar dengan indikator jumlah notifikasi belum dibaca.
   - Dropdown pratinjau cepat notifikasi interaktif.
   - Halaman pusat notifikasi lengkap dengan aksi sekali klik *"Tandai Semua Dibaca"*.

---

## 🚀 Akun Pengujian & Kredensial Demo

Halaman login telah dilengkapi dengan **Tombol Quick Fill Demo** (sekali klik langsung mengisi kredensial):

| Peran (Role) | Nama Pengguna | Alamat Email | Kata Sandi | Hak Akses Utama |
| :--- | :--- | :--- | :--- | :--- |
| **Admin** | Administrator FixTrack | `admin@fixtrack.com` | `password` | Akses penuh laporan, ubah status & prioritas, catatan penanganan, master barang & kategori, data user, ekspor PDF/Excel. |
| **User (Pelapor)** | Budi Santoso | `user@fixtrack.com` | `password` | Buat laporan kerusakan/kehilangan, upload foto bukti, lihat dashboard dan riwayat tiket sendiri. |

---

## 🛠️ Stack Teknologi

- **Backend Framework**: Laravel 12 on PHP 8.4
- **Template Engine**: Laravel Blade Components & Layouts
- **Styling**: Tailwind CSS v4 (Industrial Dark Theme: Background `#0F1117`, Sidebar `#151922`, Card `#1C2330`, Border `#263143`, Akses Biru `#3B82F6`)
- **JavaScript & Interaktivitas**: Alpine.js
- **Visualisasi Grafik**: Chart.js (Grafik Donat Status Laporan)
- **Ikon**: Lucide Icons
- **Basis Data**: MySQL (InnoDB foreign keys & cascade constraints)
- **Ekspor Dokumen**:
  - PDF: Laravel DomPDF (`barryvdh/laravel-dompdf`)
  - Excel: Laravel Excel (`maatwebsite/excel`)
- **Code Formatter**: Laravel Pint (PSR-12 standard)
- **Pengujian**: PHPUnit Test Suite (15 Tests, 60 Assertions)

---

## 📦 Panduan Menjalankan Aplikasi

### 1. Prasyarat Sistem
- PHP 8.2+ (PHP 8.4 disarankan) dengan ekstensi `pdo_mysql`, `gd`, `zip`, `fileinfo`, `mbstring`.
- Composer 2.x
- Node.js & NPM
- MySQL Server (misal via XAMPP atau Laragon pada `127.0.0.1:3306`)

### 2. Konfigurasi Database (.env)
Pastikan file `.env` mengarah ke database MySQL:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fixtrack_db
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Migrasi & Seeder Database
Jalankan perintah berikut untuk membuat tabel dan mengisi data awal:
```bash
php artisan migrate:fresh --seed
```

### 4. Hubungkan Symlink Storage Foto
```bash
php artisan storage:link
```

### 5. Kompilasi Aset Frontend
```bash
npm run build
```

### 6. Jalankan Local Development Server
```bash
php artisan serve
```
Buka browser dan akses: **`http://127.0.0.1:8000`**

---

## 🗄️ Struktur Basis Data Sederhana

Sistem menggunakan 5 tabel utama yang saling berelasi:

```
[users] (role: admin, user)
   │
   ├──< [reports] >── [categories]
   │       │               │
   │       ├──< [items] ───┘
   │       │
   └──< [report_histories]
```

1. **`users`**: Kolom `name`, `email`, `password`, `role` (`admin` / `user`), `phone`, `is_active`.
2. **`categories`**: Kolom `name`, `description`.
3. **`items`**: Kolom `item_code`, `name`, `category_id`, `location`, `stock_quantity`, `condition` (`Baik`, `Rusak Ringan`, `Rusak Berat`), `description`.
4. **`reports`**: Kolom `ticket_number`, `user_id`, `item_id`, `item_name`, `category_id`, `location`, `problem_type` (`Rusak`, `Hilang`, `Kekurangan Stok`), `description`, `quantity`, `priority` (`Rendah`, `Sedang`, `Tinggi`), `photo_path`, `status` (`Pending`, `Sedang Diproses`, `Selesai`, `Ditolak`), `handling_notes`.
5. **`report_histories`**: Audit trail pencatat setiap aksi perubahan status dan catatan admin.

---

## 🧭 Struktur Navigasi Berdasarkan Role

- **Sidebar Admin**:
  - Dashboard
  - Data Laporan
  - Data Barang
  - Kategori Barang
  - Data Pengguna
  - Logout

- **Sidebar User (Pelapor)**:
  - Dashboard
  - Buat Laporan
  - Riwayat Laporan
  - Logout

---

## 🧪 Pengujian Otomatis (PHPUnit)

Jalankan test suite untuk memastikan semua fungsionalitas dan otorisasi berjalan normal:

```bash
php artisan test
```

Hasil eksekusi:
```
PASS  Tests\Unit\ExampleTest
✓ that true is true

PASS  Tests\Feature\ExampleTest
✓ the application returns a successful response

PASS  Tests\Feature\FixTrackFeatureTest
✓ login page is accessible
✓ admin can login with valid credentials
✓ user can login with valid credentials
✓ user can access dashboard and see own reports
✓ user cannot access user management
✓ user cannot access category or item management
✓ user can create report
✓ admin can update report status and priority
✓ admin can delete report
✓ admin can access and export reports
✓ admin can manage categories
✓ admin can manage items
✓ user can access and update settings
✓ admin can upload completion photo when updating status
✓ user and admin can discuss in report
✓ notifications are created and can be marked as read

Tests:    18 passed (77 assertions)
```

---

## 🛡️ Lisensi

Proyek FixTrack dilisensikan di bawah [MIT License](LICENSE).
