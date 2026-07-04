# DEV STANDARD

Dokumen ini berisi standar development dan tooling yang digunakan dalam Laravel Modular Core System. Seluruh isi di sini bersifat **development-only** dan tidak mempengaruhi runtime aplikasi.

---

# 1 Node Development Setup (Dev Only)

Digunakan hanya untuk **tooling development**, bukan runtime aplikasi.

---

## Install Node dependencies

```bash
npm init -y
npm install --save-dev prettier @prettier/plugin-php prettier-plugin-blade
```

---

## 1 Code Formatter — Prettier

Digunakan untuk menjaga **konsistensi formatting code** di seluruh project.

---

### Configuration file

`.prettierrc`

```json
{
  "semi": true,
  "tabWidth": 2,
  "printWidth": 100,
  "singleQuote": true,
  "trailingComma": "es5",
  "bracketSameLine": true,
  "plugins": ["@prettier/plugin-php", "prettier-plugin-blade"],
  "overrides": [
    {
      "files": "*.php",
      "options": {
        "tabWidth": 4
      }
    },
    {
      "files": "*.blade.php",
      "options": {
        "tabWidth": 2
      }
    }
  ]
}
```

---

---

### Optional npm script

```bash
{
  "scripts": {
    "format": "prettier --write ."
  }
}
```

---

### Run Development Tooling (Formatter + Minifier)

```
npm run format
npm run build:modules
```

---

# 2 Laravel IDE Helper

Digunakan untuk meningkatkan **autocompletion IDE dan static analysis support**.

---

## Install package

```bash
composer require --dev barryvdh/laravel-ide-helper
```

---

## Generate helper files

```bash
php artisan ide-helper:generate
php artisan ide-helper:meta
```

---

## Generated files

```
_ide_helper.php
.phpstorm.meta.php
```

---

## Git ignore

```
_ide_helper.php
.phpstorm.meta.php
```

---

# 3 Composer Script Automation (Optional)

---

## composer.json

```json
{
  "scripts": {
    "post-autoload-dump": [
      "@php artisan ide-helper:generate",
      "@php artisan ide-helper:meta" //khusus environment != production
    ]
  }
}
```

---
