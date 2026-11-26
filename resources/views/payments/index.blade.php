@extends('layouts.app')

@section('title', 'Riwayat Pembayaran | SKENA')

@section('content')
<div class="flex items-center gap-[10px] mb-6">
    <label class="h-[44px] w-[320px] px-5 rounded-full border border-border bg-white flex items-center gap-[10px] box-border shrink-0">
        <span class="msr text-on-surface-muted" style="font-size:20px">search</span>
        <form method="GET" action="{{ route('payments.index') }}" id="searchForm" class="flex-1 min-w-0 flex">
            <input type="hidden" name="filter" value="{{ $filter }}">
            <input
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari pembayaran…"
                class="border-0 outline-none bg-transparent font-sans text-[15px] text-on-surface flex-1 min-w-0"
                oninput="document.getElementById('searchForm').submit()"
            >
        </form>
    </label>

    <div class="flex gap-2 ml-auto">
        @foreach([['semua','Semua'], ['menunggu','Menunggu'], ['lunas','Lunas']] as [$val, $label])
            <a
                href="{{ route('payments.index', ['filter' => $val, 'search' => request('search')]) }}"
                class="h-[44px] px-5 rounded-full font-semibold text-[14px] border whitespace-nowrap flex items-center
                    {{ $filter === $val
                        ? 'bg-primary text-white border-primary'
                        : 'bg-white text-on-surface-muted border-border hover:border-primary hover:text-primary' }}"
            >{{ $label }}</a>
        @endforeach
    </div>
</div>

<div class="pt-2 pb-8">
    <div class="text-label-sm text-primary tracking-widest uppercase">RIWAYAT</div>
    <div class="flex items-center gap-[14px] mt-[10px]">
        <h1 class="m-0 text-headline-lg">Pembayaran</h1>
    </div>
    <p class="mt-[10px] text-body-md text-on-surface-muted">
        Bukti bisa diubah atau dihapus selama belum dikonfirmasi penyedia.
    </p>
</div>

@if($payments->isEmpty())
    <div class="bg-white border border-border rounded-[24px] flex flex-col items-center justify-center text-center gap-[10px] py-16 px-8">
        <span class="w-16 h-16 rounded-full bg-tint text-primary grid place-items-center msr" style="font-size:30px">payments</span>
        <div class="text-title font-semibold mt-2">Belum ada pembayaran</div>
        <p class="m-0 max-w-[300px] text-body-md text-on-surface-muted">
            Pembayaran untuk pesananmu akan tercatat di sini.
        </p>
        <a href="{{ route('orders.index') }}"
           class="h-[44px] px-[22px] rounded-full bg-primary text-white flex items-center font-semibold text-[14px] mt-[10px] hover:bg-primary-hover">
            Lihat Pesanan
        </a>
    </div>

@else
    <div class="bg-white border border-border rounded-[24px] shadow-card overflow-hidden">
        <div class="grid gap-4 items-center h-11 px-6 bg-surface border-b border-border
                    text-label-sm tracking-widest text-on-surface-muted uppercase"
             style="grid-template-columns:2.4fr 1fr 1.6fr 1fr 1.5fr 1fr">
            <span>PESANAN</span>
            <span>NOMINAL</span>
            <span>TANGGAL</span>
            <span>BUKTI</span>
            <span>STATUS</span>
            <span class="text-right">AKSI</span>
        </div>

        @foreach($payments as $i => $pay)
            @php
                $statusColors = match($pay->status) {
                    'menunggu_konfirmasi' => ['bg-warning-container text-on-warning-container', '#F59E0B'],
                    'lunas'              => ['bg-success-container text-on-success-container', '#10B981'],
                    'ditolak'            => ['bg-error-container text-on-error-container', '#EF4444'],
                    default              => ['bg-tint text-on-surface-variant', '#64748B'],
                };
            @endphp
            <div class="grid gap-4 items-center px-6 py-[14px] hover:bg-surface
                        {{ $i > 0 ? 'border-t border-border' : '' }}"
                 style="grid-template-columns:2.4fr 1fr 1.6fr 1fr 1.5fr 1fr">
                <div>
                    <a href="{{ route('payments.show', $pay) }}" class="font-semibold text-[14px] leading-snug text-on-surface hover:text-primary transition-colors block">
                        {{ $pay->order?->service?->judul ?? 'Jasa SKENA' }}
                    </a>
                    <div class="text-[12px] text-on-surface-muted mt-0.5">
                        <a href="{{ route('payments.show', $pay) }}" class="text-primary hover:underline font-medium">
                            {{ $pay->order->code ?? '#SKN-' . str_pad($pay->order_id, 4, '0', STR_PAD_LEFT) }}
                        </a>
                        @if(auth()->user()?->isAdmin())
                            · Dari {{ $pay->payer->name ?? 'Pencari Jasa' }} ke {{ $pay->payee->name ?? 'Penyedia Jasa' }}
                        @elseif(auth()->id() === $pay->payer_id)
                            · Penyedia: {{ $pay->payee->name ?? 'Penyedia Jasa' }}
                        @else
                            · Pembeli: {{ $pay->payer->name ?? 'Pencari Jasa' }}
                        @endif
                    </div>
                </div>

                <span class="font-bold text-[15px]">{{ $pay->amountFormatted() }}</span>

                <span class="text-[14px] text-on-surface-variant">
                    {{ $pay->proof_uploaded_at
                        ? $pay->proof_uploaded_at->translatedFormat('d M Y, H.i') . ' WIB'
                        : '—' }}
                </span>

                @if($pay->proof_path)
                    <a href="{{ route('payments.show', $pay) }}"
                       class="flex items-center gap-[6px] font-semibold text-[13px] text-primary hover:underline">
                        <span class="msr" style="font-size:18px">image</span>Lihat
                    </a>
                @else
                    <span class="text-[13px] text-on-surface-muted">—</span>
                @endif

                <span>
                    <span class="inline-flex items-center gap-2 h-8 px-[14px] rounded-full font-semibold text-[13px] {{ $statusColors[0] }}">
                        <span class="w-[7px] h-[7px] rounded-full" style="background:{{ $statusColors[1] }}"></span>
                        {{ $pay->statusLabel() }}
                    </span>
                </span>

                <span class="flex justify-end gap-[6px]">
                    <a href="{{ route('payments.show', $pay) }}"
                       aria-label="Lihat / Konfirmasi"
                       title="Lihat / Konfirmasi"
                       class="w-9 h-9 rounded-full border border-border grid place-items-center text-primary hover:bg-tint">
                        <span class="msr" style="font-size:18px">visibility</span>
                    </a>

                    @php
                        $canManageProof = auth()->user()?->isAdmin() || auth()->id() === $pay->payer_id;
                    @endphp

                    @if($canManageProof && $pay->isEditable())
                        <a href="{{ route('payments.create', ['order_id' => $pay->order_id]) }}"
                           aria-label="Ubah bukti"
                           title="Ubah bukti"
                           class="w-9 h-9 rounded-full border border-border grid place-items-center text-on-surface hover:bg-tint">
                            <span class="msr" style="font-size:18px">edit</span>
                        </a>
                        <button
                            type="button"
                            aria-label="Hapus bukti"
                            title="Hapus bukti"
                            onclick="openDeleteModal({{ $pay->id }}, '{{ addslashes($pay->order->code ?? '#SKN-' . str_pad($pay->order_id, 4, '0', STR_PAD_LEFT)) }}')"
                            class="w-9 h-9 rounded-full border border-border grid place-items-center text-on-error-container hover:bg-error-container p-0">
                            <span class="msr" style="font-size:18px">delete</span>
                        </button>
                    @elseif($canManageProof && $pay->status === 'menunggu_pembayaran')
                        <a href="{{ route('payments.create', ['order_id' => $pay->order_id]) }}"
                           class="h-9 px-4 rounded-full bg-primary text-white font-semibold text-[13px] flex items-center hover:bg-primary-hover">
                            Bayar
                        </a>
                    @endif
                </span>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $payments->links() }}
    </div>
@endif

<div id="deleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 hidden" role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle">
    <div class="w-[420px] bg-white rounded-[24px] p-7 shadow-card-hover flex flex-col gap-[10px]">
        <span class="w-[52px] h-[52px] rounded-full bg-error-container text-on-error-container grid place-items-center msr" style="font-size:26px">delete</span>
        <h3 id="deleteModalTitle" class="mt-[10px] text-[20px] font-bold leading-snug m-0">
            Hapus bukti pembayaran <span id="deleteOrderCode"></span>?
        </h3>
        <p class="m-0 text-body-md text-on-surface-muted">
            Status pesanan kembali ke Menunggu Pembayaran. Kamu bisa mengunggah bukti baru setelahnya.
        </p>
        <div class="grid grid-cols-2 gap-[10px] mt-[14px]">
            <button
                type="button"
                onclick="closeDeleteModal()"
                class="h-[46px] rounded-full border border-border text-on-surface font-bold text-[14px] hover:bg-surface">
                Batal
            </button>
            <form id="deleteForm" method="POST" class="contents">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="h-[46px] rounded-full bg-error text-white font-bold text-[14px] hover:opacity-90">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openDeleteModal(paymentId, orderCode) {
        document.getElementById('deleteOrderCode').textContent = orderCode;
        document.getElementById('deleteForm').action = '/payments/' + paymentId;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').focus();
    }
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) closeDeleteModal();
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeDeleteModal();
    });
</script>
@endpush
