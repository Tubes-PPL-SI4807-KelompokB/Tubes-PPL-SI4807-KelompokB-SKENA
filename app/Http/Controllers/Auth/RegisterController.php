<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * RegisterController – FR-01
 * Fondasi Bersama Autentikasi & Verifikasi Mahasiswa
 */
class RegisterController extends Controller
{
    /** TIM-A: domain email kampus yang diterima (desain ta-3). */
    public const CAMPUS_DOMAIN = 'student.telkomuniversity.ac.id';

    public function showRegistrationForm(Request $request)
    {
        // Isian dari layar "Email kampus tidak valid" (Ubah Email) dipakai lagi
        $draft = $request->session()->get('register_draft', []);
        return view('auth.register', compact('draft'));
    }

    public function register(Request $request)
    {
        $email = strtolower(trim((string) $request->input('email')));

        // ta-3 keadaan gagal: email bukan email kampus
        if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL) && !str_ends_with($email, '@' . self::CAMPUS_DOMAIN)) {
            $request->session()->put('register_draft', $request->only('name', 'email', 'nim'));
            return redirect()->route('register.email-invalid');
        }

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'nim'      => ['required', 'string', 'max:50'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms'    => ['accepted'],
        ], [
            'name.required'      => 'Masukkan nama lengkap kamu.',
            'email.required'     => 'Masukkan email kampus kamu.',
            'email.email'        => 'Tulis email lengkap, misalnya nama@student.telkomuniversity.ac.id.',
            'email.unique'       => 'Email ini sudah terdaftar. Masuk dengan email tersebut.',
            'nim.required'       => 'Masukkan NIM sesuai data kampus.',
            'password.required'  => 'Buat kata sandi untuk akunmu.',
            'password.min'       => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi belum sama.',
            'terms.accepted'     => 'Centang persetujuan Syarat & Ketentuan untuk melanjutkan.',
        ]);

        $request->session()->forget('register_draft');

        // Satu akun mahasiswa = penyedia sekaligus pencari jasa
        $roleModel = Role::firstOrCreate(['name' => 'freelancer'], ['display_name' => 'Freelancer Mahasiswa']);

        // Buat User Baru
        $user = User::create([
            'role_id'                     => $roleModel->id,
            'name'                        => $validated['name'],
            'email'                       => $email,
            'password'                    => Hash::make($validated['password']),
            'status'                      => 'active',
            'student_verification_status' => 'unverified',
            'nim'                         => $validated['nim'],
            'university'                  => 'Telkom University',
        ]);

        // Buat Profil Kosong
        Profile::create(['user_id' => $user->id]);

        // Masuk, lalu lanjut ke langkah 2: verifikasi email kampus (ta-2)
        Auth::login($user);
        VerificationController::sendCode($user);

        return redirect()->route('verification.notice');
    }
}
