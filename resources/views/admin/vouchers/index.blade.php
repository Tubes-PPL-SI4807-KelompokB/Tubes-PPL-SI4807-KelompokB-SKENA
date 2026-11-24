@extends('layouts.admin')
@section('title', 'Voucher/Promo — SKENA')
@section('page-title', 'Voucher & Promo')
@section('page-subtitle', 'Voucher bisa dipakai pencari jasa saat mengubah pesanan.')

@section('content')
@php
    $filters = ['Semua', ...\App\Models\Voucher::STATUSES];
    $url = fn ($s) => route('admin.vouchers.index', array_filter(['status' => $s === 'Semua' ? null : $s, 'q' => $q]));
@endphp

<div x-data="{ target: null, action: '' }" @keydown.escape.window="target = null">

    {{-- Toolbar: cari, filter status, tambah --}}
    <div class="flex flex-wrap items-center gap-2.5 mb-6">
        <form method="GET" action="{{ route('admin.vouchers.index') }}"
              class="h-11 w-full sm:w-[280px] px-5 rounded-full border border-border bg-white flex items-center gap-2.5 focus-within:border-primary focus-within:ring-[3px] focus-within:ring-tint-strong">
            @if($status !== 'Semua') <input type="hidden" name="status" value="{{ $status }}"> @endif
            <svg class="ic text-on-surface-muted"><use href="#i-search"/></svg>
            <input type="search" name="q" value="{{ $q }}" placeholder="Cari kode voucher…" aria-label="Cari kode voucher"
                   class="flex-1 min-w-0 bg-transparent border-0 outline-none text-[15px] text-on-surface">
        </form>

        <div class="flex flex-wrap gap-2 sm:ml-auto">
            @foreach($filters as $f)
                <a href="{{ $url($f) }}"
                   class="h-11 px-5 rounded-full text-sm font-semibold inline-flex items-center whitespace-nowrap border transition-colors
                          {{ $f === $status ? 'bg-primary border-primary text-white' : 'bg-white border-border text-on-surface-muted hover:border-primary hover:text-primary' }}"
                   @if($f === $status) aria-current="true" @endif>{{ $f }}</a>
            @endforeach
        </div>

        <a href="{{ route('admin.vouchers.create') }}"
           class="h-11 px-[22px] rounded-full bg-primary hover:bg-primary-hover text-white text-sm font-semibold inline-flex items-center gap-1.5 whitespace-nowrap transition-colors">
            <svg class="ic"><use href="#i-plus"/></svg>Tambah Voucher
        </a>
    </div>

    @if(! $hasAny)
        {{-- Keadaan kosong (belum ada voucher sama sekali) --}}
        <div class="bg-white border border-border rounded-3xl shadow-card min-h-[420px] flex flex-col items-center justify-center text-center gap-2.5 p-8">
            <span class="w-16 h-16 rounded-full bg-tint text-primary grid place-items-center">
                <svg class="w-[30px] h-[30px]" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 9V6a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v3a3 3 0 0 0 0 6v3a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-3a3 3 0 0 0 0-6z"/><path d="M14 5v2m0 4v2m0 4v2" stroke-dasharray="0"/></svg>
            </span>
            <div class="text-lg font-semibold mt-2">Belum ada voucher</div>
            <p class="max-w-[280px] text-sm text-on-surface-muted">Voucher dan promo yang kamu buat akan muncul di sini.</p>
            <a href="{{ route('admin.vouchers.create') }}" class="h-11 px-[22px] rounded-full bg-primary hover:bg-primary-hover text-white text-sm font-semibold inline-flex items-center gap-1.5 mt-2.5 transition-colors">
                <svg class="ic"><use href="#i-plus"/></svg>Tambah Voucher
            </a>
        </div>
    @else
        <div class="bg-white border border-border rounded-3xl shadow-card overflow-hidden">
            <div class="overflow-x-auto">
                <div class="min-w-[960px]">
                    <div class="grid grid-cols-[1.5fr_1.6fr_2fr_1.8fr_1.2fr_0.9fr] gap-4 items-center h-11 px-6 bg-surface border-b border-border text-[11px] font-bold tracking-[0.06em] text-on-surface-muted">
                        <span>KODE</span><span>JENIS &amp; NILAI POTONGAN</span><span>PERIODE BERLAKU</span><span>KUOTA</span><span>STATUS</span><span class="text-right">AKSI</span>
                    </div>

                    @forelse($vouchers as $v)
                        @php([$sbg, $sfg, $sdot] = $v->status_color)
                        <div class="grid grid-cols-[1.5fr_1.6fr_2fr_1.8fr_1.2fr_0.9fr] gap-4 items-center px-6 py-4 border-t border-border hover:bg-surface">
                            <span class="font-mono font-bold text-[15px] tracking-[0.02em] break-all">{{ $v->code }}</span>
                            <span><span class="text-base font-bold">{{ $v->value_label }}</span> <span class="text-[13px] text-on-surface-muted">{{ $v->type_label }}</span></span>
                            <span class="text-sm text-on-surface-variant leading-snug">{{ $v->period_label }}</span>
                            <span class="flex flex-col gap-2">
                                <span class="text-[13px] font-semibold">{{ number_format($v->used_count, 0, ',', '.') }} / {{ number_format($v->quota, 0, ',', '.') }} <span class="font-normal text-on-surface-muted">dipakai</span></span>
                                <span class="h-1 w-[85%] rounded-full bg-[#EEF1F5] overflow-hidden"><span class="block h-full rounded-full bg-primary" style="width:{{ $v->usage_percent }}%"></span></span>
                            </span>
                            <span>
                                <span class="h-8 px-3.5 rounded-full inline-flex items-center gap-2 text-[13px] font-semibold whitespace-nowrap" style="background:{{ $sbg }};color:{{ $sfg }}">
                                    <span class="w-[7px] h-[7px] rounded-full" style="background:{{ $sdot }}"></span>{{ $v->status }}
                                </span>
                            </span>
                            <span class="flex justify-end gap-1.5">
                                <a href="{{ route('admin.vouchers.edit', $v) }}" aria-label="Ubah voucher {{ $v->code }}"
                                   class="w-9 h-9 rounded-full border border-border grid place-items-center text-on-surface hover:border-primary hover:text-primary transition-colors">
                                    <svg class="ic sm"><use href="#i-pen"/></svg>
                                </a>
                                <button type="button" aria-label="Hapus voucher {{ $v->code }}"
                                        @click="target = @js($v->code); action = @js(route('admin.vouchers.destroy', $v))"
                                        class="w-9 h-9 rounded-full border border-border grid place-items-center text-on-error-container hover:bg-error-container transition-colors">
                                    <svg class="ic sm"><use href="#i-trash"/></svg>
                                </button>
                            </span>
                        </div>
                    @empty
                        <div class="p-10 text-center text-sm text-on-surface-muted border-t border-border">
                            @if($q !== '') Tidak ada voucher dengan kode “{{ $q }}”@if($status !== 'Semua') dan status {{ $status }}@endif.
                            @else Belum ada voucher dengan status ini. @endif
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        @if($vouchers->hasPages())
            <div class="mt-5">{{ $vouchers->links() }}</div>
        @endif
    @endif

    {{-- Modal konfirmasi hapus --}}
    <div x-show="target" x-cloak x-transition.opacity @click.self="target = null"
         class="fixed inset-0 z-50 grid place-items-center p-6 bg-[#0F172A]/40">
        <div role="dialog" aria-modal="true" aria-labelledby="del-title" class="w-full max-w-[400px] bg-white rounded-3xl p-7 shadow-card-hover flex flex-col gap-2.5">
            <span class="w-[52px] h-[52px] rounded-full bg-error-container text-on-error-container grid place-items-center">
                <svg class="w-[26px] h-[26px]" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><use href="#i-trash"/></svg>
            </span>
            <h3 id="del-title" class="mt-2.5 text-xl font-bold leading-snug">Hapus voucher <span x-text="target"></span>?</h3>
            <p class="text-sm text-on-surface-muted">Voucher tidak bisa dipakai lagi. Pesanan yang sudah memakai voucher ini tetap tercatat.</p>
            <form method="POST" :action="action" class="grid grid-cols-2 gap-2.5 mt-3.5">
                @csrf @method('DELETE')
                <button type="button" @click="target = null" class="h-[46px] rounded-full border border-border text-sm font-bold hover:bg-surface transition-colors">Batal</button>
                <button type="submit" class="h-[46px] rounded-full bg-error hover:bg-[#DC2626] text-white text-sm font-bold transition-colors">Hapus</button>
            </form>
        </div>
    </div>
</div>
<style>[x-cloak]{display:none!important}</style>
@endsection
