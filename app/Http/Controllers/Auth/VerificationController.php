<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

/**
 * TIM-A ta-2 / ta-3 – Verifikasi email kampus dengan kode 6 digit + konfirmasi NIM.
 * Kode berlaku 60 detik (sesuai desain "Kode berlaku 00:59"); setelah habis bisa kirim ulang.
 * Di lingkungan lokal (MAIL_MAILER=log) email tercatat di storage/logs/laravel.log.
 */
class VerificationController extends Controller
{
    public const TTL = 60;

    public static function sendCode(User $user): void
    {
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Cache::put(self::key($user), ['hash' => Hash::make($code), 'expires' => now()->addSeconds(self::TTL)->timestamp], self::TTL + 300);

        Mail::raw(
            "Halo {$user->name},\n\nKode verifikasi email kampus SKENA kamu: {$code}\nKode berlaku " . self::TTL . " detik.\n\nAbaikan email ini jika kamu tidak mendaftar di SKENA.",
            fn ($m) => $m->to($user->email)->subject("Kode verifikasi SKENA: {$code}")
        );
    }

    private static function key(User $user): string
    {
        return 'email-otp:' . $user->id;
    }

    /** ta-2: layar verifikasi */
    public function notice(Request $request)
    {
        $user = $request->user();
        if ($user->email_verified_at) {
            return redirect()->route('verification.done');
        }

        $otp = Cache::get(self::key($user));
        $remaining = $otp ? max(0, $otp['expires'] - now()->timestamp) : 0;

        return view('auth.verify', compact('user', 'remaining'));
    }

    public function verify(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'code' => ['required', 'digits:6'],
            'nim'  => ['required', 'string', 'max:50'],
        ], [
            'code.required' => 'Masukkan 6 digit kode dari email.',
            'code.digits'   => 'Kode terdiri dari 6 angka.',
            'nim.required'  => 'Masukkan NIM kamu.',
        ]);

        $otp = Cache::get(self::key($user));
        if (!$otp || $otp['expires'] < now()->timestamp) {
            return back()->withErrors(['code' => 'Kode sudah tidak berlaku. Kirim ulang kode, lalu masukkan kode yang baru.'])->withInput();
        }
        if (!Hash::check($data['code'], $otp['hash'])) {
            return back()->withErrors(['code' => 'Kode belum cocok. Periksa lagi email dari SKENA.'])->withInput();
        }
        if (trim($data['nim']) !== (string) $user->nim) {
            return back()->withErrors(['nim' => 'NIM belum sama dengan yang didaftarkan. Periksa lagi NIM kamu.'])->withInput();
        }

        Cache::forget(self::key($user));
        $user->forceFill([
            'email_verified_at'           => now(),
            'student_verification_status' => 'verified',
            'verified_at'                 => now(),
            'verification_notes'          => 'Email kampus dan NIM terverifikasi.',
        ])->save();

        return redirect()->route('verification.done');
    }

    public function resend(Request $request)
    {
        $user = $request->user();
        $otp  = Cache::get(self::key($user));

        if ($otp && $otp['expires'] > now()->timestamp) {
            return back()->with('error', 'Tunggu sampai kode lama habis sebelum kirim ulang.');
        }

        self::sendCode($user);
        return back()->with('success', 'Kode baru sudah dikirim ke ' . $user->email);
    }

    /** ta-3: berhasil */
    public function done(Request $request)
    {
        if (!$request->user()->email_verified_at) {
            return redirect()->route('verification.notice');
        }
        return view('auth.verified', ['user' => $request->user()]);
    }

    /** ta-3: gagal – email bukan email kampus */
    public function invalidEmail(Request $request)
    {
        if (!$request->session()->has('register_draft')) {
            return redirect()->route('register');
        }
        return view('auth.email-invalid', ['draft' => $request->session()->get('register_draft')]);
    }
}
