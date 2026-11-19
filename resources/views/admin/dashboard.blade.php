@extends('layouts.admin')
@section('title', 'Dashboard Admin — SKENA')
@section('page-title', 'Dashboard Admin')
@section('page-subtitle', 'Statistik platform dan antrian verifikasi mahasiswa')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Total User</p>
        <p class="text-3xl font-black" style="color:#3f749c">{{ $stats['total_users'] }}</p>
        <p class="text-xs text-gray-400 mt-1">Terdaftar</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Freelancer</p>
        <p class="text-3xl font-black text-gray-900">{{ $stats['total_freelancers'] }}</p>
        <p class="text-xs text-gray-400 mt-1">Mahasiswa</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Klien</p>
        <p class="text-3xl font-black text-gray-900">{{ $stats['total_clients'] }}</p>
        <p class="text-xs text-gray-400 mt-1">Pemberi kerja</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Terverifikasi</p>
        <p class="text-3xl font-black text-green-600">{{ $stats['verified_students'] }}</p>
        <p class="text-xs text-gray-400 mt-1">Mahasiswa aktif</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm relative overflow-hidden">
        @if($stats['pending_verifications'] > 0)
        <div class="absolute top-3 right-3 w-2 h-2 rounded-full" style="background:#EF4444"></div>
        @endif
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Antrean KTM</p>
        <p class="text-3xl font-black" style="color:{{ $stats['pending_verifications'] > 0 ? '#EF4444' : '#6B7280' }}">{{ $stats['pending_verifications'] }}</p>
        <p class="text-xs text-gray-400 mt-1">Menunggu review</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Antrian Verifikasi Mahasiswa --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-sm font-black text-gray-900">Antrian Verifikasi KTM</h2>
                <p class="text-xs text-gray-400 mt-0.5">Mahasiswa menunggu persetujuan identitas</p>
            </div>
            @if($stats['pending_verifications'] > 0)
            <span class="text-xs font-bold text-white px-2.5 py-1 rounded-full" style="background:#EF4444">
                {{ $stats['pending_verifications'] }} pending
            </span>
            @endif
        </div>

        @forelse($pendingStudents as $student)
        <div class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 mb-3 hover:border-blue-200 transition-colors">
            <img src="{{ $student->avatar_url }}" class="w-10 h-10 rounded-full object-cover shrink-0">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-900 truncate">{{ $student->name }}</p>
                <p class="text-xs text-gray-500">{{ $student->nim }} · {{ $student->university }}</p>
                <p class="text-xs text-gray-400">{{ $student->major }}</p>
            </div>
            <div class="flex flex-col gap-1.5 shrink-0">
                <form method="POST" action="{{ route('admin.users.verify', $student->id) }}">
                    @csrf
                    <input type="hidden" name="status" value="verified">
                    <input type="hidden" name="notes" value="KTM valid, identitas mahasiswa terverifikasi.">
                    <button type="submit"
                            class="text-xs font-bold text-white px-3 py-1 rounded-lg transition-all hover:opacity-90"
                            style="background:#3f749c">
                        Setujui
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.users.verify', $student->id) }}">
                    @csrf
                    <input type="hidden" name="status" value="rejected">
                    <input type="hidden" name="notes" value="Dokumen KTM tidak valid atau buram, mohon upload ulang.">
                    <button type="submit"
                            class="text-xs font-bold px-3 py-1 rounded-lg border border-red-300 transition-all hover:bg-red-50"
                            style="color:#EF4444">
                        Tolak
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="text-center py-8">
            <div class="w-12 h-12 mx-auto mb-3 rounded-full flex items-center justify-center bg-green-50">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-sm font-semibold text-gray-600">Semua antrean selesai!</p>
            <p class="text-xs text-gray-400 mt-1">Tidak ada mahasiswa yang menunggu verifikasi.</p>
        </div>
        @endforelse

        @if($pendingStudents->count() > 0)
        <a href="{{ route('admin.users.index') }}?verification=pending"
           class="block text-center text-xs font-semibold mt-3 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:border-blue-300 hover:text-brand-blue transition-colors">
            Lihat Semua Antrian →
        </a>
        @endif
    </div>

    {{-- Pengguna Baru --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-sm font-black text-gray-900">Pengguna Terbaru</h2>
                <p class="text-xs text-gray-400 mt-0.5">5 pendaftaran akun terkini</p>
            </div>
            <a href="{{ route('admin.users.index') }}"
               class="text-xs font-semibold hover:underline" style="color:#3f749c">Semua Pengguna →</a>
        </div>

        @forelse($recentUsers as $user)
        <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors">
            <img src="{{ $user->avatar_url }}" class="w-9 h-9 rounded-full object-cover shrink-0">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $user->name }}</p>
                    @if($user->isVerifiedStudent())
                        <svg class="w-3.5 h-3.5 shrink-0" style="color:#3f749c" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    @endif
                </div>
                <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
            </div>
            <div class="flex flex-col items-end gap-1 shrink-0">
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                    {{ $user->isAdmin() ? 'bg-purple-100 text-purple-700' : ($user->isFreelancer() ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700') }}">
                    {{ $user->role?->display_name ?? 'Unknown' }}
                </span>
                <span class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</span>
            </div>
        </div>
        @empty
        <p class="text-sm text-gray-400 text-center py-8">Belum ada pengguna terdaftar.</p>
        @endforelse
    </div>
</div>

@endsection
