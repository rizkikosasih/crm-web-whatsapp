@echo off
title Laravel + Vite Starter
chcp 65001 > nul
cls

rem ✅ Pindah ke root proyek Laravel
cd /d "%~dp0\.." || (
  echo ❌ Tidak bisa pindah ke root project.
  pause
  exit /b 1
)

php artisan route:clear && php artisan route:cache

echo Menjalankan Laravel + Vite Dev Server...
REM Cek apakah ada yarn.lock → berarti pakai yarn
if exist package-lock.json (
  npm run dev-all
) else (
  yarn dev-all
)
