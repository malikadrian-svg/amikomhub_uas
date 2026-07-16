<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: IsAdmin (deprecated - now delegates to IsPanel logic)
 * Dipertahankan agar route group 'admin' lama tidak error.
 * Izinkan 'superadmin' dan 'organizer' masuk panel.
 */
class IsAdmin
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
