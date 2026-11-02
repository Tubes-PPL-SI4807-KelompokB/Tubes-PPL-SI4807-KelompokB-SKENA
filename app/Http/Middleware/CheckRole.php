<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // TODO: cek role user (admin, freelancer, client)
        if (!$request->user() || !in_array($request->user()->role?->name, $roles)) {
            abort(403, 'Akses ditolak.');
        }
        return $next($request);
    }
}
