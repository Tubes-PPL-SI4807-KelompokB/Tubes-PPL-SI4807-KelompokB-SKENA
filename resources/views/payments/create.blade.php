@extends('layouts.app')

@section('title', 'Bayar Pesanan | SKENA')

@section('content')
<div class="flex items-center gap-3 px-[18px] py-[14px] rounded-[18px] bg-tint text-on-surface-variant font-semibold text-[14px] mb-6">
    <span class="msr text-primary" style="font-size:22px;font-variation-settings:'wght' 300">info</span>
    Pembayaran disimulasikan, belum terhubung payment gateway
</div>

<div class="pb-8 pt-0">
    <div class="text-label-sm text-primary tracking-widest uppercase">
        BAYAR PESANAN · {{ $order->code ?? '#SKN-' . str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
    </div>
    <div class="flex items-center gap-[14px] mt-[10px]">
        <h1 class="m-0 text-headline-lg">Pembayaran</h1>
    </div>
    <p class="mt-[10px] text-body-md text-on-surface-muted">
        Transfer sesuai total, lalu unggah bukti agar penyedia bisa mengonfirmasi.
    </p>
</div>

@php
    // Data dari pesanan PBI-11 (harga jasa, voucher, total) — lihat App\Models\Order.
    $serviceTitle  = $order->service->judul ?? 'Jasa SKENA';
    $providerName  = $order->service->provider->name ?? ($payment->payee->name ?? 'Penyedia Jasa');
    $basePrice     = $order->exists ? $order->harga_jasa : 0;
    $voucherCode   = $order->voucher->code ?? null;
    $voucherDisc   = (int) ($order->voucher_discount ?? 0);
    $amount        = $order->exists ? $order->final_price : 0;
    $orderCode     = $order->exists ? $order->code : '#SKN-' . str_pad($order->id ?: 1, 4, '0', STR_PAD_LEFT);

    $existingPayment = $payment ?? null;
    $hasProof = $existingPayment && $existingPayment->proof_path;
    $isWaiting = $existingPayment && $existingPayment->status === 'menunggu_konfirmasi';
@endphp

<div class="grid grid-cols-2 gap-[18px] items-start">
    <div class="flex flex-col gap-[18px]">
        <div class="bg-white border border-border rounded-[24px] shadow-card p-7 flex flex-col gap-[14px]">
            <div class="text-title font-semibold">Ringkasan Pesanan</div>

            <div class="flex justify-between text-body-md text-on-surface-variant">
                <span>{{ $serviceTitle }} · {{ $providerName }}</span>
                <span>{{ 'Rp' . number_format($basePrice, 0, ',', '.') }}</span>
            </div>

            @if($voucherCode && $voucherDisc > 0)
                <div class="flex justify-between text-body-md text-on-success-container">
                    <span>Voucher {{ $voucherCode }}</span>
                    <span>−{{ 'Rp' . number_format($voucherDisc, 0, ',', '.') }}</span>
                </div>
            @endif

            <div class="h-px bg-border"></div>

            <div class="flex justify-between items-baseline">
                <span class="font-semibold text-[15px]">Total Bayar</span>
                <span class="font-bold text-[32px] leading-none tracking-tight">
                    {{ 'Rp' . number_format($amount, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <div class="bg-white border border-border rounded-[24px] shadow-card p-7 flex flex-col gap-4">
            <div class="text-title font-semibold">Instruksi Transfer</div>

            <div class="flex items-center gap-4 p-[18px] rounded-[18px] bg-surface"
                 x-data="{ copied: false }"
                 @keydown.escape="copied = false">
                <span class="w-12 h-12 rounded-[14px] bg-tint text-primary grid place-items-center msr" style="font-size:24px;font-variation-settings:'wght' 300">
                    account_balance
                </span>
                <div class="flex-1">
                    <div class="text-[13px] text-on-surface-muted">
                        Bank BNI · a.n. {{ $providerName }}
                    </div>
                    <div class="font-bold text-[22px] font-mono tracking-widest mt-2">
                        0812 3344 5566
                    </div>
                </div>
                <button
                    type="button"
                    onclick="copyAccount(this)"
                    id="copyBtn"
                    class="h-10 px-4 rounded-full border-0 bg-tint text-primary flex items-center gap-[6px] font-semibold text-[14px] cursor-pointer whitespace-nowrap hover:bg-primary hover:text-white transition-colors"
                >
                    <span class="msr" style="font-size:18px" id="copyIcon">content_copy</span>
                    <span id="copyLabel">Salin</span>
                </button>
            </div>

            <ol class="m-0 pl-5 text-body-md text-on-surface-variant leading-7">
                <li>Transfer tepat <strong>{{ 'Rp' . number_format($amount, 0, ',', '.') }}</strong> ke rekening di atas.</li>
                <li>Simpan bukti transfer berupa foto atau tangkapan layar.</li>
                <li>Unggah bukti di kolom samping, lalu tekan Kirim Bukti.</li>
            </ol>
        </div>
    </div>

    <div class="bg-white border border-border rounded-[24px] shadow-card p-7 flex flex-col gap-[18px]">
        <div class="flex justify-between items-center">
            <div class="text-title font-semibold">Unggah Bukti Pembayaran</div>
            @if($isWaiting)
                <span class="inline-flex items-center gap-2 h-8 px-[14px] rounded-full font-semibold text-[13px] bg-warning-container text-on-warning-container">
                    <span class="w-[7px] h-[7px] rounded-full bg-warning"></span>
                    Menunggu Konfirmasi
                </span>
            @elseif($hasProof)
                <span class="inline-flex items-center gap-2 h-8 px-[14px] rounded-full font-semibold text-[13px] bg-warning-container text-on-warning-container">
                    <span class="w-[7px] h-[7px] rounded-full bg-warning"></span>
                    Menunggu Konfirmasi
                </span>
            @else
                <span class="inline-flex items-center gap-2 h-8 px-[14px] rounded-full font-semibold text-[13px] bg-tint text-on-surface-variant">
                    <span class="w-[7px] h-[7px] rounded-full bg-on-surface-muted"></span>
                    Menunggu Pembayaran
                </span>
            @endif
        </div>

        @if(!$isWaiting)
        <form
            method="POST"
            action="{{ route('payments.store') }}"
            enctype="multipart/form-data"
            id="paymentForm"
            x-data="{
                file: null,
                fileName: '',
                fileSize: '',
                preview: null,
                handleFile(e) {
                    const f = e.target.files[0];
                    if (!f) return;
                    this.file = f;
                    this.fileName = f.name;
                    this.fileSize = f.size < 1048576
                        ? Math.round(f.size/1024) + ' KB'
                        : (f.size/1048576).toFixed(1) + ' MB';
                    if (f.type.startsWith('image/')) {
                        const r = new FileReader();
                        r.onload = ev => this.preview = ev.target.result;
                        r.readAsDataURL(f);
                    } else {
                        this.preview = null;
                    }
                },
                removeFile() {
                    this.file = null;
                    this.fileName = '';
                    this.fileSize = '';
                    this.preview = null;
                    this.$refs.fileInput.value = '';
                }
            }"
        >
            @csrf
            <input type="hidden" name="order_id" value="{{ $order->id }}">

            <div x-show="!file">
                <label
                    for="proofFile"
                    class="h-[260px] rounded-[24px] border-[1.5px] border-dashed border-border bg-surface flex flex-col items-center justify-center gap-[10px] cursor-pointer hover:border-primary hover:bg-tint transition-colors"
                >
                    <span class="w-14 h-14 rounded-full bg-tint text-primary grid place-items-center msr" style="font-size:28px;font-variation-settings:'wght' 300">
                        upload
                    </span>
                    <span class="font-semibold text-[15px] text-on-surface leading-snug">Pilih file atau tarik ke sini</span>
                    <span class="text-[13px] text-on-surface-muted">JPG, PNG, atau PDF · maks. 2 MB</span>
                    <input
                        type="file"
                        name="proof"
                        id="proofFile"
                        accept=".jpg,.jpeg,.png,.pdf"
                        class="hidden"
                        x-ref="fileInput"
                        @change="handleFile($event)"
                    >
                </label>
                @error('proof')
                    <p class="mt-2 text-[13px] text-on-error-container">{{ $message }}</p>
                @enderror
            </div>

            <div x-show="file" class="flex flex-col gap-3">
                <div class="h-[200px] rounded-[18px] overflow-hidden bg-surface-container flex items-center justify-center">
                    <template x-if="preview">
                        <img :src="preview" class="w-full h-full object-contain" alt="Pratinjau bukti transfer">
                    </template>
                    <template x-if="!preview">
                        <div class="flex flex-col items-center gap-2 text-on-surface-muted">
                            <span class="msr" style="font-size:40px">picture_as_pdf</span>
                            <span class="text-[13px]" x-text="fileName"></span>
                        </div>
                    </template>
                </div>

                <div class="flex items-center gap-3 p-3 px-[14px] rounded-[18px] border border-border">
                    <span class="w-[38px] h-[38px] rounded-[12px] bg-tint text-primary grid place-items-center msr" style="font-size:20px">
                        image
                    </span>
                    <div class="flex-1">
                        <div class="font-semibold text-[14px] leading-snug" x-text="fileName"></div>
                        <div class="text-[12px] text-on-surface-muted mt-0.5" x-text="fileSize + ' · dipilih baru saja'"></div>
                    </div>
                    <button
                        type="button"
                        @click="removeFile()"
                        aria-label="Hapus file"
                        class="w-9 h-9 rounded-full border border-border bg-white grid place-items-center text-on-error-container p-0 hover:bg-error-container"
                    >
                        <span class="msr" style="font-size:18px">delete</span>
                    </button>
                </div>
            </div>

            <button
                type="submit"
                class="mt-[18px] w-full h-[50px] rounded-full border-0 font-bold text-[15px] cursor-pointer transition-colors"
                :class="file ? 'bg-primary text-white hover:bg-primary-hover' : 'bg-border text-on-surface-muted cursor-not-allowed'"
                :disabled="!file"
            >
                Kirim Bukti
            </button>

            <div class="flex gap-5 justify-center text-[13px] text-on-surface-muted mt-1">
                <span>1. Menunggu Pembayaran</span>
                <span>2. Menunggu Konfirmasi</span>
                <span>3. Lunas</span>
            </div>
        </form>

        @else
        <div class="flex flex-col items-center text-center gap-4 py-8">
            <span class="w-16 h-16 rounded-full bg-warning-container text-on-warning-container grid place-items-center msr" style="font-size:32px">hourglass_top</span>
            <div>
                <div class="font-bold text-[20px]">Bukti Terkirim</div>
                <p class="mt-2 text-body-md text-on-surface-muted max-w-[280px]">
                    Penyedia sedang memverifikasi bukti transfer. Kamu akan mendapat notifikasi setelah dikonfirmasi.
                </p>
            </div>
            @if($existingPayment->proof_path)
                <div class="w-full h-[150px] rounded-[18px] overflow-hidden bg-surface-container flex items-center justify-center">
                    @if(str_ends_with($existingPayment->proof_path, '.pdf'))
                        <span class="text-on-surface-muted text-[13px]">pratinjau bukti transfer (PDF)</span>
                    @else
                        <img src="{{ Storage::url($existingPayment->proof_path) }}"
                             class="w-full h-full object-contain"
                             alt="Bukti transfer yang diunggah">
                    @endif
                </div>
                <div class="flex items-center gap-3 w-full p-3 px-[14px] rounded-[18px] border border-border">
                    <span class="w-[38px] h-[38px] rounded-[12px] bg-tint text-primary grid place-items-center msr" style="font-size:20px">image</span>
                    <div class="flex-1 text-left">
                        <div class="font-semibold text-[14px]">{{ $existingPayment->proof_original_name ?? 'bukti-transfer.jpg' }}</div>
                        <div class="text-[12px] text-on-surface-muted">
                            {{ $existingPayment->proofSizeFormatted() }}
                            @if($existingPayment->proof_uploaded_at)
                                · diunggah {{ $existingPayment->proof_uploaded_at->translatedFormat('d M Y, H.i') }} WIB
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <button disabled class="w-full h-[50px] rounded-full bg-border text-on-surface-muted font-bold text-[15px] cursor-not-allowed">
                Bukti Terkirim
            </button>

            <div class="flex gap-5 justify-center text-[13px] text-on-surface-muted">
                <span>1. Menunggu Pembayaran</span>
                <span class="font-semibold text-on-warning-container">2. Menunggu Konfirmasi</span>
                <span>3. Lunas</span>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
    function copyAccount(btn) {
        navigator.clipboard.writeText('081233445566').then(() => {
            const icon  = document.getElementById('copyIcon');
            const label = document.getElementById('copyLabel');
            icon.textContent  = 'check';
            label.textContent = 'Tersalin';
            setTimeout(() => {
                icon.textContent  = 'content_copy';
                label.textContent = 'Salin';
            }, 1800);
        });
    }
</script>
@endpush
