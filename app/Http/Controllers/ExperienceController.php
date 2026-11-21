<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * ExperienceController – PBI-01
 * PIC: Earliyanza
 *
 * Daftar, form tambah/ubah, dan konfirmasi hapus tampil di halaman profil
 * (drawer & modal), jadi aksi tampilan diarahkan ke profile.index.
 */
class ExperienceController extends Controller
{
    /** PBI-01 – daftar */
    public function index()
    {
        return redirect()->route('profile.index');
    }

    /** PBI-01 – form tambah */
    public function create()
    {
        return redirect()->route('profile.index');
    }

    /** PBI-01 – simpan */
    public function store(Request $request)
    {
        Auth::user()->experiences()->create($this->validated($request));

        return redirect()->route('profile.index')->with('success', 'Pengalaman berhasil ditambahkan');
    }

    /** PBI-01 – detail */
    public function show($id)
    {
        return redirect()->route('profile.index');
    }

    /** PBI-01 – form edit */
    public function edit($id)
    {
        return redirect()->route('profile.index');
    }

    /** PBI-01 – update */
    public function update(Request $request, $id)
    {
        $this->owned($id)->update($this->validated($request));

        return redirect()->route('profile.index')->with('success', 'Pengalaman berhasil diperbarui');
    }

    /** PBI-01 – hapus */
    public function destroy($id)
    {
        $this->owned($id)->delete();

        return redirect()->route('profile.index')->with('success', 'Pengalaman berhasil dihapus');
    }

    /** Pengguna hanya boleh mengubah pengalamannya sendiri */
    private function owned($id): Experience
    {
        return Auth::user()->experiences()->findOrFail($id);
    }

    private function validated(Request $request): array
    {
        $rules = [
            'type'         => ['nullable', Rule::in(array_keys(Experience::TYPES))],
            'name'         => ['required', 'string', 'max:255'],
            'role'         => ['required', 'string', 'max:255'],
            'start_period' => ['required', 'string', 'max:20'],
            'end_period'   => ['nullable', 'string', 'max:20'],
            'description'  => ['nullable', 'string', 'max:1000'],
        ];
        $names = [
            'type'         => 'jenis',
            'name'         => 'nama pengalaman/organisasi',
            'role'         => 'peran',
            'start_period' => 'periode mulai',
            'end_period'   => 'periode selesai',
            'description'  => 'deskripsi',
        ];

        // Isian memakai awalan experience[...] agar old() tidak bentrok dengan form profil
        $prefix = fn (array $a) => collect($a)->mapWithKeys(fn ($v, $k) => ["experience.$k" => $v])->all();

        $data = $request->validateWithBag('experience', $prefix($rules), [
            'required' => ':Attribute wajib diisi.',
            'max'      => ':Attribute maksimal :max karakter.',
            'in'       => ':Attribute tidak valid.',
        ], $prefix($names))['experience'];
        $data['type'] = $data['type'] ?? 'organisasi';

        return $data;
    }
}
