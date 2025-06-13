#!/bin/bash

cd "$(dirname "$0")/.." || exit 1

echo "Menjalankan Laravel + Vite Dev Server..."

if [ -f "package-lock.json" ]; then
  npm run dev-all
else
  yarn dev-all
fi
