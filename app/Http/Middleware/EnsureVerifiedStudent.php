<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Fondasi Bersama (FR-02) — batasan proyek: hanya mahasiswa terverifikasi yang boleh
 * bertransaksi (mengajukan request, menawarkan jasa, menerima pesanan, membayar).
 * Halaman lihat-saja tetap bisa dibuka; admin selalu lolos.
 */
class EnsureVerifiedStudent
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->isAdmin() || $user->isVerifiedStudent()) {
            return $next($request);
        }

        if ($user->student_verification_status === 'pending') {
            $message = 'Akunmu masih menunggu verifikasi admin. Setelah diverifikasi, kamu bisa memesan dan menawarkan jasa.';

            return $request->isMethod('GET')
                ? redirect()->route('dashboard')->with('error', $message)
                : back()->with('error', $message);
        }

        return redirect()->route('verification.notice')
            ->with('error', 'Verifikasi email kampus dan NIM dulu agar bisa memesan atau menawarkan jasa.');
    }
}
