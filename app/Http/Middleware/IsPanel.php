<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: IsPanel
 * Mengizinkan akses panel admin untuk user dengan role 'superadmin' atau 'organizer'.
 * Menggantikan IsAdmin yang lama (hanya memeriksa role 'admin').
 */
class IsPanel
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || !$user->canAccessPanel()) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses panel ini.');
        }

        return $next($request);
    }
}
