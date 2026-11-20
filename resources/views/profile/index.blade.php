@extends('layouts.app')
@section('title', 'Profil Saya — SKENA')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Profile Banner & Header Card --}}
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mb-6">
        {{-- Banner Gradient --}}
        <div class="h-36 sm:h-44 w-full relative" style="background:linear-gradient(135deg,#0F172A 0%,#3f749c 100%)">
            <div class="absolute -bottom-8 right-8 w-40 h-40 rounded-full opacity-20 pointer-events-none" style="background:#E0ECF6"></div>
        </div>

        {{-- Profile Details --}}
        <div class="px-6 sm:px-8 pb-8 pt-0 relative">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between -mt-16 sm:-mt-20 gap-4 mb-6">
                <div class="flex items-end gap-4">
                    <img src="{{ $user->avatar_url }}"
                         alt="{{ $user->name }}"
                         class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl object-cover ring-4 ring-white shadow-lg bg-white shrink-0">
                    <div class="mb-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h1 class="text-2xl font-black text-gray-900">{{ $user->name }}</h1>
                            @if($user->isVerifiedStudent())
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-white px-2.5 py-0.5 rounded-full" style="background:#3f749c">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    Mahasiswa Terverifikasi
                                </span>
                            @elseif($user->student_verification_status === 'pending')
                                <span class="inline-flex items-center text-xs font-bold text-amber-800 bg-amber-100 px-2.5 py-0.5 rounded-full">
                                    ⏳ Verifikasi Pending
                                </span>
                            @endif
                        </div>
                        <p class="text-xs sm:text-sm text-gray-500 mt-0.5">
                            {{ $user->role?->display_name ?? 'Pengguna' }} &middot; {{ $user->email }}
                        </p>
                    </div>
                </div>

                <a href="{{ route('profile.edit', $user->id) }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-white transition-all hover:opacity-90 shadow-sm shrink-0"
                   style="background:#3f749c">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    Edit Profil
                </a>
            </div>

            {{-- Headline & Bio --}}
            @if($user->profile?->headline)
                <p class="text-sm font-bold text-gray-800 mb-2">{{ $user->profile->headline }}</p>
            @endif
            @if($user->profile?->bio)
                <p class="text-xs sm:text-sm text-gray-600 leading-relaxed mb-6 max-w-3xl">{{ $user->profile->bio }}</p>
            @endif

            {{-- Meta Badges --}}
            <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500 pt-4 border-t border-gray-100">
                @if($user->profile?->city)
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $user->profile->city }}
                    </span>
                @endif
                @if($user->phone)
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        {{ $user->phone }}
                    </span>
                @endif
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Bergabung {{ $user->created_at->format('M Y') }}
                </span>
                @if($user->profile?->hourly_rate)
                    <span class="flex items-center gap-1 font-bold" style="color:#3f749c">
                        Rp {{ number_format($user->profile->hourly_rate, 0, ',', '.') }} / jam
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- Data Mahasiswa Info Card (Jika Freelancer) --}}
    @if($user->isFreelancer())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-black text-gray-900 uppercase tracking-wide">Data Kemahasiswaan & Identitas</h2>
            @if($user->isVerifiedStudent())
                <span class="text-xs font-bold text-green-700 bg-green-50 px-2.5 py-1 rounded-full border border-green-200">
                    Status: Valid & Aktif
                </span>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
            <div class="p-3 bg-gray-50 rounded-xl">
                <span class="text-gray-400 block mb-1">Universitas / Kampus</span>
                <span class="font-bold text-gray-900">{{ $user->university ?? 'Belum diisi' }}</span>
            </div>
            <div class="p-3 bg-gray-50 rounded-xl">
                <span class="text-gray-400 block mb-1">NIM (Nomor Mahasiswa)</span>
                <span class="font-bold text-gray-900">{{ $user->nim ?? 'Belum diisi' }}</span>
            </div>
            <div class="p-3 bg-gray-50 rounded-xl">
                <span class="text-gray-400 block mb-1">Fakultas</span>
                <span class="font-bold text-gray-900">{{ $user->faculty ?? '—' }}</span>
            </div>
            <div class="p-3 bg-gray-50 rounded-xl">
                <span class="text-gray-400 block mb-1">Program Studi</span>
                <span class="font-bold text-gray-900">{{ $user->major ?? '—' }}</span>
            </div>
        </div>

        @if($user->verification_notes)
        <div class="mt-4 p-3 rounded-xl bg-blue-50/70 border border-blue-100 text-xs text-blue-900">
            <strong>Catatan Verifikator:</strong> {{ $user->verification_notes }}
        </div>
        @endif
    </div>
    @endif

</div>
@endsection
