{{--
    PBI-10 p10-2 — form ajukan / ubah request jasa (dipakai create & edit).
    Param: $service (Service, dengan provider & category), $item (ServiceRequest|null),
           $action (URL), $method ('POST'|'PUT'), $desc, $submit (label tombol), $cancelUrl.
--}}
@php
    $item    = $item ?? null;
    $field   = 'w-full px-5 rounded-full border bg-white text-[15px] text-on-surface outline-none focus:border-primary focus:ring-[3px] focus:ring-tint-strong min-w-0 aria-[invalid=true]:border-error';
    $bd      = fn ($f) => $errors->has($f) ? 'border-error' : 'border-border';
    $lbl     = 'text-[14px] leading-[1.2] font-semibold';
    $prov    = $service->provider;
    $byline  = collect([$prov?->name, collect([$prov?->major, $prov?->angkatan])->filter()->implode(' ')])->filter()->implode(' · ');
    $img     = \App\Http\Controllers\SearchController::imageFor($service->category?->nama_kategori);
    $err     = fn ($f) => $errors->has($f) ? 'aria-invalid=true aria-describedby='.$f.'_err' : '';
@endphp

{{-- Breadcrumb + judul --}}
<div class="pt-2 pb-8">
    <nav class="flex flex-wrap items-center gap-2 text-[13px] text-on-surface-muted" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="no-underline">Jelajahi Jasa</a><span aria-hidden="true">/</span>
        <span class="truncate max-w-[260px]">{{ $service->judul }}</span><span aria-hidden="true">/</span>
        <span class="text-on-surface font-semibold" aria-current="page">{{ $item ? 'Ubah Request' : 'Ajukan Request' }}</span>
    </nav>
    <div class="flex flex-wrap items-center gap-x-3.5 gap-y-2 mt-3.5">
        <h1 class="m-0 text-[26px] sm:text-[32px] leading-[1.15] tracking-[-0.02em] font-bold">{{ $item ? 'Ubah Request' : 'Ajukan Request' }}</h1>
    </div>
    <p class="mt-2.5 text-[15px] leading-normal text-on-surface-muted">{{ $desc }}</p>
</div>

<div class="max-w-[900px] bg-white border border-border rounded-3xl shadow-card overflow-hidden">
    {{-- Ringkasan jasa --}}
    <div class="flex flex-wrap sm:flex-nowrap gap-4 sm:gap-5 items-center p-5 border-b border-border bg-surface">
        <img src="{{ asset('images/dashboard/'.$img) }}" alt="" class="w-[120px] sm:w-[150px] h-[84px] sm:h-[100px] flex-none rounded-[18px] object-cover">
        <div class="flex-1 min-w-[180px]">
            <span class="inline-block h-6 px-2.5 rounded-full bg-tint text-on-secondary-container text-[13px] leading-6">{{ $service->category?->nama_kategori ?? 'Jasa' }}</span>
            <div class="mt-2 text-[18px] leading-[1.28] font-semibold">{{ $service->judul }}</div>
            <div class="mt-1 text-[14px] leading-[1.4] text-on-surface-muted">Penyedia: {{ $byline ?: 'Penyedia SKENA' }}</div>
        </div>
        <div class="sm:text-right">
            <div class="text-[13px] text-on-surface-muted">Mulai</div>
            <div class="mt-1 text-[24px] leading-[1.2] font-bold">{{ $service->harga_label }}</div>
        </div>
    </div>

    <form method="POST" action="{{ $action }}" data-validate class="p-6 sm:p-8 flex flex-col gap-5">
        @csrf
        @if($method !== 'POST') @method($method) @endif
        @unless($item)<input type="hidden" name="service_id" value="{{ $service->id }}">@endunless

        @if($errors->any() || session('error'))
            <p class="text-[14px] font-semibold text-on-error-container bg-error-container rounded-[18px] px-[18px] py-3.5" role="alert">
                {{ session('error') ?? 'Ada isian yang perlu diperbaiki. Periksa kolom bertanda merah lalu kirim lagi.' }}
            </p>
        @endif

        <div class="fld flex flex-col gap-2">
            <label for="kebutuhan" class="{{ $lbl }}">Kebutuhan</label>
            <textarea id="kebutuhan" name="kebutuhan" rows="4" maxlength="5000" required
                      data-msg-required="Tuliskan kebutuhanmu agar penyedia paham apa yang diminta."
                      placeholder="Contoh: desain ulang 15 slide sidang dengan tema biru"
                      class="{{ $field }} {{ $bd('kebutuhan') }} !rounded-[20px] py-3.5 leading-normal resize-y min-h-[120px]" {!! $err('kebutuhan') !!}>{{ old('kebutuhan', $item?->kebutuhan) }}</textarea>
            <p id="kebutuhan_err" class="err flex gap-1.5 items-center text-[13px] leading-[1.45] text-on-error-container" @unless($errors->has('kebutuhan')) hidden @endunless>
                <span class="msr" style="font-size:17px">error</span><span data-err-text>{{ $errors->first('kebutuhan') }}</span></p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[18px]">
            <div class="fld flex flex-col gap-2">
                <label for="tanggal" class="{{ $lbl }}">Tanggal</label>
                <input type="date" id="tanggal" name="tanggal" required min="{{ now()->toDateString() }}"
                       data-msg-required="Pilih tanggal hasil dibutuhkan."
                       data-msg-invalid="Pilih tanggal hari ini atau setelahnya."
                       value="{{ old('tanggal', $item?->tanggal?->format('Y-m-d')) }}"
                       class="{{ $field }} {{ $bd('tanggal') }} h-12" {!! $err('tanggal') !!}>
                <p class="hint text-[13px] leading-[1.45] text-on-surface-muted" @if($errors->has('tanggal')) hidden @endif>Tanggal hasil dibutuhkan.</p>
                <p id="tanggal_err" class="err flex gap-1.5 items-center text-[13px] leading-[1.45] text-on-error-container" @unless($errors->has('tanggal')) hidden @endunless>
                    <span class="msr" style="font-size:17px">error</span><span data-err-text>{{ $errors->first('tanggal') }}</span></p>
            </div>
            <div class="fld flex flex-col gap-2">
                <label for="catatan" class="{{ $lbl }}">Catatan <span class="font-normal text-on-surface-muted">(opsional)</span></label>
                <input id="catatan" name="catatan" maxlength="2000" value="{{ old('catatan', $item?->catatan) }}"
                       placeholder="Contoh: file draf dikirim lewat Google Drive"
                       class="{{ $field }} {{ $bd('catatan') }} h-12" {!! $err('catatan') !!}>
                <p id="catatan_err" class="err flex gap-1.5 items-center text-[13px] leading-[1.45] text-on-error-container" @unless($errors->has('catatan')) hidden @endunless>
                    <span class="msr" style="font-size:17px">error</span><span data-err-text>{{ $errors->first('catatan') }}</span></p>
            </div>
        </div>

        <div class="flex flex-col-reverse sm:flex-row justify-end gap-2.5 mt-1">
            <a href="{{ $cancelUrl }}" class="h-12 px-7 rounded-full border border-border text-on-surface flex items-center justify-center text-[14px] font-bold no-underline">Batal</a>
            <button type="submit" class="h-12 px-7 rounded-full bg-primary hover:bg-primary-hover text-white text-[14px] font-bold" data-loading="Mengirim…">{{ $submit }}</button>
        </div>
    </form>
</div>
