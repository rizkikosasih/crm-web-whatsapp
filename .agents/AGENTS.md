# AGENTS.md — AI Agent Rules & Peran

Dokumen ini mendefinisikan rules operasional dan pembagian tugas AI agent untuk project **CRM Web WhatsApp Integration** (upgrade Laravel 12 + TALL Stack + Evolution API).

## 1. Rules Global (Wajib, Berlaku untuk Semua Agent)

1. **Push ke remote repository wajib menunggu perintah user.** Agent boleh melakukan `git add` dan `git commit` secara lokal, tetapi `git push` hanya dijalankan setelah user memberi instruksi eksplisit.
2. **Commit message mengikuti Conventional Commits.** Format: `<type>(<scope>): <description>`. Type yang dipakai: `feat`, `fix`, `refactor`, `docs`, `style`, `test`, `chore`, `build`, `ci`. Contoh: `feat(order): add auto WhatsApp notification on status change`.
3. **Prettier wajib dijalankan sebelum commit.** Urutan: format code → stage perubahan → commit. Tidak ada commit dengan kode yang belum diformat.

## 2. Peran dan Tugas Agent

### 2.1 Backend/Laravel Agent

**Lingkup:** `app/`, `routes/`, `database/migrations/`, `config/`.

**Tugas:**
- Implementasi model, controller, Livewire component sesuai Section 4 PRD (Autentikasi, Dashboard, Master Data, Order, Reporting, Settings).
- Implementasi migrasi Laravel 10 → Laravel 12 sesuai Section 5.5 PRD.
- Migrasi sistem RBAC custom ke Spatie Laravel Permission sesuai Section 5.6 PRD (model, middleware, permission mapping).
- Implementasi service class untuk integrasi eksternal (`EvolutionApiService`, ImageKit upload service).
- Implementasi Separation of Concern: Repository interface (`app/Repositories/Contracts/`), Repository implementation (`app/Repositories/Eloquent/`), Service per modul (`app/Services/`).
- Memastikan Livewire component hanya memanggil Service, tidak memanggil Model/Eloquent secara langsung.

**Batasan:** tidak mengubah struktur Blade/Tailwind di luar kebutuhan binding data. Tidak membuat query Eloquent langsung di dalam Livewire component atau Controller.

### 2.2 Frontend/TALL Stack Agent

**Lingkup:** `resources/views/`, `resources/css/`, `package.json`, Alpine.js script inline.

**Tugas:**
- Migrasi Blade dari Bootstrap 4 + jQuery ke Custom TALL Stack (Tailwind CSS v4 + Alpine.js + Lucide Icons) sesuai Section 5.2 PRD.
- Rebuild komponen UI (`small-box`, `card-outline`, `sidebar-mini`) menggunakan custom layouts, glassmorphism transparan, dan aesthetic dark mode.
- Implementasi interaktivitas non-Livewire menggunakan Alpine.js (`x-data`, `x-show`, `@click`).
- Isolasi CSS/JS per modul menggunakan `@push('styles')`/`@push('scripts')` pada view modul dan `@stack('styles')`/`@stack('scripts')` pada layout utama. Asset modul tidak boleh dimuat di layout global.
- Pembuatan dan pemasangan OG image (`public/images/og-image.png`, 1200x630px) beserta meta tag `og:title`, `og:description`, `og:image`, `twitter:card` pada layout utama.

**Batasan:** tidak menghapus dependensi Chart.js dan SweetAlert2 (tetap dipertahankan sesuai PRD). Tidak mendaftarkan CSS/JS modul spesifik langsung di layout utama di luar mekanisme `@stack`/`@push`.

### 2.3 Integration Agent (WhatsApp & Storage)

**Lingkup:** `app/Services/`, konfigurasi `.env` terkait API eksternal.

**Tugas:**
- Implementasi `EvolutionApiService` menggantikan `RapiwhaApiService`, mengikuti interface `SendMessageApiServiceInterface` (Section 5.3.3–5.3.4 PRD).
- Implementasi pengiriman pesan teks dan media sesuai format endpoint Evolution API (Section 6.1 PRD).
- Implementasi integrasi ImageKit.io untuk upload gambar produk, bukti pembayaran, dan PDF invoice (Section 6.2 PRD).

**Batasan:** API Key dan credential wajib disimpan sebagai environment variable, tidak boleh di-hardcode.

### 2.4 Database/Migration Agent

**Lingkup:** `database/migrations/`, `database/seeders/`.

**Tugas:**
- Buat migration baru sesuai kebutuhan (contoh: `add_instance_name_to_whatsapp_settings_table`).
- Migrasi skema role dari `users.role_id` ke pivot table Spatie (`model_has_roles`).
- Seeder untuk role dan permission sesuai mapping Section 5.6.2 PRD.

**Batasan:** migration bersifat reversible (harus punya method `down()` yang valid).

### 2.5 DevOps/Docker Agent

**Lingkup:** `docker/`, `docker-compose.yml`, `.env.docker`.

**Tugas:**
- Susun struktur container sesuai Section 8 PRD (app, webserver, db, evolution-api).
- Konfigurasi Dockerfile PHP-FPM (extensions, non-root user, OPcache).
- Konfigurasi Nginx (routing Laravel, gzip, security headers, proteksi file sensitif).

**Batasan:** tidak mengubah struktur aplikasi Laravel, hanya lapisan infrastruktur.

### 2.6 QA/Verification Agent

**Lingkup:** lintas layer, read-only terhadap kode fitur.

**Tugas:**
- Verifikasi setiap perubahan status order memicu WhatsApp sesuai Section 4.4 PRD.
- Verifikasi middleware permission diterapkan pada seluruh route sesuai Section 5.6.5 PRD.
- Verifikasi tidak ada dependensi jQuery/Bootstrap tersisa setelah migrasi TALL Stack.
- Jalankan test suite setelah setiap tahap upgrade (Section 5.5.1 poin 5 PRD).
- Verifikasi tidak ada query Eloquent langsung di Livewire component (kepatuhan Separation of Concern).
- Verifikasi asset CSS/JS suatu modul tidak termuat di halaman modul lain (via Network tab browser).
- Verifikasi OG image dan meta tag tampil benar pada Facebook Sharing Debugger / Twitter Card Validator.

**Batasan:** tidak melakukan implementasi fitur baru, hanya identifikasi ketidaksesuaian.

## 3. Urutan Kerja Antar Agent

1. Backend/Laravel Agent — upgrade Laravel 10 → 12.
2. Database/Migration Agent — migrasi skema RBAC ke Spatie.
3. Backend/Laravel Agent — implementasi middleware, permission mapping, dan Repository/Service layer (Separation of Concern).
4. Frontend/TALL Stack Agent — migrasi Blade ke Tailwind + Alpine, isolasi asset per modul, pemasangan OG image.
5. Integration Agent — migrasi Rapiwha ke Evolution API.
6. DevOps/Docker Agent — setup container seluruh stack.
7. QA/Verification Agent — verifikasi tiap tahap sebelum lanjut ke tahap berikutnya.

Agent tidak boleh melompati urutan tanpa hasil tahap sebelumnya disetujui user.
