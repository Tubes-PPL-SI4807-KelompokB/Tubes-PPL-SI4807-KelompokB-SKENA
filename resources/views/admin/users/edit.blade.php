@extends('layouts.admin')
@section('title', "Edit Pengguna: {$user->name} — SKENA")
@section('page-title', 'Edit Pengguna')
@section('page-subtitle', "Ubah data, role, dan status akun: {$user->name}")

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">

        @if($errors->any())
        <div class="mb-5 bg-red-50 border border-red-200 rounded-xl p-4">
            <ul class="text-xs text-red-700 space-y-1">
                @foreach($errors->all() as $e)
                    <li class="flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-red-500 inline-block"></span>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-5">
            @csrf @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-gray-600 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Nomor HP</label>
                    <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Role</label>
                    <select name="role_id" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-blue-400 bg-white">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                {{ $role->display_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Status Akun</label>
                    <select name="status" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-blue-400 bg-white">
                        <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>✓ Aktif</option>
                        <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>⏸ Tidak Aktif</option>
                        <option value="banned" {{ old('status', $user->status) === 'banned' ? 'selected' : '' }}>⛔ Dibekukan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Password Baru <span class="text-gray-400 font-normal">(kosongkan jika tidak diubah)</span></label>
                    <input type="password" name="password"
                           placeholder="Minimal 8 karakter"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 bg-white">
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="px-5 py-2.5 rounded-xl text-sm font-bold text-white transition-all hover:opacity-90"
                        style="background:#3f749c">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.users.show', $user->id) }}"
                   class="px-5 py-2.5 rounded-xl text-sm font-semibold border border-gray-200 text-gray-600 hover:border-gray-300 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

