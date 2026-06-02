<div align="center">
  <img src="https://capsule-render.vercel.app/api?type=waving&color=0:00aaff,100:00ffaa&height=200&section=header&text=Laravel%20Breeze%20Auth&fontSize=50&animation=fadeIn&fontAlignY=38&desc=Tugas%20Mandiri%207.2%20-%20Pemrograman%20Web&descAlignY=61&descAlign=62" alt="Header" />

  ![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
  ![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
  ![SQLite](https://img.shields.io/badge/SQLite-07405E?style=for-the-badge&logo=sqlite&logoColor=white)
  ![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)

  <img src="https://readme-typing-svg.demolab.com?font=Fira+Code&size=22&pause=1000&color=00D4AA&center=true&vCenter=true&width=600&lines=Sistem+Autentikasi+Modern;Role-Based+Access+Control;SQLite+%2B+Breeze+Blade" alt="Typing SVG" />
</div>

---

## 📌 Deskripsi Proyek

Proyek ini adalah implementasi sistem autentikasi modern menggunakan **Laravel Breeze** sebagai starter kit resmi untuk memenuhi **Tugas Mandiri 7.2 Mata Kuliah Pemrograman Web**.

Aplikasi ini dikembangkan dari dasar dengan menambahkan kustomisasi pada skema basis data, form registrasi, manajemen profil, serta pembatasan hak akses berbasis peran (role-based middleware).

<div align="center">

| Identitas | Detail |
| :--- | :--- |
| **Nama** | Jovantri Immanuel Gulo |
| **NIM** | 2411532014 |
| **Mata Kuliah** | Pemrograman Web |
| **Tugas** | Mandiri 7.2 |

</div>

---

## ✨ Fitur Utama

- 🔐 Autentikasi lengkap (Register, Login, Logout) via Laravel Breeze
- 📱 Field tambahan **No. HP** dengan validasi ketat
- 👤 Halaman Profil yang dapat diedit
- 👑 Role-based Middleware (`user` & `admin`)
- 📊 Dashboard Admin untuk melihat semua user
- 💾 Database **SQLite** (tanpa XAMPP/MySQL)

---

## 🛠 Langkah-Langkah Instalasi & Setup Lokal

> Proyek ini sepenuhnya dikonfigurasi menggunakan **SQLite** lokal. Anda **tidak memerlukan XAMPP atau MySQL**.

### 1. Pembuatan Proyek Baru & Instalasi Breeze

Buka terminal di folder direktori kerja Anda, kemudian jalankan:

```bash
composer create-project laravel/laravel auth-demo
cd auth-demo
composer require laravel/breeze --dev
php artisan breeze:install blade
```

---

## 💻 Dokumentasi Implementasi Kode

Berikut adalah seluruh perubahan kode yang diterapkan untuk memenuhi kriteria Tugas Mandiri 7.2:

### 📝 TUGAS 1: Menambah Field No. HP (30 Poin)

**1. Modifikasi File Migration Database**
`database/migrations/xxxx_xx_xx_xxxxxx_create_users_table.php`
```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->string('no_hp', 15); // Tambahan kolom no_hp varchar 15
    $table->rememberToken();
    $table->timestamps();
});
```

**2. Update Properti Fillable pada Model User**
`app/Models/User.php`
```php
protected $fillable = [
    'name',
    'email',
    'password',
    'no_hp', // Izinkan mass assignment untuk no_hp
];
```

**3. Kustomisasi Validasi Registrasi**
`app/Http/Controllers/Auth/RegisteredUserController.php`
```php
public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'no_hp' => ['required', 'string', 'min:10', 'max:15', 'regex:/^[0-9]+$/'], // Wajib, angka, min 10 karakter
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'no_hp' => $request->no_hp,
    ]);

    event(new Registered($user));
    Auth::login($user);

    return redirect(route('dashboard', absolute: false));
}
```

**4. Penambahan Input Form Registrasi**
`resources/views/auth/register.blade.php`
```blade
<div class="mt-4">
    <x-input-label for="no_hp" :value="__('No. HP')" />
    <x-text-input id="no_hp" class="block mt-1 w-full" type="text" name="no_hp" :value="old('no_hp')" required autocomplete="no_hp" />
    <x-input-error :messages="$errors->get('no_hp')" class="mt-2" />
</div>
```

**5. Menampilkan Data di Dashboard**
`resources/views/dashboard.blade.php`
```blade
<div class="p-6 text-gray-900">
    {{ __("You're logged in!") }}
    <p class="mt-4"><strong>No HP Anda:</strong> {{ Auth::user()->no_hp }}</p>
</div>
```

### 👤 TUGAS 2: Halaman Profil (30 Poin)

**1. Update Validasi Update Profil**
`app/Http/Requests/ProfileUpdateRequest.php`
```php
public function rules(): array
{
    return [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
        'no_hp' => ['required', 'string', 'min:10', 'max:15', 'regex:/^[0-9]+$/'], // Validasi edit no_hp
    ];
}
```

**2. Penambahan Input Form Edit Profil**
`resources/views/profile/partials/update-profile-information-form.blade.php`
```blade
<div>
    <x-input-label for="no_hp" :value="__('No. HP')" />
    <x-text-input id="no_hp" name="no_hp" type="text" class="mt-1 block w-full" :value="old('no_hp', $user->no_hp)" required autocomplete="no_hp" />
    <x-input-error class="mt-2" :messages="$errors->get('no_hp')" />
</div>
```

### 👑 TUGAS 3: Halaman Admin Bonus (40 Poin)

**1. Modifikasi Migration untuk Kolom Role**
`database/migrations/xxxx_xx_xx_xxxxxx_create_users_table.php`
```php
// Tambahkan baris enum ini di dalam skema tabel users
$table->enum('role', ['user', 'admin'])->default('user');
```
> Ingat untuk menambahkan `'role'` ke properti `$fillable` di `app/Models/User.php`

**2. Membuat dan Mengonfigurasi Middleware**

Jalankan perintah:
```bash
php artisan make:middleware AdminMiddleware
```

`app/Http/Middleware/AdminMiddleware.php`
```php
public function handle(Request $request, Closure $next): Response
{
    if (Auth::check() && Auth::user()->role === 'admin') {
        return $next($request);
    }
    
    abort(403, 'Anda tidak memiliki akses ke halaman ini.');
}
```

Daftarkan alias di `bootstrap/app.php`:
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
    ]);
})
```

**3. Definisi Route Khusus Admin**
`routes/web.php`
```php
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', function () {
        $users = \App\Models\User::all(); // Mengambil seluruh data user
        return view('admin.index', compact('users'));
    })->name('admin.index');
});
```

**4. Membuat Tampilan Halaman Admin**
`resources/views/admin/index.blade.php`
```blade
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Halaman Admin - Daftar User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="border-b py-2">Nama</th>
                                <th class="border-b py-2">Email</th>
                                <th class="border-b py-2">No HP</th>
                                <th class="border-b py-2">Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td class="border-b py-2">{{ $user->name }}</td>
                                <td class="border-b py-2">{{ $user->email }}</td>
                                <td class="border-b py-2">{{ $user->no_hp }}</td>
                                <td class="border-b py-2">{{ ucfirst($user->role) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
```

---

## ⚡ Langkah Final Menjalankan Aplikasi

Eksekusi perintah di bawah ini secara berurutan:

**1. Setup File Environment Lokal**
Salin `.env.example` menjadi `.env` dan pastikan:
```env
DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=
```

**2. Generate Application Key**
```bash
php artisan key:generate
```

**3. Eksekusi Migrasi Segar**
```bash
php artisan migrate:fresh
```
> Ketik `yes` apabila muncul konfirmasi pembuatan berkas database baru.

**4. Kompilasi Aset Frontend (Vite)**
```bash
npm install
npm run build
```

**5. Jalankan Server Lokal**
```bash
php artisan serve
```
Akses aplikasi melalui: **http://localhost:8000**

---

## 📋 Panduan Pengujian & Pengisian Nilai Admin via Tinker

1. Buka http://localhost:8000/register dan daftarkan akun baru pertama (default role: `user`).

2. Jalankan Tinker:
```bash
php artisan tinker
```

3. Ketikkan di dalam shell tinker:
```php
$user = \App\Models\User::first();
$user->role = 'admin';
$user->save();
```

4. Ketik `exit` untuk menutup Tinker.

5. Kembali ke browser, pastikan login, lalu akses http://localhost:8000/admin untuk melihat tabel rekapitulasi seluruh pengguna.

---

<div align="center">
  <img src="https://capsule-render.vercel.app/api?type=waving&color=0:00ffaa,100:00aaff&height=120&section=footer" />
  
  <img src="https://img.shields.io/badge/Built%20with-Laravel%20Breeze-FF2D20?style=flat-square&logo=laravel" />
  <img src="https://img.shields.io/badge/Database-SQLite-003B57?style=flat-square&logo=sqlite" />
  
  <sub>Made with ❤️ • Tugas Mandiri 7.2 • 2026</sub>
</div>
