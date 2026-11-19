@extends('layouts.app')
@section('title', 'Masuk ke SKENA — Marketplace Jasa Kreatif Mahasiswa')

@section('content')
<div class="min-h-[calc(100vh-8rem)] flex items-center justify-center py-12 px-4 sm:px-6" style="background:#F8FAFC">
    <div class="w-full max-w-md">

        {{-- Brand Mark --}}
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center border-2 border-slate-900 bg-white shadow-[2px_2px_0px_0px_#0F2942]">
                    <span class="text-slate-950 font-black text-sm">S</span>
                </div>
                <span class="text-xl font-extrabold tracking-tight text-slate-950">SKENA</span>
            </a>
            <h1 class="text-2xl font-black text-slate-950 tracking-tight">Masuk ke Akun Anda</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Platform marketplace jasa kreatif mahasiswa Indonesia</p>
        </div>

        {{-- Main Login Card --}}
        <div class="bg-white rounded-3xl border-2 border-slate-900 p-6 sm:p-8 shadow-[4px_4px_0px_0px_#0F2942]">

            @if($errors->any())
            <div class="mb-5 bg-red-50 border border-red-200 rounded-2xl p-4">
                <ul class="text-xs text-red-700 space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:#EB2E33"></span>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                {{-- Email Input --}}
                <div>
                    <label for="email" class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Email
                    </label>
                    <input type="email" id="email" name="email"
                           value="{{ old('email') }}"
                           placeholder="nama@kampus.ac.id atau email@klien.com"
                           required autocomplete="email" autofocus
                           class="w-full border-2 border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 outline-none transition-all focus:border-slate-950 bg-white font-medium">
                </div>

                {{-- Password Input --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-700">
                            Password
                        </label>
                        <a href="#" class="text-xs font-semibold text-slate-400 hover:text-slate-900">Lupa sandi?</a>
                    </div>
                    <div class="relative" x-data="{ show: false }">
                        <input :type="show ? 'text' : 'password'" id="password" name="password"
                               placeholder="Minimal 8 karakter"
                               required autocomplete="current-password"
                               class="w-full border-2 border-slate-200 rounded-xl px-4 py-2.5 pr-11 text-sm text-slate-900 placeholder-slate-400 outline-none transition-all focus:border-slate-950 bg-white font-medium">
                        <button type="button" @click="show = !show" class="absolute right-3 top-3 text-slate-400 hover:text-slate-600">
                            <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" id="remember" name="remember"
                           class="w-4 h-4 rounded border-slate-300 text-brand-blue focus:ring-0 cursor-pointer">
                    <label for="remember" class="text-xs font-medium text-slate-600 cursor-pointer select-none">
                        Tetap masuk selama 30 hari
                    </label>
                </div>

                {{-- Submit Button --}}
                <button type="submit"
                        class="w-full py-3 rounded-xl text-sm font-bold text-white transition-transform active:translate-y-0.5 shadow-sm mt-2"
                        style="background:#2C99D6">
                    Masuk Sekarang
                </button>
            </form>

            {{-- 1-Click Quick Demo Autofill (Developer & Presentation Friendly) --}}
            <div class="mt-6 pt-5 border-t border-slate-100" x-data>
                <p class="text-[10px] font-mono font-bold text-slate-400 uppercase tracking-wider text-center mb-2">
                    Akun Uji Coba Cepat (1-Klik Isi):
                </p>
                <div class="grid grid-cols-3 gap-1.5 text-[11px]">
                    <button type="button"
                            @click="document.getElementById('email').value = 'admin@skena.id'; document.getElementById('password').value = 'password123';"
                            class="px-2 py-1.5 rounded-lg border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold transition-colors text-center">
                        Admin
                    </button>
                    <button type="button"
                            @click="document.getElementById('email').value = 'earliyanza@student.telkomuniversity.ac.id'; document.getElementById('password').value = 'password123';"
                            class="px-2 py-1.5 rounded-lg border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold transition-colors text-center">
                        Freelancer
                    </button>
                    <button type="button"
                            @click="document.getElementById('email').value = 'rizal@kreatifstudio.com'; document.getElementById('password').value = 'password123';"
                            class="px-2 py-1.5 rounded-lg border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold transition-colors text-center">
                        Klien
                    </button>
                </div>
            </div>

            {{-- Footer Register Link --}}
            <div class="mt-6 pt-4 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-600">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-bold underline hover:text-brand-blue" style="color:#2C99D6">
                        Daftar gratis di sini →
                    </a>
                </p>
            </div>
        </div>

    </div>
</div>
@endsection
