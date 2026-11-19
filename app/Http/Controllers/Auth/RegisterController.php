<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

/**
 * RegisterController – FR-01
 * Fondasi Bersama Autentikasi & Verifikasi Mahasiswa
 */
class RegisterController extends Controller
{
    public function showRegistrationForm(Request $request)
    {
        $selectedRole = $request->query('role', 'freelancer');
        return view('auth.register', compact('selectedRole'));
    }

    public function register(Request $request)
    {
        $rules = [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'role'     => ['required', 'in:freelancer,client'],
        ];

        // Validasi data mahasiswa jika mendaftar sebagai freelancer
        if ($request->input('role') === 'freelancer') {
            $rules['nim']        = ['required', 'string', 'max:50'];
            $rules['university'] = ['required', 'string', 'max:255'];
            $rules['faculty']    = ['nullable', 'string', 'max:255'];
            $rules['major']      = ['nullable', 'string', 'max:255'];
            $rules['ktm']        = ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:4096'];
        }

        $messages = [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar di SKENA.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Pilih jenis akun yang diinginkan.',
            'nim.required' => 'NIM / Nomor Mahasiswa wajib diisi untuk Freelancer Mahasiswa.',
            'university.required' => 'Nama Universitas / Politeknik wajib diisi.',
            'ktm.mimes' => 'Format KTM harus berupa JPG, PNG, atau PDF.',
            'ktm.max' => 'Ukuran file KTM maksimal 4MB.',
        ];

        $validated = $request->validate($rules, $messages);

        // Ambil ID Role dari database
        $roleModel = Role::where('name', $validated['role'])->first();
        if (!$roleModel) {
            $roleModel = Role::create([
                'name' => $validated['role'],
                'display_name' => $validated['role'] === 'freelancer' ? 'Freelancer Mahasiswa' : 'Klien / Pemberi Kerja',
            ]);
        }

        // Upload file KTM jika disertakan
        $ktmPath = null;
        if ($request->hasFile('ktm')) {
            $ktmPath = $request->file('ktm')->store('ktm', 'public');
        }

        $verificationStatus = ($validated['role'] === 'freelancer') ? 'pending' : 'unverified';

        // Buat User Baru
        $user = User::create([
            'role_id'                     => $roleModel->id,
            'name'                        => $validated['name'],
            'email'                       => $validated['email'],
            'password'                    => Hash::make($validated['password']),
            'phone'                       => $validated['phone'] ?? null,
            'status'                      => 'active',
            'student_verification_status' => $verificationStatus,
            'nim'                         => $validated['nim'] ?? null,
            'university'                  => $validated['university'] ?? null,
            'faculty'                     => $validated['faculty'] ?? null,
            'major'                       => $validated['major'] ?? null,
            'ktm_path'                    => $ktmPath,
            'verification_notes'          => ($validated['role'] === 'freelancer') ? 'Dokumen verifikasi mahasiswa baru telah diajukan.' : null,
        ]);

        // Buat Profil Kosong
        Profile::create([
            'user_id'  => $user->id,
            'headline' => ($validated['role'] === 'freelancer') ? 'Mahasiswa Kreatif di ' . ($user->university ?? 'Indonesia') : 'Pemberi Kerja Kreatif',
            'city'     => null,
        ]);

        // Otomatis Login
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Akun berhasil dibuat! Selamat datang di SKENA.');
    }
}
