@extends('layouts.app')
@section('title', 'Verifikasi Email Kampus — SKENA')

{{-- TIM-A ta-2 Verifikasi Mahasiswa — persis Claude Design (langkah 2 dari 3). --}}

@php
    $steps = ['Data Akun', 'Verifikasi Email Kampus', 'Selesai'];
    $code  = str_split(str_pad((string) old('code', ''), 6));
@endphp

@section('content')
<div class="flex justify-center pt-10">
    <div class="w-full max-w-[560px] bg-white border border-border rounded-3xl shadow-card px-6 py-9 sm:px-12 sm:py-11 flex flex-col gap-7">

        {{-- Stepper --}}
        <ol class="flex items-start" aria-label="Langkah pendaftaran">
            @foreach($steps as $i => $label)
                <li class="flex-1 flex flex-col items-center gap-2.5 relative" @if($i === 1) aria-current="step" @endif>
                    @if($i > 0)
                        <span class="absolute top-4 h-0.5 {{ $i === 1 ? 'bg-primary' : 'bg-border' }}" style="left:-50%;right:50%"></span>
                    @endif
                    <span class="relative w-[34px] h-[34px] rounded-full grid place-items-center text-[14px] font-bold border
                        {{ $i === 0 ? 'bg-primary text-white border-primary' : ($i === 1 ? 'bg-tint text-primary border-primary' : 'bg-white text-on-surface-muted border-border') }}">
                        @if($i === 0)<span class="msr" style="font-size:18px">check</span>@else{{ $i + 1 }}@endif
                    </span>
                    <span class="text-[13px] leading-[1.3] font-semibold text-center {{ $i === 2 ? 'text-on-surface-muted' : 'text-on-surface' }}">{{ $label }}</span>
                </li>
            @endforeach
        </ol>

        <div class="text-center">
            <div class="text-[11px] leading-[1.2] font-bold tracking-[0.06em] text-primary">LANGKAH 2 DARI 3</div>
            <h1 class="mt-2 text-[32px] leading-[1.15] tracking-[-0.02em] font-bold">Verifikasi Email Kampus</h1>
            <p class="mt-2.5 text-[15px] leading-normal text-on-surface-muted break-words">Kami mengirim kode 6 digit ke {{ $user->email }}</p>
        </div>

        <form method="POST" action="{{ route('verification.verify') }}" class="flex flex-col gap-7" id="otpForm">
            @csrf
            <input type="hidden" name="code" id="otpCode" value="{{ old('code') }}">

            <div class="flex flex-col items-center gap-2">
                <div class="flex justify-center gap-2 sm:gap-2.5" role="group" aria-label="Kode verifikasi 6 digit">
                    @for($i = 0; $i < 6; $i++)
                        <input class="otp w-11 h-14 sm:w-14 sm:h-16 rounded-[18px] border bg-white text-center text-[26px] font-bold text-on-surface outline-none focus:border-primary focus:ring-[3px] focus:ring-tint-strong {{ $errors->has('code') ? 'border-error' : 'border-border' }}"
                               inputmode="numeric" maxlength="1" autocomplete="{{ $i === 0 ? 'one-time-code' : 'off' }}"
                               aria-label="Digit {{ $i + 1 }}" value="{{ trim($code[$i] ?? '') }}" @if($i === 0) autofocus @endif>
                    @endfor
                </div>
                @error('code')<p class="text-[13px] text-[#B91C1C] text-center" role="alert">{{ $message }}</p>@enderror
            </div>

            <div class="flex justify-center items-center gap-2.5 text-[14px] text-on-surface-muted">
                Kode berlaku <span class="font-semibold text-on-surface" id="otpTimer">00:{{ str_pad((string) $remaining, 2, '0', STR_PAD_LEFT) }}</span>
                <span class="w-1 h-1 rounded-full bg-[#CBD5E1]"></span>
                <button type="submit" form="resendForm" id="resendBtn" class="font-semibold {{ $remaining ? 'text-soft' : 'text-primary' }}"
                        style="color:{{ $remaining ? '#94A3B8' : '#3F749C' }}" @if($remaining) aria-disabled="true" @endif>Kirim Ulang Kode</button>
            </div>

            <div class="fld">
                <label for="nim">Konfirmasi NIM</label>
                <input id="nim" name="nim" class="inp" value="{{ old('nim') }}" required maxlength="50" inputmode="numeric" autocomplete="off"
                       aria-describedby="nim-hint" aria-invalid="{{ $errors->has('nim') ? 'true' : 'false' }}">
                @error('nim')
                    <p class="err" role="alert"><span>{{ $message }}</span></p>
                @else
                    <p class="hint" id="nim-hint">Pastikan NIM sama dengan yang tercatat di kampus.</p>
                @enderror
            </div>

            <button type="submit" class="h-[52px] rounded-full bg-primary hover:bg-primary-hover text-white text-[15px] font-bold transition-colors">Verifikasi</button>
        </form>
        <form method="POST" action="{{ route('verification.resend') }}" id="resendForm" hidden>@csrf</form>

        @if(app()->environment('local') && config('mail.default') === 'log')
            <p class="text-center text-[12px] text-on-surface-muted -mt-3">Mode lokal: email kode tercatat di storage/logs/laravel.log</p>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Kotak OTP: pindah otomatis, Backspace mundur, tempel 6 digit sekaligus; hitung mundur masa berlaku kode.
    (function () {
        var boxes = Array.prototype.slice.call(document.querySelectorAll('.otp'));
        var hidden = document.getElementById('otpCode');
        function sync() { hidden.value = boxes.map(function (b) { return b.value; }).join(''); }
        boxes.forEach(function (b, i) {
            b.addEventListener('input', function () {
                b.value = b.value.replace(/\D/g, '').slice(-1);
                sync();
                if (b.value && boxes[i + 1]) boxes[i + 1].focus();
            });
            b.addEventListener('keydown', function (e) {
                if (e.key === 'Backspace' && !b.value && boxes[i - 1]) { boxes[i - 1].focus(); boxes[i - 1].value = ''; sync(); }
            });
            b.addEventListener('paste', function (e) {
                var d = (e.clipboardData.getData('text') || '').replace(/\D/g, '').slice(0, 6);
                if (!d) return;
                e.preventDefault();
                boxes.forEach(function (x, k) { x.value = d[k] || ''; });
                sync();
                (boxes[d.length] || boxes[5]).focus();
            });
        });
        sync();

        var t = {{ (int) $remaining }}, timer = document.getElementById('otpTimer'), resend = document.getElementById('resendBtn');
        function draw() {
            timer.textContent = '00:' + String(t).padStart(2, '0');
            resend.style.color = t ? '#94A3B8' : '#3F749C';
            if (t) resend.setAttribute('aria-disabled', 'true'); else resend.removeAttribute('aria-disabled');
        }
        resend.addEventListener('click', function (e) { if (t) e.preventDefault(); });
        draw();
        var iv = setInterval(function () { if (t > 0) { t--; draw(); } else clearInterval(iv); }, 1000);
    })();
</script>
@endpush
