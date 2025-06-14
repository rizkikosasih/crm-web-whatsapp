#!/bin/bash

# Laravel + Vite Starter
echo "=============================="
echo "Laravel + Vite Dev Server"
echo "=============================="
echo ""

# Change to project root directory
cd "$(dirname "$0")/.." || {
  echo "❌ Failed to change to project root directory."
  exit 1
}

# Clear and cache routes
{
  php artisan route:clear && \
  php artisan route:cache
}

echo "Starting Laravel + Vite Dev Server..."
# Check which package manager to use
if [ -f "package-lock.json" ]; then
  npm run dev-all
elif [ -f "yarn.lock" ]; then
  yarn dev-all
else
  echo "❌ No package manager lock file found."
  exit 1
fi
