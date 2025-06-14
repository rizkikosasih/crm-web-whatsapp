#!/bin/bash

# Pindah ke root proyek Laravel
cd "$(dirname "$0")/.." || exit 1

echo "============================================"
echo "      Laravel Setup Starter (Linux/macOS)"
echo "============================================"
echo ""

# ✅ Install dependensi PHP
echo "📦 Install dependensi PHP (composer install)..."
composer update --lock || composer install || read -p "Tekan ENTER untuk keluar..."
echo ""

# ✅ Install dependensi Node.js (npm/yarn otomatis)
echo "📦 Install dependensi frontend (Node.js)..."
npm install || read -p "Tekan ENTER untuk keluar..."
echo ""

# ✅ Buat symbolic link storage
echo "🔗 Membuat symbolic link storage..."
php artisan storage:link || read -p "Tekan ENTER untuk keluar..."
echo ""

# ✅ Jalankan migrasi database
echo "🗄️  Menjalankan migrasi database..."
php artisan app:migrate || read -p "Tekan ENTER untuk keluar..."
echo ""

# ✅ Selesai
echo "============================================"
echo "✅ SEMUA PROSES SELESAI"
echo "============================================"
read -p "Tekan ENTER untuk keluar..."
