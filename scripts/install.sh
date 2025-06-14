#!/bin/bash

# Laravel Setup Starter
echo "=============================="
echo "Laravel Setup Starter"
echo "=============================="
echo ""

# Change to project root directory
cd "$(dirname "$0")/.." || {
  echo "❌ Failed to change to project root directory."
  exit 1
}

echo "📦 Installing Dependencies..."
if [ ! -f ".env" ]; then
  cp ".env.example" ".env"
fi

# Run all installation commands
{
  composer install && \
  npm install && \
  php artisan storage:link && \
  php artisan app:migrate && \
  echo "==============================" && \
  echo "✅ Setup Completed" && \
  echo "=============================="
} || {
  echo "❌ Some process failed."
  exit 1
}

# Keep terminal open if running in interactive mode
if [ -t 1 ]; then
  read -p "Press any key to continue..." -n1 -s
fi
