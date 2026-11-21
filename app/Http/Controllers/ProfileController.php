<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
        $user = Auth::user()->load('role', 'profile', 'experiences');
        return view('profile.index', compact('user'));
    }

    /** PBI-01 – Form Edit Profil: form sekarang menyatu dengan halaman profil */
    public function edit($id)
    {
        return redirect()->route('profile.index');
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
            'angkatan'    => ['nullable', 'integer', 'between:2000,' . (date('Y') + 1)],
            'is_available'=> ['nullable', 'boolean'],
            'nim'         => ['nullable', 'string', 'max:50'],
            'ktm'         => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:4096'],
            'avatar'      => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'required'     => ':Attribute wajib diisi.',
            'max.string'   => ':Attribute maksimal :max karakter.',
            'max.file'     => ':Attribute maksimal :max KB.',
            'url'          => ':Attribute harus berupa URL lengkap (https://...).',
            'numeric'      => ':Attribute harus berupa angka.',
            'integer'      => ':Attribute harus berupa angka.',
            'min.numeric'  => ':Attribute tidak boleh negatif.',
            'between.numeric' => ':Attribute harus antara :min dan :max.',
            'image'        => ':Attribute harus berupa gambar.',
            'mimes'        => ':Attribute harus berformat :values.',
        ], [
            'name' => 'nama', 'phone' => 'kontak', 'city' => 'kota', 'hourly_rate' => 'tarif per jam',
            'university' => 'kampus', 'faculty' => 'fakultas', 'major' => 'program studi',
            'ktm' => 'file KTM', 'avatar' => 'foto profil',
        ]);

        // Hanya kolom yang dikirim form yang diperbarui (form desain PBI-01 tidak memuat
        // headline/kota/website/tarif/fakultas/NIM, jadi data lama itu tetap utuh).
        $sent = fn (string $k) => $request->exists($k);

        $userData = ['name' => $validated['name']];
        foreach (['phone', 'university', 'major', 'angkatan', 'nim', 'faculty'] as $k) {
            if ($sent($k)) $userData[$k] = $validated[$k] ?? null;
        }

        // Jika mengunggah KTM baru
        if ($request->hasFile('ktm')) {
            $userData['ktm_path'] = $request->file('ktm')->store('ktm', 'public');
            $userData['student_verification_status'] = 'pending';
            $userData['verification_notes'] = 'KTM baru telah diunggah dan sedang ditinjau.';
        }

        $user->update($userData);

        $profileData = [];
        foreach (['headline', 'bio', 'city', 'website', 'instagram', 'linkedin', 'hourly_rate'] as $k) {
            if ($sent($k)) $profileData[$k] = $validated[$k] ?? null;
        }

        // Toggle "Terima Pesanan"
        if ($sent('is_available')) {
            $profileData['is_available'] = $request->boolean('is_available');
        }

        // Foto profil baru (tombol "Ubah Foto")
        if ($request->hasFile('avatar')) {
            $old = $user->profile?->avatar;
            $profileData['avatar'] = $request->file('avatar')->store('avatars', 'public');
            if ($old) {
                Storage::disk('public')->delete($old);
            }
        }

        Profile::updateOrCreate(['user_id' => $user->id], $profileData);

        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui');
    }
}
