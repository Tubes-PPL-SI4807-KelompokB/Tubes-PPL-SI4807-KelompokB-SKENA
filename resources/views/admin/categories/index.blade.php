@extends('layouts.admin')

@section('title', 'Manajemen Kategori Jasa — SKENA Admin')
@section('page-title', 'Manajemen Kategori Jasa')
@section('page-subtitle', 'PBI-05 (Sprint 1): Kelola kategori layanan yang tersedia untuk seluruh mahasiswa penyedia jasa (FR-11).')

@section('content')
<div class="space-y-6">

    <!-- Action Bar & Search -->
    <div class="flex flex-wrap items-center gap-3 justify-between">
        <form action="{{ route('admin.categories.index') }}" method="GET"
              class="h-11 w-full sm:w-[320px] px-5 rounded-full border border-border bg-white flex items-center gap-2.5 focus-within:border-primary focus-within:ring-[3px] focus-within:ring-tint-strong">
            <svg class="ic text-on-surface-muted"><use href="#i-search"/></svg>
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama atau deskripsi kategori…" aria-label="Cari kategori"
                   class="flex-1 min-w-0 bg-transparent border-0 outline-none text-[15px] text-on-surface">
            @if (request('search'))
                <a href="{{ route('admin.categories.index') }}" class="text-xs text-on-surface-muted hover:text-on-surface">Reset</a>
            @endif
        </form>

        <a href="{{ route('admin.categories.create') }}"
           class="h-11 px-[22px] rounded-full bg-primary hover:bg-primary-hover text-white text-sm font-semibold inline-flex items-center gap-1.5 whitespace-nowrap transition-colors">
            <svg class="ic"><use href="#i-plus"/></svg>Tambah Kategori
        </a>
    </div>

    <!-- Tabel Kategori -->
    <div class="bg-white rounded-3xl border border-border overflow-hidden shadow-card">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-border bg-surface-muted text-[11px] font-bold text-on-surface-muted uppercase tracking-wider">
                    <th class="py-3.5 px-6"># ID</th>
                    <th class="py-3.5 px-6">Nama Kategori</th>
                    <th class="py-3.5 px-6">Deskripsi</th>
                    <th class="py-3.5 px-6 text-center">Jumlah Jasa Terkait</th>
                    <th class="py-3.5 px-6 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border text-sm text-on-surface">
                @forelse ($categories as $cat)
                    <tr class="hover:bg-surface-muted/50 transition">
                        <td class="py-4 px-6 font-mono text-on-surface-muted text-xs">#{{ $cat->id }}</td>
                        <td class="py-4 px-6 font-semibold text-on-surface">
                            {{ $cat->nama_kategori }}
                        </td>
                        <td class="py-4 px-6 text-on-surface-muted max-w-md text-xs">
                            {{ $cat->deskripsi ?? '—' }}
                        </td>
                        <td class="py-4 px-6 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $cat->services_count > 0 ? 'bg-tint text-primary' : 'bg-surface-muted text-on-surface-muted' }}">
                                {{ $cat->services_count }} jasa
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.categories.edit', $cat) }}"
                                   class="px-3 py-1.5 rounded-lg text-primary hover:bg-tint font-semibold text-xs transition">
                                    Edit
                                </a>

                                <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori {{ $cat->nama_kategori }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-3 py-1.5 rounded-lg text-red-600 hover:bg-red-50 font-semibold text-xs transition"
                                            {{ $cat->services_count > 0 ? 'title=Kategori_masih_memiliki_jasa' : '' }}>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-on-surface-muted">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <span class="w-12 h-12 rounded-full bg-tint text-primary grid place-items-center">
                                    <svg class="ic"><use href="#i-folder"/></svg>
                                </span>
                                <p class="text-sm font-semibold">Belum ada kategori jasa yang dibuat</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($categories->hasPages())
            <div class="p-4 border-t border-border">
                {{ $categories->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
