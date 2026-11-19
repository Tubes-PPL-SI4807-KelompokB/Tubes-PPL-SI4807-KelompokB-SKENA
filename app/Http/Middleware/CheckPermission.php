<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (!$user->hasPermission($permission)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki hak akses: ' . $permission);
        }

        return $next($request);
    }
}
