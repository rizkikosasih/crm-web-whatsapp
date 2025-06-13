#!/bin/bash

# Pindah ke root proyek Laravel
cd "$(dirname "$0")/.." || exit 1

echo "============================================"
echo "      Laravel Setup Starter (Linux/macOS)"
echo "============================================"
echo ""

# ✅ Salin .env.example ke .env jika belum ada
echo "📄 Menyalin .env.example ke .env ..."
if [ -f .env ]; then
  echo "⚠️  File .env sudah ada, dilewati."
else
  cp .env.example .env
  echo "✅ File .env berhasil disalin."
fi
echo ""

# ✅ Install dependensi PHP
echo "📦 Install dependensi PHP (composer install)..."
composer install || { echo "❌ Gagal menjalankan composer install"; exit 1; }
echo ""

# ✅ Install dependensi Node.js (npm/yarn otomatis)
echo "📦 Install dependensi frontend (Node.js)..."
npm install || { echo "❌ Gagal menjalankan npm install"; exit 1; }
echo ""

# ✅ Buat symbolic link storage
echo "🔗 Membuat symbolic link storage..."
php artisan storage:link || { echo "❌ Gagal membuat symbolic link storage"; exit 1; }
echo ""

# ✅ Jalankan migrasi database
echo "🗄️  Menjalankan migrasi database..."
php artisan app:migrate || { echo "❌ Migrasi database gagal"; exit 1; }
echo ""

# ✅ Selesai
echo "============================================"
echo "✅ SEMUA PROSES SELESAI"
echo "============================================"
