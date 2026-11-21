@extends('layouts.app')

@section('title', $service->judul . ' - SKENA')

@section('content')
<div class="space-y-6">

    <!-- Breadcrumb -->
    <nav class="flex text-xs text-slate-500 gap-2 items-center">
        <a href="{{ route('services.index') }}" class="hover:text-blue-600">Jasa</a>
        <span>&rsaquo;</span>
        <span class="text-slate-700 font-medium">{{ $service->category?->nama_kategori }}</span>
        <span>&rsaquo;</span>
        <span class="text-slate-900 font-semibold truncate max-w-xs">{{ $service->judul }}</span>
    </nav>

    <!-- Layout Grid 2 Kolom (Sesuai Mockup Gambar 4.5.2 Detail Jasa) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Kolom Kiri (2 Kolom): Judul, Galeri Portofolio, Deskripsi -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-blue-50 text-blue-600">
                        {{ $service->category?->nama_kategori }}
                    </span>
                    <span class="text-xs text-slate-400">&bull; Durasi pengerjaan: {{ $service->durasi }} hari</span>
                </div>

                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $service->judul }}</h1>

                <!-- Rating Placeholder (Sesuai Mockup Gambar 4.5.2) -->
                <div class="flex items-center gap-2 mt-2 text-xs">
                    <div class="flex items-center text-amber-500">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                        <span class="font-bold text-slate-800 ml-1">4.9</span>
                    </div>
                    <span class="text-slate-400">(32 ulasan)</span>
                </div>

                <!-- Deskripsi Jasa -->
                <div class="mt-6 pt-5 border-t border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900 mb-2">Deskripsi Layanan</h3>
                    <p class="text-sm text-slate-600 leading-relaxed whitespace-pre-line">
                        {{ $service->deskripsi }}
                    </p>
                </div>
            </div>

            <!-- Galeri Portofolio Terkait (PBI-06 Integration) -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-bold text-slate-900">Portofolio Karya Terkait</h3>
                    <a href="{{ route('portfolios.index', ['service_id' => $service->service_id]) }}" class="text-xs text-blue-600 hover:text-blue-800 font-semibold">
                        Kelola di Portofolio &rarr;
                    </a>
                </div>

                @if ($service->portfolios->isEmpty())
                    <div class="border border-dashed border-slate-200 rounded-xl p-8 text-center">
                        <p class="text-xs text-slate-500">Belum ada karya yang ditautkan ke jasa ini.</p>
                        <a href="{{ route('portfolios.create') }}" class="inline-block mt-2 text-xs font-semibold text-blue-600 hover:underline">
                            + Tautkan Karya Sekarang
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach ($service->portfolios as $work)
                            <div class="rounded-xl border border-slate-200 overflow-hidden bg-slate-50 flex flex-col justify-between">
                                <div class="h-28 bg-blue-50/60 border-b flex items-center justify-center p-3 text-center">
                                    @if ($work->berkas_tautan && filter_var($work->berkas_tautan, FILTER_VALIDATE_URL) && !str_contains($work->berkas_tautan, 'youtube'))
                                        <img src="{{ $work->berkas_tautan }}" alt="{{ $work->judul }}" class="h-full w-full object-cover rounded-lg">
                                    @else
                                        <span class="text-xs font-semibold text-blue-600">[{{ $work->media_type }}]</span>
                                    @endif
                                </div>
                                <div class="p-3">
                                    <h4 class="font-bold text-xs text-slate-800 line-clamp-1">{{ $work->judul }}</h4>
                                    <p class="text-[11px] text-slate-500 mt-1 line-clamp-2">{{ $work->deskripsi }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Kolom Kanan (1 Kolom): Card Harga & Profil Penyedia -->
        <div class="space-y-5">
            <!-- Box Pemesanan / Harga -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm sticky top-24">
                <div class="text-2xl font-black text-slate-900">{{ $service->formatted_harga }}</div>
                <p class="text-xs text-slate-400 mt-0.5">Harga dapat dinegosiasikan</p>

                <div class="mt-4 pt-4 border-t border-slate-100 space-y-2">
                    <button type="button" class="w-full py-2.5 bg-blue-600 text-white rounded-xl text-xs font-bold shadow-sm opacity-90 cursor-not-allowed">
                        Request Jasa (PBI-10 Preview)
                    </button>
                    <button type="button" class="w-full py-2 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition">
                        &hearts; Simpan ke Favorit (PBI-07)
                    </button>
                </div>

                <!-- Info Penyedia Jasa -->
                <div class="mt-6 pt-5 border-t border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 font-bold flex items-center justify-center text-xs">
                            {{ substr($service->provider?->nama ?? 'Aulia R.', 0, 2) }}
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-800">{{ $service->provider?->nama ?? 'Aulia R.' }}</h4>
                            <p class="text-[11px] text-slate-400">Desain Komunikasi Visual &bull; 2023</p>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center gap-1.5 text-[11px] text-emerald-600 font-medium">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Tersedia menerima pesanan
                    </div>
                </div>

                <!-- Edit Jasa Shortcut untuk Pemilik -->
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                    <a href="{{ route('services.edit', $service) }}" class="text-xs font-semibold text-blue-600 hover:underline">
                        Edit Jasa Ini
                    </a>
                    <form action="{{ route('services.destroy', $service) }}" method="POST" onsubmit="return confirm('Hapus jasa ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-rose-600 hover:underline font-semibold">
                            Hapus Jasa
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
