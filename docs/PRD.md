# Product Requirement Document (PRD)

## CRM Web WhatsApp Integration

| Metadata              | Rincian                                       |
| :-------------------- | :-------------------------------------------- |
| **Nama Produk**       | CRM Web dengan Integrasi Otomatis WhatsApp    |
| **Versi Dokumen**     | 2.0 — Upgrade Teknologi                       |
| **Tanggal Pembuatan** | 4 Juli 2026                                   |
| **Target Pengguna**   | Super Admin, Staf Admin, Pemilik Toko (Owner) |

---

## 1. Pendahuluan & Latar Belakang

Banyak usaha kecil hingga menengah menghadapi tantangan dalam mengelola data pelanggan, inventaris produk, dan pelacakan transaksi secara efisien. Komunikasi manual terkait status pembayaran, rincian pesanan, dan pengiriman seringkali memakan waktu dan rentan terhadap kesalahan manusia.

**CRM Web WhatsApp Integration** hadir untuk menyelesaikan masalah ini dengan menyediakan dasbor CRM terpusat yang menggabungkan manajemen inventaris, siklus transaksi pesanan, serta pengiriman pesan otomatis melalui WhatsApp API. Dengan sistem ini, operasional toko menjadi lebih terstruktur dan hubungan dengan pelanggan terjalin secara lebih personal serta real-time.

---

## 2. Sasaran & Tujuan Produk

- **Otomasi Alur Transaksi**: Mengurangi intervensi manual dengan mengirimkan pesan WhatsApp otomatis di setiap perubahan status pesanan.
- **Sentralisasi Data**: Menyediakan database tunggal untuk pelanggan, produk, transaksi, dan histori pesan.
- **Peningkatan Layanan Pelanggan**: Mengoptimalkan waktu respon komunikasi dengan mengirimkan bukti invoice berbentuk PDF langsung ke WhatsApp pelanggan.
- **Keamanan & Otorisasi**: Mengamankan data dengan membatasi akses fitur sesuai peran pengguna (RBAC).

---

## 3. Persona Pengguna & Tingkat Akses

Aplikasi ini mendukung tiga peran utama (Roles) dengan pembagian menu sebagai berikut:

| Peran (Role)             | Deskripsi                                                                                           | Hak Akses Utama                                                                                                                                    |
| :----------------------- | :-------------------------------------------------------------------------------------------------- | :------------------------------------------------------------------------------------------------------------------------------------------------- |
| **Super Admin**          | Pengguna teknis dengan kendali penuh atas sistem dan infrastruktur aplikasi.                        | Mengakses semua modul Master, Transaksi, Laporan, dan Pengaturan Teknis (User, Role, Menu, Template Pesan, WhatsApp API).                          |
| **Admin (Staf)**         | Staf operasional yang bertanggung jawab atas proses penjualan sehari-hari dan manajemen inventaris. | Mengelola data Pelanggan, Produk, membuat pesanan (Order), dan menyebarkan Campaign. Tidak bisa melihat laporan keuangan atau mengubah pengaturan. |
| **Pemilik Toko (Owner)** | Pemangku kepentingan bisnis yang membutuhkan pemantauan performa toko.                              | Mengawasi laporan penjualan harian/bulanan, performa produk terlaris, manajemen pengguna staf (Admin), dan operasional transaksi dasar.            |

---

## 4. Cakupan Fitur (Feature Scope)

### 4.1. Autentikasi & Otorisasi (Spatie Permission)

- **Login Dinamis**: Pengguna dapat masuk menggunakan _username_ atau _email_.
- **Role & Permission Management**: Manajemen peran dan hak akses menggunakan paket **Spatie Laravel Permission**. Setiap role memiliki kumpulan permission yang mengontrol akses ke modul-modul aplikasi.
- **Middleware Otorisasi**: Rute dilindungi oleh middleware bawaan Spatie (`role`, `permission`, `role_or_permission`) yang didaftarkan di `bootstrap/app.php`.
- **Blade Directives**: Tampilan UI dikontrol menggunakan directive `@role`, `@hasanyrole`, `@can`, dan `@hasanypermission` untuk menyembunyikan/menampilkan elemen sesuai hak akses.
- **Status Akun**: Fitur aktif/non-aktif akun pengguna untuk keamanan tambahan.

### 4.2. Dasbor Interaktif (Dashboard)

- **Ringkasan Indikator Status**: Kotak ringkasan (_small boxes_) yang menampilkan jumlah transaksi berdasarkan status (Belum Dibayar, Sudah Dibayar, Pengiriman, Selesai) dalam 1 tahun terakhir.
- **Visualisasi Grafik**:
  - Grafik pesanan 1 bulan terakhir (_Line Chart_) untuk memantau tren transaksi.
  - Grafik penjualan produk terlaris 1 tahun terakhir (_Doughnut Chart_) untuk analisis inventaris.
- **Histori Pesan Keluar**: Tabel log dinamis yang melacak setiap pesan WhatsApp yang berhasil dikirim ke pelanggan.

### 4.3. Manajemen Data Master

- **Pelanggan (Customer)**: Manajemen data pelanggan (Nama, No. Telepon, Alamat).
- **Produk (Product)**:
  - Input data produk (SKU, Nama, Stok, Harga, Deskripsi, Gambar).
  - Integrasi dengan WhatsApp untuk membagikan info produk secara langsung ke nomor tujuan beserta gambar pendukung.
- **Campaign Broadcast**:
  - Pembuatan siaran promosi masal.
  - Pengiriman pesan WhatsApp massal ke semua pelanggan terdaftar dengan dukungan kustomisasi gambar promosi.

### 4.4. Alur Transaksi & Otomasi Pesanan (Orders)

Setiap perubahan status pesanan secara otomatis memicu pengiriman pesan WhatsApp kepada pelanggan:

1. **Belum Bayar (Status 0)**:
   - Dibuat melalui form pemesanan dengan pencarian pelanggan & produk yang dinamis.
   - Mengurangi stok produk secara langsung.
   - Memicu WhatsApp rincian pesanan dan petunjuk pembayaran.
2. **Sudah Bayar (Status 1)**:
   - Memerlukan unggahan bukti transfer pembayaran (`proof_of_payment`).
   - Memicu WhatsApp berisi konfirmasi bahwa pembayaran telah diterima.
3. **Pengiriman (Status 2)**:
   - Memicu WhatsApp informasi kurir sedang dalam perjalanan ke alamat tujuan.
   - Menyediakan tombol cepat untuk mengganti status pengiriman.
4. **Selesai (Status 3)**:
   - Sistem secara otomatis me-render file PDF Invoice menggunakan DomPDF.
   - Mengunggah file PDF tersebut ke ImageKit.io.
   - Mengirim WhatsApp berisi link unduhan invoice PDF tersebut ke pelanggan.
5. **Batal (Status 4)**:
   - Mengembalikan (_restore_) stok produk yang sebelumnya dikurangi ke database.
   - Memicu WhatsApp pemberitahuan pembatalan pesanan.

### 4.5. Pelaporan (Reporting)

- **Laporan Penjualan**: Laporan transaksi dengan filter berdasarkan rentang tanggal dan status transaksi.
- **Ekspor Dokumen**: Fitur unduh laporan penjualan dalam format spreadsheet (.xls) berbasis stream table HTML.

### 4.6. Pengaturan Sistem (Settings)

- **Template Pesan**: Kustomisasi isi pesan notifikasi WhatsApp untuk setiap status transaksi menggunakan placeholder dinamis seperti `{{customer_name}}`, `{{order_number}}`, `{{order_total}}`, `{{contact_number}}`, `{{store_name}}`, dan `{{invoice_link}}`.
- **Pengaturan WhatsApp API**: Menghubungkan nomor WhatsApp secara interaktif dengan melihat status koneksi real-time, memindai QR Code inline, atau memutuskan koneksi sesi Evolution API secara dinamis (tanpa perlu mengisi ulang form konfigurasi karena kredensial dibaca otomatis dari berkas `.env`).
- **Manajemen Role & Permission**: Mengelola role dan permission menggunakan Spatie Permission. Super Admin dapat membuat role baru, menetapkan permission ke role, dan mengassign role ke user melalui antarmuka web.
- **Manajemen Menu**: Mengonfigurasi menu navigasi sidebar. Visibilitas menu dikontrol berdasarkan permission yang dimiliki role user.

---

## 5. Upgrade Teknologi (Technology Upgrade Plan)

Bagian ini mendokumentasikan rencana upgrade stack teknologi dari versi saat ini ke versi target. Semua fungsionalitas fitur pada Bagian 4 tetap dipertahankan; yang berubah adalah fondasi teknis di bawahnya.

### 5.1. Ringkasan Perubahan

| Komponen           | Versi Lama (Saat Ini)                  | Versi Target (Upgrade)                                                                                                 | Alasan Upgrade                                                                                                                                                                 |
| :----------------- | :------------------------------------- | :--------------------------------------------------------------------------------------------------------------------- | :----------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **Framework**      | Laravel 10                             | **Laravel 12**                                                                                                         | Dukungan PHP 8.2+, peningkatan performa Eloquent, perbaikan keamanan, dan siklus dukungan yang lebih panjang (bug fix hingga Agustus 2026, security fix hingga Februari 2027). |
| **PHP**            | >= 8.1                                 | **>= 8.2**                                                                                                             | Kebutuhan minimum Laravel 12.                                                                                                                                                  |
| **Frontend Stack** | AdminLTE 3 + Bootstrap 4 + jQuery      | **TALL Stack** (Tailwind CSS v4 + Alpine.js + Livewire + Laravel) + Custom Glassmorphic Premium Dark UI & Lucide Icons | Desain modern utility-first, penghapusan ketergantungan jQuery, tampilan premium gelap dengan glassmorphism.                                                                   |
| **Livewire**       | 3.x                                    | **3.x** (kompatibel Laravel 12)                                                                                        | Tetap di Livewire 3 untuk kompatibilitas stabil dengan Laravel 12.                                                                                                             |
| **RBAC**           | Custom middleware + tabel `menu_roles` | **Spatie Laravel Permission**                                                                                          | Standar industri, fitur lengkap (roles, permissions, middleware, Blade directives, cache), menghapus kebutuhan custom middleware.                                              |
| **Node.js**        | >= 18.20                               | **24 LTS**                                                                                                             | Versi Active LTS terbaru (dukungan hingga April 2028), peningkatan performa V8 engine dan keamanan.                                                                            |
| **Script Runner**  | `concurrently`                         | **`npm-run-all2`**                                                                                                     | Sintaks lebih bersih (`run-p`, `run-s`), lebih ringan, dan cocok untuk pipeline sederhana proyek ini.                                                                          |
| **WhatsApp API**   | Rapiwha (berbayar, closed-source)      | **Evolution API** (open-source, self-hosted, gratis)                                                                   | Menghilangkan biaya per pesan, kontrol penuh atas infrastruktur, dukungan media lengkap, dan REST API standar.                                                                 |

---

### 5.2. TALL Stack & Custom Premium UI — Detail Migrasi Frontend

Migrasi dari AdminLTE 3 (Bootstrap 4 + jQuery) ke **TALL Stack dengan Custom Premium UI** merupakan perubahan arsitektur frontend terbesar dalam upgrade ini.

#### 5.2.1. Komponen TALL Stack & Aset Kustom

| Layer                | Teknologi       | Fungsi                                                                                                                           |
| :------------------- | :-------------- | :------------------------------------------------------------------------------------------------------------------------------- |
| **T** — Tailwind CSS | Tailwind CSS v4 | Framework CSS utility-first. Konfigurasi langsung via `@theme` di CSS untuk mendesain layout kustom dark-mode dan glassmorphism. |
| **A** — Alpine.js    | Alpine.js 3.x   | Library JavaScript ringan untuk interaktivitas sisi klien (dropdown, modal, toggle, tab selector). Menggantikan jQuery.          |
| **L** — Livewire     | Livewire 3.x    | Komponen full-stack reaktif tanpa reload halaman.                                                                                |
| **L** — Laravel      | Laravel 12      | Framework backend utama.                                                                                                         |
| **Icons**            | Lucide Icons    | Paket npm `lucide` untuk outline icon yang sangat bersih, minimalis, dan modern. Menggantikan FontAwesome.                       |

#### 5.2.2. Dampak Migrasi ke Custom TALL Stack

| Area                    | Perubahan                                                                                                                                                                                                                                                             |
| :---------------------- | :-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **CSS / Styling**       | Seluruh class Bootstrap 4 (`col-md-6`, `btn-primary`, `card`, `table-striped`, dll.) diganti ke utility class Tailwind CSS (`grid grid-cols-2`, `bg-indigo-600`, `rounded-xl shadow-lg`, `divide-y`, dll.). File `app.css` dirombak total untuk custom glassmorphism. |
| **JavaScript**          | jQuery (`$()`, `$(document).tooltip(...)`, `$('.error').remove()`) dihapus dan diganti Alpine.js (`x-data`, `x-show`, `x-on:click`, `@click`). Semua dependensi jQuery-based (Select2, Bootstrap JS) diganti alternatif kustom non-jQuery.                            |
| **Komponen UI**         | Seluruh kerangka AdminLTE 3 (`small-box`, `card-outline`, `sidebar-mini`, `content-wrapper`) dihapus total dan diganti dengan desain dasbor kustom sendiri dengan estetika modern premium (tampilan gelap, card transparan, efek glow borders, dan micro-animations). |
| **Chart.js**            | Tetap dipertahankan (tidak terikat jQuery) dengan kustomisasi visual neon gradients agar serasi dengan layout kustom.                                                                                                                                                 |
| **SweetAlert2**         | Tetap dipertahankan (tidak terikat jQuery).                                                                                                                                                                                                                           |
| **Dependensi Dihapus**  | `admin-lte`, `bootstrap`, `jquery`, `@popperjs/core`, `popper.js`, `select2`, `postcss`, `autoprefixer`.                                                                                                                                                              |
| **Dependensi Ditambah** | `tailwindcss`, `@tailwindcss/vite`, `lucide` (paket npm icon modern).                                                                                                                                                                                                 |

#### 5.2.3. Manajemen Tema (Light/Dark Mode)

Sistem tema dirancang untuk memberikan transisi instan, konsisten, dan bebas kedipan (no flash):

- **Default State**: Halaman akan dimuat pertama kali dalam **Light Mode** (default) apabila user baru pertama kali membuka aplikasi dan belum menentukan preferensinya.
- **Sinkronisasi Cookie & LocalStorage**: Preferensi tema disimpan di `localStorage` (untuk kebutuhan manipulasi sisi klien) serta cookie `theme` (agar server-side PHP langsung menyajikan kelas `.dark` pada tag `<html>`). Cara ini efektif mengeliminasi bug morphing DOM pada Livewire 3 yang seringkali me-reset tema kembali ke light mode setelah redirect/login.
- **Optimasi Anti-FOUC (Flash of Unstyled Content)**: Skrip inisialisasi diletakkan di bagian paling atas tag `<head>` bersama dengan inline background style (`#f8fafc` / `#0f172a` untuk aplikasi, serta `#f1f5f9` / `#020617` untuk halaman login) untuk mewarnai kanvas browser secara instan sebelum aset CSS eksternal selesai di-load.

---

### 5.3. Evolution API — Migrasi WhatsApp Gateway

**Evolution API** adalah platform REST API open-source yang di-deploy mandiri (self-hosted) untuk menggantikan layanan berbayar Rapiwha. Ini adalah perubahan paling berdampak pada modul integrasi pihak ketiga.

#### 5.3.1. Perbandingan Rapiwha vs Evolution API

| Aspek                  | Rapiwha (Lama)              | Evolution API (Baru)                                          |
| :--------------------- | :-------------------------- | :------------------------------------------------------------ |
| **Lisensi**            | Closed-source, berbayar     | Open-source (Apache 2.0), gratis                              |
| **Hosting**            | Cloud pihak ketiga (SaaS)   | Self-hosted (Docker)                                          |
| **Biaya**              | Biaya langganan / per pesan | Gratis (hanya biaya VPS)                                      |
| **Metode Kirim Pesan** | HTTP GET                    | HTTP POST (REST API standar)                                  |
| **Dukungan Media**     | Terbatas (URL teks saja)    | Lengkap (gambar, dokumen, audio, video, lokasi)               |
| **Multi-Instance**     | Tidak                       | Ya (kelola banyak nomor WA)                                   |
| **Webhook**            | Tidak aktif                 | Sistem event real-time (pesan masuk, status terkirim/terbaca) |
| **Koneksi**            | QR Code (WhatsApp Web)      | QR Code (Baileys) + opsional WhatsApp Cloud API               |

#### 5.3.2. Arsitektur Integrasi Evolution API

```
┌─────────────┐    HTTP POST     ┌───────────────────┐     WhatsApp     ┌──────────┐
│  Laravel App │ ───────────────> │   Evolution API   │ ──────────────> │ Pelanggan │
│  (CRM)       │ <─────────────  │  (Docker / VPS)   │ <────────────── │          │
└─────────────┘    Webhook POST  └───────────────────┘   (opsional)    └──────────┘
```

#### 5.3.3. Perubahan Implementasi Kode

**Sebelum (Rapiwha — HTTP GET):**

```php
$response = Http::timeout(10)
  ->retry(3, 1000)
  ->get($this->baseUrl, [
    'apikey' => $this->apiKey,
    'number' => $number,
    'text' => $text,
  ]);
```

**Sesudah (Evolution API — HTTP POST):**

```php
$response = Http::timeout(10)
  ->retry(3, 1000)
  ->withHeaders([
    'apikey' => $this->apiKey,
    'Content-Type' => 'application/json',
  ])
  ->post("{$this->baseUrl}/message/sendText/{$this->instanceName}", [
    'number' => $number,
    'text' => $text,
  ]);
```

**Pengiriman Media (Evolution API — HTTP POST):**

```php
$response = Http::timeout(10)
  ->retry(3, 1000)
  ->withHeaders([
    'apikey' => $this->apiKey,
    'Content-Type' => 'application/json',
  ])
  ->post("{$this->baseUrl}/message/sendMedia/{$this->instanceName}", [
    'number' => $number,
    'mediatype' => 'image',
    'media' => $fileUrl,
    'caption' => $text,
  ]);
```

#### 5.3.4. Perubahan pada Model & UI

| Area                                           | Perubahan                                                                                                                     |
| :--------------------------------------------- | :---------------------------------------------------------------------------------------------------------------------------- |
| **Model `WhatsappApiSetting`**                 | Tambah kolom `instance_name` untuk menyimpan nama instance Evolution API.                                                     |
| **Migration**                                  | Tambah migration baru: `add_instance_name_to_whatsapp_settings_table`.                                                        |
| **Interface `SendMessageApiServiceInterface`** | Method `sendMessage()` tetap sama — hanya implementasi internal yang berubah.                                                 |
| **Service Class**                              | Buat class baru `EvolutionApiService` mengimplementasikan `SendMessageApiServiceInterface`, menggantikan `RapiwhaApiService`. |
| **Halaman Pengaturan WA**                      | Tambah input field "Instance Name" di form setting WhatsApp API.                                                              |
| **Environment (`.env`)**                       | Ubah key menjadi `EVOLUTION_API_URL`, `EVOLUTION_API_KEY`, `EVOLUTION_INSTANCE_NAME`.                                         |

#### 5.3.5. Deployment Evolution API

Evolution API di-deploy secara mandiri menggunakan Docker:

```yaml
# docker-compose.yml (contoh minimal)
services:
  evolution-api:
    image: atendai/evolution-api:latest
    ports:
      - '8080:8080'
    environment:
      - AUTHENTICATION_API_KEY=your_global_api_key
    volumes:
      - evolution_data:/evolution/data
volumes:
  evolution_data:
```

Setelah container berjalan, hubungkan nomor WhatsApp melalui QR Code di endpoint `/instance/connect/{instanceName}`.

---

### 5.4. Node.js 24 LTS & npm-run-all2

#### 5.4.1. Upgrade Node.js

| Aspek            | Detail                                                                                                                               |
| :--------------- | :----------------------------------------------------------------------------------------------------------------------------------- |
| **Versi Target** | Node.js 24 LTS (Active LTS, dukungan hingga April 2028)                                                                              |
| **Alasan**       | Peningkatan performa V8 engine, dukungan ESM yang lebih matang, perbaikan keamanan, dan kompatibilitas dengan ekosistem npm terbaru. |
| **Dampak**       | Tidak ada breaking change untuk proyek ini. Vite dan Tailwind CSS berjalan baik di Node.js 24.                                       |

#### 5.4.2. Migrasi concurrently → npm-run-all2

`npm-run-all2` adalah fork terpelihara dari `npm-run-all` yang sudah tidak dipertahankan. Paket ini menyediakan dua perintah utama: `run-p` (parallel) dan `run-s` (sequential).

**Sebelum (`package.json` dengan `concurrently`):**

```json
{
  "scripts": {
    "serve": "php artisan serve",
    "vite": "vite",
    "open": "wait-on http://localhost:8000 && start http://localhost:8000",
    "dev-all": "concurrently \"npm run serve\" \"npm run vite\" \"npm run open\""
  },
  "devDependencies": {
    "concurrently": "9.1.2",
    "wait-on": "8.0.3"
  }
}
```

**Sesudah (`package.json` dengan `npm-run-all2`):**

```json
{
  "scripts": {
    "serve": "php artisan serve",
    "vite": "vite",
    "open": "wait-on http://localhost:8000 && start http://localhost:8000",
    "dev-all": "run-p serve vite open"
  },
  "devDependencies": {
    "npm-run-all2": "^7.0.0",
    "wait-on": "8.0.3"
  }
}
```

**Perubahan:**

- Hapus dependensi `concurrently`.
- Tambah dependensi `npm-run-all2`.
- Ganti perintah `concurrently \"...\" \"...\"` menjadi `run-p serve vite open` (lebih pendek dan bersih).

---

### 5.5. Laravel 12 — Detail Upgrade Framework

| Aspek               | Detail                                                         |
| :------------------ | :------------------------------------------------------------- |
| **Versi Target**    | Laravel 12 (Rilis: 24 Februari 2025)                           |
| **PHP Minimum**     | >= 8.2                                                         |
| **PHP Didukung**    | 8.2 — 8.5                                                      |
| **Siklus Dukungan** | Bug fix hingga Agustus 2026, security fix hingga Februari 2027 |

#### 5.5.1. Langkah-langkah Upgrade dari Laravel 10

1. **Upgrade PHP** ke versi >= 8.2.
2. **Update `composer.json`:**
   - `"laravel/framework": "^12.0"`
   - `"laravel/sanctum": "^4.0"`
   - `"laravel/tinker": "^2.10"`
   - `"livewire/livewire": "^3.6"` (tetap kompatibel)
   - Sesuaikan versi paket dev (`phpunit`, `collision`, `pint`, dll.) dengan matriks kompatibilitas Laravel 12.
3. **Jalankan `composer update`** dan perbaiki breaking changes.
4. **Periksa deprecations:** Beberapa facade dan helper mungkin berubah; ikuti panduan resmi [Laravel Upgrade Guide](https://laravel.com/docs/12.x/upgrade).
5. **Jalankan test suite** untuk memastikan tidak ada regresi.

---

### 5.6. Spatie Laravel Permission — Migrasi Sistem Otorisasi

Sistem otorisasi custom (tabel `roles`, `menu_roles`, middleware `RoleMiddleware`) digantikan sepenuhnya oleh paket **Spatie Laravel Permission** yang merupakan standar industri.

#### 5.6.1. Perbandingan Sistem Lama vs Baru

| Aspek                | Sistem Lama (Custom)                        | Sistem Baru (Spatie)                                                                                        |
| :------------------- | :------------------------------------------ | :---------------------------------------------------------------------------------------------------------- |
| **Tabel Database**   | `roles`, `menus`, `menu_roles` (custom)     | `roles`, `permissions`, `model_has_roles`, `model_has_permissions`, `role_has_permissions` (Spatie managed) |
| **Relasi User-Role** | `users.role_id` (FK, 1 role per user)       | Pivot `model_has_roles` (multi-role per user)                                                               |
| **Middleware**       | `RoleMiddleware.php` (custom, parsing slug) | Bawaan Spatie: `role:name`, `permission:name`, `role_or_permission:name`                                    |
| **Blade Directives** | Tidak ada (manual `@if`)                    | `@role('admin')`, `@can('manage-orders')`, `@hasanypermission(...)`                                         |
| **Caching**          | Tidak ada                                   | Built-in cache otomatis untuk performa query                                                                |

#### 5.6.2. Pemetaan Roles & Permissions

**Roles (tetap 3):**

| Role          | Deskripsi                                    |
| :------------ | :------------------------------------------- |
| `super-admin` | Akses penuh ke seluruh sistem                |
| `admin`       | Operasional harian (Master, Transaksi)       |
| `owner`       | Pemantauan bisnis (Laporan, User, Transaksi) |

**Permissions:**

| Permission            | Deskripsi                   | super-admin | admin | owner |
| :-------------------- | :-------------------------- | :---------: | :---: | :---: |
| `view-dashboard`      | Melihat halaman dashboard   |     ✅      |  ✅   |  ✅   |
| `manage-customers`    | CRUD data pelanggan         |     ✅      |  ✅   |  ✅   |
| `manage-products`     | CRUD data produk            |     ✅      |  ✅   |  ✅   |
| `manage-campaigns`    | CRUD & kirim campaign       |     ✅      |  ✅   |  ✅   |
| `manage-orders`       | Membuat & mengelola pesanan |     ✅      |  ✅   |  ✅   |
| `view-reports`        | Melihat laporan penjualan   |     ✅      |  ❌   |  ✅   |
| `export-reports`      | Ekspor laporan ke XLS       |     ✅      |  ❌   |  ✅   |
| `manage-users`        | CRUD akun pengguna          |     ✅      |  ❌   |  ✅   |
| `manage-roles`        | Kelola role & permission    |     ✅      |  ❌   |  ❌   |
| `manage-menus`        | Kelola menu navigasi        |     ✅      |  ❌   |  ❌   |
| `manage-templates`    | Kelola template pesan WA    |     ✅      |  ❌   |  ❌   |
| `manage-whatsapp-api` | Setting API WhatsApp        |     ✅      |  ❌   |  ❌   |

#### 5.6.3. Perubahan pada Model User

```php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
  use HasRoles;

  // Kolom role_id DIHAPUS — relasi role dikelola oleh Spatie via pivot table
}
```

#### 5.6.4. Middleware (di `bootstrap/app.php`)

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role'               => \Spatie\Permission\Middleware\RoleMiddleware::class,
        'permission'         => \Spatie\Permission\Middleware\PermissionMiddleware::class,
        'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
    ]);
})
```

#### 5.6.5. Contoh Penggunaan di Route

```php
// Akses berdasarkan permission
Route::middleware(['auth', 'permission:manage-orders'])->group(function () {
  Route::get('transaksi/order', App\Livewire\Order\Index::class);
});

// Akses berdasarkan role
Route::middleware(['auth', 'role:super-admin'])->group(function () {
  Route::get('setting/role', App\Livewire\Role\Index::class);
});
```

#### 5.6.6. Kontrol Menu Sidebar via Permission

Tabel `menus` tetap dipertahankan untuk menyimpan struktur navigasi, tetapi kolom akses berubah dari relasi `menu_roles` menjadi kolom `permission` yang mereferensikan nama permission Spatie:

```php
// Model Menu — kolom baru: permission (string, nullable)
// Contoh: menu "Pesanan" → permission = "manage-orders"

// Di Blade sidebar:
@can($menu->permission)
    <li><a href="{{ $menu->route }}">{{ $menu->name }}</a></li>
@endcan
```

## 6. Integrasi Sistem & Layanan Eksternal

### 6.1. Evolution API (WhatsApp API Gateway — Open-Source)

- Platform open-source self-hosted yang di-deploy via Docker.
- Pengiriman pesan menggunakan request HTTP POST ke endpoint REST API Evolution.
- Mendukung pengiriman teks, gambar, dokumen (PDF), audio, video, dan lokasi.
- Menyediakan sistem webhook untuk menerima event pesan masuk dan status pengiriman secara real-time (opsional untuk pengembangan masa depan).
- Format pengiriman teks: `POST /message/sendText/{instanceName}` dengan body `{ "number": "628xxx", "text": "..." }` dan header `apikey`.
- Format pengiriman media: `POST /message/sendMedia/{instanceName}` dengan body `{ "number": "628xxx", "mediatype": "image|document", "media": "https://...", "caption": "..." }`.

### 6.2. ImageKit.io (Media & Document Storage)

- Digunakan untuk menghindari keterbatasan ruang penyimpanan lokal server dan menyediakan CDN berkas yang cepat.
- Mengunggah bukti pembayaran, gambar produk, dan gambar promosi kampanye.
- Mengunggah konten biner dari PDF invoice yang dihasilkan sistem untuk memperoleh URL publik.

---

## 7. Persyaratan Non-Fungsional (Non-Functional Requirements)

- **Keamanan**:
  - Kata sandi pengguna harus di-hash menggunakan algoritma Bcrypt (bawaan Laravel).
  - Seluruh sesi login dilindungi oleh mekanisme CSRF Token.
  - API Key Evolution API harus disimpan sebagai environment variable, tidak boleh di-hardcode.
- **Skalabilitas**: Penyimpanan gambar didelegasikan ke ImageKit Cloud CDN untuk memastikan beban server web tetap ringan meskipun volume transaksi meningkat.
- **Performa & Interaktivitas**: Operasi CRUD dan transisi status menggunakan Livewire 3 + Alpine.js untuk memberikan pengalaman pengguna yang interaktif dan tanpa jeda reload browser.
- **Persyaratan Sistem (Setelah Upgrade)**:
  - PHP >= 8.2
  - Node.js 24 LTS
  - DBMS MySQL / MariaDB
  - Docker & Docker Compose (untuk seluruh infrastruktur)

---

## 8. Infrastruktur Docker (Docker Setup)

Seluruh stack aplikasi dikemas dalam container Docker untuk memastikan konsistensi lingkungan antara development, staging, dan production.

### 8.1. Arsitektur Container

```
┌──────────────────────────────────────────────────────────┐
│                    Docker Network: crm-network           │
│                                                          │
│  ┌──────────────┐    ┌──────────────┐                    │
│  │  webserver    │    │     app      │                    │
│  │  (Nginx)      │───>│  (PHP-FPM)   │                    │
│  │  :8000 → :80  │    │   :9000      │                    │
│  └──────────────┘    └──────┬───────┘                    │
│                             │                            │
│                    ┌────────▼───────┐                    │
│                    │       db       │                    │
│                    │   (MySQL 8.0)  │                    │
│                    │  :3306 → :3306 │                    │
│                    └────────────────┘                    │
│                                                          │
│  ┌────────────────────┐                                  │
│  │   evolution-api     │                                  │
│  │  (WhatsApp Gateway) │                                  │
│  │   :8080 → :8080     │                                  │
│  └────────────────────┘                                  │
└──────────────────────────────────────────────────────────┘
```

### 8.2. Daftar Service

| Service           | Image / Build                                    | Port            | Fungsi                                                                 |
| :---------------- | :----------------------------------------------- | :-------------- | :--------------------------------------------------------------------- |
| **app**           | Build dari `docker/php/Dockerfile` (PHP 8.2-FPM) | 9000 (internal) | Menjalankan aplikasi Laravel via PHP-FPM.                              |
| **webserver**     | `nginx:stable-alpine`                            | 8000 → 80       | Reverse proxy yang menerima request HTTP dan meneruskannya ke PHP-FPM. |
| **db**            | `mysql:8.0`                                      | 3306 → 3306     | Database MySQL dengan persistent volume.                               |
| **evolution-api** | `evoapicloud/evolution-api:latest`               | 8080 → 8080     | Gateway WhatsApp API (self-hosted, open-source).                       |

### 8.3. Struktur File Docker

```
crm-whatsapp/
├── docker/
│   ├── php/
│   │   ├── Dockerfile          # PHP 8.2-FPM + extensions Laravel
│   │   └── php-local.ini       # Override konfigurasi PHP
│   └── nginx/
│       └── default.conf        # Virtual host Nginx untuk Laravel
├── docker-compose.yml          # Orchestrasi seluruh service
└── .env.docker                 # Contoh environment khusus Docker
```

### 8.4. Detail Konfigurasi

#### PHP-FPM (`docker/php/Dockerfile`)

- **Base Image**: `php:8.2-fpm`
- **Extensions**: `pdo_mysql`, `mbstring`, `exif`, `pcntl`, `bcmath`, `gd` (freetype + jpeg), `zip`, `intl`
- **Composer**: Terinstal dari image resmi
- **Security**: Berjalan sebagai user non-root (`appuser`)
- **PHP Overrides** (`php-local.ini`):
  - `upload_max_filesize = 10M` (untuk gambar produk & bukti bayar)
  - `memory_limit = 256M`
  - OPcache diaktifkan untuk performa production

#### Nginx (`docker/nginx/default.conf`)

- Routing Laravel (`try_files $uri $uri/ /index.php?$query_string`)
- Gzip compression untuk CSS, JS, JSON, SVG
- Static file caching 30 hari (gambar, font, CSS, JS)
- Security headers (X-Frame-Options, X-Content-Type-Options, X-XSS-Protection)
- Proteksi file sensitif (`.env`, `composer.json`, `artisan`)
- `client_max_body_size = 12M` (sinkron dengan `post_max_size` PHP)

### 8.5. Cara Penggunaan

#### Menjalankan untuk pertama kali:

```bash
# 1. Salin environment Docker
cp .env.docker .env

# 2. Build dan jalankan semua container
docker compose up -d --build

# 3. Install dependensi Composer
docker compose exec app composer install

# 4. Generate application key
docker compose exec app php artisan key:generate

# 5. Jalankan migrasi dan seeder
docker compose exec app php artisan migrate --seed

# 6. Buat symbolic link storage
docker compose exec app php artisan storage:link
```

#### Perintah harian:

```bash
# Menjalankan semua service
docker compose up -d

# Menghentikan semua service
docker compose down

# Melihat log aplikasi
docker compose logs -f app

# Masuk ke shell container PHP
docker compose exec app bash

# Menjalankan Artisan command
docker compose exec app php artisan <command>

# Rebuild container setelah mengubah Dockerfile
docker compose up -d --build app
```

### 8.6. Konfigurasi Environment Docker (`.env.docker`)

| Variable                  | Default                     | Deskripsi                                    |
| :------------------------ | :-------------------------- | :------------------------------------------- |
| `APP_PORT`                | `8000`                      | Port akses web di host                       |
| `DB_HOST`                 | `db`                        | Hostname container MySQL (nama service)      |
| `DB_PORT`                 | `3306`                      | Port MySQL di host                           |
| `DB_PASSWORD`             | `secret`                    | Password root MySQL                          |
| `EVOLUTION_API_PORT`      | `8080`                      | Port akses Evolution API di host             |
| `EVOLUTION_API_KEY`       | —                           | API Key untuk autentikasi Evolution API      |
| `EVOLUTION_API_URL`       | `http://evolution-api:8080` | URL internal Evolution API (antar container) |
| `EVOLUTION_INSTANCE_NAME` | `crm-whatsapp`              | Nama instance WhatsApp di Evolution API      |
