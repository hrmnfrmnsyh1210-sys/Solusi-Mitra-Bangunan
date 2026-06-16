#!/usr/bin/env bash
#
# Script deploy CodeIgniter 4 ke Hostinger.
# Cara pakai (via SSH), dari dalam folder project:
#
#   cd ~/domains/solusimitrabangunan.com/app
#   bash deploy.sh
#
# Aman dijalankan berulang (idempotent): composer + migrate hanya menambah yg perlu,
# .env & seed tidak ditimpa jika sudah ada.

set -euo pipefail

# --- Lokasi ---------------------------------------------------------------
APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"   # folder project (root repo)
DOMAIN_DIR="$(dirname "$APP_DIR")"                        # .../domains/solusimitrabangunan.com
PUBLIC_HTML="$DOMAIN_DIR/public_html"
cd "$APP_DIR"

DOMAIN="solusimitrabangunan.com"
DB_NAME="u299742649_mitrabangunan"
DB_USER="u299742649_mitrabangunan"

echo "================================================================"
echo " Deploy $DOMAIN"
echo " App dir : $APP_DIR"
echo "================================================================"

# --- 1. Composer ----------------------------------------------------------
echo ""
echo "==> [1/6] Install dependency (composer)"
if command -v composer >/dev/null 2>&1; then
    composer install --no-dev --optimize-autoloader
elif [ -f composer.phar ]; then
    php composer.phar install --no-dev --optimize-autoloader
else
    echo "    composer tidak ditemukan."
    if [ -d vendor ]; then
        echo "    vendor/ sudah ada, lanjut."
    else
        echo "    ERROR: vendor/ tidak ada dan composer tidak tersedia. Hentikan." >&2
        exit 1
    fi
fi

# --- 2. Symlink public_html -> app/public ---------------------------------
echo ""
echo "==> [2/6] Arahkan public_html ke folder public"
if [ -L "$PUBLIC_HTML" ]; then
    echo "    Symlink sudah ada: $PUBLIC_HTML"
else
    rm -rf "$PUBLIC_HTML"
    ln -s "$APP_DIR/public" "$PUBLIC_HTML"
    echo "    Dibuat: $PUBLIC_HTML -> $APP_DIR/public"
fi

# --- 3. File .env ---------------------------------------------------------
echo ""
echo "==> [3/6] Konfigurasi .env"
if [ -f .env ] && grep -q "CI_ENVIRONMENT = production" .env; then
    echo "    .env produksi sudah ada, dilewati."
else
    read -rsp "    Masukkan password database MySQL ($DB_USER): " DB_PASS
    echo ""
    cat > .env <<ENVEOF
#--------------------------------------------------------------------
# Konfigurasi produksi (dibuat otomatis oleh deploy.sh)
#--------------------------------------------------------------------
CI_ENVIRONMENT = production

app.baseURL = 'https://$DOMAIN/'
app.indexPage = ''
app.forceGlobalSecureRequests = true

database.default.hostname = localhost
database.default.database = $DB_NAME
database.default.username = $DB_USER
database.default.password = '$DB_PASS'
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306

# Google OAuth (login customer) - isi jika dipakai, lalu set redirect URI:
# https://$DOMAIN/shop/auth/callback
# GOOGLE_CLIENT_ID =
# GOOGLE_CLIENT_SECRET =
ENVEOF
    chmod 600 .env
    echo "    .env dibuat."
fi

# --- 4. Migrasi database --------------------------------------------------
echo ""
echo "==> [4/6] Migrasi database"
php spark migrate

# --- 5. Seed (hanya pertama kali) -----------------------------------------
echo ""
echo "==> [5/6] Seed data awal"
if [ -f writable/.seeded ]; then
    echo "    Sudah pernah di-seed, dilewati."
else
    php spark db:seed DatabaseSeeder
    touch writable/.seeded
    echo "    Seed selesai. Login admin default: admin / admin123 (segera ganti!)"
fi

# --- 6. Permission --------------------------------------------------------
echo ""
echo "==> [6/6] Set permission folder writable"
chmod -R 755 writable

echo ""
echo "================================================================"
echo " SELESAI. Buka: https://$DOMAIN"
echo " Pastikan SSL aktif di hPanel -> Security -> SSL"
echo "================================================================"
