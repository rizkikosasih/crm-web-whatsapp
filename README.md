# CRM Web WhatsApp Integration

Sistem Manajemen Hubungan Pelanggan (CRM) berbasis Web dengan integrasi otomatis WhatsApp API untuk optimalisasi penjualan dan layanan pelanggan.

---

## Ikhtisar Proyek

Proyek ini adalah sistem CRM (Customer Relationship Management) yang dirancang untuk membantu pengelolaan data pelanggan, inventaris produk, hingga otomasi alur kerja pesanan. Dilengkapi dengan integrasi WhatsApp API (Rapiwha), sistem ini memungkinkan pengiriman notifikasi otomatis terkait status pesanan dan kampanye pemasaran secara langsung ke nomor pelanggan.

## Fitur Utama

### Dashboard Interaktif

- Statistik ringkas, grafik performa, dan log pengiriman pesan WhatsApp.

### Manajemen Pelanggan

- Sistem CRUD data pelanggan yang terpusat.

### Manajemen Produk & Inventaris

- Pengelolaan data produk terintegrasi dengan pengiriman info dan gambar produk via WhatsApp menggunakan ImageKit.

### Manajemen Pesanan (Order)

- Pelacakan siklus hidup pesanan dengan notifikasi otomatis status: Dibuat, Dibayar, Dikirim, Selesai, Dibatalkan.

### Marketing Campaign

- Fitur pengiriman pesan massal (broadcast) untuk promosi atau informasi kampanye ke seluruh database pelanggan.
    
### Manajemen Media

- Optimasi penyimpanan dan pengiriman gambar melalui integrasi URL ImageKit.
    
## Tech Stack

- Framework: Laravel 10 (Back-End)
    
- Frontend: Livewire 3 & Vite
    
- Database: MySQL
    
- Integrasi Pihak Ketiga: Rapiwha (WhatsApp API) & ImageKit
    

---

## Requirements

Pastikan perangkat Anda telah terinstal versi berikut:

- PHP >= 8.1
    
- Node.js >= 18.20
    
- MySQL
    

---

## Instalasi

- Buat database baru di MySQL dengan nama: crm
    
- Jalankan perintah instalasi sesuai dengan sistem operasi yang digunakan:
    
	Sistem Operasi Windows: Jalankan file `scripts/install.cmd` (klik dua kali atau via CMD)
	
	Sistem Operasi Linux/macOS: Buka terminal, lalu jalankan: `./scripts/install.sh`

---

## Menjalankan Aplikasi

- Jalankan aplikasi sesuai dengan sistem operasi yang digunakan:

	Sistem Operasi Windows: Jalankan file `scripts/run.cmd`
	
	Sistem Operasi Linux/macOS: Buka terminal, lalu jalankan: `./scripts/run.sh`
	
	Setelah proses berjalan, aplikasi akan otomatis terbuka di browser default Anda. 

	Jika aplikasi tidak otomatis terbuka di browser default Anda, silakan ikuti langkah berikut:

	1. Pastikan terminal atau command prompt tetap terbuka (jangan ditutup).
	    
	2. Buka browser Anda (Chrome, Edge, atau Firefox).
	    
	3. Ketik alamat berikut di bilah alamat (address bar) browser: `http://localhost:8000` atau `http://127.0.0.1:8000`.

---

## Catatan Tambahan

- Pastikan konfigurasi koneksi database dan API Key sudah sesuai di file .env.
    
- Jika terjadi error saat menjalankan php artisan app:migrate, periksa database crm dan pengaturan di .env.
    
- Jika file script tidak bisa dijalankan di Linux/macOS, berikan izin eksekusi: `chmod +x scripts/*.sh`.