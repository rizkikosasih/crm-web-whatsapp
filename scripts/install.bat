@echo off
title Laravel Setup Starter
chcp 65001 > nul
cls

echo Laravel Setup Starter
echo ==============================
echo.

cd /d "%~dp0\.." || (
  echo ❌ Gagal pindah ke root project.
  pause
  exit /b 1
)

echo 📦 Install Dependensi ...
if not exist ".env" copy ".env.example" ".env"

composer install && npm install && php artisan key:generate && php artisan storage:link && php artisan app:migrate && (
  echo ==============================
  echo ✅ Setup Selesai
  echo ==============================
  pause
) || (
  echo ❌ Ada proses yang gagal.
  pause
  exit /b 1
)
