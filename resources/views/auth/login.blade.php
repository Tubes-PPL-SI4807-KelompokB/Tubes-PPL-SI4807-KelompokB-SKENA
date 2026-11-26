@extends('layouts.app')
@section('title', 'Masuk — SKENA')

{{--
    TIM-B Login & Logout (FR-02) — persis Claude Design "TIM-B Login":
    tb-1 login, tb-2 login gagal & sesi berakhir. Konfirmasi keluar (tb-3) ada di layouts/sheet.
--}}

@php
    $failed  = $errors->has('email') || $errors->has('password');
    // Sesi berakhir (token kedaluwarsa) dibedakan dari tamu yang diarahkan ke login dari halaman terkunci.
    $sessionEnded = (bool) session('session_expired');
    $needLogin    = ! $sessionEnded && session()->has('url.intended');
    $expired      = $sessionEnded || $needLogin;
    $blocked = $errors->has('email') && str_contains($errors->first('email'), 'dinonaktifkan');
@endphp

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-card-gap mt-2">

    @include('auth._brand-panel', ['photo' => 'hero-tutor.jpg', 'alt' => 'Mahasiswa di kampus'])

    <div class="bg-white border border-border rounded-3xl shadow-card px-6 py-10 sm:p-14 flex flex-col justify-center gap-5 w-full">
        @if($expired)
            <div class="flex gap-3 items-center px-[18px] py-3.5 rounded-[18px] bg-warning-container text-on-warning-container text-[14px] leading-[1.4] font-semibold" role="status">
                @if($sessionEnded)
                    <span class="msr" style="font-size:22px">schedule</span>Sesi berakhir karena tidak aktif. Masuk lagi untuk melanjutkan.
                @else
                    <span class="msr" style="font-size:22px">login</span>Masuk dulu untuk melanjutkan. Setelah masuk, kamu kembali ke halaman tadi.
                @endif
            </div>
        @endif

        <div>
            @unless($expired)<div class="text-[11px] leading-[1.2] font-bold tracking-[0.06em] text-primary">MASUK</div>@endunless
            <h2 class="{{ $expired ? '' : 'mt-2' }} text-[32px] leading-[1.15] tracking-[-0.02em] font-bold">Masuk ke SKENA</h2>
            @unless($expired)<p class="mt-2 text-[15px] leading-normal text-on-surface-muted">Gunakan email kampus yang sudah terverifikasi.</p>@endunless
        </div>

        <form id="loginForm" method="POST" action="{{ route('login') }}" class="flex flex-col gap-5" data-validate>
            @csrf

            <div class="fld">
                <label for="email">Email Kampus</label>
                <input id="email" name="email" type="email" class="inp" value="{{ old('email') }}" required
                       autocomplete="username" inputmode="email"
                       aria-describedby="email-err" aria-invalid="{{ $failed ? 'true' : 'false' }}"
                       data-msg-required="Masukkan email kampus kamu." data-msg-type="Tulis email lengkap, misalnya nama@student.telkomuniversity.ac.id.">
                <p class="err" id="email-err" hidden><span data-err-text></span></p>
            </div>

            <div class="fld">
                <label for="password">Kata Sandi</label>
                <div class="pw">
                    <input id="password" name="password" type="password" class="inp" required autocomplete="current-password"
                           placeholder="{{ $expired ? 'Masukkan kata sandi' : '' }}"
                           aria-describedby="password-err" aria-invalid="{{ $failed ? 'true' : 'false' }}"
                           data-msg-required="Masukkan kata sandi kamu.">
                    <button type="button" class="eye" data-toggle-pw="password" aria-pressed="false" aria-label="Tampilkan kata sandi">
                        <span class="msr" style="font-size:19px">visibility</span>
                    </button>
                </div>
                <p class="err" id="password-err" @unless($failed) hidden @endunless role="alert">
                    <span class="msr">error</span>
                    <span data-err-text>@if($blocked){{ $errors->first('email') }}@elseif($failed)Email atau kata sandi salah. Periksa lagi penulisannya lalu coba masuk kembali.@endif</span>
                </p>
            </div>

            @unless($expired)
                <div class="flex justify-between items-center">
                    <label class="flex items-center gap-2.5 text-[14px] cursor-pointer">
                        <input type="checkbox" name="remember" value="1" class="w-[18px] h-[18px] m-0 accent-primary" @checked(old('remember'))>
                        Ingat saya
                    </label>
                    <a href="#" class="text-[14px] font-semibold text-primary hover:text-primary-hover">Lupa kata sandi?</a>
                </div>
            @endunless

            <button type="submit" class="h-[52px] rounded-full bg-primary hover:bg-primary-hover text-white text-[15px] font-bold transition-colors {{ $expired ? '' : 'mt-1' }}" data-loading="Memproses…">Masuk</button>
        </form>

        @unless($expired)
            <p class="text-center text-[14px] text-on-surface-muted">Belum punya akun? <a class="font-semibold text-primary hover:text-primary-hover" href="{{ route('register') }}">Daftar</a></p>
        @endunless

        {{-- Sementara: pintasan akun demo (hanya tampil di lingkungan lokal, akun dari UserSeeder) --}}
        @if(app()->environment('local'))
            <div class="border-t border-border pt-5">
                <p class="text-[13px] text-on-surface-muted mb-2.5">Demo cepat (hanya di lokal)</p>
                <div class="flex flex-wrap gap-2">
                    @foreach([
                        ['Admin', 'admin_panel_settings', 'admin@skena.id'],
                        ['Freelancer', 'storefront', 'earliyanza@student.telkomuniversity.ac.id'],
                        ['Mahasiswa (pending)', 'school', 'anindya@student.telkomuniversity.ac.id'],
                        ['Klien', 'person_search', 'rizal@student.telkomuniversity.ac.id'],
                    ] as [$label, $icon, $mail])
                        <button type="button" data-demo="{{ $mail }}"
                                class="h-10 px-4 rounded-full bg-tint text-primary hover:bg-primary hover:text-white flex items-center gap-1.5 text-[14px] font-semibold transition-colors">
                            <span class="msr" style="font-size:18px">{{ $icon }}</span>Masuk sebagai {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@if(app()->environment('local'))
    @push('scripts')
    <script>
        // Pintasan demo: isi akun seeder (password123) lalu langsung masuk
        document.querySelectorAll('[data-demo]').forEach(function (b) {
            b.addEventListener('click', function () {
                document.getElementById('email').value = b.dataset.demo;
                document.getElementById('password').value = 'password123';
                document.getElementById('loginForm').requestSubmit();
            });
        });
    </script>
    @endpush
@endif
