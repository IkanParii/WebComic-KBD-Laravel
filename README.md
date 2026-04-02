# WebKBD


## 📌 Deskripsi (Readme Tuh Dibaca Blok)

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

## 6. Install Authentication (Laravel Breeze)

```bash
composer require laravel/breeze --dev
php artisan breeze:install
```

Pilih:

```
blade
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

# Punya Pahri (Minimal Baca Kids)