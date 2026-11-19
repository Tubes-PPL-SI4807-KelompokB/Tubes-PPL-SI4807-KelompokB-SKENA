@extends('layouts.app')
@section('title', 'Masuk — SKENA')

@section('content')
<div class="min-h-screen flex" style="background:#F8FAFC">

    {{-- Left Panel: Branding --}}
    <div class="hidden lg:flex lg:w-5/12 flex-col justify-between p-12 relative overflow-hidden" style="background:linear-gradient(135deg,#0F2942 0%,#2C99D6 100%)">
        {{-- Decorative circles --}}
        <div class="absolute -top-20 -right-20 w-80 h-80 rounded-full opacity-10" style="background:#EB2E33"></div>
        <div class="absolute -bottom-16 -left-16 w-64 h-64 rounded-full opacity-10" style="background:#2C99D6"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-16">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-white/20 backdrop-blur">
                    <span class="text-white font-black text-lg">S</span>
                </div>
                <span class="text-white font-black text-xl tracking-tight">SKENA</span>
            </div>

            <h1 class="text-4xl font-black text-white leading-tight mb-4">
                Jasa Kreatif<br>
                <span style="color:#2C99D6" class="bg-white/90 bg-clip-text">Mahasiswa Berbakat</span>
            </h1>
            <p class="text-blue-200 text-sm leading-relaxed max-w-xs">
                Temukan freelancer mahasiswa kreatif terbaik untuk proyek desain, ilustrasi, video, coding, dan lainnya.
            </p>
        </div>

        {{-- Testimonial Card --}}
        <div class="relative z-10 bg-white/10 backdrop-blur border border-white/20 rounded-2xl p-5">
            <p class="text-white/90 text-sm italic mb-3">
                "SKENA membantu saya mendapatkan proyek nyata sambil kuliah. Klien saya sangat puas!"
            </p>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-xs" style="background:#EB2E33">A</div>
                <div>
                    <p class="text-white text-xs font-semibold">Anindya P.</p>
                    <p class="text-blue-200 text-xs">UI/UX Designer · Telkom University</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Panel: Form --}}
    <div class="flex-1 flex items-center justify-center p-6 lg:p-12">
        <div class="w-full max-w-md">

            {{-- Mobile Logo --}}
            <div class="flex items-center gap-2 mb-8 lg:hidden">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:#2C99D6">
                    <span class="text-white font-black text-sm">S</span>
                </div>
                <span class="text-lg font-black text-gray-900">SKENA</span>
            </div>

            <h2 class="text-2xl font-black text-gray-900 mb-1">Selamat datang kembali</h2>
            <p class="text-sm text-gray-500 mb-8">Masuk untuk melanjutkan ke SKENA.</p>

            {{-- Error Bag --}}
            @if($errors->any())
            <div class="mb-5 bg-red-50 border border-red-200 rounded-xl p-4">
                <ul class="text-sm text-red-700 space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:#EB2E33"></span>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                    <input type="email" id="email" name="email"
                           value="{{ old('email') }}"
                           placeholder="email@universitas.ac.id"
                           required autocomplete="email" autofocus
                           class="w-full border rounded-xl px-4 py-3 text-sm text-gray-900 placeholder-gray-400 transition-all outline-none focus:ring-2 @error('email') border-red-400 bg-red-50 focus:ring-red-200 @else border-gray-200 bg-white focus:border-blue-400 focus:ring-blue-100 @enderror">
                </div>

                {{-- Password --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="text-sm font-semibold text-gray-700">Password</label>
                        <a href="#" class="text-xs font-medium hover:underline" style="color:#2C99D6">Lupa password?</a>
                    </div>
                    <div class="relative" x-data="{ show: false }">
                        <input :type="show ? 'text' : 'password'" id="password" name="password"
                               placeholder="Minimal 8 karakter"
                               required autocomplete="current-password"
                               class="w-full border rounded-xl px-4 py-3 text-sm text-gray-900 placeholder-gray-400 transition-all outline-none focus:ring-2 @error('password') border-red-400 bg-red-50 focus:ring-red-200 @else border-gray-200 bg-white focus:border-blue-400 focus:ring-blue-100 @enderror pr-11">
                        <button type="button" @click="show = !show" class="absolute right-3 top-3.5 text-gray-400 hover:text-gray-600">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Remember me --}}
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="remember" name="remember"
                           class="w-4 h-4 rounded border-gray-300 text-blue-500 focus:ring-blue-200 cursor-pointer">
                    <label for="remember" class="text-sm text-gray-600 cursor-pointer">Ingat saya selama 30 hari</label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full py-3 rounded-xl text-sm font-bold text-white transition-all hover:opacity-90 active:scale-[0.98] shadow-sm"
                        style="background:#2C99D6">
                    Masuk ke SKENA
                </button>
            </form>

            {{-- Divider --}}
            <div class="flex items-center gap-3 my-6">
                <div class="flex-1 h-px bg-gray-200"></div>
                <span class="text-xs text-gray-400 font-medium">Belum punya akun?</span>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>

            <a href="{{ route('register') }}"
               class="flex items-center justify-center w-full py-3 rounded-xl text-sm font-bold border-2 border-gray-200 text-gray-700 hover:border-blue-300 hover:text-brand-blue transition-all">
                Daftar sebagai Freelancer / Klien
            </a>

            <p class="text-center text-xs text-gray-400 mt-6">
                Dengan masuk, Anda menyetujui
                <a href="#" class="underline hover:text-brand-blue">Syarat & Ketentuan</a>
                dan <a href="#" class="underline hover:text-brand-blue">Kebijakan Privasi</a> SKENA.
            </p>
        </div>
    </div>
</div>
@endsection
