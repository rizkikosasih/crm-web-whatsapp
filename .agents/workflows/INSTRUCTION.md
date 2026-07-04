# Panduan Setup & Instruksi Proyek (INSTRUCTION.md)

Dokumen ini mendefinisikan langkah-langkah setup awal, perintah pengembangan harian, standardisasi penulisan kode, serta deployment menggunakan Docker untuk proyek **CRM Web WhatsApp Integration**.

---

## 1. Persyaratan Sistem (Prerequisites)

Pastikan mesin lokal Anda telah terinstal software berikut:
- **PHP** >= 8.2
- **Composer** >= 2.x
- **Node.js** >= 24.x (LTS)
- **MySQL / MariaDB** (jika running lokal tanpa Docker)
- **Docker & Docker Compose** (jika ingin di-deploy dalam container)

---

## 2. Inisialisasi Proyek (First-Time Setup)

### A. Lingkungan Lokal (Tanpa Docker)

1. **Clone repository & masuk ke direktori proyek**:
   ```bash
   cd crm-whatsapp
   ```

2. **Salin file konfigurasi environment**:
   ```bash
   cp .env.example .env
   ```
   *Sesuaikan konfigurasi database (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`), ImageKit (`IMAGEKIT_*`), dan Evolution API (`EVOLUTION_API_*`) di dalam `.env`.*

3. **Install dependensi PHP**:
   ```bash
   composer install
   ```

4. **Install dependensi Node.js**:
   ```bash
   npm install
   ```

5. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

6. **Jalankan database migration & seeder**:
   ```bash
   php artisan migrate --seed
   ```

7. **Buat symbolic link storage**:
   ```bash
   php artisan storage:link
   ```

---

## 3. Pengembangan Harian (Development Workflow)

### Perintah Cepat (Concurrently Running)
Aplikasi ini dikonfigurasi menggunakan `npm-run-all2` untuk menjalankan server PHP internal dan Vite secara paralel dalam satu perintah terminal:

- **Menjalankan dev server (paralel)**:
  ```bash
  npm run dev
  ```
  *(Perintah di atas otomatis mengeksekusi `php artisan serve` dan `vite` secara bersamaan).*

- **Format kode otomatis (Prettier)**:
  Sebelum melakukan commit lokal, pastikan seluruh kode telah diformat menggunakan Prettier:
  ```bash
  npm run format
  ```

- **Uji kelayakan (Test Suite)**:
  Jalankan pengujian test suite untuk mendeteksi regresi:
  ```bash
  php artisan test
  ```

---

## 4. Setup & Deployment Docker

Seluruh container infrastruktur (Nginx, PHP-FPM, MySQL, dan Evolution API WhatsApp Gateway) dapat dijalankan dalam sekali perintah.

1. **Salin environment khusus Docker**:
   ```bash
   cp .env.docker .env
   ```

2. **Jalankan docker container**:
   ```bash
   docker compose up -d --build
   ```

3. **Inisialisasi aplikasi di dalam container**:
   ```bash
   # Install dependensi
   docker compose exec app composer install
   docker compose exec app npm install
   
   # Setup database & storage link
   docker compose exec app php artisan migrate --seed
   docker compose exec app php artisan storage:link
   ```

4. **Menghentikan container**:
   ```bash
   docker compose down
   ```

---

## 5. Standardisasi Code (Dev Standard)
- **Separation of Concerns (SoC)**: Jangan menulis query database (Eloquent/SQL) langsung di dalam Controller atau Livewire Component. Selalu gunakan Repository Class untuk query, dan panggil melalui Service Class.
- **Dynamic Access Controls**: Akses sidebar menu dikontrol oleh Spatie Permission yang dicantumkan pada kolom `permission` di tabel `menus`.
- **Minimalist Iconography**: Gunakan **Lucide Icons** sebagai pengganti FontAwesome untuk tampilan yang lebih modern.
- **Commit Messages**: Selalu ikuti kaidah **Conventional Commits** (contoh: `feat(order): ...` atau `fix(settings): ...`).
