<div align="center">
  <img src="https://capsule-render.vercel.app/api?type=waving&color=0:00aaff,100:00ffaa&height=180&section=header&text=Laravel%20Breeze%20Auth&fontSize=42&fontAlignY=35&desc=Tugas%20Mandiri%20Pemrograman%20Web&descAlignY=55" alt="Header" />

  ![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
  ![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
  ![SQLite](https://img.shields.io/badge/SQLite-07405E?style=for-the-badge&logo=sqlite&logoColor=white)
  ![Tailwind](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
</div>

## Deskripsi Proyek

Ini adalah proyek autentikasi Laravel menggunakan Laravel Breeze (Blade). Dibuat untuk Tugas Mandiri mata kuliah Pemrograman Web. Sudah dimodifikasi dengan field tambahan no_hp dan role-based access untuk halaman admin.

Proyek ini sengaja menggunakan SQLite supaya ringan, tidak perlu menjalankan XAMPP atau MySQL. Cukup clone, setup .env, migrate, jalan.

**Identitas:**
- Nama: Jovantri Immanuel Gulo
- NIM: 2411532014

---

## Yang Perlu Disiapkan

Sebelum mulai, pastikan di laptop sudah ada:
- PHP 8.2 atau lebih baru (dengan extension sqlite, pdo_sqlite, mbstring, openssl)
- Composer 2.x
- Node.js 18+ dan NPM
- Git (opsional, kalau mau clone)

Cek cepat di terminal:
```bash
php -v
composer -v
node -v
npm -v
```

---

## Cara Menjalankan Proyek Ini

Saya asumsikan kamu sudah membuat folder project laravelnya dengan nama `auth-demo`.

### 1. Masuk ke folder project
```bash
cd auth-demo
```

### 2. Install dependency PHP
```bash
composer install
```
Ini akan download semua package Laravel dan Breeze yang dibutuhkan. Tunggu sampai selesai, biasanya 1-3 menit tergantung internet.

### 3. Siapkan file environment
Copy file contoh:
```bash
cp .env.example .env
```
Buka file `.env` pakai text editor, lalu pastikan bagian database seperti ini:
```env
APP_NAME="Laravel Breeze Auth"
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=
# DB_USERNAME=
# DB_PASSWORD=
```
Penting: biarkan yang MySQL di-comment dengan tanda pagar. Laravel akan otomatis pakai file `database/database.sqlite`.

### 4. Buat file database SQLite
Laravel tidak otomatis bikin filenya, jadi buat manual:
```bash
# untuk Windows (PowerShell)
type nul > database\database.sqlite

# untuk Mac/Linux
touch database/database.sqlite
```
Kalau file ini tidak ada, nanti migrate akan error.

### 5. Generate application key
```bash
php artisan key:generate
```
Perintah ini mengisi `APP_KEY` di .env, wajib untuk enkripsi session dan password.

### 6. Jalankan migrasi database
Karena kita pakai fresh install, jalankan:
```bash
php artisan migrate:fresh
```
Perintah ini akan:
- Membuat tabel users (sudah termasuk kolom no_hp dan role)
- Membuat tabel sessions, password_reset_tokens, dll bawaan Laravel

Kalau muncul pertanyaan "Do you want to create the database?", ketik `yes`.

Cek hasilnya:
```bash
php artisan migrate:status
```
Pastikan semua statusnya "Ran".

### 7. Install dan build frontend
Breeze pakai Vite + Tailwind, jadi perlu build asset:
```bash
npm install
npm run build
```
Pakai `npm run dev` kalau mau hot-reload saat development, tapi untuk tugas cukup `build`.

### 8. Jalankan server
```bash
php artisan serve
```
Secara default jalan di http://127.0.0.1:8000

Buka browser, daftar akun baru di `/register`. Isi nama, email, password, dan no_hp (minimal 10 digit angka).

---

## Membuat Akun Admin

Secara default user baru rolenya `user`. Untuk mengakses halaman `/admin`, ubah manual lewat Tinker:

1. Buka terminal baru (biarkan `php artisan serve` tetap jalan)
2. Jalankan:
```bash
php artisan tinker
```
3. Di dalam tinker, ketik:
```php
$user = \App\Models\User::where('email', 'emailkamu@contoh.com')->first();
$user->role = 'admin';
$user->save();
exit;
```
4. Refresh browser, login ulang, lalu buka http://localhost:8000/admin

Kamu akan lihat tabel daftar semua user beserta no_hp dan rolenya.

---

## Perintah Penting Lain

Kalau suatu saat database berantakan dan mau reset total:
```bash
php artisan migrate:fresh --seed
```

Kalau ganti .env dan config tidak terbaca:
```bash
php artisan config:clear
php artisan cache:clear
```

Cek koneksi SQLite:
```bash
php artisan tinker
>>> DB::connection()->getPdo();
```
Kalau tidak error, berarti koneksi berhasil.

---

## Struktur Database yang Dipakai

Tabel `users` di proyek ini:
- id (bigint)
- name (varchar)
- email (varchar, unique)
- email_verified_at (timestamp, nullable)
- password (varchar)
- no_hp (varchar 15)
- role (enum: user, admin, default user)
- remember_token
- timestamps

Semua sudah dibuat lewat migration, jadi kamu tidak perlu buat manual di phpMyAdmin.

---

## Troubleshooting

**Error "could not find driver"**  
Artinya extension pdo_sqlite belum aktif. Buka php.ini, hapus titik koma di depan `extension=pdo_sqlite` dan `extension=sqlite3`, lalu restart terminal.

**Error Vite manifest not found**  
Kamu lupa `npm run build`. Jalankan lagi.

**Halaman admin 403**  
Pastikan role user sudah diubah jadi `admin` lewat tinker, bukan `user`.

**Port 8000 sudah dipakai**  
Jalankan di port lain:
```bash
php artisan serve --port=8080
```

---

<div align="center">
  <img src="https://capsule-render.vercel.app/api?type=waving&color=0:00ffaa,100:00aaff&height=100&section=footer" />
  <p>Proyek ini dibuat untuk pembelajaran, bukan untuk production.</p>
</div>
