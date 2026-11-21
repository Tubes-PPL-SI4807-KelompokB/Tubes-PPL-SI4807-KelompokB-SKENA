@extends('layouts.app')
@section('title', 'Akun Terverifikasi — SKENA')

{{-- TIM-A ta-3 keadaan berhasil — persis Claude Design. --}}

@section('content')
<div class="flex justify-center pt-14">
    <div class="w-full max-w-[460px] bg-white border border-border rounded-3xl shadow-card px-6 py-12 sm:px-10 flex flex-col items-center text-center gap-3.5">
        <span class="w-[72px] h-[72px] mt-3 rounded-full bg-success-container text-on-success-container grid place-items-center"><span class="msr" style="font-size:36px">check</span></span>
        <h1 class="mt-2 text-[24px] leading-[1.25] tracking-[-0.01em] font-bold">Akun Mahasiswa Terverifikasi</h1>
        <p class="max-w-[320px] text-[15px] leading-normal text-on-surface-muted">Selamat datang, {{ strtok($user->name, ' ') }}. Kamu sudah bisa mencari dan menawarkan jasa di SKENA.</p>
        <a href="{{ route('dashboard') }}" class="h-12 px-7 mt-4 rounded-full bg-primary hover:bg-primary-hover text-white flex items-center text-[15px] font-bold transition-colors">Lanjut ke Beranda</a>
    </div>
</div>
@endsection
