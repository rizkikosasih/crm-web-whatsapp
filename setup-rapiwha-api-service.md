# Cara Setup Rapiwha

1. **Login atau Registrasi Akun Rapiwha**
   Kunjungi: [https://panel.rapiwha.com/landing/login.php](https://panel.rapiwha.com/landing/login.php)
   Silakan login jika sudah memiliki akun, atau lakukan pendaftaran terlebih dahulu.

2. **Tautkan Perangkat dengan QR Code**
   Setelah berhasil login, lakukan pemindaian QR Code (QRIS) untuk menautkan perangkat WhatsApp Anda.

3. **Nonaktifkan Incoming Message**
   Masuk ke menu **My Number**, lalu nonaktifkan opsi **"Incoming Message"** agar pesan masuk tidak diteruskan ke webhook.

4. **Salin API Key ke File `.env` atau Tambahkan pada Menu WhatsApp Setting**
   Salin API Key yang tersedia, lalu:
   - Masukkan ke dalam file `.env` proyek Anda dengan format berikut:
     ```env
     RAPIWHA_KEY=isi_api_key_anda
     ```
   - _Atau_, tambahkan ke dalam menu **WhatsApp Setting** di aplikasi Anda agar dapat digunakan sesuai kebutuhan.
