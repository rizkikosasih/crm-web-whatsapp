<h1 align="center">CRM Web WhatsApp</h1>

<h2>📦 Requirements</h2>
<p>Pastikan perangkat Anda telah terinstal versi berikut:</p>
<ul>
  <li><strong>PHP</strong> ≥ 8.1</li>
  <li><strong>Node.js</strong> ≥ 18.20</li>
  <li><strong>MySQL</strong></li>
</ul>

<hr />

<h2>⚙️ Instalasi</h2>
<ol>
  <li>Buat database baru di MySQL dengan nama:
    <pre><code>crm</code></pre>
  </li>
  <li>Jalankan perintah instalasi sesuai dengan sistem operasi yang digunakan:</li>
</ol>

<table>
<thead>
<tr><th>Sistem Operasi</th><th>Perintah</th></tr>
</thead>
<tbody>
<tr><td><strong>Windows</strong></td><td>Jalankan file <code>scripts/install.cmd</code> (klik dua kali atau via CMD)</td></tr>
<tr><td><strong>Linux/macOS</strong></td><td>Buka terminal, lalu jalankan:<br><code>./scripts/install.sh</code></td></tr>
</tbody>
</table>

<hr />

<h2>🚀 Menjalankan Aplikasi</h2>
<ol>
  <li>Jalankan aplikasi sesuai dengan sistem operasi yang digunakan:</li>
</ol>

<table>
<thead>
<tr><th>Sistem Operasi</th><th>Perintah</th></tr>
</thead>
<tbody>
<tr><td><strong>Windows</strong></td><td>Jalankan file <code>scripts/run.cmd</code></td></tr>
<tr><td><strong>Linux/macOS</strong></td><td>Buka terminal, lalu jalankan:<br><code>./scripts/run.sh</code></td></tr>
</tbody>
</table>

<p>Setelah proses berjalan, aplikasi akan otomatis terbuka di browser default Anda.</p>

<hr />

<h2>📝 Catatan Tambahan</h2>
<ul>
  <li>Pastikan konfigurasi koneksi database sudah sesuai di file <code>.env</code>.</li>
  <li>Jika terjadi error saat menjalankan <code>php artisan app:migrate</code>, periksa:
    <ul>
      <li>Pastikan database <code>crm</code> sudah dibuat.</li>
      <li>Pastikan pengaturan database di file <code>.env</code> sudah benar.</li>
    </ul>
  </li>
  <li>Jika file <code>install.sh</code> atau <code>run.sh</code> tidak bisa dijalankan di Linux/macOS, berikan izin eksekusi terlebih dahulu:
    <pre><code>chmod +x scripts/*.sh</code></pre>
  </li>
</ul>
