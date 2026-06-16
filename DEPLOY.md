# Panduan Deploy ke Hostinger (CodeIgniter 4 + GitHub + SSH)

Domain: **solusimitrabangunan.com**
Repo: https://github.com/hrmnfrmnsyh1210-sys/Solusi-Mitra-Bangunan

Aplikasi ini CodeIgniter 4. Entry point ada di folder `public/`, jadi web server
HARUS menunjuk ke `public/`, bukan ke root project (di situ ada `app/`, `.env`,
`vendor/` yang tidak boleh diakses publik).

---

## Ringkasan strategi

- Repo di-clone ke folder **di luar** `public_html` → `domains/solusimitrabangunan.com/app`
- `public_html` dijadikan **symlink** ke `app/public` (cara teraman & terbersih)
- `composer install` dan `php spark migrate` dijalankan via SSH
- `.env` dibuat manual di server (TIDAK ikut ke GitHub karena berisi rahasia)

---

## Langkah 1 — Hubungkan GitHub (hPanel → Git)

1. hPanel → **Advanced → GIT**
2. **Create new repository**:
   - Repository: `https://github.com/hrmnfrmnsyh1210-sys/Solusi-Mitra-Bangunan.git`
     (repo private? tambahkan deploy key Hostinger ke GitHub dulu)
   - Branch: `main`
   - **Install path**: `domains/solusimitrabangunan.com/app`
3. Klik **Create**. Hostinger akan clone repo ke folder `app`.
4. Aktifkan **Auto-Deploy** (salin Webhook URL → GitHub repo → Settings → Webhooks →
   Add webhook → paste URL, content type `application/json`, event: Just the push event).
   Setelah ini, setiap `git push` ke `main` otomatis ter-deploy.

> Alternatif tanpa Git UI: `git clone <repo> domains/solusimitrabangunan.com/app` via SSH.

## Langkah 2 — Arahkan public_html ke folder public (via SSH)

Login SSH (hPanel → Advanced → SSH Access), lalu:

```bash
cd ~/domains/solusimitrabangunan.com

# Hapus public_html bawaan (kosong) dan ganti dengan symlink ke app/public
rm -rf public_html
ln -s app/public public_html

# Verifikasi
ls -la
```

> Jika tidak bisa membuat symlink / domain utama dikunci ke `public_html`,
> pakai **Cara Alternatif** di bagian bawah dokumen ini.

## Langkah 3 — Install dependency (composer)

```bash
cd ~/domains/solusimitrabangunan.com/app
composer install --no-dev --optimize-autoloader
```

Jika `composer` tidak ada, pakai: `php8.2 composer.phar install --no-dev -o`
(atau cek `composer --version`; Hostinger umumnya sudah menyediakan composer).

## Langkah 4 — Buat file .env produksi

Buat file `.env` di dalam folder `app`:

```bash
cd ~/domains/solusimitrabangunan.com/app
nano .env
```

Isi dengan (lihat isi lengkap di `.env.production.example`, sesuaikan password):

```ini
CI_ENVIRONMENT = production
app.baseURL = 'https://solusimitrabangunan.com/'
app.indexPage = ''

database.default.hostname = localhost
database.default.database = u299742649_mitrabangunan
database.default.username = u299742649_mitrabangunan
database.default.password = 'PASSWORD_DB_ANDA'
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306

# Update redirect URI di Google Console ke domain produksi:
# https://solusimitrabangunan.com/shop/auth/callback
GOOGLE_CLIENT_ID = ...
GOOGLE_CLIENT_SECRET = ...
```

Simpan (Ctrl+O, Enter, Ctrl+X).

## Langkah 5 — Migrasi + seed database

```bash
cd ~/domains/solusimitrabangunan.com/app
php spark migrate
php spark db:seed DatabaseSeeder
```

Ini membuat semua tabel + data awal. Login admin default:
**username: `admin` / password: `admin123`** — segera ganti setelah login!

## Langkah 6 — Permission folder writable

```bash
cd ~/domains/solusimitrabangunan.com/app
chmod -R 755 writable
```

## Langkah 7 — SSL & cek

- hPanel → **Security → SSL** → pastikan SSL aktif untuk solusimitrabangunan.com
- Buka https://solusimitrabangunan.com

---

## Update aplikasi ke depannya

Tinggal `git push` (jika auto-deploy aktif). Lalu via SSH:

```bash
cd ~/domains/solusimitrabangunan.com/app
git pull                      # jika tidak pakai auto-deploy
composer install --no-dev -o  # hanya jika dependency berubah
php spark migrate             # hanya jika ada migration baru
```

---

## Cara Alternatif (tanpa symlink / docroot tidak bisa diubah)

Jika domain utama terkunci ke `public_html`:

1. Set **Install path** Git ke `public_html` (repo langsung di web root).
2. Buat file `.htaccess` di `public_html` (root repo) untuk mengarahkan semua
   request ke folder `public/` dan memblok akses ke folder sensitif:

   ```apache
   RewriteEngine On
   RewriteRule ^(app|system|writable|tests|vendor)/ - [F,L]
   RewriteCond %{REQUEST_URI} !^/public/
   RewriteRule ^(.*)$ public/$1 [L]
   ```

3. Edit `public/.htaccess` → `RewriteBase /public/`.
4. Lakukan Langkah 3–7 dengan path `public_html` (bukan `app`).

Symlink (cara utama) lebih aman karena `app/`, `.env`, `vendor/` benar-benar
berada di luar web root.
