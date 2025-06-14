@echo off
title Laravel Setup Starter
cd /d %~dp0\..

echo ==============================
echo Install dependensi PHP...
echo ==============================
composer update --lock || composer install || pause

echo ==============================
echo Install dependensi frontend (Node.js)...
echo ==============================
npm install || pause

echo ==============================
echo Membuat symbolic link storage...
echo ==============================
php artisan storage:link || pause

echo ==============================
echo Menjalankan migrasi database...
echo ==============================
php artisan app:migrate || pause

echo ==============================
echo SEMUA PROSES SELESAI ✅
echo ==============================
pause
