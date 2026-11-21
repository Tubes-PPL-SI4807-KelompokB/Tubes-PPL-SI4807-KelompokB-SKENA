@extends('layouts.app')
@section('title', 'Daftar — SKENA')

{{--
    TIM-A Registrasi Akun (FR-01) — persis Claude Design "TIM-A Registrasi" (ta-1).
    Setelah daftar: verifikasi email kampus (ta-2, auth/verify) lalu berhasil/gagal (ta-3).
--}}

@php
    $val = fn ($f) => old($f, $draft[$f] ?? '');
    $err = fn ($f) => $errors->has($f) ? 'true' : 'false';
@endphp

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-card-gap mt-2">

    @include('auth._brand-panel', ['photo' => 'hero-video.jpg', 'alt' => 'Mahasiswa berkolaborasi', 'minH' => 760, 'photoH' => 300, 'photoTop' => 48])

    <div class="bg-white border border-border rounded-3xl shadow-card px-6 py-10 sm:px-14 sm:py-12 flex flex-col gap-5 w-full">
        <div>
            <div class="text-[11px] leading-[1.2] font-bold tracking-[0.06em] text-primary">DAFTAR AKUN</div>
            <h2 class="mt-2 text-[32px] leading-[1.15] tracking-[-0.02em] font-bold">Buat Akun Mahasiswa</h2>
            <p class="mt-2 text-[15px] leading-normal text-on-surface-muted">Satu akun bisa menjadi penyedia sekaligus pencari jasa.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-5" data-validate>
            @csrf

            <div class="fld">
                <label for="name">Nama Lengkap</label>
                <input id="name" name="name" class="inp" value="{{ $val('name') }}" required maxlength="255" autocomplete="name"
                       aria-describedby="name-err" aria-invalid="{{ $err('name') }}" data-msg-required="Masukkan nama lengkap kamu.">
                <p class="err" id="name-err" @unless($errors->has('name')) hidden @endunless><span data-err-text>{{ $errors->first('name') }}</span></p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-card-gap">
                <div class="fld">
                    <label for="email">Email Kampus</label>
                    <input id="email" name="email" type="email" class="inp" value="{{ $val('email') }}" required maxlength="255"
                           autocomplete="email" inputmode="email" placeholder="nama@student.telkomuniversity.ac.id"
                           aria-describedby="email-err" aria-invalid="{{ $err('email') }}"
                           data-msg-required="Masukkan email kampus kamu." data-msg-type="Tulis email lengkap, misalnya nama@student.telkomuniversity.ac.id.">
                    <p class="err" id="email-err" @unless($errors->has('email')) hidden @endunless><span data-err-text>{{ $errors->first('email') }}</span></p>
                </div>
                <div class="fld">
                    <label for="nim">NIM</label>
                    <input id="nim" name="nim" class="inp" value="{{ $val('nim') }}" required maxlength="50" inputmode="numeric" autocomplete="off"
                           aria-describedby="nim-err" aria-invalid="{{ $err('nim') }}" data-msg-required="Masukkan NIM sesuai data kampus.">
                    <p class="err" id="nim-err" @unless($errors->has('nim')) hidden @endunless><span data-err-text>{{ $errors->first('nim') }}</span></p>
                </div>
            </div>

            <div class="fld">
                <label for="password">Kata Sandi</label>
                <div class="pw">
                    <input id="password" name="password" type="password" class="inp" required minlength="8" autocomplete="new-password"
                           aria-describedby="pw-meter password-err" aria-invalid="{{ $err('password') }}"
                           data-msg-required="Buat kata sandi untuk akunmu." data-msg-short="Kata sandi minimal 8 karakter.">
                    <button type="button" class="eye" data-toggle-pw="password" aria-pressed="false" aria-label="Tampilkan kata sandi">
                        <span class="msr" style="font-size:19px">visibility</span>
                    </button>
                </div>
                <div class="flex items-center gap-1.5" id="pw-meter" data-strength-for="password" aria-live="polite">
                    <i class="flex-1 h-1 rounded-full"></i><i class="flex-1 h-1 rounded-full"></i><i class="flex-1 h-1 rounded-full"></i><i class="flex-1 h-1 rounded-full"></i>
                    <b class="ml-2 w-24 text-right text-[12px] leading-none font-semibold">Belum diisi</b>
                </div>
                <p class="err" id="password-err" @unless($errors->has('password')) hidden @endunless><span data-err-text>{{ $errors->first('password') }}</span></p>
            </div>

            <div class="fld">
                <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="inp" required autocomplete="new-password"
                       data-match="#password" aria-describedby="pwc-err" aria-invalid="false"
                       data-msg-required="Ketik ulang kata sandi kamu." data-msg-match="Belum sama dengan kata sandi di atas.">
                <p class="err" id="pwc-err" hidden><span data-err-text></span></p>
            </div>

            <div class="fld">
                <label class="flex items-center gap-2.5 text-[14px] leading-[1.4] cursor-pointer" style="font-weight:400">
                    <input type="checkbox" name="terms" value="1" required class="w-[18px] h-[18px] m-0 accent-primary" @checked(old('terms'))
                           aria-describedby="terms-err" aria-invalid="{{ $err('terms') }}"
                           data-msg-required="Centang persetujuan Syarat & Ketentuan untuk melanjutkan.">
                    Saya setuju dengan Syarat &amp; Ketentuan
                </label>
                <p class="err" id="terms-err" @unless($errors->has('terms')) hidden @endunless><span data-err-text>{{ $errors->first('terms') }}</span></p>
            </div>

            <button type="submit" class="h-[52px] mt-1 rounded-full bg-primary hover:bg-primary-hover text-white text-[15px] font-bold transition-colors" data-loading="Membuat akun…">Daftar Sekarang</button>
        </form>

        <p class="text-center text-[14px] text-on-surface-muted">Sudah punya akun? <a class="font-semibold text-primary hover:text-primary-hover" href="{{ route('login') }}">Masuk</a></p>
    </div>
</div>
@endsection
