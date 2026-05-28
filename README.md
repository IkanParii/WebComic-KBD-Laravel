# WebComic KBD

Aplikasi web berbasis **Laravel 12 + Tailwind CSS** untuk membaca dan mengelola komik dengan sistem role: **User**, **Publisher**, dan **Admin**.

---

## ⚙️ Tech Stack

- Laravel 12 + Breeze
- Tailwind CSS + Alpine.js
- MySQL
- Pest PHP (testing)
- SMTP Gmail (email verification & OTP)

---

## 🖥️ Instalasi Lokal

### Requirements

- PHP ≥ 8.2
- Composer
- Node.js ≥ 18
- MySQL
- Git

### Langkah-langkah

**1. Clone repository**
```bash
git clone https://github.com/IkanParii/WebComic-KBD-Laravel.git
cd WebComic-KBD-Laravel
```

**2. Install dependency**
```bash
composer install
npm install
```

**3. Setup environment**
```bash
cp .env.example .env
php artisan key:generate
```

**4. Buat database MySQL**
```sql
CREATE DATABASE laravel_db;
```

**5. Edit `.env`**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME=emailkamu@gmail.com
MAIL_PASSWORD=app_password_gmail
MAIL_FROM_ADDRESS=emailkamu@gmail.com

SECRET_PANEL_PASSWORD=password_rahasia_kuat
```

**6. Migrasi database**
```bash
php artisan migrate
```

**7. Jalankan frontend & server**
```bash
npm run dev
php artisan serve
```

Buka: `http://127.0.0.1:8000`

---

## 🚀 Deploy Production (Ubuntu + Nginx + PHP-FPM)

### 1. Update server & install package dasar
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y software-properties-common ca-certificates lsb-release apt-transport-https curl unzip git nginx mysql-server supervisor
```

### 2. Install PHP 8.2 + ekstensi Laravel
```bash
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.2-fpm php8.2-cli php8.2-common php8.2-mysql php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip php8.2-bcmath php8.2-gd php8.2-intl
php -v
```

### 3. Install Composer
```bash
cd /tmp
curl -sS https://getcomposer.org/installer -o composer-setup.php
php composer-setup.php
sudo mv composer.phar /usr/local/bin/composer
```

### 4. Setup database MySQL
```bash
sudo mysql
```
```sql
CREATE DATABASE webcomic_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'webcomic_user'@'localhost' IDENTIFIED BY 'PasswordKuatBanget!';
GRANT ALL PRIVILEGES ON webcomic_db.* TO 'webcomic_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 5. Clone project ke server
```bash
sudo mkdir -p /var/www/webcomic
sudo chown -R $USER:$USER /var/www/webcomic
cd /var/www/webcomic
git clone https://github.com/IkanParii/WebComic-KBD-Laravel.git .
```

### 6. Install dependency & setup environment
```bash
composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate
```

Edit `.env` untuk production:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domainkamu.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=webcomic_db
DB_USERNAME=webcomic_user
DB_PASSWORD=PasswordKuatBanget!

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME=emailkamu@gmail.com
MAIL_PASSWORD=app_password_gmail
MAIL_FROM_ADDRESS=emailkamu@gmail.com

SESSION_SECURE_COOKIE=true
SECRET_PANEL_PASSWORD=password_rahasia_kuat_baru
```

### 7. Migrasi & optimize
```bash
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### 8. Permission folder
```bash
sudo chown -R www-data:www-data /var/www/webcomic
sudo find /var/www/webcomic -type f -exec chmod 644 {} \;
sudo find /var/www/webcomic -type d -exec chmod 755 {} \;
sudo chmod -R 775 /var/www/webcomic/storage /var/www/webcomic/bootstrap/cache
```

### 9. Konfigurasi Nginx
```bash
sudo nano /etc/nginx/sites-available/webcomic
```

Isi:
```nginx
server {
    listen 80;
    server_name domainkamu.com www.domainkamu.com;
    root /var/www/webcomic/public;

    index index.php index.html;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

```bash
sudo ln -s /etc/nginx/sites-available/webcomic /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm
```

### 10. SSL (Let's Encrypt)
```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d domainkamu.com -d www.domainkamu.com
```

### 11. Final check
```bash
php artisan about
sudo systemctl status nginx
sudo systemctl status php8.2-fpm
```

---

## 🤝 Cara Collaboration

**1. Ambil update terbaru**
```bash
git pull origin main
```

**2. Buat branch baru**
```bash
git checkout -b nama-branch
```

**3. Commit & push**
```bash
git add .
git commit -m "feat: deskripsi fitur"
git push origin nama-branch
```

**4. Buat Pull Request** di GitHub → Compare & Pull Request → tunggu review sebelum merge.

### Semantic Commit

| Prefix | Kegunaan |
|--------|----------|
| `feat:` | Fitur baru |
| `fix:` | Perbaikan bug |
| `update:` | Perubahan kecil |
| `refactor:` | Perapihan kode |
| `docs:` | Dokumentasi |

### Aturan Penting

- ❌ Jangan push langsung ke `main`
- ❌ Jangan commit `.env`, `vendor/`, `node_modules/`
- ✔️ 1 fitur = 1 branch
- ✔️ Selalu pull sebelum mulai

---

## 🧪 Testing

```bash
php artisan test
```

---

## 🧠 Troubleshooting

```bash
# Error config / cache
php artisan config:clear
php artisan cache:clear

# Error frontend
npm install
npm run dev

# Error database
# → Cek .env, pastikan MySQL aktif dan database sudah dibuat
```

---

## 📖 Documentation

### Arsitektur Sistem

**Alur Request:**
```
Browser → Web Server → Laravel Router → Middleware Stack → Controller → Model → Database
```

---

### Role & Business Logic

#### Alur Akses per Role

```
Semua Role:
  Login → Verifikasi Email → Akses sesuai role

Publisher (tambahan):
  Login → Verifikasi Email → Verifikasi OTP → Akses dashboard publisher

Secret Panel (admin tertentu):
  Admin login → Admin middleware → OTP email + Panel Password + CAPTCHA → Backup/Restore
```

#### User
- Membaca komik
- Toggle favorit (maks 30x/menit)
- Tidak bisa membuat, mengedit, atau menghapus konten apapun

#### Publisher
- CRUD cerita **milik sendiri saja** — query selalu difilter `where('user_id', Auth::id())`
- Wajib verifikasi OTP email setiap sesi baru
- Upload cerita dibatasi 5x/menit (anti spam bot)

#### Admin
- CRUD semua user dan cerita
- Tidak bisa hapus/edit sesama admin (anti privilege abuse)
- Tidak bisa hapus diri sendiri (anti accidental lockout)
- Akses activity log seluruh sistem

#### Secret Backup Panel (`/pahrigantenguye`)
- Hanya admin yang tahu path ini
- 4 lapis verifikasi: admin role + OTP email + panel password + CAPTCHA manual
- Sesi aktif 15 menit setelah verifikasi
- Semua aksi backup/restore tercatat di activity log

---

### Keamanan

#### SQL Injection
Laravel Eloquent menggunakan **PDO parameterized query** secara default. Input user tidak pernah di-concatenate langsung ke SQL string.
```php
$query->where('judul', 'like', '%' . $request->search . '%');
// Dieksekusi sebagai: WHERE judul LIKE ? -- value terpisah dari query
```

#### XSS (Cross-Site Scripting)
Perlindungan 2 lapis:
1. **Sanitasi input** — `strip_tags()` di controller sebelum disimpan ke database
2. **Escaping output** — Blade `{{ }}` auto-escape, `{!! e() !!}` untuk field yang butuh render newline

#### CSRF (Cross-Site Request Forgery)
Setiap form menggunakan `@csrf`. Laravel memvalidasi token unik per session di setiap request POST/PUT/DELETE. Request dari domain lain tidak memiliki token valid → ditolak otomatis.

#### IDOR (Insecure Direct Object Reference)
Publisher tidak bisa mengakses data milik publisher lain meskipun mengetahui ID-nya:
```php
$cerita = Cerita::where('user_id', Auth::id())->findOrFail($id);
// ID valid tapi bukan miliknya → 404 (bukan 403, untuk obscurity)
```

#### Mass Assignment
Model `User` menggunakan `$fillable`. Field `role` tidak ada di validation rules form profile, sehingga user tidak bisa upgrade role sendiri lewat form.

#### Brute Force
Rate limiting via Laravel throttle middleware:

| Endpoint | Limit |
|----------|-------|
| Forgot password | 3x/menit |
| OTP verify publisher | 5x/menit |
| OTP resend | 3x/menit |
| Secret panel verify | 5x/menit |
| Backup / Restore | 3x/menit |

Khusus OTP publisher: **5 kali gagal → logout paksa + OTP di-invalidate di database**.

#### Command Injection
`mysqldump` dan `mysql` dijalankan menggunakan array command, bukan string:
```php
$command = [$binary, '--host=' . $host, '--user=' . $username, $database];
Process::run($command); // setiap argumen terisolasi, tidak bisa di-inject
```

#### Security Headers
Setiap response menyertakan:
- `X-Frame-Options: DENY`
- `X-Content-Type-Options: nosniff`
- `X-XSS-Protection: 1; mode=block`
- `Referrer-Policy: strict-origin-when-cross-origin`
- `Permissions-Policy: camera=(), microphone=(), geolocation=()`
- `Strict-Transport-Security` — aktif di production

---

### Password Policy

Sesuai standar NIST SP 800-63B:
- Minimal **12 karakter**
- Wajib ada **huruf besar dan kecil**
- Wajib ada **simbol** (@, #, !, dll)
- Disimpan sebagai **bcrypt hash** dengan cost factor 12

---

### Email Verification

Setelah register, user menerima email berisi **signed URL** dengan signature kriptografis. Jika URL dimodifikasi, signature tidak valid dan verifikasi ditolak.

---

### Audit Trail

Setiap aksi penting dicatat ke tabel `activity_logs` beserta timestamp dan IP address:

| Event | Trigger |
|-------|---------|
| `login` | User berhasil login |
| `login_failed` | Percobaan login gagal (email/password salah) |
| `login_lockout` | Akun terkunci sementara setelah 3x gagal login |
| `register` | User mendaftar |
| `cerita_created` | Publisher tambah cerita |
| `cerita_updated` | Publisher edit cerita |
| `cerita_deleted` | Publisher hapus cerita |
| `admin_deleted_user` | Admin hapus user |
| `admin_deleted_cerita` | Admin hapus cerita |
| `secret_panel_backup` | Admin backup database |
| `secret_panel_restore` | Admin restore database |

---

### Testing

Project menggunakan **Pest PHP** dengan 30+ test case:
- RBAC & middleware access control
- IDOR protection
- XSS sanitization
- Mass assignment protection
- SQL injection safety
- OTP brute force lockout
- Business logic (unique title, genre validation, boundary testing)

```bash
php artisan test
```
