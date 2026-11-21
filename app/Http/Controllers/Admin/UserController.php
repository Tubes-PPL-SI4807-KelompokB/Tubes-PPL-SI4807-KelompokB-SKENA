<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * UserController – FR-05 (TIM-E)
 * Manajemen pengguna: daftar + filter, tambah/ubah lewat slide-over,
 * toggle status akun, hapus dengan konfirmasi, verifikasi KTM (halaman detail).
 */
class UserController extends Controller
{
    /** Filter status di toolbar: kunci URL => label */
    public const FILTERS = [
        'semua'         => 'Semua',
        'terverifikasi' => 'Terverifikasi',
        'belum'         => 'Belum Verifikasi',
        'nonaktif'      => 'Nonaktif',
    ];

    public function index(Request $request)
    {
        $query  = User::with('role', 'profile');
        $filter = array_key_exists($request->input('filter'), self::FILTERS) ? $request->input('filter') : 'semua';

        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        match ($filter) {
            'terverifikasi' => $query->where('student_verification_status', 'verified'),
            'belum'         => $query->where('student_verification_status', '!=', 'verified'),
            'nonaktif'      => $query->where('status', '!=', 'active'),
            default         => null,
        };

        $users = $query->latest()->paginate(15)->withQueryString();
        $roles = Role::orderBy('display_name')->get();
        $total = User::count();

        return view('admin.users.index', compact('users', 'roles', 'total', 'filter', 'search'));
    }

    public function show($id)
    {
        $user = User::with('role', 'profile')->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /** Form ubah tampil sebagai slide-over di halaman daftar. */
    public function create()
    {
        return redirect()->route('admin.users.index', ['tambah' => 1]);
    }

    public function edit($id)
    {
        return redirect()->route('admin.users.index', ['ubah' => (int) $id]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        // Desain te-2 tidak punya field kata sandi: buat kata sandi awal acak dan tampilkan sekali ke admin.
        $plain = Str::password(10, symbols: false);
        $user  = User::create($data + ['password' => Hash::make($plain)]);
        Profile::firstOrCreate(['user_id' => $user->id]);

        return redirect()->route('admin.users.index')
            ->with('success', "Pengguna {$user->name} ditambahkan. Kata sandi awal: {$plain}");
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $this->validated($request, $user);

        if ($user->id === auth()->id() && $data['status'] !== 'active') {
            return back()->with('error', 'Kamu tidak bisa menonaktifkan akunmu sendiri.');
        }

        $user->update($data);

        return redirect()->route('admin.users.index', $request->only('filter', 'search', 'page'))
            ->with('success', "Data {$user->name} berhasil disimpan.");
    }

    /** Toggle status akun dari tabel (switch langsung tersimpan). */
    public function toggleStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Kamu tidak bisa menonaktifkan akunmu sendiri.');
        }

        $user->update(['status' => $user->status === 'active' ? 'inactive' : 'active']);

        return back()->with('success', $user->status === 'active'
            ? "Akun {$user->name} diaktifkan kembali."
            : "Akun {$user->name} dinonaktifkan. Pengguna tidak bisa masuk sampai diaktifkan lagi.");
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Kamu tidak bisa menghapus akunmu sendiri.');
        }

        $name = $user->name;
        $user->delete();

        return back()->with('success', "Akun {$name} berhasil dihapus.");
    }

    /**
     * Verifikasi Mahasiswa (Approve / Reject) dari halaman detail.
     */
    public function verifyStudent(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'status' => ['required', 'in:verified,rejected'],
            'notes'  => ['nullable', 'string', 'max:500'],
        ]);

        $status = $request->input('status');
        $notes  = $request->input('notes') ?? ($status === 'verified' ? 'Identitas mahasiswa dan KTM berhasil diverifikasi.' : 'Dokumen KTM tidak valid atau buram.');

        $user->update([
            'student_verification_status' => $status,
            'verification_notes'          => $notes,
            'verified_at'                 => $status === 'verified' ? now() : null,
        ]);

        $message = $status === 'verified'
            ? "Mahasiswa {$user->name} berhasil diverifikasi! Badge aktif."
            : "Pengajuan verifikasi mahasiswa {$user->name} telah ditolak.";

        return back()->with('success', $message);
    }

    /** Validasi form slide-over (bag "user" agar tidak bentrok dengan form lain). */
    private function validated(Request $request, ?User $user = null): array
    {
        $v = $request->validateWithBag('user', [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user?->id)],
            'nim'      => ['nullable', 'string', 'max:50'],
            'role_id'  => ['required', 'exists:roles,id'],
            'verified' => ['required', 'boolean'],
            'active'   => ['required', 'boolean'],
        ], [
            'required'     => ':Attribute wajib diisi.',
            'email'        => 'Tulis email lengkap, misalnya nama@kampus.ac.id.',
            'email.unique' => 'Email ini sudah dipakai akun lain.',
            'min'          => ':Attribute minimal :min karakter.',
            'exists'       => 'Pilih role yang tersedia.',
        ], [
            'name' => 'nama', 'email' => 'email', 'nim' => 'NIM', 'role_id' => 'role',
        ]);

        $verified = (bool) $v['verified'];
        $current  = $user?->student_verification_status ?? 'unverified';

        return [
            'name'    => $v['name'],
            'email'   => $v['email'],
            'nim'     => $v['nim'] ?? null,
            'role_id' => $v['role_id'],
            'status'  => $v['active'] ? 'active' : 'inactive',
            // "Belum Verifikasi" mempertahankan status menunggu/ditolak yang sudah ada.
            'student_verification_status' => $verified ? 'verified' : ($current === 'verified' ? 'unverified' : $current),
            'verified_at' => $verified ? ($user?->verified_at ?? now()) : null,
        ];
    }
}
