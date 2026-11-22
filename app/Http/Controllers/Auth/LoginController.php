<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * LoginController – FR-02
 * Fondasi Bersama Autentikasi
 */
class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // Cek status banned/inactive (TIM-E: akun nonaktif tidak bisa masuk)
            if ($user->status !== 'active') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Akun kamu sedang dinonaktifkan oleh admin. Hubungi dukungan SKENA untuk mengaktifkannya kembali.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            // Role-based redirect
            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'))->with('success', 'Selamat datang kembali, Administrator!');
            }

            return redirect()->intended(route('dashboard'))->with('success', 'Selamat datang kembali di SKENA, ' . $user->name . '!');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi belum cocok.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda berhasil keluar dari akun.');
    }
}
