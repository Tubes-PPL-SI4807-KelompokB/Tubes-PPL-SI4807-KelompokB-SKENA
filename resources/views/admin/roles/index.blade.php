@extends('layouts.admin')
@section('title', 'Role & Hak Akses — SKENA')
@section('page-title', 'Manajemen Role & Hak Akses')
@section('page-subtitle', 'Kelola izin akses pengguna (RBAC: Role-Based Access Control)')

@section('content')

{{-- Info Card --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h2 class="text-sm font-black text-gray-900">Struktur Hak Akses Platform</h2>
        <p class="text-xs text-gray-500 mt-0.5">Sistem memisahkan peran antara Administrator, Freelancer Mahasiswa, dan Klien.</p>
    </div>
    <div class="flex items-center gap-2">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-brand-blue border border-blue-200">
            <span class="w-1.5 h-1.5 rounded-full" style="background:#2C99D6"></span>
            {{ $roles->count() }} Role Aktif
        </span>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-50 text-gray-700 border border-gray-200">
            {{ $permissions->count() }} Permission
        </span>
    </div>
</div>

{{-- Role Cards Grid --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    @foreach($roles as $role)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col justify-between hover:border-blue-200 transition-all">
        <div>
            <div class="flex items-start justify-between gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                     style="background: {{ $role->name === 'admin' ? 'linear-gradient(135deg,#0F2942,#2C99D6)' : ($role->name === 'freelancer' ? 'linear-gradient(135deg,#2C99D6,#60A5FA)' : 'linear-gradient(135deg,#EB2E33,#F87171)') }}">
                    @if($role->name === 'admin')
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    @elseif($role->name === 'freelancer')
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    @else
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    @endif
                </div>
                <span class="text-xs font-bold text-gray-500 bg-gray-100 rounded-full px-2.5 py-0.5">
                    {{ $role->users->count() }} User
                </span>
            </div>

            <h3 class="text-base font-black text-gray-900">{{ $role->display_name }}</h3>
            <p class="text-xs font-mono text-gray-400 mb-2">name: {{ $role->name }}</p>
            <p class="text-xs text-gray-600 mb-4">{{ $role->description }}</p>

            {{-- Permissions Badges --}}
            <div class="mb-5">
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">Hak Akses ({{ $role->permissions->count() }}):</p>
                <div class="flex flex-wrap gap-1.5 max-h-36 overflow-y-auto">
                    @forelse($role->permissions as $perm)
                        <span class="inline-block text-[11px] font-medium bg-blue-50 text-brand-blue rounded-lg px-2 py-0.5 border border-blue-100">
                            {{ $perm->display_name }}
                        </span>
                    @empty
                        <span class="text-xs text-gray-400 italic">Belum ada izin khusus</span>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
            <a href="{{ route('admin.roles.edit', $role->id) }}"
               class="text-xs font-bold text-brand-blue hover:underline">
                Ubah Hak Akses →
            </a>
            <span class="text-[11px] text-gray-400">Role Sistem</span>
        </div>
    </div>
    @endforeach
</div>

{{-- Master Permissions List --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-sm font-black text-gray-900">Daftar Semua Permission</h2>
            <p class="text-xs text-gray-400 mt-0.5">Seluruh permission yang terdaftar di database platform</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
        @foreach($permissions as $perm)
        <div class="p-3 rounded-xl border border-gray-100 bg-gray-50 flex items-start gap-3">
            <div class="w-6 h-6 rounded-md flex items-center justify-center shrink-0 mt-0.5" style="background:#2C99D6">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-900">{{ $perm->display_name }}</p>
                <p class="text-[11px] font-mono text-gray-400">{{ $perm->name }}</p>
                <p class="text-[11px] text-gray-500 mt-0.5">{{ $perm->description }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection
