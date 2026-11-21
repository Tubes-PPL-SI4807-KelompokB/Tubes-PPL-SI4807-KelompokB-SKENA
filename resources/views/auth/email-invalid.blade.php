@extends('layouts.app')
@section('title', 'Email Kampus Tidak Valid — SKENA')

{{-- TIM-A ta-3 keadaan gagal — persis Claude Design. --}}

@section('content')
<div class="flex justify-center pt-14">
    <div class="w-full max-w-[460px] bg-white border border-border rounded-3xl shadow-card px-6 py-12 sm:px-10 flex flex-col items-center text-center gap-3.5">
        <span class="w-[72px] h-[72px] mt-3 rounded-full bg-error-container text-on-error-container grid place-items-center"><span class="msr" style="font-size:36px">mail</span></span>
        <h1 class="mt-2 text-[24px] leading-[1.25] tracking-[-0.01em] font-bold">Email kampus tidak valid</h1>
        <p class="max-w-[320px] text-[15px] leading-normal text-on-surface-muted">Gunakan email kampus yang berakhiran {{ \App\Http\Controllers\Auth\RegisterController::CAMPUS_DOMAIN }}</p>
        <a href="{{ route('register') }}" class="h-12 px-7 mt-4 rounded-full border border-border bg-white text-on-surface flex items-center text-[15px] font-bold hover:border-secondary transition-colors">Ubah Email</a>
    </div>
</div>
@endsection
