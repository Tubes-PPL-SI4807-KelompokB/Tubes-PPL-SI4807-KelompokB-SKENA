@extends('layouts.app')
@section('title', 'Detail Pesanan ' . $order->code . ' — SKENA')

{{--
    PBI-11 p11-2 Detail & Ubah Pesanan — pencari dapat mengubah catatan dan menerapkan
    voucher (PBI-09) sebelum pembayaran (PBI-13). Penyedia & admin melihat versi baca saja.
--}}

@php
    $rp   = fn ($n) => 'Rp' . number_format((float) $n, 0, ',', '.');
    [$bg, $fg, $dot] = $order->status_tone;
    $initials = fn (?string $name) => collect(preg_split('/\s+/', trim((string) $name)))->filter()->take(2)->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('') ?: '?';
    $meta = fn ($u) => trim(($u?->major ?? '') . ' ' . ($u?->angkatan ?? '')) ?: 'Mahasiswa SKENA';
    $people = [
        'PENYEDIA' => $order->service?->provider,
        'PENCARI'  => $order->seeker,
    ];
    $voucherCode = old('kode_voucher', $order->voucher?->code);
    $voucherErr  = $errors->has('kode_voucher');
@endphp

@section('content')
    {{-- Judul --}}
    <div class="pt-2 pb-8">
        <div class="text-[11px] leading-[1.2] font-bold tracking-[0.06em] text-primary">DETAIL PESANAN · {{ $order->code }}</div>
        <h1 class="mt-2.5 text-[26px] sm:text-[32px] leading-[1.15] tracking-[-0.02em] font-bold">Manajemen Pesanan</h1>
        <p class="mt-2.5 text-[15px] leading-normal text-on-surface-muted">
            {{ $editable ? 'Ubah catatan atau terapkan voucher, lalu lanjutkan ke pembayaran.' : 'Rincian pesanan dan ringkasan biaya.' }}
        </p>
    </div>

    <form method="POST" action="{{ route('orders.update', $order) }}" class="grid grid-cols-1 lg:grid-cols-2 gap-[18px] items-start">
        @csrf @method('PATCH')

        {{-- Kartu kiri: jasa, pihak terlibat, catatan --}}
        <section class="bg-white border border-border rounded-3xl shadow-card p-6 sm:p-8 flex flex-col gap-[22px]" aria-labelledby="orderSvc">
            <div class="flex flex-wrap gap-[18px] items-center">
                <div class="w-[120px] h-[84px] flex-none rounded-[18px] grid place-items-center text-primary" style="background:repeating-linear-gradient(135deg,#E3ECF4 0 8px,#EDF3F8 8px 16px)" aria-hidden="true">
                    <span class="msr" style="font-size:32px">design_services</span>
                </div>
                <div class="min-w-0">
                    @if($order->service?->category)
                        <span class="h-6 px-2.5 rounded-full bg-tint text-[#346284] text-[13px] leading-6 inline-block">{{ $order->service->category->nama_kategori }}</span>
                    @endif
                    <h2 id="orderSvc" class="mt-2 text-[18px] leading-[1.28] font-semibold">{{ $order->service?->judul ?? 'Jasa sudah dihapus' }}</h2>
                    <div class="flex flex-wrap items-center gap-2 mt-1.5">
                        <span class="h-7 px-3 rounded-full inline-flex items-center gap-1.5 text-[12px] font-semibold" style="background:{{ $bg }};color:{{ $fg }}">
                            <span class="w-1.5 h-1.5 rounded-full" style="background:{{ $dot }}"></span>{{ $order->status_label }}
                        </span>
                        @if($order->serviceRequest?->tanggal)
                            <span class="text-[13px] text-on-surface-muted">Tenggat {{ $order->serviceRequest->tanggal->translatedFormat('j M Y') }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-[18px]">
                @foreach($people as $role => $person)
                    <div class="p-4 rounded-[18px] bg-surface">
                        <div class="text-[11px] leading-[1.2] font-bold tracking-[0.06em] text-on-surface-muted">{{ $role }}</div>
                        <div class="flex gap-2.5 items-center mt-2.5">
                            <span class="w-9 h-9 flex-none rounded-full bg-tint text-primary grid place-items-center text-[13px] font-bold" aria-hidden="true">{{ $initials($person?->name) }}</span>
                            <div class="min-w-0">
                                <div class="text-[14px] leading-[1.3] font-semibold truncate">{{ $person?->name ?? 'Pengguna SKENA' }}</div>
                                <div class="text-[12px] leading-[1.4] text-on-surface-muted truncate">{{ $meta($person) }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($order->serviceRequest?->kebutuhan)
                <div>
                    <div class="text-[14px] font-semibold">Kebutuhan</div>
                    <p class="mt-1.5 text-[14px] leading-normal text-on-surface-variant whitespace-pre-line [overflow-wrap:anywhere]">{{ $order->serviceRequest->kebutuhan }}</p>
                </div>
            @endif

            <div class="flex flex-col gap-2">
                <label for="catatan" class="text-[14px] font-semibold">Catatan</label>
                @if($editable)
                    <textarea id="catatan" name="catatan" rows="3" maxlength="1000"
                              placeholder="Contoh: 15 slide, tema biru tua, 2 slide diagram alur."
                              class="px-5 py-3.5 rounded-[20px] border bg-white text-[15px] leading-normal text-on-surface outline-none resize-none focus:border-primary focus:ring-[3px] focus:ring-tint-strong {{ $errors->has('catatan') ? 'border-error' : 'border-border' }}"
                              @error('catatan') aria-invalid="true" aria-describedby="catatanErr" @enderror>{{ old('catatan', $order->catatan) }}</textarea>
                    @error('catatan')<p id="catatanErr" class="text-[13px] text-on-error-container">{{ $message }}</p>@enderror
                @else
                    <p id="catatan" class="text-[14px] leading-normal text-on-surface-variant whitespace-pre-line [overflow-wrap:anywhere]">{{ $order->catatan ?: 'Tidak ada catatan.' }}</p>
                @endif
            </div>

            <div class="flex justify-between items-center pt-[18px] border-t border-border">
                <span class="text-[15px] text-on-surface-muted">Harga jasa</span>
                <span class="text-[18px] font-bold">{{ $rp($order->harga_jasa) }}</span>
            </div>
        </section>

        {{-- Kartu kanan: ringkasan biaya + voucher --}}
        <section class="bg-white border border-border rounded-3xl shadow-card p-6 sm:p-8 flex flex-col gap-5" aria-labelledby="costTitle">
            <h2 id="costTitle" class="text-[18px] leading-[1.28] font-semibold">Ringkasan Biaya</h2>

            @if($editable)
                <div class="flex flex-col gap-2">
                    <label for="kodeVoucher" class="text-[14px] font-semibold">Kode Voucher</label>
                    <div class="flex gap-2.5">
                        <input id="kodeVoucher" name="kode_voucher" value="{{ $voucherCode }}" maxlength="30" autocomplete="off"
                               placeholder="Contoh: MAHASISWA10"
                               class="flex-1 min-w-0 h-12 px-5 rounded-full bg-white font-mono font-bold text-[15px] uppercase text-on-surface outline-none focus:ring-[3px] focus:ring-tint-strong border {{ $voucherErr ? 'border-error' : ($order->voucher_id ? 'border-success' : 'border-border') }}"
                               aria-describedby="voucherMsg" @if($voucherErr) aria-invalid="true" @endif>
                        <button type="submit" name="aksi" value="terapkan" formnovalidate
                                class="h-12 px-6 rounded-full bg-tint text-primary hover:bg-primary hover:text-white text-[14px] font-bold transition-colors" data-loading="Menerapkan…">Terapkan</button>
                    </div>
                    <p id="voucherMsg" class="text-[13px] leading-[1.45] {{ $voucherErr ? 'text-on-error-container' : 'text-on-success-container' }}" aria-live="polite">
                        @if($voucherErr)
                            {{ $errors->first('kode_voucher') }}
                        @elseif(session('voucher_msg'))
                            {{ session('voucher_msg') }}
                        @elseif($order->voucher_id)
                            Voucher {{ $order->voucher->code }} diterapkan, hemat {{ $rp($order->voucher_discount) }}.
                        @endif
                    </p>
                </div>
            @endif

            <div class="flex flex-col gap-3.5 p-5 rounded-[18px] bg-surface">
                <div class="flex justify-between text-[15px] text-on-surface-variant"><span>Harga jasa</span><span>{{ $rp($order->harga_jasa) }}</span></div>
                <div class="flex justify-between text-[15px] text-on-success-container">
                    <span>Potongan voucher @if($order->voucher)({{ $order->voucher->value_label }})@endif</span>
                    <span>−{{ $rp($order->voucher_discount) }}</span>
                </div>
                <div class="h-px bg-border"></div>
                <div class="flex justify-between items-baseline">
                    <span class="text-[15px] font-semibold">Total</span>
                    <span class="text-[28px] sm:text-[32px] leading-none tracking-[-0.02em] font-bold">{{ $rp($order->final_price) }}</span>
                </div>
            </div>

            @php
                $isSeeker  = $order->isSeeker(auth()->user());
                $payStatus = $order->payment?->status;
                $unpaid    = in_array($order->status, [\App\Models\Order::DIKERJAKAN, \App\Models\Order::REVISI], true)
                    && (! $payStatus || in_array($payStatus, [\App\Models\Payment::STATUS_MENUNGGU_PEMBAYARAN, \App\Models\Payment::STATUS_DITOLAK], true));
            @endphp

            {{-- Status pembayaran (PBI-13) dijelaskan sesuai peran yang melihat --}}
            @if($unpaid && $isSeeker)
                <div class="flex gap-3 items-start px-4 py-3.5 rounded-[18px] bg-[#FEF3C7] text-[#B45309] text-[14px] leading-normal font-semibold" role="status">
                    <span class="msr" style="font-size:20px">payments</span>
                    <span>{{ $payStatus === \App\Models\Payment::STATUS_DITOLAK ? 'Bukti bayar sebelumnya ditolak. Unggah ulang bukti transfer yang sesuai.' : 'Pesanan ini belum dibayar. Terapkan voucher bila ada, lalu klik Lanjut ke Pembayaran.' }}</span>
                </div>
            @elseif($unpaid)
                <div class="flex gap-3 items-start px-4 py-3.5 rounded-[18px] bg-tint text-[#346284] text-[14px] leading-normal font-semibold" role="status">
                    <span class="msr" style="font-size:20px">hourglass_top</span>
                    <span>Menunggu pembayaran dari {{ $order->seeker?->name ?? 'pencari jasa' }}. Kamu akan mendapat notifikasi saat bukti transfer dikirim.</span>
                </div>
            @elseif($order->payment)
                <p class="text-[13px] text-on-surface-muted">Status pembayaran: <span class="font-semibold text-on-surface">{{ $order->payment->statusLabel() }}</span></p>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-[1fr_1.4fr] gap-2.5 mt-1">
                <a href="{{ route('orders.index') }}" class="h-12 rounded-full border border-border bg-white text-on-surface flex items-center justify-center text-[14px] font-bold">Kembali</a>
                @if($editable)
                    <button type="submit" name="aksi" value="simpan" class="h-12 rounded-full bg-primary hover:bg-primary-hover text-white text-[14px] font-bold inline-flex items-center justify-center gap-1.5" data-loading="Menyimpan…"><span class="msr" style="font-size:18px">payments</span>Lanjut ke Pembayaran</button>
                @elseif($order->payment && ($isSeeker || $order->isProvider(auth()->user())))
                    <a href="{{ route('payments.show', $order->payment) }}" class="h-12 rounded-full bg-primary hover:bg-primary-hover text-white flex items-center justify-center text-[14px] font-bold">Lihat Pembayaran</a>
                @endif
            </div>
        </section>
    </form>
@endsection
