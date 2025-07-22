# 📦 Integrasi ImageKit.io di Laravel

Panduan ini menjelaskan cara mengintegrasikan layanan **ImageKit.io** untuk upload dan delete gambar di Laravel menggunakan kunci API dari dashboard ImageKit.

---

## 🔧 1. Daftar dan Buat Project di [ImageKit.io](https://imagekit.io)

1. Kunjungi [https://imagekit.io](https://imagekit.io) dan buat akun.
2. Setelah login, buat 1 project (misal: `MyLaravelApp`).
3. Masuk ke halaman **Developer > API Keys**, dan catat:
   - **Public API Key**
   - **Private API Key**
   - **URL-endpoint** (contoh: `https://ik.imagekit.io/your_imagekit_id`)
   - **ImageKit ID** (bisa ditemukan di bagian **Dashboard > URL-endpoint**)

---

## 📁 2. Tambahkan Konfigurasi ke `.env`

Tambahkan variabel berikut ke file `.env` Laravel:

```env
IMAGE_KIT_PUBLIC_KEY=your_public_api_key
IMAGE_KIT_PRIVATE_KEY=your_private_api_key
IMAGE_KIT_URL_ENDPOINT=https://ik.imagekit.io/your_imagekit_id
IMAGE_KIT_ID=your_imagekit_id
```
