<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: IsSuperadmin
 * Hanya user dengan role 'superadmin' yang boleh melewati middleware ini.
 * Digunakan untuk rute-rute sensitif: approval, kelola organizer, kategori, partner.
 */
class IsSuperadmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || !$user->isSuperadmin()) {
            abort(403, 'Akses ditolak. Fitur ini hanya tersedia untuk Superadmin.');
        }

        return $next($request);
    }
}
