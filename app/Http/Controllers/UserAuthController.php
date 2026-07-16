<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserAuthController extends Controller
{
    // ─── Login ──────────────────────────────────────────────────────────────────

    /**
     * Tampilkan halaman login user.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            // Jika sudah login, jangan tampilkan halaman login lagi
            return redirect()->route('home');
        }
        return view('auth.user-login');
    }

    /**
     * Proses submit form login user.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Blok akses panel admin dari halaman login user
            // (admin/organizer tetap bisa pakai web biasa, tapi redirect ke panel mereka)
            if ($user->canAccessPanel()) {
                return redirect()->route('admin.dashboard');
            }

            // Kembalikan ke URL yang dimaksud (misal: checkout) atau ke home
            return redirect()->intended(route('home'))
                ->with('success', 'Selamat datang, ' . $user->name . '!');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'Email atau password tidak cocok dengan data kami.',
            ]);
    }

    /**
     * Logout user.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Anda telah berhasil keluar.');
    }

    // ─── Register ───────────────────────────────────────────────────────────────

    /**
     * Tampilkan halaman registrasi user.
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.user-register');
    }

    /**
     * Proses submit form registrasi user baru.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'name.required'      => 'Nama lengkap wajib diisi.',
            'email.required'     => 'Alamat email wajib diisi.',
            'email.unique'       => 'Email ini sudah terdaftar. Silakan login.',
            'password.required'  => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min'       => 'Password minimal 8 karakter.',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'user',
        ]);

        Auth::login($user, remember: true);

        return redirect()->route('home')
            ->with('success', 'Akun berhasil dibuat! Selamat datang, ' . $user->name . '!');
    }
}
