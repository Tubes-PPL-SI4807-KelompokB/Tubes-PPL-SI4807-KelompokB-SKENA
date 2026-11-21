@extends('layouts.admin')
@section('page-title', 'Dashboard Admin')

{{-- TIM-D Dashboard Admin (FR-03) — persis Claude Design (td-1, td-2 kosong). --}}

@section('page-head')
    <form method="GET" action="{{ route('admin.users.index') }}" role="search"
          class="h-11 w-full sm:w-[340px] px-5 rounded-full border border-border bg-white flex items-center gap-2.5 focus-within:border-primary">
        <span class="msr text-on-surface-muted">search</span>
        <label for="adminSearch" class="sr-only">Cari pengguna, jasa, atau pesanan</label>
        <input id="adminSearch" type="search" name="search" placeholder="Cari pengguna, jasa, atau pesanan…"
               class="flex-1 min-w-0 border-0 outline-none bg-transparent text-[15px] text-on-surface placeholder:text-on-surface-muted">
    </form>
    @include('admin._head', ['kicker' => 'RINGKASAN PLATFORM', 'title' => 'Dashboard Admin (FR-03)', 'desc' => 'Pantau pengguna, jasa, pesanan, dan laporan dalam satu tempat.'])
@endsection

@section('content')
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-card-gap">
        @foreach($stats as $s)
            <div class="bg-white border border-border rounded-3xl p-6 shadow-card">
                <div class="flex justify-between items-start gap-2">
                    <span class="text-[11px] leading-[1.3] font-bold tracking-[0.06em] text-on-surface-muted">{{ $s['label'] }}</span>
                    <span class="w-[38px] h-[38px] flex-none rounded-full bg-tint text-primary grid place-items-center"><span class="msr">{{ $s['icon'] }}</span></span>
                </div>
                <div class="mt-[18px] text-[40px] leading-[1.05] tracking-[-0.02em] font-bold">{{ number_format($s['value'], 0, ',', '.') }}</div>
                <div class="mt-1.5 text-[13px] leading-[1.45] text-on-surface-muted">{{ $s['note'] }}</div>
            </div>
        @endforeach
    </div>

    <div class="mt-14 flex justify-between items-end gap-4">
        <div>
            <div class="text-[11px] leading-[1.2] font-bold tracking-[0.06em] text-primary">HARI INI</div>
            <h2 class="mt-2 text-[24px] leading-[1.25] tracking-[-0.01em] font-bold">Aktivitas Terbaru</h2>
        </div>
        @if($activities->isNotEmpty())
            <a href="{{ route('admin.users.index') }}" class="text-[14px] font-semibold text-primary hover:text-primary-hover">Lihat Semua</a>
        @endif
    </div>

    @if($activities->isEmpty())
        <div class="mt-6 bg-white border border-border rounded-3xl shadow-card px-6 py-16 flex flex-col items-center text-center gap-2.5">
            <span class="w-16 h-16 rounded-full bg-tint text-primary grid place-items-center"><span class="msr" style="font-size:30px">inbox</span></span>
            <div class="mt-2 text-[18px] leading-[1.3] font-semibold">Belum ada aktivitas</div>
            <p class="max-w-[380px] text-[15px] leading-normal text-on-surface-muted">Pengguna baru, jasa baru, pesanan, dan laporan akan muncul di sini.</p>
            <a href="{{ route('admin.users.index') }}" class="h-11 px-6 mt-2.5 rounded-full bg-primary hover:bg-primary-hover text-white flex items-center text-[14px] font-semibold">Kelola Pengguna</a>
        </div>
    @else
        <div class="mt-6 bg-white border border-border rounded-3xl shadow-card overflow-hidden">
            @foreach($activities as $i => $a)
                <a href="{{ $a['url'] }}" class="flex items-center gap-[18px] px-6 py-4 hover:bg-surface transition-colors {{ $i ? 'border-t border-border' : '' }}">
                    <span class="w-11 h-11 flex-none rounded-[14px] bg-tint text-primary grid place-items-center"><span class="msr" style="font-size:21px">{{ $a['icon'] }}</span></span>
                    <div class="flex-1 min-w-0">
                        <div class="text-[15px] leading-[1.3] font-semibold">{{ $a['title'] }}</div>
                        <div class="mt-0.5 text-[13px] leading-[1.45] text-on-surface-muted break-words">{{ $a['meta'] }}</div>
                    </div>
                    <span class="text-[13px] text-on-surface-muted whitespace-nowrap">{{ $a['time'] }}</span>
                </a>
            @endforeach
        </div>
    @endif
@endsection
