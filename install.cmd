@echo off
title Laravel Setup Starter
cd /d %~dp0

echo ==============================
echo Salin .env.example ke .env
echo ==============================
copy /Y .env.example .env

echo ==============================
echo Install dependensi PHP...
echo ==============================
composer install

echo ==============================
echo Install dependensi frontend (Node.js)...
echo ==============================
npm install

echo ==============================
echo Membuat symbolic link storage...
echo ==============================
php artisan storage:link

echo ==============================
echo Menjalankan migrasi database...
echo ==============================
php artisan app:migrate

echo ==============================
echo SEMUA PROSES SELESAI ✅
echo ==============================
exit
