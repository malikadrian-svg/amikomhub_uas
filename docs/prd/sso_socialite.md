# Product Requirement Document (PRD) - Fitur SSO (Single Sign-On)
## Fitur: Login Instan via Google (Laravel Socialite)

---

## 1. Deskripsi Fitur
Fitur ini bertujuan untuk memotong alur pemesanan tiket dengan meniadakan formulir pendaftaran manual bagi pelanggan baru. Cukup dengan menekan tombol **"Continue with Google"**, pengguna dapat langsung ter-autentikasi dan merampungkan transaksi tiket secara instan.

---

## 2. Alur Pengguna (User Flow)
1. User mengunjungi halaman utama, memilih event, dan masuk ke halaman checkout (`/checkout/{event}`).
2. Apabila user belum login, Modal/Halaman login akan menampilkan tombol **"Continue with Google"**.
3. User mengklik tombol tersebuat $\rightarrow$ Sistem menyimpan event sasaran (`event_id`) ke dalam Session Laravel $\rightarrow$ Mengalihkan user ke Google Consent Page.
4. User memilih akun Google $\rightarrow$ Google mengalihkan kembali ke Callback URL aplikasi dengan kode otorisasi.
5. Sistem memproses data Google (`email`, `name`, `id`):
   - **User Terdaftar (Ada):** Jika email cocok, login-kan user. Simpan `social_id` dan `social_provider` jika sebelumnya login manual.
   - **User Baru (Belum Ada):** Buat record user baru dengan role `'user'`, isi email & nama sesuai Google, password di-hash secara acak (`Str::random(16)`), serta simpan `social_id` dan `social_provider`.
6. Sistem memulihkan event sasaran (`event_id`) dari Session.
7. Redirect user kembali ke halaman checkout (`/checkout/{event}`) dalam status login untuk menyelesaikan transaksi.

---

## 3. Perubahan Skema Database
Sistem membutuhkan migrasi untuk memodifikasi tabel `users`.
* Mengubah kolom `password` menjadi **nullable**.
* Menambahkan kolom `social_id` (string, nullable) dan `social_provider` (string, nullable).

```php
Schema::table('users', function (Blueprint $table) {
    $table->string('password')->nullable()->change();
    $table->string('social_id')->nullable()->after('email');
    $table->string('social_provider')->nullable()->after('social_id');
});
```

---

## 4. Panduan Langkah-Langkah Integrasi (Step-by-Step Implementation)

### Langkah 1: Instalasi Package Laravel Socialite
Jalankan composer require untuk mengunduh package resmi Socialite:
```bash
composer require laravel/socialite
```

### Langkah 2: Konfigurasi Kredensial Google di `.env`
Buka konsol pengembang Google Cloud Console, dapatkan Client ID serta Secret Key, lalu simpan pada file `.env`:
```env
GOOGLE_CLIENT_ID="XXXXXX-your-client-id-XXXXXX.apps.googleusercontent.com"
GOOGLE_CLIENT_SECRET="GOCSPX-your-client-secret-XXXXXX"
GOOGLE_REDIRECT_URI="http://localhost:8000/auth/callback/google"
```

### Langkah 3: Daftarkan Layanan di `config/services.php`
Hubungkan variabel lingkungan `.env` dengan konfigurasi internal Laravel:
```php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI'),
],
```

### Langkah 4: Buat Migration File Baru
Jalankan perintah pembuatan migrasi:
```bash
php artisan make:migration modify_users_table_for_socialite
```
Tulis method `up()` dan `down()` untuk menambahkan field yang diperlukan dan ubah `password` agar nullable (pastikan package `doctrine/dbal` terpasang jika memakai Laravel versi lama untuk command `change()`, di Laravel 11/10 sudah didukung secara bawaan).

### Langkah 5: Buat Controller Autentikasi `SocialAuthController`
Jalankan perintah:
```bash
php artisan make:controller SocialAuthController
```
Implementasikan logika utama otentikasi di dalam Controller tersebut:
```php
namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class SocialAuthController extends Controller
{
    // Mengalihkan pengguna ke Google
    public function redirectToProvider($provider)
    {
        if ($provider !== 'google') {
            return redirect()->route('home')->with('error', 'Provider tidak didukung.');
        }

        // Simpan referer URL (misal halaman checkout asal) untuk dikembalikan pasca redirect
        if (request()->has('redirect_to')) {
            session(['social_redirect_url' => request()->get('redirect_to')]);
        }

        return Socialite::driver($provider)->redirect();
    }

    // Menangani callback dari Google
    public function handleProviderCallback($provider, Request $request)
    {
        if ($provider !== 'google') {
            return redirect()->route('home')->with('error', 'Provider tidak didukung.');
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Gagal login via Google.');
        }

        // Cari user berdasarkan email
        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            // Update social_id jika belum terhubung
            if (empty($user->social_id)) {
                $user->update([
                    'social_id' => $socialUser->getId(),
                    'social_provider' => $provider,
                ]);
            }
        } else {
            // Buat user baru jika belum terdaftar
            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'password' => bcrypt(Str::random(16)), // Password acak aman
                'role' => 'user',
                'social_id' => $socialUser->getId(),
                'social_provider' => $provider,
            ]);
        }

        Auth::login($user);

        // Arahkan kembali ke halaman awal (misal checkout) atau fallback ke dashboard/home
        $redirectTo = session()->pull('social_redirect_url', route('home'));
        return redirect()->to($redirectTo);
    }
}
```

### Langkah 6: Definisikan Rute Autentikasi Sosial
Buka file `routes/web.php` dan tambahkan rute-rute berikut:
```php
use App\Http\Controllers\SocialAuthController;

Route::get('/auth/redirect/{provider}', [SocialAuthController::class, 'redirectToProvider'])->name('social.redirect');
Route::get('/auth/callback/{provider}', [SocialAuthController::class, 'handleProviderCallback'])->name('social.callback');
```

### Langkah 7: Modifikasi Tampilan Halaman Login & Checkout
* Tambahkan tombol **"Continue with Google"** ke form login dan modal checkout.
* Contoh markup menggunakan Blade & CSS standar:
```html
<a href="{{ route('social.redirect', ['provider' => 'google', 'redirect_to' => url()->current()]) }}" 
   class="btn btn-secondary w-full" 
   style="display: flex; align-items: center; justify-content: center; gap: 8px;">
    <!-- SVG Google Icon -->
    <svg width="18" height="18" viewBox="0 0 18 18">
        <path d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.717v2.258h2.909c1.702-1.567 2.683-3.874 2.683-6.616z" fill="#4285F4"/>
        <path d="M9 18c2.43 0 4.467-.806 5.956-2.18l-2.909-2.259c-.806.54-1.838.86-3.047.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332C2.438 15.932 5.485 18 9 18z" fill="#34A853"/>
        <path d="M3.964 10.71c-.18-.54-.282-1.117-.282-1.71s.102-1.17.282-1.71V4.957H.957A8.996 8.996 0 0 0 0 9c0 1.452.348 2.827.957 4.042l3.007-2.332z" fill="#FBBC05"/>
        <path d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.896 11.426 0 9 0 5.485 0 2.438 2.068.957 5.043l3.007 2.332C4.672 5.164 6.656 3.58 9 3.58z" fill="#EA4335"/>
    </svg>
    <span>Continue with Google</span>
</a>
```

---

## 5. Kriteria Keberhasilan & Pengujian

### 5.1 Pengujian Integrasi
1. Tekan tombol **"Continue with Google"**. Pastikan rute mengarah ke URI auth authorization Google.
2. Selesaikan otorisasi di Google page. Callback harus sukses menangani kembalian data.
3. Cek tabel `users` di database: verify bahwa akun baru memiliki kolom `social_provider = 'google'` dan `social_id` terisi.
4. Cek apakah session `social_redirect_url` dipulihkan dan pengalihan ke checkout event awal berjalan mulus.
