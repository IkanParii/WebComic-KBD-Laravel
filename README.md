# WebKBD


## 📌 Deskripsi (PUNYA README TUH DI BACA BLOK)

**WebComic KBD** adalah aplikasi web berbasis **Laravel + Tailwind CSS** untuk membaca dan mengelola komik dengan sistem role:

* User → membaca komik
* Admin → mengelola user, publisher, dan komik
* Publisher → menambah, mengedit, dan menghapus komik

---

# ⚙️ Requirements

Pastikan sudah install:

* PHP ≥ 8.2
* Composer
* Node.js ≥ 18
* MySQL
* Git

---

# 🚀 Cara Install Project (WAJIB IKUTIN URUTAN)

## 1. Clone Repository

```bash
git clone https://github.com/IkanParii/WebComic-KBD-Laravel.git
cd WebComic-KBD-Laravel
```

---

## 2. Install Dependency Backend

```bash
composer install
```

---

## 3. Setup Environment

```bash
cp .env.example .env
php artisan key:generate
```

---

## 4. Setup Database (MySQL)

Masuk ke MySQL:

```sql
CREATE DATABASE laravel_db;
```

Edit file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=root
DB_PASSWORD=
```

---

## 5. Migrasi Database

```bash
php artisan migrate
```

---

## 7. Install Frontend

```bash
npm install
npm run dev
```

---

## 8. Jalankan Project

```bash
php artisan serve
```

Buka di browser:

```
http://127.0.0.1:8000
```

---

# 🧪 Testing (Opsional)

Jika diminta framework testing:

```
Pilih: Pest
```

---

# 🚀 Cara Collaboration (WAJIB DIIKUTI)

## 1. Ambil Update Terbaru

```bash
git pull origin main
```

---

## 2. Buat Branch Baru

```bash
git checkout -b nama-branch
```

Contoh:

* fitur-login
* fitur-komik
* nama-kalian

---

## 3. Tambahkan Perubahan

```bash
git add .
```

---

## 4. Commit Perubahan

```bash
git commit -m "feat: tambah fitur login"
```

📌 Gunakan **semantic commit**:

* feat: fitur baru
* fix: perbaikan bug
* update: perubahan kecil
* refactor: perapihan kode
* docs: dokumentasi

Referensi:
https://www.codepolitan.com/blog/panduan-mendalam-tentang-github-semantic-commit/

---

## 5. Push ke GitHub

```bash
git push origin nama-branch
```

---

## 6. Buat Pull Request (PR)

* Buka GitHub
* Pilih branch
* Klik **Compare & Pull Request**
* Tunggu review sebelum merge

---

# ⚠️ Aturan Penting

* ❌ Jangan push langsung ke `main`
* ❌ Jangan upload `.env`
* ❌ Jangan upload `vendor/` dan `node_modules/`
* ✔️ 1 fitur = 1 branch
* ✔️ Selalu pull sebelum mulai

---

# 🧩 Pengerjaan Laporan

## 1. Role System

* **User**

  * Membaca komik

* **Admin**

  * CRUD user
  * CRUD publisher
  * CRUD komik

* **Publisher**

  * Tambah komik
  * Edit komik
  * Hapus komik

---

## 2. Tech Stack

* Laravel
* Tailwind CSS
* MySQL

---

## 3. Desain Sistem

* Landing Page
* Dashboard Admin
* Halaman Publisher:

  * Upload komik
  * Edit komik
  * Tampilan scroll/slider

---

## 4. Database

Tabel utama:

* roles
* users
* publishers
* comics

---

## 5. Diagram

* ERD (Entity Relationship Diagram)

---

# 🧠 Troubleshooting

Jika terjadi error:

```bash
php artisan config:clear
php artisan cache:clear
```

Jika frontend error:

```bash
npm install
npm run dev
```

Jika database error:

* Cek `.env`
* Pastikan MySQL aktif
* Pastikan database sudah dibuat

---

# Punya Pahri (Minimal Baca Bang Capek Bikin Readme)

---

# Deploy Production Ubuntu (Nginx + PHP-FPM 8.5)

Panduan ini dari awal (server baru) sampai aplikasi siap online.

## 1. Update server dan install package dasar

```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y software-properties-common ca-certificates lsb-release apt-transport-https curl unzip git nginx mysql-server redis-server supervisor
```

## 2. Install PHP 8.5 + ekstensi Laravel

```bash
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.5-fpm php8.5-cli php8.5-common php8.5-mysql php8.5-mbstring php8.5-xml php8.5-curl php8.5-zip php8.5-bcmath php8.5-gd php8.5-intl php8.5-redis
php -v
```

## 3. Install Composer

```bash
cd /tmp
curl -sS https://getcomposer.org/installer -o composer-setup.php
php composer-setup.php
sudo mv composer.phar /usr/local/bin/composer
composer --version
```

## 4. Setup database MySQL

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

## 5. Upload project ke server

```bash
sudo mkdir -p /var/www/webcomic
sudo chown -R $USER:$USER /var/www/webcomic
cd /var/www/webcomic
git clone https://github.com/IkanParii/WebComic-KBD-Laravel.git .
```

## 6. Install dependency dan setup environment

```bash
composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate
```

Edit file `.env`:

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
```

## 7. Migrasi dan optimize

```bash
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

## 8. Permission folder

```bash
sudo chown -R www-data:www-data /var/www/webcomic
sudo find /var/www/webcomic -type f -exec chmod 644 {} \;
sudo find /var/www/webcomic -type d -exec chmod 755 {} \;
sudo chmod -R 775 /var/www/webcomic/storage /var/www/webcomic/bootstrap/cache
```

## 9. Konfigurasi Nginx

Buat file:

```bash
sudo nano /etc/nginx/sites-available/webcomic
```

Isi konfigurasi:

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
        fastcgi_pass unix:/run/php/php8.5-fpm.sock;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Aktifkan site:

```bash
sudo ln -s /etc/nginx/sites-available/webcomic /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
sudo systemctl restart php8.5-fpm
```

## 10. SSL (Let's Encrypt)

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d domainkamu.com -d www.domainkamu.com
```

## 11. Queue worker (opsional kalau pakai queue)

```bash
sudo nano /etc/supervisor/conf.d/webcomic-worker.conf
```

Isi:

```ini
[program:webcomic-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/webcomic/artisan queue:work --sleep=3 --tries=3 --timeout=90
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/webcomic/storage/logs/worker.log
```

Aktifkan:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start webcomic-worker:*
```

## 12. Scheduler Laravel

```bash
crontab -e
```

Tambahkan:

```cron
* * * * * cd /var/www/webcomic && php artisan schedule:run >> /dev/null 2>&1
```

## 13. Final check sebelum go live

```bash
php artisan about
php artisan test
sudo systemctl status nginx
sudo systemctl status php8.5-fpm
sudo supervisorctl status
```
