@echo off
title Laravel + Vite Starter
chcp 65001 > nul
cls

rem Pindah ke root proyek Laravel
cd /d "%~dp0\.." || (
  echo [X] Tidak bisa pindah ke root project.
  pause
  exit /b 1
)

rem Cek PHP
where php > nul 2>&1
if errorlevel 1 (
  echo [X] PHP tidak ditemukan.
  pause
  exit /b 1
)

rem Clear & cache routes
php artisan route:clear && php artisan route:cache

echo.
echo [.] Menjalankan Laravel + Vite Dev Server...
echo.

rem Cek file lock dan tool yang digunakan
if exist package-lock.json (
  where npm > nul 2>&1
  if errorlevel 1 (
    echo [X] npm tidak ditemukan.
    pause
    exit /b 1
  )
  echo [>] Menggunakan npm...
  npm run dev-all
) else if exist yarn.lock (
  where yarn > nul 2>&1
  if errorlevel 1 (
    echo [X] yarn.lock ditemukan tapi yarn tidak ditemukan.
    pause
    exit /b 1
  )
  echo [>] Menggunakan yarn...
  yarn dev-all
) else (
  echo [!] Tidak ditemukan package-lock.json atau yarn.lock.
  echo     Default menggunakan npm...
  where npm > nul 2>&1
  if errorlevel 1 (
    echo [X] npm tidak ditemukan.
    pause
    exit /b 1
  )
  npm run dev-all
)
