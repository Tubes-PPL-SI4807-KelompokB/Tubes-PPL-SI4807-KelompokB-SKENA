@extends('layouts.admin')
@section('title', "Edit Role {$role->display_name} — SKENA")
@section('page-title', 'Edit Role & Hak Akses')
@section('page-subtitle', "Atur nama dan izin akses untuk role: {$role->display_name}")

@section('content')
<div class="max-w-3xl">
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

        <form method="POST" action="{{ route('admin.roles.update', $role->id) }}" class="space-y-6">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Nama Tampilan Role</label>
                    <input type="text" name="display_name" value="{{ old('display_name', $role->display_name) }}" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Identifier Teknis (Slug)</label>
                    <input type="text" value="{{ $role->name }}" disabled
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-400 bg-gray-50 cursor-not-allowed">
                    <p class="text-[11px] text-gray-400 mt-1">Identifier sistem tidak dapat diubah.</p>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Deskripsi</label>
                    <input type="text" name="description" value="{{ old('description', $role->description) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 bg-white">
                </div>
            </div>

            {{-- Permissions Checkbox Grid --}}
            <div class="border-t border-gray-100 pt-5">
                <div class="flex items-center justify-between mb-3">
                    <label class="block text-xs font-black text-gray-900 uppercase tracking-wider">
                        Pilih Hak Akses (Permissions)
                    </label>
                    <span class="text-xs text-gray-500">Centang izin yang diberikan kepada role ini</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-96 overflow-y-auto p-1">
                    @php
                        $assignedIds = $role->permissions->pluck('id')->toArray();
                    @endphp
                    @foreach($permissions as $perm)
                    <label class="flex items-start gap-3 p-3 rounded-xl border border-gray-200 hover:border-blue-300 hover:bg-blue-50/50 cursor-pointer transition-all">
                        <input type="checkbox" name="permissions[]" value="{{ $perm->id }}"
                               {{ in_array($perm->id, old('permissions', $assignedIds)) ? 'checked' : '' }}
                               class="w-4 h-4 rounded text-brand-blue border-gray-300 focus:ring-blue-200 mt-0.5 cursor-pointer">
                        <div class="text-xs">
                            <p class="font-bold text-gray-900">{{ $perm->display_name }}</p>
                            <p class="font-mono text-[11px] text-gray-400">{{ $perm->name }}</p>
                            @if($perm->description)
                                <p class="text-[11px] text-gray-500 mt-0.5">{{ $perm->description }}</p>
                            @endif
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl text-sm font-bold text-white transition-all hover:opacity-90"
                        style="background:#2C99D6">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.roles.index') }}"
                   class="px-5 py-2.5 rounded-xl text-sm font-semibold border border-gray-200 text-gray-600 hover:border-gray-300 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
