@extends('layouts.admin')

@section('title', ($voucher->exists ? 'Ubah' : 'Tambah') . ' Voucher — SKENA')
@section('page-title', ($voucher->exists ? 'Ubah' : 'Tambah') . ' Voucher')
@section('page-subtitle', 'Atur kode, potongan, periode berlaku, dan kuota voucher.')

@section('content')
@php
    $editing = $voucher->exists;
    $old = fn ($k, $d) => old($k, $d);
    $init = [
        'code'   => $old('code', $voucher->code),
        'type'   => $old('type', $voucher->type),
        'value'  => (string) $old('value', $voucher->value),
        'start'  => $old('start_date', optional($voucher->start_date)->toDateString()),
        'end'    => $old('end_date', optional($voucher->end_date)->toDateString()),
        'quota'  => (string) $old('quota', $voucher->quota),
        'used'   => (int) $voucher->used_count,
        'active' => (bool) $old('is_active', $voucher->is_active),
    ];
    $field = 'h-12 px-5 rounded-full border bg-white text-[15px] text-on-surface outline-none box-border w-full min-w-0 focus:border-primary focus:ring-[3px] focus:ring-tint-strong';
    $err = fn ($k) => $errors->has($k) ? 'border-error' : 'border-border';
@endphp

<form method="POST" novalidate
      action="{{ $editing ? route('admin.vouchers.update', $voucher) : route('admin.vouchers.store') }}"
      x-data='voucherForm(@json($init, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT))'
      class="grid grid-cols-1 lg:grid-cols-2 gap-[18px] items-start">
    @csrf
    @if($editing) @method('PUT') @endif

    {{-- Kolom form --}}
    <div class="bg-white border border-border rounded-3xl shadow-card p-8 flex flex-col gap-5">

        <label class="flex flex-col gap-2">
            <span class="text-sm font-semibold">Kode Voucher</span>
            <input name="code" x-model="code" @input="code = code.toUpperCase()" maxlength="30" autocomplete="off" required
                   class="{{ $field }} {{ $err('code') }} font-mono font-bold uppercase">
            @error('code')
                <span class="flex gap-1.5 items-center text-[13px] text-on-error-container">
                    <svg class="ic sm"><use href="#i-x"/></svg>{{ $message }}
                </span>
            @enderror
        </label>

        @if($suggestions = session('code_suggestions'))
            <div class="flex flex-wrap gap-2 -mt-2">
                <span class="w-full text-[13px] text-on-surface-muted">Saran kode:</span>
                @foreach($suggestions as $s)
                    <button type="button" @click="code = @js($s)"
                            class="h-8 px-3.5 rounded-full bg-tint hover:bg-tint-strong text-on-secondary-container text-[13px] font-semibold font-mono transition-colors">{{ $s }}</button>
                @endforeach
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[18px]">
            <div class="flex flex-col gap-2">
                <span class="text-sm font-semibold" id="lbl-type">Jenis Potongan</span>
                <input type="hidden" name="type" :value="type">
                <div class="flex p-1 rounded-full bg-surface border border-border" role="group" aria-labelledby="lbl-type">
                    <template x-for="t in [['percent','Persen'],['nominal','Nominal']]" :key="t[0]">
                        <button type="button" @click="setType(t[0])" :aria-pressed="type === t[0]"
                                :class="type === t[0] ? 'bg-primary text-white' : 'text-on-surface-muted'"
                                class="flex-1 h-[38px] rounded-full text-sm font-semibold transition-colors" x-text="t[1]"></button>
                    </template>
                </div>
                @error('type') <span class="text-[13px] text-on-error-container">{{ $message }}</span> @enderror
            </div>

            <label class="flex flex-col gap-2">
                <span class="text-sm font-semibold">Nilai Potongan</span>
                <span class="flex items-center h-12 px-5 gap-1.5 rounded-full border bg-white {{ $err('value') }} focus-within:border-primary focus-within:ring-[3px] focus-within:ring-tint-strong">
                    <span class="font-semibold text-[15px] text-on-surface-muted" x-show="type === 'nominal'">Rp</span>
                    <input name="value" x-model="value" inputmode="numeric" autocomplete="off" required
                           class="flex-1 min-w-0 border-0 outline-none bg-transparent text-[15px]">
                    <span class="font-semibold text-[15px] text-on-surface-muted" x-show="type === 'percent'">%</span>
                </span>
                @error('value') <span class="text-[13px] text-on-error-container">{{ $message }}</span> @enderror
            </label>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[18px]">
            <label class="flex flex-col gap-2">
                <span class="text-sm font-semibold">Tanggal Mulai</span>
                <input type="date" name="start_date" x-model="start" required class="{{ $field }} {{ $err('start_date') }}">
                @error('start_date') <span class="text-[13px] text-on-error-container">{{ $message }}</span> @enderror
            </label>
            <label class="flex flex-col gap-2">
                <span class="text-sm font-semibold">Tanggal Berakhir</span>
                <input type="date" name="end_date" x-model="end" :min="start" required class="{{ $field }} {{ $err('end_date') }}">
                @error('end_date') <span class="text-[13px] text-on-error-container">{{ $message }}</span> @enderror
            </label>
        </div>

        <label class="flex flex-col gap-2">
            <span class="text-sm font-semibold">Kuota</span>
            <input name="quota" x-model="quota" inputmode="numeric" autocomplete="off" required class="{{ $field }} {{ $err('quota') }}">
            @error('quota')
                <span class="text-[13px] text-on-error-container">{{ $message }}</span>
            @else
                <span class="text-[13px] leading-snug text-on-surface-muted">
                    Jumlah maksimal voucher bisa dipakai.@if($editing && $voucher->used_count) Sudah dipakai {{ $voucher->used_count }}×.@endif
                </span>
            @enderror
        </label>

        <div class="flex justify-between items-center px-5 py-4 rounded-[18px] border border-border">
            <div>
                <div class="text-sm font-semibold">Status</div>
                <div class="text-[13px] text-on-surface-muted mt-0.5" x-text="active ? 'Aktif sesuai periode berlaku' : 'Nonaktif, tidak bisa dipakai'"></div>
            </div>
            <input type="hidden" name="is_active" :value="active ? 1 : 0">
            <button type="button" @click="active = !active" role="switch" :aria-checked="active" aria-label="Status voucher"
                    :class="[active ? 'bg-primary justify-end' : 'bg-border justify-start']"
                    class="w-11 h-[26px] rounded-full p-[3px] flex transition-colors">
                <span class="w-5 h-5 rounded-full bg-white shadow-[0_1px_2px_rgba(0,0,0,.15)]"></span>
            </button>
        </div>

        <div class="flex justify-end gap-2.5">
            <a href="{{ route('admin.vouchers.index') }}" class="h-12 px-7 rounded-full border border-border text-sm font-bold inline-flex items-center hover:bg-surface transition-colors">Batal</a>
            <button type="submit" class="h-12 px-7 rounded-full bg-primary hover:bg-primary-hover text-white text-sm font-bold transition-colors">Simpan Voucher</button>
        </div>
    </div>

    {{-- Kolom pratinjau --}}
    <div class="flex flex-col gap-3 lg:sticky lg:top-6">
        <span class="text-[11px] font-bold tracking-[0.06em] text-on-surface-muted">PRATINJAU VOUCHER</span>
        <div class="relative w-full max-w-[440px] min-h-[170px] grid grid-cols-[150px_1fr] bg-white border border-border rounded-3xl shadow-card">
            <div class="bg-primary text-white rounded-l-[23px] flex flex-col items-center justify-center gap-1 px-2">
                <span class="text-[11px] font-bold tracking-[0.1em]">POTONGAN</span>
                <span class="text-[34px] leading-[1.05] font-bold tracking-tight break-all text-center" x-text="big"></span>
            </div>
            <div class="px-6 py-[22px] flex flex-col justify-between gap-3 border-l-2 border-dashed border-border min-w-0">
                <div>
                    <div class="font-mono font-bold text-lg uppercase break-all" x-text="code || 'KODE'"></div>
                    <div class="text-[13px] leading-snug text-on-surface-muted mt-2" x-text="'Berlaku ' + period + ' · untuk semua kategori jasa'"></div>
                </div>
                <div class="flex justify-between items-center gap-2">
                    <span class="text-[13px] font-semibold text-on-surface-variant" x-text="'Sisa ' + remaining + ' kuota'"></span>
                    <span class="h-7 px-3 rounded-full text-xs font-semibold inline-flex items-center whitespace-nowrap"
                          :class="active ? 'bg-success-container text-on-success-container' : 'bg-error-container text-on-error-container'"
                          x-text="active ? 'Aktif' : 'Nonaktif'"></span>
                </div>
            </div>
            <span class="absolute left-[139px] -top-3 w-[22px] h-[22px] rounded-full bg-surface border-b border-border"></span>
            <span class="absolute left-[139px] -bottom-3 w-[22px] h-[22px] rounded-full bg-surface border-t border-border"></span>
        </div>
    </div>
</form>

<style>[x-cloak]{display:none!important}</style>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('voucherForm', (init) => ({
            ...init,
            setType(t) {
                if (t === this.type) return;
                this.type = t;
                this.value = t === 'percent' ? '10' : '5000';
            },
            get digits() { return String(this.value || '').replace(/\D/g, ''); },
            get big() {
                const n = this.digits || '0';
                return this.type === 'percent' ? n + '%' : 'Rp' + Number(n).toLocaleString('id-ID');
            },
            get remaining() {
                const q = parseInt(String(this.quota || '').replace(/\D/g, '') || '0', 10);
                return Math.max(0, q - this.used).toLocaleString('id-ID');
            },
            get period() {
                const M = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
                const p = (s) => { const m = /^(\d{4})-(\d{2})-(\d{2})$/.exec(s || ''); return m ? { y: +m[1], m: +m[2] - 1, d: +m[3] } : null; };
                const a = p(this.start), b = p(this.end);
                if (!a || !b) return '—';
                if (a.y !== b.y) return `${a.d} ${M[a.m]} ${a.y} – ${b.d} ${M[b.m]} ${b.y}`;
                if (a.m === b.m) return `${a.d} – ${b.d} ${M[b.m]} ${b.y}`;
                return `${a.d} ${M[a.m]} – ${b.d} ${M[b.m]} ${b.y}`;
            },
        }));
    });
</script>
@endsection