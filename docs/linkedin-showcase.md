# LinkedIn Showcase - CRM Web WhatsApp Integration

Dokumen ini berisi materi siap pakai untuk publikasi portofolio atau postingan **LinkedIn Showcase** guna mempromosikan hasil proyek integrasi CRM dan WhatsApp Gateway ini.

---

## 📌 Metadata Portofolio LinkedIn

- **Judul Proyek (Project Title)**: CRM Web WhatsApp Integration (Laravel 12 & TALL Stack)
- **Kategori Proyek**: Web Development / Systems Integration
- **URL Repositori**: `https://github.com/rizkikosasih/crm-web-whatsapp`
- **Keahlian Terkait (Skills)**:
  - Laravel Framework (v12)
  - TALL Stack (Tailwind CSS v4, Alpine.js, Livewire 3)
  - WhatsApp API Integration (Evolution API / Baileys Engine)
  - Cloud Storage Integration (ImageKit.io)
  - Role-Based Access Control (Spatie Laravel Permission)
  - Docker & Containerization
  - MySQL Database Administration
  - Asynchronous & Event-Driven PHP

---

## 📝 LinkedIn Post Copy (Siap Bagikan)

🚀 **[PROJECT SHOWCASE] CRM Web WhatsApp Integration: Automasi Siklus Transaksi Modern!**

Saya baru saja menyelesaikan modernisasi penuh pada sistem **CRM Web & WhatsApp Integration** menggunakan **Laravel 12 + TALL Stack (Tailwind CSS v4, Alpine.js, Livewire 3)** dengan arsitektur UI _Glassmorphic Premium_.

Sistem ini dirancang untuk mengotomatisasi interaksi dengan pelanggan dan memangkas pekerjaan manual administrasi toko secara drastis melalui gerbang WhatsApp Gateway (_self-hosted_ Evolution API).

---

### 🌟 Fitur Unggulan Proyek:

1.  **Automasi Notifikasi WhatsApp (State-Machine)**:
    Sistem secara asinkron mendeteksi transisi status pesanan (Checkout, Konfirmasi Pembayaran, Pengiriman Kurir, Selesai, atau Batal) dan langsung mengirim notifikasi WhatsApp yang disesuaikan secara dinamis ke pelanggan.
2.  **In-App WhatsApp Connector (Real-Time Scan QR)**:
    Refaktorisasi modul integrasi yang membaca setelan langsung dari `.env` secara aman, mengecek status secara _lazy-loading_ via `wire:init`, dan menampilkan QR Code untuk pemindaian langsung secara _inline_ di dasbor web.
3.  **PDF Invoice & ImageKit Cloud Integration**:
    Saat transaksi selesai, sistem me-render invoice PDF secara otomatis (via DomPDF), mengunggahnya langsung ke CDN cloud **ImageKit.io**, dan mengirimkan link unduhan instan ke WhatsApp pelanggan.
4.  **Role-Based Access Control (RBAC)**:
    Pengamanan hak akses penuh untuk modul Master Data, Transaksi, Laporan Penjualan, dan Sistem Navigasi menggunakan **Spatie Laravel Permission**.
5.  **Neon Interactive Charts & Anti-FOUC Toggling**:
    Dasbor interaktif dilengkapi grafik penjualan dinamis (Chart.js) dan sistem deteksi tema gelap/terang berbasis Cookie & LocalStorage demi menghindari kedipan putih (_anti-flicker FOUC_).

---

### 🛠️ Stack Teknologi:

- **Backend**: PHP 8.2+, Laravel 12 (Unified App Builder)
- **Frontend**: Tailwind CSS v4, Alpine.js 3, Livewire 3
- **Infrastruktur**: Docker Compose (MySQL db + Evolution API container)
- **API Pihak Ketiga**: ImageKit CDN, Evolution API (Baileys Engine)
- **Kredensial**: Diisolasi penuh pada berkas `.env` dan diakses melalui caching-safe `config()` wrapper.

📂 **Lihat Repositori & Dokumentasi Lengkap**:
👉 [github.com/rizkikosasih/crm-web-whatsapp](https://github.com/rizkikosasih/crm-web-whatsapp)

---

## 📈 Gambar/Media Pendukung yang Disarankan:

1.  **Tangkapan Layar Dashboard Utama**: Menampilkan grafik penjualan neon (Dark/Light mode).
2.  **Halaman WhatsApp Connector**: Menampilkan status hijau _"Terhubung"_ atau QR Code yang siap dipindai secara inline.
3.  **Contoh Bukti Pesan WhatsApp**: Foto pratinjau pesan otomatis di HP pelanggan yang berisi rincian pesanan dan link invoice PDF ImageKit.
