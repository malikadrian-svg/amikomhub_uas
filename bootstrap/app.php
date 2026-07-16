<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin'      => \App\Http\Middleware\IsAdmin::class,
            'panel'      => \App\Http\Middleware\IsPanel::class,
            'superadmin' => \App\Http\Middleware\IsSuperadmin::class,
        ]);
        // Redirect cerdas: admin/* → /admin/login, lainnya → /login (user)
        $middleware->redirectGuestsTo(function (\Illuminate\Http\Request $request) {
            if ($request->is('admin/*') || $request->is('admin')) {
                return '/admin/login';
            }
            return '/login';
        });

        // Mengecualikan route webhook Midtrans dari blokir CSRF
        $middleware->validateCsrfTokens(except: [
            '/midtrans/callback',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
