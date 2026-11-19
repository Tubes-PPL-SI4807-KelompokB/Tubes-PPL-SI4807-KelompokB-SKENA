@extends('layouts.admin')
@section('title', "Detail Pengguna: {$user->name} — SKENA")
@section('page-title', 'Detail Pengguna')
@section('page-subtitle', "Profil lengkap: {$user->name}")

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-5">

        {{-- Header --}}
        <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-100">
            <img src="{{ $user->avatar_url }}" class="w-16 h-16 rounded-full object-cover ring-2 ring-gray-100">
            <div class="flex-1">
                <div class="flex items-center gap-2 flex-wrap">
                    <h2 class="text-lg font-black text-gray-900">{{ $user->name }}</h2>
                    @if($user->isVerifiedStudent())
                        <span class="text-xs font-bold text-white px-2 py-0.5 rounded-full" style="background:#2C99D6">✓ Terverifikasi</span>
                    @endif
                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full {{ $user->status === 'active' ? 'bg-green-100 text-green-700' : ($user->status === 'banned' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600') }}">
                        {{ ucfirst($user->status) }}
                    </span>
                </div>
                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                <p class="text-sm text-gray-500">{{ $user->role?->display_name ?? '—' }} · Bergabung {{ $user->created_at->format('d M Y') }}</p>
            </div>
            <a href="{{ route('admin.users.edit', $user->id) }}"
               class="px-4 py-2 rounded-xl text-sm font-bold text-white transition-all hover:opacity-90"
               style="background:#2C99D6">
                Edit Pengguna
            </a>
        </div>

        {{-- Data Grid --}}
        <div class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Nomor HP</p>
                <p class="text-gray-700">{{ $user->phone ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Status Verifikasi KTM</p>
                @php
                    $vs = $user->student_verification_status;
                    $vClass = match($vs) { 'verified' => 'text-green-600', 'pending' => 'text-amber-600', 'rejected' => 'text-red-600', default => 'text-gray-500' };
                    $vLabel = match($vs) { 'verified' => '✓ Terverifikasi', 'pending' => '⏳ Menunggu Review', 'rejected' => '✕ Ditolak', default => '— Belum Mengajukan' };
                @endphp
                <p class="font-semibold {{ $vClass }}">{{ $vLabel }}</p>
            </div>
            @if($user->nim)
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">NIM</p>
                <p class="text-gray-700">{{ $user->nim }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Universitas</p>
                <p class="text-gray-700">{{ $user->university }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Fakultas</p>
                <p class="text-gray-700">{{ $user->faculty ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Program Studi</p>
                <p class="text-gray-700">{{ $user->major ?? '—' }}</p>
            </div>
            @endif
            @if($user->verification_notes)
            <div class="col-span-2">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Catatan Verifikasi</p>
                <p class="text-gray-700">{{ $user->verification_notes }}</p>
            </div>
            @endif
            @if($user->verified_at)
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Diverifikasi Pada</p>
                <p class="text-gray-700">{{ $user->verified_at->format('d M Y, H:i') }}</p>
            </div>
            @endif
        </div>

        {{-- File KTM --}}
        @if($user->ktm_path)
        <div class="mt-5 pt-5 border-t border-gray-100">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">Dokumen KTM</p>
            <a href="{{ asset('storage/' . $user->ktm_path) }}" target="_blank"
               class="inline-flex items-center gap-2 text-sm font-semibold transition-colors hover:underline"
               style="color:#2C99D6">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13l-3 3m0 0l-3-3m3 3V8m0 13a9 9 0 110-18 9 9 0 010 18z"/></svg>
                Lihat / Unduh Dokumen KTM
            </a>
        </div>
        @endif

        {{-- Quick Verify Actions (jika pending) --}}
        @if($user->student_verification_status === 'pending')
        <div class="mt-5 pt-5 border-t border-gray-100 flex gap-3">
            <form method="POST" action="{{ route('admin.users.verify', $user->id) }}">
                @csrf
                <input type="hidden" name="status" value="verified">
                <button type="submit"
                        class="px-5 py-2.5 rounded-xl text-sm font-bold text-white transition-all hover:opacity-90"
                        style="background:#2C99D6">
                    ✓ Setujui Verifikasi
                </button>
            </form>
            <form method="POST" action="{{ route('admin.users.verify', $user->id) }}">
                @csrf
                <input type="hidden" name="status" value="rejected">
                <button type="submit"
                        class="px-5 py-2.5 rounded-xl text-sm font-bold border-2 transition-all hover:bg-red-50"
                        style="color:#EB2E33;border-color:#EB2E33">
                    ✕ Tolak Verifikasi
                </button>
            </form>
        </div>
        @endif
    </div>

    {{-- Profil --}}
    @if($user->profile)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-black text-gray-900 uppercase tracking-wide mb-4">Profil Publik</h3>
        <div class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Headline</p>
                <p class="text-gray-700">{{ $user->profile->headline ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Kota</p>
                <p class="text-gray-700">{{ $user->profile->city ?? '—' }}</p>
            </div>
            @if($user->profile->hourly_rate)
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Rate per Jam</p>
                <p class="font-semibold" style="color:#2C99D6">Rp {{ number_format($user->profile->hourly_rate, 0, ',', '.') }}</p>
            </div>
            @endif
            @if($user->profile->bio)
            <div class="col-span-2">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Bio</p>
                <p class="text-gray-700 text-sm">{{ $user->profile->bio }}</p>
            </div>
            @endif
        </div>
    </div>
    @endif

    <div class="mt-4">
        <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold text-gray-500 hover:text-brand-blue transition-colors">
            ← Kembali ke Daftar Pengguna
        </a>
    </div>
</div>
@endsection

