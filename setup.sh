#!/usr/bin/env bash
# =============================================================================
# SKENA – Setup Script (Laravel 12 + MySQL 8)
# Jalankan sekali setelah clone: bash setup.sh
# =============================================================================
set -e

# ── Warna ────────────────────────────────────────────────────────────────────
RED='\033[0;31m'; GREEN='\033[0;32m'; YELLOW='\033[1;33m'; NC='\033[0m'
info()    { echo -e "${GREEN}[SKENA]${NC} $1"; }
warning() { echo -e "${YELLOW}[WARN]${NC}  $1"; }
error()   { echo -e "${RED}[ERROR]${NC} $1"; exit 1; }

# ── Prasyarat ─────────────────────────────────────────────────────────────────
command -v php      >/dev/null 2>&1 || error "PHP tidak ditemukan. Install PHP 8.2+ terlebih dahulu."
command -v composer >/dev/null 2>&1 || error "Composer tidak ditemukan. Install Composer terlebih dahulu."

info "PHP version: $(php -r 'echo PHP_VERSION;')"
info "Composer version: $(composer --version --no-ansi 2>&1 | head -1)"

# ── Install dependensi ────────────────────────────────────────────────────────
info "Menginstall dependensi PHP (composer install)..."
composer install --no-interaction --prefer-dist

info "Menginstall dependensi Node (npm install)..."
npm install

# ── Environment ───────────────────────────────────────────────────────────────
if [ ! -f .env ]; then
    cp .env.example .env
    info ".env dibuat dari .env.example"
fi

php artisan key:generate
info "APP_KEY di-generate"

# ── Database ──────────────────────────────────────────────────────────────────
warning "Pastikan MySQL 8 sudah berjalan dan DB_DATABASE=skena sudah di-set di .env"
read -p "Jalankan migrasi + seed sekarang? [y/N]: " RUN_DB
if [[ "$RUN_DB" =~ ^[Yy]$ ]]; then
    php artisan migrate --seed
    info "Migrasi dan seeder selesai"
fi

# ── Storage link ──────────────────────────────────────────────────────────────
php artisan storage:link
info "Storage link dibuat"

# ── Cache clear ───────────────────────────────────────────────────────────────
php artisan config:clear
php artisan route:clear
php artisan view:clear

info "✅ Setup SKENA selesai! Jalankan: php artisan serve"
