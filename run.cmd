@echo off
title Laravel + Vite Starter
cd /d %~dp0

echo Menjalankan Laravel + Vite Dev Server...
npm run dev-all
yarn dev-all

echo Membuka browser...
start "" http://localhost:8000
