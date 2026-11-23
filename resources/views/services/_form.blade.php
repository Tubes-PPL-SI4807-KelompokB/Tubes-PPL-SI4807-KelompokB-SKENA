{{--
    PBI-04 p04-2 — form tambah/ubah jasa + pratinjau kartu (dipakai create & edit).
    Param: $service (Service), $categories, $durations, $action (URL), $method ('POST'|'PUT'), $kicker, $desc.
--}}
@php
    $isEdit  = $service->exists;
    $catId   = (string) old('category_id', $service->category_id ?? $categories->first()?->id);
    $harga   = old('harga', $service->harga !== null ? number_format((float) $service->harga, 0, ',', '.') : '');
    $aktif   = (bool) old('aktif', ($service->status ?? 'aktif') === 'aktif');
    $catMap  = $categories->mapWithKeys(fn ($c) => [(string) $c->id => ['name' => $c->nama_kategori, 'icon' => $c->icon]]);
    $field   = 'h-12 w-full px-5 rounded-full border bg-white text-[15px] text-on-surface outline-none focus:border-primary focus:ring-[3px] focus:ring-tint-strong';
    $bd      = fn ($f) => $errors->has($f) ? 'border-error' : 'border-border';
    $lbl     = 'text-[14px] leading-[1.2] font-semibold';
    $user    = auth()->user();
    $byline  = collect([$user->name, collect([$user->major, $user->angkatan])->filter()->implode(' ')])->filter()->implode(' · ');
@endphp

{{-- Judul --}}
<div class="pt-2 pb-8">
    <div class="text-[11px] leading-[1.2] font-bold tracking-[0.06em] text-primary">{{ $kicker }}</div>
    <div class="flex flex-wrap items-center gap-x-3.5 gap-y-2 mt-2.5">
        <span class="h-[30px] px-3.5 rounded-full bg-primary text-white text-[13px] leading-[30px] font-bold">PBI-04</span>
        <h1 class="m-0 text-[26px] sm:text-[32px] leading-[1.15] tracking-[-0.02em] font-bold">Manajemen Jasa</h1>
        <span class="text-[14px] font-semibold text-on-surface-muted">Sprint 1</span>
    </div>
    <p class="mt-2.5 text-[15px] leading-normal text-on-surface-muted">{{ $desc }}</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-[18px] items-start"
     x-data="{
        title: @js(old('judul', $service->judul ?? '')),
        cat: @js($catId),
        price: @js((string) $harga),
        dur: @js(old('durasi', $service->durasi ?? '2 hari')),
        on: @js($aktif),
        cats: @js($catMap),
        get priceLabel() { const n = (this.price || '').replace(/\D/g, ''); return 'Rp' + (n ? Number(n).toLocaleString('id-ID') : '0') + (this.dur.includes('jam') ? '/jam' : ''); },
        formatPrice() { const n = (this.price || '').replace(/\D/g, ''); this.price = n ? Number(n).toLocaleString('id-ID') : ''; },
     }">

    <form method="POST" action="{{ $action }}" novalidate
          class="bg-white border border-border rounded-3xl shadow-card p-6 sm:p-8 flex flex-col gap-5">
        @csrf
        @if($method !== 'POST') @method($method) @endif

        @if($errors->any())
            <p class="text-[14px] font-semibold text-on-error-container bg-error-container rounded-[18px] px-[18px] py-3.5" role="alert">
                Ada isian yang perlu diperbaiki. Periksa kolom bertanda merah lalu simpan lagi.
            </p>
        @endif

        <div class="flex flex-col gap-2">
            <label for="judul" class="{{ $lbl }}">Judul Jasa</label>
            <input id="judul" name="judul" x-model="title" maxlength="150" placeholder="Contoh: Desain PPT Sidang" required
                   class="{{ $field }} {{ $bd('judul') }}" @error('judul') aria-invalid="true" aria-describedby="judul_err" @enderror>
            @error('judul')<p id="judul_err" class="flex gap-1.5 items-center text-[13px] leading-[1.45] text-on-error-container"><span class="msr" style="font-size:17px">error</span>{{ $message }}</p>@enderror
        </div>

        <div class="flex flex-col gap-2">
            <label for="category_id" class="{{ $lbl }}">Kategori</label>
            <select id="category_id" name="category_id" x-model="cat" required class="{{ $field }} {{ $bd('category_id') }}"
                    @error('category_id') aria-invalid="true" aria-describedby="cat_err" @enderror>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" @selected($catId === (string) $c->id)>{{ $c->nama_kategori }}</option>
                @endforeach
            </select>
            @error('category_id')<p id="cat_err" class="flex gap-1.5 items-center text-[13px] leading-[1.45] text-on-error-container"><span class="msr" style="font-size:17px">error</span>{{ $message }}</p>@enderror
        </div>

        <div class="flex flex-col gap-2">
            <label for="deskripsi" class="{{ $lbl }}">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" rows="4" maxlength="2000" required
                      placeholder="Jelaskan apa yang dikerjakan, batas revisi, dan hasil yang diterima pemesan."
                      class="px-5 py-3.5 rounded-[20px] border bg-white text-[15px] leading-normal text-on-surface outline-none resize-none focus:border-primary focus:ring-[3px] focus:ring-tint-strong {{ $bd('deskripsi') }}"
                      @error('deskripsi') aria-invalid="true" aria-describedby="desc_err" @enderror>{{ old('deskripsi', $service->deskripsi) }}</textarea>
            @error('deskripsi')<p id="desc_err" class="flex gap-1.5 items-center text-[13px] leading-[1.45] text-on-error-container"><span class="msr" style="font-size:17px">error</span>{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[18px]">
            <div class="flex flex-col gap-2 min-w-0">
                <label for="harga" class="{{ $lbl }}">Harga (Rp)</label>
                <span class="flex items-center gap-1.5 h-12 px-5 rounded-full border bg-white focus-within:border-primary focus-within:ring-[3px] focus-within:ring-tint-strong {{ $bd('harga') }}">
                    <span class="text-[15px] font-semibold text-on-surface-muted">Rp</span>
                    <input id="harga" name="harga" x-model="price" @blur="formatPrice()" inputmode="numeric" required placeholder="20.000"
                           class="flex-1 min-w-0 border-0 outline-none bg-transparent text-[15px] text-on-surface"
                           @error('harga') aria-invalid="true" aria-describedby="harga_err" @else aria-describedby="harga_hint" @enderror>
                </span>
                @error('harga')
                    <p id="harga_err" class="flex gap-1.5 items-center text-[13px] leading-[1.45] text-on-error-container"><span class="msr" style="font-size:17px">error</span>{{ $message }}</p>
                @else
                    <p id="harga_hint" class="text-[13px] leading-[1.45] text-on-surface-muted">Minimal Rp5.000.</p>
                @enderror
            </div>
            <div class="flex flex-col gap-2 min-w-0">
                <label for="durasi" class="{{ $lbl }}">Durasi Pengerjaan</label>
                <select id="durasi" name="durasi" x-model="dur" required class="{{ $field }} {{ $bd('durasi') }}">
                    @foreach($durations as $d)
                        <option value="{{ $d }}" @selected(old('durasi', $service->durasi) === $d)>{{ $d }}</option>
                    @endforeach
                </select>
                @error('durasi')<p class="flex gap-1.5 items-center text-[13px] leading-[1.45] text-on-error-container"><span class="msr" style="font-size:17px">error</span>{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="flex justify-between items-center gap-4 px-5 py-4 rounded-[18px] border border-border">
            <div>
                <div class="{{ $lbl }}" id="aktif_lbl">Aktifkan jasa</div>
                <div class="mt-0.5 text-[13px] leading-[1.4] text-on-surface-muted" x-text="on ? 'Jasa langsung tampil di pencarian' : 'Disimpan sebagai draf, belum tampil'"></div>
            </div>
            <input type="hidden" name="aktif" :value="on ? 1 : 0" value="{{ $aktif ? 1 : 0 }}">
            <button type="button" class="switch" role="switch" :aria-checked="on.toString()" aria-checked="{{ $aktif ? 'true' : 'false' }}" aria-labelledby="aktif_lbl" @click="on = !on"></button>
        </div>

        <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2.5">
            <a href="{{ route('services.index') }}" class="h-12 px-7 rounded-full border border-border bg-white text-on-surface flex items-center justify-center text-[14px] font-bold">Batal</a>
            <button type="submit" class="h-12 px-7 rounded-full bg-primary hover:bg-primary-hover text-white text-[14px] font-bold" data-loading="Menyimpan…">{{ $isEdit ? 'Simpan Perubahan' : 'Simpan Jasa' }}</button>
        </div>
    </form>

    {{-- Pratinjau kartu bagi pencari jasa --}}
    <div class="flex flex-col gap-3" aria-label="Pratinjau kartu jasa">
        <span class="text-[11px] leading-[1.2] font-bold tracking-[0.06em] text-on-surface-muted">PRATINJAU KARTU</span>
        <div class="w-full max-w-[340px] bg-white border border-border rounded-3xl p-3 shadow-card">
            <div class="relative h-[190px] rounded-[18px] grid place-items-center text-primary" style="background:repeating-linear-gradient(135deg,#E3ECF4 0 8px,#EDF3F8 8px 16px)">
                <span class="msr" style="font-size:44px" aria-hidden="true" x-text="cats[cat]?.icon || 'storefront'">storefront</span>
                @unless($isEdit)<span class="absolute top-3 left-3 h-[26px] px-3 rounded-full bg-primary text-white text-[11px] leading-[26px] font-bold tracking-[0.06em]">BARU</span>@endunless
            </div>
            <div class="pt-3.5 px-1.5 pb-1">
                <div class="flex justify-between items-center gap-2">
                    <span class="h-6 px-2.5 rounded-full bg-tint text-[#346284] text-[13px] leading-6 truncate" x-text="cats[cat]?.name || 'Kategori'"></span>
                    <span class="flex items-center gap-1 text-[13px] text-on-surface-muted whitespace-nowrap"><span class="msr" style="font-size:16px">schedule</span><span x-text="dur"></span></span>
                </div>
                <div class="mt-3 min-h-[44px] text-[17px] leading-[1.3] font-semibold break-words" x-text="title || 'Judul jasa'"></div>
                <div class="mt-1.5 text-[13px] leading-[1.4] text-on-surface-muted">{{ $byline }}</div>
                <div class="flex justify-between items-center mt-4">
                    <span class="text-[18px] leading-none font-bold" x-text="priceLabel"></span>
                    <span class="h-10 px-[18px] rounded-full bg-tint text-primary text-[14px] leading-10 font-semibold">Pesan</span>
                </div>
            </div>
        </div>
        <div class="w-full max-w-[340px] flex gap-2.5 items-center px-4 py-3 rounded-[18px] bg-tint text-on-surface-variant text-[13px] leading-[1.45]">
            <span class="msr text-primary">info</span>
            <span x-text="on ? 'Jasa aktif akan tampil di Jelajahi Jasa dan Rekomendasi Kampus.' : 'Jasa nonaktif hanya terlihat olehmu.'"></span>
        </div>
    </div>
</div>
