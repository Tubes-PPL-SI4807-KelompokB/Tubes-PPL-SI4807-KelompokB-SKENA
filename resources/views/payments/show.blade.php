@extends('layouts.app')

@section('title', 'Konfirmasi Pembayaran | SKENA')

@section('content')
@php
    $user         = auth()->user();
    $isAdmin      = $user?->isAdmin() ?? false;
    $isPayee      = $user && (int)$user->id === (int)$payment->payee_id;
    $isPayer      = $user && (int)$user->id === (int)$payment->payer_id;
    $canConfirm   = $isAdmin || $isPayee;
    $isPending    = $payment->status === 'menunggu_konfirmasi';
    $isConfirmed  = $payment->status === 'lunas';
    $isRejected   = $payment->status === 'ditolak';
    $orderCode    = $payment->order->code ?? '#SKN-' . str_pad($payment->order_id, 4, '0', STR_PAD_LEFT);
    $payerName    = $payment->payer->name ?? 'Pencari Jasa';
    $payeeName    = $payment->payee->name ?? 'Penyedia Jasa';
    $serviceTitle = $payment->order?->service?->title ?? 'Desain PPT Presentasi Sidang';
@endphp

<div class="pt-2 pb-8">
    <div class="text-label-sm text-primary tracking-widest uppercase">
        KONFIRMASI PEMBAYARAN · {{ $orderCode }}
    </div>
    <div class="flex items-center gap-[14px] mt-[10px]">
        <h1 class="m-0 text-headline-lg">Pembayaran</h1>
    </div>
    <p class="mt-[10px] text-body-md text-on-surface-muted">
        @if($canConfirm)
            Cek mutasi rekeningmu, lalu konfirmasi bila dana sudah masuk.
        @else
            Rincian status pembayaran pesananmu. Penyedia jasa akan memverifikasi mutasi rekening.
        @endif
    </p>
</div>

<div class="grid grid-cols-2 gap-[18px] items-start">
    <div class="bg-white border border-border rounded-[24px] shadow-card p-7 flex flex-col gap-[18px]">
        <div class="h-[300px] rounded-[18px] overflow-hidden bg-surface-container flex items-center justify-center">
            @if($payment->proof_path && !str_ends_with($payment->proof_path, '.pdf'))
                <img
                    src="{{ Storage::url($payment->proof_path) }}"
                    class="w-full h-full object-contain"
                    alt="Bukti transfer dari {{ $payerName }}"
                >
            @elseif($payment->proof_path)
                <div class="flex flex-col items-center gap-3 text-on-surface-muted">
                    <span class="msr" style="font-size:48px">picture_as_pdf</span>
                    <a href="{{ Storage::url($payment->proof_path) }}"
                       target="_blank"
                       class="text-[13px] text-primary hover:underline">
                        Buka Dokumen PDF
                    </a>
                </div>
            @else
                <span class="font-mono text-[11px] text-on-surface-muted">Bukti transfer belum diunggah</span>
            @endif
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div class="p-[14px] px-4 rounded-[18px] bg-surface">
                <div class="text-label-sm tracking-widest text-on-surface-muted uppercase">NOMINAL</div>
                <div class="font-bold text-[24px] leading-snug mt-[6px]">{{ $payment->amountFormatted() }}</div>
            </div>
            <div class="p-[14px] px-4 rounded-[18px] bg-surface">
                <div class="text-label-sm tracking-widest text-on-surface-muted uppercase">TANGGAL</div>
                <div class="font-semibold text-[16px] leading-snug mt-2">
                    {{ $payment->proof_uploaded_at
                        ? $payment->proof_uploaded_at->translatedFormat('d M Y, H.i') . ' WIB'
                        : '—' }}
                </div>
            </div>
        </div>

        <div class="text-body-md text-on-surface-muted">
            Dari {{ $payerName }} · {{ $serviceTitle }}
        </div>

        @if($canConfirm && $isPending)
            <div class="grid gap-[10px]" style="grid-template-columns:1.5fr 1fr">
                <form method="POST" action="{{ route('payments.update', $payment) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="action" value="confirm">
                    <button
                        type="submit"
                        class="w-full h-12 rounded-full border-0 bg-primary text-white font-bold text-[14px] cursor-pointer hover:bg-primary-hover"
                    >
                        Konfirmasi Penerimaan
                    </button>
                </form>

                <button
                    type="button"
                    onclick="document.getElementById('rejectModal').classList.remove('hidden')"
                    class="w-full h-12 rounded-full border border-border bg-white text-on-surface font-bold text-[14px] cursor-pointer hover:bg-surface"
                >
                    Tolak Bukti
                </button>
            </div>
        @elseif(!$canConfirm && $isPending)
            <div class="flex items-center gap-3 p-[14px] rounded-[18px] bg-warning-container text-on-warning-container text-[14px] font-semibold">
                <span class="msr" style="font-size:20px">hourglass_top</span>
                Bukti transfer sudah dikirim. Menunggu konfirmasi dari penyedia jasa ({{ $payeeName }}).
            </div>
        @endif

        @if($isRejected)
            <div class="flex flex-col gap-2 p-4 rounded-[18px] bg-error-container">
                <div class="font-semibold text-on-error-container">Bukti Ditolak</div>
                @if($payment->rejection_note)
                    <p class="m-0 text-[14px] text-on-error-container/80">{{ $payment->rejection_note }}</p>
                @endif
                @if($isPayer || $isAdmin)
                    <a href="{{ route('payments.create', ['order_id' => $payment->order_id]) }}"
                       class="mt-1 h-10 px-5 rounded-full bg-primary text-white font-bold text-[13px] inline-flex items-center hover:bg-primary-hover">
                        Unggah Ulang Bukti
                    </a>
                @endif
            </div>
        @endif
    </div>

    <div class="flex flex-col gap-3">
        <span class="text-label-sm tracking-widest text-on-surface-muted uppercase">
            @if($isConfirmed) BERHASIL DIKONFIRMASI
            @elseif($isRejected) DITOLAK
            @else KEADAAN SAAT INI
            @endif
        </span>

        @if($isConfirmed)
            <div class="bg-white border border-border rounded-[24px] shadow-card px-10 py-12 flex flex-col items-center text-center gap-3">
                <span class="w-[72px] h-[72px] rounded-full bg-success-container text-on-success-container grid place-items-center msr" style="font-size:36px;font-variation-settings:'wght' 300">
                    check
                </span>
                <h2 class="mt-2 text-headline-md m-0">Pembayaran Lunas</h2>
                <p class="m-0 max-w-[340px] text-body-md text-on-surface-muted">
                    {{ $payment->amountFormatted() }} untuk pesanan {{ $orderCode }} sudah tercatat.
                    Layanan siap dikerjakan oleh penyedia jasa.
                </p>
                <span class="inline-flex items-center gap-2 h-8 px-[14px] rounded-full font-semibold text-[13px] bg-success-container text-on-success-container mt-[6px]">
                    <span class="w-[7px] h-[7px] rounded-full bg-success"></span>
                    Lunas · {{ $payment->confirmed_at
                        ? $payment->confirmed_at->translatedFormat('d M Y, H.i') . ' WIB'
                        : '' }}
                </span>
                <a href="{{ route('orders.index') }}"
                   class="mt-3 h-[46px] px-6 rounded-full border border-border text-on-surface font-bold text-[14px] flex items-center box-border hover:bg-surface">
                    Kembali ke Pesanan
                </a>
            </div>

        @elseif($isRejected)
            <div class="bg-white border border-border rounded-[24px] shadow-card px-10 py-12 flex flex-col items-center text-center gap-3">
                <span class="w-[72px] h-[72px] rounded-full bg-error-container text-on-error-container grid place-items-center msr" style="font-size:36px;font-variation-settings:'wght' 300">
                    close
                </span>
                <h2 class="mt-2 text-headline-md m-0">Bukti Ditolak</h2>
                <p class="m-0 max-w-[340px] text-body-md text-on-surface-muted">
                    Bukti transfer untuk pesanan {{ $orderCode }} telah ditolak.
                    Silakan unggah bukti transfer yang sesuai.
                </p>
                @if($isPayer || $isAdmin)
                    <a href="{{ route('payments.create', ['order_id' => $payment->order_id]) }}"
                       class="mt-3 h-[46px] px-6 rounded-full bg-primary text-white font-bold text-[14px] flex items-center hover:bg-primary-hover">
                        Unggah Ulang Bukti
                    </a>
                @endif
                <a href="{{ route('orders.index') }}"
                   class="h-[46px] px-6 rounded-full border border-border text-on-surface font-bold text-[14px] flex items-center box-border hover:bg-surface">
                    Kembali ke Pesanan
                </a>
            </div>

        @else
            <div class="bg-white border border-border rounded-[24px] shadow-card px-10 py-12 flex flex-col items-center text-center gap-3">
                <span class="w-[72px] h-[72px] rounded-full bg-warning-container text-on-warning-container grid place-items-center msr" style="font-size:36px;font-variation-settings:'wght' 300">
                    hourglass_top
                </span>
                <h2 class="mt-2 text-headline-md m-0">Menunggu Konfirmasi</h2>
                <p class="m-0 max-w-[340px] text-body-md text-on-surface-muted">
                    Bukti transfer sudah diterima. Penyedia jasa akan mengonfirmasi setelah mengecek mutasi rekening.
                </p>
                <span class="inline-flex items-center gap-2 h-8 px-[14px] rounded-full font-semibold text-[13px] bg-warning-container text-on-warning-container mt-[6px]">
                    <span class="w-[7px] h-[7px] rounded-full bg-warning"></span>
                    Menunggu Konfirmasi
                </span>
                <a href="{{ route('payments.index') }}"
                   class="mt-3 h-[46px] px-6 rounded-full border border-border text-on-surface font-bold text-[14px] flex items-center box-border hover:bg-surface">
                    Lihat Riwayat
                </a>
            </div>
        @endif
    </div>
</div>

@if($canConfirm && $isPending)
<div id="rejectModal"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 hidden"
     role="dialog" aria-modal="true" aria-labelledby="rejectTitle">
    <div class="w-[440px] bg-white rounded-[24px] p-7 shadow-card-hover flex flex-col gap-4">
        <span class="w-[52px] h-[52px] rounded-full bg-error-container text-on-error-container grid place-items-center msr" style="font-size:26px">close</span>
        <h3 id="rejectTitle" class="m-0 font-bold text-[20px] leading-snug mt-2">Tolak bukti pembayaran?</h3>
        <p class="m-0 text-body-md text-on-surface-muted">
            Pencari jasa akan diberi tahu dan dapat mengunggah bukti transfer baru.
        </p>
        <form method="POST" action="{{ route('payments.update', $payment) }}">
            @csrf
            @method('PATCH')
            <input type="hidden" name="action" value="reject">
            <div class="flex flex-col gap-2">
                <label for="rejNote" class="font-semibold text-[13px] text-on-surface-variant">
                    Alasan penolakan <span class="text-on-surface-muted font-normal">(opsional)</span>
                </label>
                <textarea
                    id="rejNote"
                    name="rejection_note"
                    rows="3"
                    maxlength="500"
                    placeholder="Mis: Nominal transfer tidak sesuai, bukti terpotong atau tidak terbaca…"
                    class="border border-border rounded-[12px] p-3 font-sans text-[14px] text-on-surface resize-none outline-none focus:border-primary"
                ></textarea>
            </div>
            <div class="grid grid-cols-2 gap-[10px] mt-4">
                <button
                    type="button"
                    onclick="document.getElementById('rejectModal').classList.add('hidden')"
                    class="h-[46px] rounded-full border border-border text-on-surface font-bold text-[14px] hover:bg-surface"
                >Batal</button>
                <button
                    type="submit"
                    class="h-[46px] rounded-full bg-error text-white font-bold text-[14px] hover:opacity-90"
                >Tolak Bukti</button>
            </div>
        </form>
    </div>
</div>
<script>
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') document.getElementById('rejectModal').classList.add('hidden');
    });
    document.getElementById('rejectModal').addEventListener('click', function(e) {
        if (e.target === this) this.classList.add('hidden');
    });
</script>
@endif
@endsection
