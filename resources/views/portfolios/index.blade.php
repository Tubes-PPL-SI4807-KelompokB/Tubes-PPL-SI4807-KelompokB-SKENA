@extends('layouts.app')

@section('title', 'Kelola Portfolio - SKENA')

@section('content')
<div class="flex flex-col lg:flex-row gap-6">

    <!-- Sidebar Navigasi Profil Mahasiswa (Sesuai Mockup Gambar 4.5.5 - 4.5.7) -->
    <aside class="w-full lg:w-56 flex-shrink-0">
        <div class="bg-white rounded-2xl border border-slate-200 p-3 shadow-sm space-y-1">
            <a href="{{ Route::has('profile.index') ? route('profile.index') : '#' }}" class="flex items-center px-4 py-2.5 text-sm font-medium text-slate-600 rounded-xl hover:bg-slate-50 transition">
                Profil
            </a>
            <a href="{{ Route::has('skills.index') ? route('skills.index') : '#' }}" class="flex items-center px-4 py-2.5 text-sm font-medium text-slate-600 rounded-xl hover:bg-slate-50 transition">
                Skill
            </a>
            <a href="{{ route('services.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium text-slate-600 rounded-xl hover:bg-slate-50 transition">
                Jasa saya
            </a>
            <a href="{{ route('portfolios.index') }}" class="flex items-center px-4 py-2.5 text-sm font-semibold text-blue-600 bg-blue-50 rounded-xl transition">
                Portfolio
            </a>
            <a href="{{ Route::has('orders.index') ? route('orders.index') : '#' }}" class="flex items-center px-4 py-2.5 text-sm font-medium text-slate-600 rounded-xl hover:bg-slate-50 transition">
                Pesanan masuk
            </a>
        </div>
    </aside>

    <!-- Main Content Area: Kelola Portfolio (Gambar 4.5.7) -->
    <div class="flex-1 grid grid-cols-1 xl:grid-cols-3 gap-6">
        
        <!-- Left 2 Cols: Header, Filter, and Grid of Portfolio Cards -->
        <div class="xl:col-span-2 space-y-5">
            <!-- Header Section -->
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Kelola Portfolio</h1>
                <p class="text-sm text-slate-500 mt-1">
                    Tampilkan contoh hasil karya nyata. Setiap karya bisa ditautkan ke jasa yang kamu tawarkan dan tampil di halaman detail jasa.
                </p>
            </div>

            <!-- Filter Bar & Counter -->
            <div class="flex flex-wrap items-center justify-between gap-3 bg-white p-3 rounded-2xl border border-slate-200 shadow-sm">
                <form action="{{ route('portfolios.index') }}" method="GET" class="flex items-center gap-2">
                    <select name="service_id" onchange="this.form.submit()" 
                            class="text-sm bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua jasa</option>
                        @foreach ($services as $svc)
                            <option value="{{ $svc->service_id }}" {{ request('service_id') == $svc->service_id ? 'selected' : '' }}>
                                {{ $svc->judul }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <div class="flex items-center gap-3">
                    <span class="text-xs font-semibold px-3 py-1.5 rounded-full bg-slate-100 text-slate-600">
                        {{ $totalCount }} karya
                    </span>
                    <a href="#form-tambah-karya" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Tambah karya
                    </a>
                </div>
            </div>

            <!-- Portfolio Cards Grid (2 Kolom) -->
            @if ($portfolios->isEmpty())
                <div class="bg-white rounded-2xl border border-dashed border-slate-300 p-12 text-center">
                    <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </div>
                    <h3 class="font-semibold text-slate-800 text-base">Belum ada karya di portofolio</h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                        Gunakan form di samping untuk mengunggah hasil karya terbaikmu agar pencari jasa semakin yakin.
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($portfolios as $portfolio)
                        @php
                            $mediaType = $portfolio->media_type;
                            // Palette warna thumbnail sesuai Mockup Gambar 4.5.7
                            $bgColors = [
                                'Gambar karya' => 'bg-blue-50 text-blue-600 border-blue-100',
                                'Berkas PDF' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                'Tautan video' => 'bg-amber-50 text-amber-600 border-amber-100',
                                'Tangkapan layar' => 'bg-purple-50 text-purple-600 border-purple-100',
                            ];
                            $colorClass = $bgColors[$mediaType] ?? 'bg-slate-50 text-slate-600 border-slate-100';
                        @endphp
                        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm flex flex-col hover:border-slate-300 transition">
                            <!-- Thumbnail Area (Mockup Box) -->
                            <div class="h-32 {{ $colorClass }} border-b flex flex-col items-center justify-center p-4 text-center relative group">
                                @if (Str::startsWith($portfolio->berkas_tautan, 'http') && (preg_match('/\.(jpg|jpeg|png|webp)$/i', $portfolio->berkas_tautan) || str_contains($portfolio->berkas_tautan, 'unsplash.com')))
                                    <img src="{{ $portfolio->berkas_tautan }}" alt="{{ $portfolio->judul }}" class="w-full h-full object-cover rounded-lg">
                                @else
                                    <div class="w-10 h-10 rounded-xl bg-white/70 flex items-center justify-center mb-1 shadow-xs">
                                        @if ($mediaType === 'Berkas PDF')
                                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                        @elseif ($mediaType === 'Tautan video')
                                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        @elseif ($mediaType === 'Gambar karya')
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        @else
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                        @endif
                                    </div>
                                    <span class="text-xs font-semibold tracking-wide">[{{ $mediaType }}]</span>
                                @endif

                                <!-- Badge Visibilitas -->
                                @if (!$portfolio->status_tampil)
                                    <span class="absolute top-2 right-2 text-[10px] bg-slate-800 text-white px-2 py-0.5 rounded-full font-medium">
                                        Disembunyikan
                                    </span>
                                @endif
                            </div>

                            <!-- Card Body -->
                            <div class="p-4 flex-1 flex flex-col justify-between">
                                <div>
                                    <h4 class="font-bold text-slate-800 text-sm leading-snug">{{ $portfolio->judul }}</h4>
                                    
                                    <div class="flex items-center gap-1.5 mt-1 text-[11px] text-blue-600 font-medium">
                                        <span>{{ $portfolio->service?->judul ?? 'Portofolio Umum' }}</span>
                                        <span class="text-slate-400">&bull;</span>
                                        <span class="text-slate-500">{{ $portfolio->created_at->format('Y') }}</span>
                                    </div>

                                    <p class="text-xs text-slate-500 mt-2 line-clamp-2">
                                        {{ $portfolio->deskripsi ?? 'Tidak ada deskripsi tambahan.' }}
                                    </p>
                                </div>

                                <!-- Action Buttons (Sesuai Mockup: Edit & Hapus) -->
                                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                                    <div class="flex items-center gap-3 text-xs">
                                        <a href="{{ route('portfolios.edit', $portfolio) }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                            Edit
                                        </a>

                                        <form action="{{ route('portfolios.destroy', $portfolio) }}" method="POST" onsubmit="return confirm('Apakah kamu yakin ingin menghapus karya ini dari portofolio?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-600 hover:text-rose-800 font-medium flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Quick Toggle Visibility Button -->
                                    <form action="{{ route('portfolios.toggle-visibility', $portfolio) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" title="{{ $portfolio->status_tampil ? 'Sembunyikan dari publik' : 'Tampilkan ke publik' }}" 
                                                class="text-slate-400 hover:text-slate-600 p-1">
                                            @if ($portfolio->status_tampil)
                                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                            @else
                                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" /></svg>
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Right 1 Col: Form Tambah Karya Baru (Sesuai Mockup Gambar 4.5.7) -->
        <div class="space-y-4" id="form-tambah-karya">
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm sticky top-24">
                <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">
                    Tambah karya baru
                </h3>

                <form action="{{ route('portfolios.store') }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
                    @csrf

                    <!-- Judul Karya -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Judul karya</label>
                        <input type="text" name="judul" value="{{ old('judul') }}" required
                               placeholder="Mis. Poster kegiatan himpunan"
                               class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Tautkan ke Jasa -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tautkan ke jasa</label>
                        <select name="service_id" 
                                class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih jasa</option>
                            @foreach ($services as $svc)
                                <option value="{{ $svc->service_id }}" {{ old('service_id') == $svc->service_id ? 'selected' : '' }}>
                                    {{ $svc->judul }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Deskripsi Singkat -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Deskripsi singkat</label>
                        <textarea name="deskripsi" rows="3"
                                  placeholder="Apa yang kamu buat dan untuk siapa?"
                                  class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('deskripsi') }}</textarea>
                    </div>

                    <!-- Berkas Karya -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Berkas karya</label>
                        <div class="border-2 border-dashed border-slate-200 rounded-2xl p-4 text-center hover:border-blue-400 transition bg-slate-50">
                            <svg class="w-6 h-6 text-blue-500 mx-auto mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                            <p class="text-[11px] text-slate-600 font-medium">Unggah gambar atau dokumen hasil karyamu</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">JPG, PNG, atau PDF &bull; maks. 5 MB</p>
                            <input type="file" name="berkas" accept=".jpg,.jpeg,.png,.pdf" class="mt-2 text-xs text-slate-500 file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                        </div>
                    </div>

                    <!-- Tautan Eksternal (Opsional) -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tautan eksternal (opsional)</label>
                        <input type="url" name="tautan_eksternal" value="{{ old('tautan_eksternal') }}"
                               placeholder="Mis. tautan video atau Google Drive"
                               class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Checkbox Tampilkan di Profil Publik & Detail Jasa -->
                    <div class="flex items-start gap-2 pt-1">
                        <input type="checkbox" name="status_tampil" value="1" id="status_tampil" 
                               {{ old('status_tampil', true) ? 'checked' : '' }}
                               class="mt-0.5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <label for="status_tampil" class="text-xs text-slate-600 cursor-pointer">
                            Tampilkan di profil publik dan halaman detail jasa
                        </label>
                    </div>

                    <!-- Tombol Batal & Simpan Karya -->
                    <div class="pt-3 flex items-center justify-end gap-2 border-t border-slate-100">
                        <button type="reset" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800 rounded-xl hover:bg-slate-100 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-sm transition">
                            Simpan karya
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</div>
@endsection
