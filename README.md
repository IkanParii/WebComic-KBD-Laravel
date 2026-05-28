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

- PHP ≥ 8.5
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

### 2. Install PHP 8.5 + ekstensi Laravel
```bash
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.5-fpm php8.5-cli php8.5-common php8.5-mysql php8.5-mbstring php8.5-xml php8.5-curl php8.5-zip php8.5-bcmath php8.5-gd php8.5-intl
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
APP_URL=http://domainkamu.com

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
        fastcgi_pass unix:/run/php/php8.5-fpm.sock;
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
sudo systemctl restart php8.5-fpm
```

### 10. Final check
```bash
php artisan about
sudo systemctl status nginx
sudo systemctl status php8.5-fpm
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


