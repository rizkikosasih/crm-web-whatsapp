@echo off
title Laravel + Vite Starter
cd /d %~dp0\..

echo Menjalankan Laravel + Vite Dev Server...
REM Cek apakah ada yarn.lock → berarti pakai yarn
if exist package-lock.json (
  npm run dev-all
) else (
  yarn dev-all
)
