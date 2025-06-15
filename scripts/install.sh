#!/bin/bash
clear
echo "[.] Laravel Setup Starter"
echo "==============================="
echo

# Pindah ke root proyek Laravel
cd "$(dirname "$0")/.." || {
  echo "[X] Gagal pindah ke root project."
  exit 1
}

echo "[.] Install Dependensi ..."

# Salin .env jika belum ada
if [ ! -f ".env" ]; then
  cp .env.example .env
fi

# Cek composer
if ! command -v composer &> /dev/null; then
  echo "[X] Composer tidak ditemukan."
  exit 1
fi

# Cek npm
if ! command -v npm &> /dev/null; then
  echo "[X] npm tidak ditemukan."
  exit 1
fi

# Cek PHP
if ! command -v php &> /dev/null; then
  echo "[X] PHP tidak ditemukan."
  exit 1
fi

# Jalankan proses setup
if composer install && npm install && php artisan key:generate && php artisan storage:link && php artisan app:migrate; then
  echo "==============================="
  echo "[OK] Setup Selesai"
  echo "==============================="
else
  echo "[X] Ada proses yang gagal."
  exit 1
fi
