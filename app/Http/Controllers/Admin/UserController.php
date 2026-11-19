<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * UserController – FR-05
 * Manajemen Pengguna & Hak Akses
 */
class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('role', 'profile');

        // Filter search nama / email / nim
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('university', 'like', "%{$search}%");
            });
        }

        // Filter role
        if ($role = $request->input('role')) {
            $query->whereHas('role', fn($q) => $q->where('name', $role));
        }

        // Filter status akun
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Filter verifikasi mahasiswa
        if ($verification = $request->input('verification')) {
            $query->where('student_verification_status', $verification);
        }

        $users = $query->latest()->paginate(15)->withQueryString();
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function show($id)
    {
        $user = User::with('role', 'profile')->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::with('role', 'profile')->findOrFail($id);
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role_id'  => ['required', 'exists:roles,id'],
            'status'   => ['required', 'in:active,inactive,banned'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $data = [
            'name'    => $request->input('name'),
            'email'   => $request->input('email'),
            'role_id' => $request->input('role_id'),
            'status'  => $request->input('status'),
            'phone'   => $request->input('phone'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    /**
     * Verifikasi Mahasiswa (Approve / Reject)
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
}
