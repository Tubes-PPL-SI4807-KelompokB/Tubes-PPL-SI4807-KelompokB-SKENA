@extends('layouts.app')

@section('title', 'Buat Jasa Baru - SKENA')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-5 flex items-center justify-between">
        <div>
            <a href="{{ route('services.index') }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1 mb-1">
                &larr; Kembali ke Kelola Jasa
            </a>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Buat Jasa Baru</h1>
            <p class="text-xs text-slate-500 mt-1">Isi formulir untuk mempublikasikan layanan keterampilanmu.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <form action="{{ route('services.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Judul Jasa -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Judul Jasa <span class="text-rose-500">*</span></label>
                <input type="text" name="judul" value="{{ old('judul') }}" required
                       placeholder="Contoh: Desain presentasi (PPT) profesional"
                       class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Kategori Jasa -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kategori Jasa <span class="text-rose-500">*</span></label>
                <select name="category_id" required
                        class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->category_id }}" {{ old('category_id') == $cat->category_id ? 'selected' : '' }}>
                            {{ $cat->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Deskripsi Jasa -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Deskripsi Lengkap Jasa <span class="text-rose-500">*</span></label>
                <textarea name="deskripsi" rows="4" required
                          placeholder="Jelaskan ruang lingkup jasa, jumlah revisi, apa yang didapat klien, dan batasan pekerjaan..."
                          class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('deskripsi') }}</textarea>
            </div>

            <!-- Grid: Harga & Durasi -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Harga Dasar (Rp) <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-2.5 text-sm font-semibold text-slate-400">Rp</span>
                        <input type="number" name="harga" value="{{ old('harga') }}" required min="5000" step="1000"
                               placeholder="20000"
                               class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-3.5 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Minimal Rp5.000 (ramah kantong mahasiswa).</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Durasi Pengerjaan (Hari) <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <input type="number" name="durasi" value="{{ old('durasi', 2) }}" required min="1" max="30"
                               class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <span class="absolute right-3.5 top-2.5 text-xs text-slate-400 font-medium">Hari</span>
                    </div>
                </div>
            </div>

            <!-- Status Publikasi -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Status Publikasi</label>
                <div class="flex items-center gap-6 pt-1">
                    <label class="flex items-center gap-2 text-xs text-slate-700 cursor-pointer">
                        <input type="radio" name="status" value="aktif" {{ old('status', 'aktif') === 'aktif' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                        <span>Aktif (Langsung tampil di katalog kampus)</span>
                    </label>
                    <label class="flex items-center gap-2 text-xs text-slate-700 cursor-pointer">
                        <input type="radio" name="status" value="nonaktif" {{ old('status') === 'nonaktif' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                        <span>Nonaktif (Simpan sebagai draf)</span>
                    </label>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('services.index') }}" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-800 rounded-xl hover:bg-slate-100 transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                    Simpan & Terbitkan Jasa
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
