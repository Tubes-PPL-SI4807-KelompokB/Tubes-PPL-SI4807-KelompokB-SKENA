<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (!$user->role || !in_array($user->role->name, $roles)) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk role: ' . implode(', ', $roles));
        }

        return $next($request);
    }
}
