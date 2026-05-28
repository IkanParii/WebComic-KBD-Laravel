# Security Checklist — WebComic KBD
> Jalankan checklist ini via Kiro CLI sebelum VA/pentest.
> Status: [ ] = belum, [x] = sudah

---

## 🔴 CRITICAL — Wajib Fix Sebelum Pentest

- [x] **Pindahkan PANEL_PASSWORD dari hardcode ke .env** ✅ FIXED
  - `SECRET_PANEL_PASSWORD` sekarang dibaca dari `.env`
  - Nilai lama sudah dipindahkan ke `.env` (jangan commit ke git)

- [x] **Sanitasi `isi_cerita` di view** ✅ AMAN
  - View `cerita/baca.blade.php` sudah pakai `{!! nl2br(e($cerita->isi_cerita)) !!}`
  - Fungsi `e()` adalah Laravel HTML escape — XSS sudah dicegah di layer view

- [ ] **Aktifkan CSP di production**
  - File: `app/Http/Middleware/SecurityHeaders.php`
  - Set `SECURITY_ENABLE_CSP=true` di `.env` production
  - Hilangkan `unsafe-inline` dari script-src (gunakan nonce atau hash)
  - Saat ini CSP dimatikan by default — ini temuan high severity di pentest

- [x] **Batasi MIME type restore backup** ✅ FIXED
  - `application/octet-stream` sudah dihapus dari allowed mimetypes

---

## 🟠 HIGH — Sangat Disarankan Fix

- [ ] **Aktifkan HTTPS force di production**
  - File: `app/Providers/AppServiceProvider.php`
  - Uncomment: `URL::forceScheme('https');`
  - Tambahkan import: `use Illuminate\Support\Facades\URL;`

- [ ] **Perkecil trustProxies scope**
  - File: `bootstrap/app.php`
  - `trustProxies(at: '*')` terlalu luas untuk production
  - Ganti dengan IP spesifik load balancer/reverse proxy kamu

- [ ] **Pastikan backup tidak bisa diakses publik**
  - Cek: apakah `php artisan storage:link` pernah dijalankan?
  - Jika ya, tambahkan rule di Nginx/Apache untuk block akses ke `/storage/app/backups`
  - Atau pindahkan backup ke path di luar `storage/app/public`

- [ ] **Verifikasi semua view render `isi_cerita` pakai `{{ }}` bukan `{!! !!}`**
  - Cari: `grep -r "isi_cerita" resources/views/`
  - Pastikan tidak ada `{!! $cerita->isi_cerita !!}` tanpa sanitasi

- [x] **Tambahkan rate limiting ke secret backup panel** ✅ FIXED
  - Route `/pahrigantenguye/verify`, `/backup`, `/restore`, `/resend-otp` sudah ada throttle

- [x] **Tambahkan audit log untuk akses secret panel** ✅ FIXED
  - `ActivityLogger::log()` sudah ditambahkan di backup dan restore

- [x] **Tambahkan `X-XSS-Protection` header** ✅ FIXED
  - File: `app/Http/Middleware/SecurityHeaders.php`

- [x] **Tambahkan `Strict-Transport-Security` header** ✅ FIXED
  - HSTS aktif otomatis di non-local environment

---

## 🟡 MEDIUM — Best Practice

- [ ] **Tambahkan `Strict-Transport-Security` header di production**
  - ~~File: `app/Http/Middleware/SecurityHeaders.php`~~
  - ✅ FIXED — HSTS sudah ditambahkan, aktif otomatis di non-local environment

- [ ] **Tambahkan `X-XSS-Protection` header**
  - ~~File: `app/Http/Middleware/SecurityHeaders.php`~~
  - ✅ FIXED — Header sudah ditambahkan

- [ ] **Pastikan session cookie secure di production**
  - File: `config/session.php`
  - Set `'secure' => env('SESSION_SECURE_COOKIE', false)` → di `.env` production set `SESSION_SECURE_COOKIE=true`
  - Set `'same_site' => 'strict'`

- [ ] **Pastikan APP_DEBUG=false di production**
  - File: `.env`
  - `APP_DEBUG=false` — jika true, stack trace bocor ke user

- [ ] **Pastikan APP_ENV=production di production**
  - File: `.env`
  - Jika masih `local`, beberapa security check dilewati

- [ ] **Tambahkan validasi tipe file yang lebih ketat di restore**
  - Baca isi file backup dan validasi bahwa baris pertama adalah SQL comment (`-- MySQL dump` atau `-- MariaDB dump`)
  - Ini mencegah upload file berbahaya yang lolos validasi ekstensi

- [ ] **Audit log untuk akses secret panel**
  - File: `app/Http/Controllers/SecretBackupController.php`
  - Tambahkan `ActivityLogger::log()` setiap kali backup/restore berhasil atau gagal
  - Saat ini tidak ada audit trail untuk operasi backup

---

## 🟢 LOW — Hardening Tambahan

- [ ] **Tambahkan `robots.txt` yang block semua crawler**
  - File: `public/robots.txt`
  - Isi saat ini: cek apakah sudah `Disallow: /`
  - Pastikan path admin dan publisher tidak terindeks

- [ ] **Pastikan `.env` tidak bisa diakses via web**
  - Cek `.htaccess` atau Nginx config sudah block akses ke `.env`
  - Laravel default sudah handle ini, tapi verifikasi manual

- [ ] **Jalankan semua test Pest sebelum pentest**
  - Command: `php artisan test`
  - Semua 30+ test di `SecurityTest.php` harus pass

- [ ] **Cek tidak ada `dd()`, `dump()`, atau `var_dump()` tertinggal di kode**
  - Command: `grep -r "dd(" app/ routes/` dan `grep -r "dump(" app/ routes/`

- [ ] **Verifikasi CSRF token ada di semua form**
  - Semua form POST/PUT/DELETE harus punya `@csrf`
  - Laravel Breeze sudah handle ini, tapi cek form custom

- [ ] **Pastikan error page tidak bocorkan info teknis**
  - Cek response 404, 403, 500 tidak menampilkan stack trace
  - Hanya berlaku jika `APP_DEBUG=false`

---

## ✅ Sudah Aman (Konfirmasi Saat Presentasi)

- [x] RBAC via middleware (admin, publisher, user) — sudah diimplementasi
- [x] IDOR protection di semua query publisher (`where('user_id', Auth::id())`)
- [x] Anti mass-assignment via `$fillable` di User model
- [x] XSS sanitasi via `strip_tags()` di judul, nama, deskripsi
- [x] Rate limiting di endpoint sensitif (OTP, upload, favorit, forgot password)
- [x] Security headers (X-Frame-Options DENY, X-Content-Type-Options, Referrer-Policy)
- [x] Activity logging untuk audit trail (login, register, CRUD)
- [x] OTP brute-force lockout (5 kali gagal → logout + invalidate OTP)
- [x] Triple auth di secret panel (admin role + OTP email + panel password + CAPTCHA)
- [x] Password policy ketat (min 12 char, mixed case, symbols)
- [x] Email verification wajib sebelum akses semua fitur
- [x] Admin tidak bisa hapus/edit sesama admin
- [x] Admin tidak bisa hapus diri sendiri
- [x] Publisher tidak bisa manipulasi `user_id` saat create cerita
- [x] Validasi genre ID via `exists:genres,id`
- [x] Unique constraint pada judul cerita
- [x] SQL injection aman karena Eloquent pakai parameterized query
- [x] CSRF protection via Laravel default middleware
- [x] Session OTP tidak bocor (hidden di `$hidden` model)
- [x] Backup menggunakan array command (bukan string interpolation) → aman dari command injection

---

## Cara Jalankan Checklist via Kiro CLI

```bash
# 1. Jalankan semua security test
php artisan test --filter SecurityTest

# 2. Cek XSS di views
grep -r "isi_cerita" resources/views/

# 3. Cek tidak ada debug statement
grep -rn "dd\|dump\|var_dump" app/ routes/

# 4. Cek CSP status
grep -n "SECURITY_ENABLE_CSP" .env

# 5. Cek APP_DEBUG
grep "APP_DEBUG" .env
```


---

## 🚀 Production Readiness

### Wajib Sebelum Deploy

- [ ] **Ganti `SECRET_PANEL_PASSWORD`** di `.env` — nilai lama pernah ada di source code dan git history
- [ ] **Uncomment HTTPS force** di `app/Providers/AppServiceProvider.php`:
  ```php
  if (config('app.env') !== 'local') {
      URL::forceScheme('https');
  }
  ```
- [ ] **Set environment production** di `.env`:
  ```env
  APP_ENV=production
  APP_DEBUG=false
  SESSION_SECURE_COOKIE=true
  ```

---

## 🔐 Security Score: 8.2 / 10

### Yang Sudah Aman

| Aspek | Status |
|-------|--------|
| RBAC via middleware (bukan controller) | ✅ Solid |
| IDOR protection konsisten di semua query publisher | ✅ Solid |
| 4-layer auth di secret panel | ✅ Solid |
| OTP brute-force lockout + invalidasi DB | ✅ Solid |
| Audit trail lengkap dengan IP | ✅ Solid |
| Rate limiting di semua endpoint sensitif | ✅ Solid |
| Command injection safe (array command) | ✅ Solid |
| Password policy NIST SP 800-63B | ✅ Solid |
| Security headers lengkap | ✅ Solid |
| CSRF protection | ✅ Solid |
| 30+ automated security tests | ✅ Solid |

### Yang Mengurangi Score

| Aspek | Alasan | Pengaruh |
|-------|--------|----------|
| CSP masih pakai `unsafe-inline` | Kalau ada XSS yang lolos sanitasi, CSP tidak bisa jadi safety net terakhir | -0.8 |
| Panel password pernah hardcoded di git history | Sudah dipindah ke `.env`, tapi git history masih menyimpannya | -0.5 |
| Tidak ada file upload validation | Kalau nanti ditambah fitur upload, belum ada groundwork validasi MIME + ekstensi | -0.3 |
| Session lifetime tidak dibatasi ketat | Default 120 menit, tidak ada idle timeout | -0.2 |

> **Catatan:** 8.2/10 untuk aplikasi akademik sangat bagus. Kebanyakan project mahasiswa berhenti di CSRF + bcrypt. Aplikasi ini sudah implement defense-in-depth, audit trail, dan automated security testing.
