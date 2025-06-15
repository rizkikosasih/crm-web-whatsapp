#!/bin/bash
clear
echo "Laravel + Vite Starter"
echo "==============================="
echo

# Pindah ke root proyek Laravel
cd "$(dirname "$0")/.." || {
  echo "[X] Tidak bisa pindah ke root project."
  exit 1
}

# Cek PHP
if ! command -v php &> /dev/null; then
  echo "[X] PHP tidak ditemukan."
  exit 1
fi

# Clear & cache route Laravel
php artisan route:clear && php artisan route:cache

echo
echo "[.] Menjalankan Laravel + Vite Dev Server..."
echo

if [ -f "package-lock.json" ]; then
  if ! command -v npm &> /dev/null; then
    echo "[X] npm tidak ditemukan."
    exit 1
  fi
  echo "[>] Menggunakan npm..."
  npm run dev-all
elif [ -f "yarn.lock" ]; then
  if ! command -v yarn &> /dev/null; then
    echo "[X] yarn.lock ditemukan tapi yarn tidak ditemukan."
    exit 1
  fi
  echo "[>] Menggunakan yarn..."
  yarn dev-all
else
  echo "[!] Tidak ditemukan package-lock.json atau yarn.lock."
  echo "    Default menggunakan npm..."
  if ! command -v npm &> /dev/null; then
    echo "[X] npm tidak ditemukan."
    exit 1
  fi
  npm run dev-all
fi
