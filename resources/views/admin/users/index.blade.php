@extends('layouts.admin')
@section('title', 'Kelola Pengguna — SKENA')
@section('page-title', 'Manajemen Pengguna')
@section('page-subtitle', 'Kelola akun, role, status, dan verifikasi mahasiswa')

@section('content')

{{-- Filters --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-5">
    <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-48">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Cari</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Nama, email, NIM, kampus..."
                   class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm text-gray-900 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 bg-white">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Role</label>
            <select name="role" class="border border-gray-200 rounded-xl px-3 py-2 text-sm text-gray-700 outline-none focus:border-blue-400 bg-white">
                <option value="">Semua Role</option>
                @foreach($roles as $role)
                <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>{{ $role->display_name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Status</label>
            <select name="status" class="border border-gray-200 rounded-xl px-3 py-2 text-sm text-gray-700 outline-none focus:border-blue-400 bg-white">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>Dibekukan</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Verifikasi</label>
            <select name="verification" class="border border-gray-200 rounded-xl px-3 py-2 text-sm text-gray-700 outline-none focus:border-blue-400 bg-white">
                <option value="">Semua</option>
                <option value="pending" {{ request('verification') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="verified" {{ request('verification') === 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                <option value="rejected" {{ request('verification') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                <option value="unverified" {{ request('verification') === 'unverified' ? 'selected' : '' }}>Belum Dikirim</option>
            </select>
        </div>
        <button type="submit"
                class="px-4 py-2 rounded-xl text-sm font-bold text-white transition-all hover:opacity-90"
                style="background:#3f749c">
            Filter
        </button>
        <a href="{{ route('admin.users.index') }}"
           class="px-4 py-2 rounded-xl text-sm font-semibold border border-gray-200 text-gray-600 hover:border-gray-300 transition-colors">
            Reset
        </a>
    </form>
</div>

{{-- Users Table --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-gray-100" style="background:#F8F9FB">
                <th class="text-left text-xs font-black text-gray-500 uppercase tracking-wide px-5 py-3">Pengguna</th>
                <th class="text-left text-xs font-black text-gray-500 uppercase tracking-wide px-4 py-3">Role</th>
                <th class="text-left text-xs font-black text-gray-500 uppercase tracking-wide px-4 py-3">Status</th>
                <th class="text-left text-xs font-black text-gray-500 uppercase tracking-wide px-4 py-3">Verifikasi KTM</th>
                <th class="text-left text-xs font-black text-gray-500 uppercase tracking-wide px-4 py-3">Daftar</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($users as $user)
            <tr class="hover:bg-gray-50 transition-colors">
                {{-- User Info --}}
                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ $user->avatar_url }}" class="w-9 h-9 rounded-full object-cover shrink-0">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                            @if($user->nim)
                            <p class="text-xs text-gray-400">NIM: {{ $user->nim }}</p>
                            @endif
                        </div>
                    </div>
                </td>
                {{-- Role --}}
                <td class="px-4 py-4">
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                        {{ $user->isAdmin() ? 'bg-purple-100 text-purple-700' : ($user->isFreelancer() ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700') }}">
                        {{ $user->role?->display_name ?? '—' }}
                    </span>
                </td>
                {{-- Status --}}
                <td class="px-4 py-4">
                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full
                        {{ $user->status === 'active' ? 'bg-green-100 text-green-700' : ($user->status === 'banned' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600') }}">
                        <span class="w-1.5 h-1.5 rounded-full inline-block
                            {{ $user->status === 'active' ? 'bg-green-500' : ($user->status === 'banned' ? 'bg-red-500' : 'bg-gray-400') }}"></span>
                        {{ ucfirst($user->status) }}
                    </span>
                </td>
                {{-- Verification --}}
                <td class="px-4 py-4">
                    @php
                        $vs = $user->student_verification_status;
                        $vClass = match($vs) {
                            'verified' => 'bg-green-100 text-green-700',
                            'pending' => 'bg-amber-100 text-amber-700',
                            'rejected' => 'bg-red-100 text-red-700',
                            default => 'bg-gray-100 text-gray-500',
                        };
                        $vLabel = match($vs) {
                            'verified' => '✓ Terverifikasi',
                            'pending' => '⏳ Pending',
                            'rejected' => '✕ Ditolak',
                            default => '— Belum',
                        };
                    @endphp
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $vClass }}">{{ $vLabel }}</span>

                    {{-- Quick approve/reject jika pending --}}
                    @if($vs === 'pending')
                    <div class="flex gap-1 mt-1.5">
                        <form method="POST" action="{{ route('admin.users.verify', $user->id) }}">
                            @csrf
                            <input type="hidden" name="status" value="verified">
                            <button type="submit" class="text-xs text-white px-2 py-0.5 rounded font-bold hover:opacity-90" style="background:#3f749c">✓</button>
                        </form>
                        <form method="POST" action="{{ route('admin.users.verify', $user->id) }}">
                            @csrf
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit" class="text-xs font-bold px-2 py-0.5 rounded border hover:bg-red-50" style="color:#EF4444;border-color:#EF4444">✕</button>
                        </form>
                    </div>
                    @endif
                </td>
                {{-- Date --}}
                <td class="px-4 py-4 text-xs text-gray-400">
                    {{ $user->created_at->format('d M Y') }}
                </td>
                {{-- Actions --}}
                <td class="px-4 py-4">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.users.show', $user->id) }}"
                           class="text-xs font-semibold text-gray-600 hover:text-brand-blue transition-colors px-2 py-1 rounded-lg hover:bg-blue-50">
                            Detail
                        </a>
                        <a href="{{ route('admin.users.edit', $user->id) }}"
                           class="text-xs font-semibold transition-colors px-2 py-1 rounded-lg hover:bg-blue-50"
                           style="color:#3f749c">
                            Edit
                        </a>
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                              onsubmit="return confirm('Hapus pengguna {{ $user->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="text-xs font-semibold px-2 py-1 rounded-lg hover:bg-red-50 transition-colors"
                                    style="color:#EF4444">
                                Hapus
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-12 text-gray-400 text-sm">Tidak ada pengguna yang sesuai filter.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    @if($users->hasPages())
    <div class="border-t border-gray-100 px-5 py-4">
        {{ $users->links() }}
    </div>
    @endif
</div>

@endsection
