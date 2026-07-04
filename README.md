# CRM Web WhatsApp Integration (Laravel 12 + TALL Stack)

Aplikasi **CRM Web WhatsApp Integration** adalah dasbor manajemen hubungan pelanggan (CRM) terpusat yang menggabungkan pengelolaan data pelanggan, katalog produk, siklus pesanan/transaksi, pelaporan penjualan, dan automasi notifikasi pesan melalui WhatsApp API.

Aplikasi ini telah dimodernisasi sepenuhnya dari stack legacy (Laravel 10 + AdminLTE + jQuery) ke **TALL Stack (Tailwind CSS v4, Alpine.js, Livewire 3, Laravel 12)** dengan desain premium Glassmorphic Dark & Light UI.

---

## 🚀 Fitur Utama

1. **Dashboard Interaktif & Log Histori**:
   - Ringkasan indikator transaksi (Belum Bayar, Lunas, Pengiriman, Selesai).
   - Grafik penjualan real-time menggunakan Neon Chart.js yang adaptif terhadap tema aktif.
   - Tabel histori status pengiriman pesan WhatsApp keluar.
2. **Manajemen Data Master (Modal-based CRUD)**:
   - CRUD Pelanggan, Produk, Campaign, Template Pesan, User, Role, dan Menu navigasi disajikan menggunakan antarmuka popup modal yang interaktif dan responsif.
3. **Automasi Siklus Transaksi (Orders)**:
   - Form Checkout dengan autocomplete pencarian dinamis (pelanggan & produk) dan validasi stok real-time.
   - Pengurangan stok otomatis saat pesanan dibuat dan pengembalian stok otomatis saat pesanan dibatalkan.
   - Unggah bukti pembayaran dan generate PDF Invoice otomatis via DomPDF.
   - Integrasi asinkronus unggahan invoice otomatis ke cloud storage **ImageKit.io**.
4. **WhatsApp Dispatcher Otomatis**:
   - Mengirim notifikasi WhatsApp secara otomatis di setiap transisi status pesanan menggunakan template dinamis berbasis placeholder (Checkout, Penerimaan Pembayaran, Pengiriman Kurir, Selesai/Kirim Invoice Link, dan Pembatalan).
5. **Akses Hak Otorisasi (Spatie Permission)**:
   - Pengaturan Role & Permission berbasis Spatie Permission yang diintegrasikan langsung dengan visibilitas menu navigasi secara dinamis.
6. **Desain Premium Bebas Kedip (Anti-FOUC & Cookie Sync)**:
   - Toggling tema (Light/Dark Mode) yang disinkronkan secara instan ke LocalStorage dan Cookie `theme` untuk menghindari bug kedipan putih (FOUC) saat inisiasi halaman dan morphing DOM Livewire.
   - Default state diatur ke **Light Mode** untuk pengguna baru.
7. **Premium UX & Scrollbar Kustom**:
   - Penyelarasan loading spinner horizontal pada tombol transaksi.
   - Custom scrollbar tipis (rounded) adaptif di seluruh web.
   - Fallback foto profil inisial nama 2 huruf adaptif.

---

## 🛠️ Stack Teknologi

- **Backend**: Laravel 12, PHP >= 8.2
- **Frontend Stack**: Livewire 3, Alpine.js 3, Tailwind CSS v4
- **Security & RBAC**: Spatie Laravel Permission
- **External APIs**: Evolution API (WhatsApp Gateway), ImageKit.io (Media Cloud Storage)
- **PDF Engine**: DomPDF
- **Icons**: Lucide Icons
- **Build Tooling**: Vite, Prettier, npm-run-all2

---

## ⚙️ Persyaratan Sistem

- PHP >= 8.2
- Composer
- Node.js 24 LTS & npm
- Database MySQL / MariaDB
- Docker (untuk menjalankan Evolution API secara lokal)

---

## 💻 Langkah Instalasi Lokal

### 1. Kloning Project & Pasang Dependensi

```bash
# Clone repository
git clone https://github.com/rizkikosasih/crm-web-whatsapp.git
cd crm-web-whatsapp

# Install Composer packages
composer install

# Install npm packages
npm install
```

### 2. Konfigurasi Environment (`.env`)

Salin file `.env.example` ke `.env` dan lengkapi kredensial database Anda:

```bash
cp .env.example .env
php artisan key:generate
```

Isi kredensial external API Anda:

```env
# Evolution API (WhatsApp Gateway)
EVOLUTION_API_URL=http://localhost:8080
EVOLUTION_API_KEY=your_evolution_api_key
EVOLUTION_INSTANCE_NAME=crm-whatsapp

# ImageKit.io Credentials
IMAGEKIT_PUBLIC_KEY="your_imagekit_public_key"
IMAGEKIT_PRIVATE_KEY="your_imagekit_private_key"
IMAGEKIT_ENDPOINT_URL="https://ik.imagekit.io/your_endpoint_id"
```

### 3. Migrasi Database & Seeding

```bash
php artisan migrate --seed
php artisan storage:link
```

### 4. Menjalankan Aplikasi

Gunakan script runner `dev-all` untuk menjalankan server PHP Laravel, kompilasi Vite, dan membuka browser secara paralel:

```bash
npm run dev-all
```

---

## 🐳 Docker Stack & Evolution API (WhatsApp Gateway)

Evolution API dijalankan menggunakan Docker Compose yang disertakan dalam repositori.

1. Jalankan container Evolution API:
   ```bash
   docker compose up -d
   ```
2. Buka dashboard Evolution API lokal Anda atau panggil API untuk memindai QR Code guna menghubungkan WhatsApp Anda dengan nomor server.
3. Gunakan panduan clipboard sekali klik yang tersedia di halaman **Pengaturan WhatsApp** untuk menyalin perintah koneksi secara instan.

---

## 🧪 Pengujian & Formatter

Seluruh kode dalam project ini wajib mengikuti standar kerapian Prettier sebelum di-commit.

```bash
# Jalankan formatting kode otomatis
npm run format

# Jalankan pengujian unit dan fitur (PHPUnit)
php artisan test
```

---

## 📄 Lisensi

Project ini dirilis di bawah lisensi [MIT License](LICENSE).
