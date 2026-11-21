<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * RoleController – FR-06 (TIM-F)
 * Pola Settings: daftar role sebagai navigasi, matriks hak akses modul x aksi
 * disimpan dengan "Simpan Perubahan". Tambah/ubah role lewat slide-over,
 * hapus role lewat dialog (pengguna dipindahkan dulu ke role lain).
 */
class RoleController extends Controller
{
    /** Role inti sistem: tidak bisa dihapus. */
    public const CORE = ['admin', 'freelancer', 'client'];

    public function index(Request $request)
    {
        $roles = Role::withCount('users')->with('permissions:id,name')->orderBy('id')->get();
        $role  = $roles->firstWhere('id', (int) $request->query('role')) ?? $roles->firstWhere('name', 'freelancer') ?? $roles->first();
        $permissions = Permission::whereIn('name', $this->matrixNames())->get()->keyBy('name');

        return view('admin.roles.index', compact('roles', 'role', 'permissions'));
    }

    public function create()
    {
        return redirect()->route('admin.roles.index', ['tambah' => 1]);
    }

    public function edit($id)
    {
        return redirect()->route('admin.roles.index', ['role' => (int) $id]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedMeta($request);
        $slug = Str::slug($data['display_name'], '_');

        if (Role::where('name', $slug)->exists()) {
            return back()->withErrors(['display_name' => 'Nama role ini sudah ada. Pakai nama lain.'], 'role')->withInput();
        }

        $role = Role::create(['name' => $slug] + $data);

        return redirect()->route('admin.roles.index', ['role' => $role->id])
            ->with('success', "Role {$role->display_name} dibuat. Atur hak aksesnya di tabel.");
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        // Form slide-over: ubah nama & deskripsi
        if ($request->has('display_name')) {
            $role->update($this->validatedMeta($request));
            return redirect()->route('admin.roles.index', ['role' => $role->id])->with('success', "Role {$role->display_name} disimpan.");
        }

        // Matriks hak akses
        $request->validate([
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ]);
        $role->permissions()->sync($request->input('permissions', []));

        return redirect()->route('admin.roles.index', ['role' => $role->id])->with('success', 'Hak akses berhasil disimpan');
    }

    public function destroy(Request $request, $id)
    {
        $role = Role::withCount('users')->findOrFail($id);

        if (in_array($role->name, self::CORE, true)) {
            return back()->with('error', "Role {$role->display_name} adalah role inti dan tidak bisa dihapus.");
        }

        if ($role->users_count > 0) {
            $request->validate([
                'move_to' => ['required', Rule::exists('roles', 'id'), Rule::notIn([$role->id])],
            ], ['move_to.required' => 'Pilih role tujuan untuk pengguna yang masih memakai role ini.']);
        }

        DB::transaction(function () use ($role, $request) {
            if ($role->users_count > 0) {
                User::where('role_id', $role->id)->update(['role_id' => $request->input('move_to')]);
            }
            $role->permissions()->detach();
            $role->delete();
        });

        return redirect()->route('admin.roles.index')->with('success', "Role {$role->display_name} dihapus.");
    }

    private function validatedMeta(Request $request): array
    {
        return $request->validateWithBag('role', [
            'display_name' => ['required', 'string', 'max:100'],
            'description'  => ['nullable', 'string', 'max:255'],
        ], [
            'display_name.required' => 'Masukkan nama role.',
            'display_name.max'      => 'Nama role maksimal 100 karakter.',
            'description.max'       => 'Deskripsi maksimal 255 karakter.',
        ]);
    }

    private function matrixNames(): array
    {
        $names = [];
        foreach (array_keys(Permission::MODULES) as $m) {
            foreach (array_keys(Permission::ACTIONS) as $a) {
                $names[] = "{$m}.{$a}";
            }
        }
        return $names;
    }
}
