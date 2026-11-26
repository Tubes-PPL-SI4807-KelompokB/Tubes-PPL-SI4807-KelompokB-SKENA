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
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'permission' => \App\Http\Middleware\CheckPermission::class,
            'verified.student' => \App\Http\Middleware\EnsureVerifiedStudent::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // TIM-B tb-2: token CSRF kedaluwarsa (halaman dibiarkan terlalu lama).
        // Tamu diarahkan ke login dengan pesan "sesi berakhir"; pengguna yang masih
        // masuk dikembalikan ke halaman sebelumnya agar bisa mengulang.
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, $request) {
            if ($e->getStatusCode() !== 419 || $request->expectsJson()) {
                return null;
            }
            if ($request->user()) {
                return back()->with('error', 'Halaman sudah terlalu lama dibuka. Silakan ulangi aksimu.');
            }
            return redirect()->route('login')->with('session_expired', true);
        });
    })->create();

