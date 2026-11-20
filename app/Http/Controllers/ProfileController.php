<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Profile;

/**
 * ProfileController – PBI-01
 * PIC: Earliyanza
 */
class ProfileController extends Controller
{
    /** PBI-01 – Tampilkan Profil Pengguna */
    public function index()
    {
        $user = Auth::user()->load('role', 'profile');
        return view('profile.index', compact('user'));
    }

    /** PBI-01 – Form Edit Profil */
    public function edit($id)
    {
        $user = Auth::user();

        // Keamanan: Pengguna hanya boleh mengedit profilnya sendiri
        if ((int)$id !== (int)$user->id) {
            return redirect()->route('profile.edit', $user->id);
        }

        $user->load('profile');
        return view('profile.edit', compact('user'));
    }

    /** PBI-01 – Simpan Pembaruan Profil */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if ((int)$id !== (int)$user->id) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'phone'       => ['nullable', 'string', 'max:20'],
            'headline'    => ['nullable', 'string', 'max:255'],
            'bio'         => ['nullable', 'string', 'max:1000'],
            'city'        => ['nullable', 'string', 'max:100'],
            'website'     => ['nullable', 'url', 'max:255'],
            'instagram'   => ['nullable', 'string', 'max:100'],
            'linkedin'    => ['nullable', 'string', 'max:100'],
            'hourly_rate' => ['nullable', 'numeric', 'min:0'],
            'university'  => ['nullable', 'string', 'max:255'],
            'faculty'     => ['nullable', 'string', 'max:255'],
            'major'       => ['nullable', 'string', 'max:255'],
            'nim'         => ['nullable', 'string', 'max:50'],
            'ktm'         => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:4096'],
        ]);

        // Update data dasar user
        $userData = [
            'name'  => $validated['name'],
            'phone' => $validated['phone'] ?? null,
        ];

        // Jika freelancer mengupdate data mahasiswa
        if ($user->isFreelancer()) {
            if (!empty($validated['nim'])) $userData['nim'] = $validated['nim'];
            if (!empty($validated['university'])) $userData['university'] = $validated['university'];
            if (!empty($validated['faculty'])) $userData['faculty'] = $validated['faculty'];
            if (!empty($validated['major'])) $userData['major'] = $validated['major'];

            // Jika mengunggah KTM baru
            if ($request->hasFile('ktm')) {
                $userData['ktm_path'] = $request->file('ktm')->store('ktm', 'public');
                $userData['student_verification_status'] = 'pending';
                $userData['verification_notes'] = 'KTM baru telah diunggah dan sedang ditinjau.';
            }
        }

        $user->update($userData);

        // Update atau buat profile
        Profile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'headline'    => $validated['headline'] ?? null,
                'bio'         => $validated['bio'] ?? null,
                'city'        => $validated['city'] ?? null,
                'website'     => $validated['website'] ?? null,
                'instagram'   => $validated['instagram'] ?? null,
                'linkedin'    => $validated['linkedin'] ?? null,
                'hourly_rate' => $validated['hourly_rate'] ?? null,
            ]
        );

        return redirect()->route('profile.index')->with('success', 'Profil Anda berhasil diperbarui!');
    }
}
