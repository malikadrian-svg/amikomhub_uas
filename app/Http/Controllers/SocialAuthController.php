<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    /**
     * Mengalihkan pengguna ke halaman consent Google.
     */
    public function redirectToProvider(string $provider)
    {
        if ($provider !== 'google') {
            return redirect()->route('home')->with('error', 'Provider tidak didukung.');
        }

        // Simpan URL asal (misal halaman checkout) untuk dikembalikan pasca redirect
        if (request()->has('redirect_to')) {
            session(['social_redirect_url' => request()->get('redirect_to')]);
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Menangani callback dari Google setelah user memberi izin.
     */
    public function handleProviderCallback(string $provider)
    {
        if ($provider !== 'google') {
            return redirect()->route('home')->with('error', 'Provider tidak didukung.');
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('user.login')
                ->with('error', 'Gagal login via Google. Silakan coba lagi.');
        }

        // Cari user berdasarkan email Google
        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            // Update social_id jika sebelumnya belum terhubung (login manual)
            if (empty($user->social_id)) {
                $user->update([
                    'social_id'       => $socialUser->getId(),
                    'social_provider' => $provider,
                ]);
            }
        } else {
            // Buat akun baru otomatis (auto-register via Google)
            $user = User::create([
                'name'            => $socialUser->getName(),
                'email'           => $socialUser->getEmail(),
                'password'        => bcrypt(Str::random(16)), // password acak, tidak dipakai
                'role'            => 'user',
                'social_id'       => $socialUser->getId(),
                'social_provider' => $provider,
            ]);
        }

        Auth::login($user, remember: true);

        // Pulihkan redirect URL dari session (ke checkout/tiket/dsb), atau fallback ke home
        $redirectTo = session()->pull('social_redirect_url', route('home'));

        return redirect()->to($redirectTo)->with('success', 'Berhasil masuk dengan Google!');
    }
}
