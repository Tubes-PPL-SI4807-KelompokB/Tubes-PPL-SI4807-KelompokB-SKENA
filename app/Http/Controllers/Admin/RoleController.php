<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

/**
 * RoleController – FR-06
 * Manajemen Role dan Hak Akses Pengguna
 */
class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with(['permissions', 'users'])->get();
        $permissions = Permission::all();

        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => ['required', 'string', 'max:50', 'unique:roles,name'],
            'display_name' => ['required', 'string', 'max:100'],
            'description'  => ['nullable', 'string', 'max:255'],
            'permissions'  => ['nullable', 'array'],
            'permissions.*'=> ['exists:permissions,id'],
        ]);

        $role = Role::create([
            'name'         => strtolower(trim($request->input('name'))),
            'display_name' => $request->input('display_name'),
            'description'  => $request->input('description'),
        ]);

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->input('permissions'));
        }

        return redirect()->route('admin.roles.index')->with('success', "Role {$role->display_name} berhasil dibuat.");
    }

    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::all();

        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'display_name' => ['required', 'string', 'max:100'],
            'description'  => ['nullable', 'string', 'max:255'],
            'permissions'  => ['nullable', 'array'],
            'permissions.*'=> ['exists:permissions,id'],
        ]);

        $role->update([
            'display_name' => $request->input('display_name'),
            'description'  => $request->input('description'),
        ]);

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->input('permissions'));
        } else {
            $role->permissions()->detach();
        }

        return redirect()->route('admin.roles.index')->with('success', "Hak akses role {$role->display_name} berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        // Lindungi role bawaan sistem
        if (in_array($role->name, ['admin', 'freelancer', 'client'])) {
            return back()->with('error', "Role '{$role->display_name}' adalah role inti sistem dan tidak boleh dihapus.");
        }

        if ($role->users()->count() > 0) {
            return back()->with('error', "Role '{$role->display_name}' masih digunakan oleh {$role->users()->count()} pengguna.");
        }

        $role->permissions()->detach();
        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil dihapus.');
    }
}
