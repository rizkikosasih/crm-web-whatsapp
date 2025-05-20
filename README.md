
<h1 align="center">CRM Web WhatsApp</h1>

<h2>📦 Requirements</h2>
<p>Pastikan kamu sudah menginstal versi berikut:</p>
<ul>
  <li><strong>PHP</strong> >= 8.1</li>
  <li><strong>Node.js</strong> >= 18.20</li>
  <li><strong>MySQL</strong></li>
</ul>

<hr />

<h2>⚙️ Instalasi</h2>
<ol>
  <li>Buka terminal, lalu masuk ke direktori proyek:
    <pre><code>cd path/to/project</code></pre>
  </li>
  <li>Salin file <code>.env.example</code> menjadi <code>.env</code>:
    <pre><code>cp .env.example .env</code></pre>
  </li>
  <li>Install dependensi PHP:
    <pre><code>composer install</code></pre>
  </li>
  <li>Install dependensi frontend (Node.js):
    <pre><code>npm install</code></pre>
  </li>
  <li>Link Storage <pre><code>php artisan storage:link</code></pre></li>
  <li>Buat database baru di MySQL dengan nama:
    <pre><code>crm</code></pre>
  </li>
  <li>Jalankan perintah migrasi database:
    <pre><code>php artisan app:migrate</code></pre>
  </li>
</ol>

<hr />

<h2>🚀 Menjalankan Aplikasi</h2>
<ol>
  <li>Jalankan frontend Vite dev server:
    <pre><code>npm run dev</code></pre>
  </li>
  <li>Buka terminal baru, lalu jalankan backend Laravel:
    <pre><code>php artisan serve</code></pre>
  </li>
  <li>Buka browser dan akses:
    <pre><code>http://localhost:8000</code></pre>
  </li>
</ol>

<hr />

<h2>📝 Catatan Tambahan</h2>
<ul>
  <li>Pastikan koneksi database sudah disesuaikan di file <code>.env</code>.</li>
  <li>Jika terjadi error saat menjalankan <code>php artisan app:migrate</code>, periksa:
    <ul>
      <li>Apakah database <code>crm</code> sudah dibuat</li>
      <li>Apakah konfigurasi <code>.env</code> sesuai</li>
    </ul>
  </li>
</ul>
