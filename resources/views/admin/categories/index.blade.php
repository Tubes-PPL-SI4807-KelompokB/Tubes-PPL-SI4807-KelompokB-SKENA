@extends('layouts.admin')
@section('page-title', 'Kategori Jasa')

{{--
    PBI-05 Manajemen Kategori Jasa (FR-11) — persis Claude Design "PBI-05 Kategori":
    p05-1 daftar + cari, p05-2 slide-over tambah/ubah, p05-3 hapus (ditolak bila masih dipakai),
    keadaan kosong, dan toast berhasil (toast bawaan layout).
--}}

@php
    $bag     = $errors->getBag('category');
    $editing = request('ubah') ? \App\Models\ServiceCategory::find((int) request('ubah')) : null;
    $toForm  = fn ($c) => [
        'id' => $c->id, 'name' => $c->nama_kategori, 'desc' => $c->deskripsi,
        'update' => route('admin.categories.update', $c),
    ];
    $reopen = $bag->any() ? [
        'id' => old('_category_id'), 'name' => old('nama_kategori'), 'desc' => old('deskripsi'),
        'update' => old('_category_id') ? route('admin.categories.update', old('_category_id')) : null,
    ] : ($editing ? $toForm($editing) : (request('tambah') ? [] : null));
    $th   = 'px-4 first:pl-6 last:pr-6 text-left text-[11px] leading-[1.2] font-bold tracking-[0.06em] text-on-surface-muted';
    $ibtn = 'w-9 h-9 rounded-full border border-border bg-white grid place-items-center';
@endphp

@section('page-head')
    {{-- Toolbar: cari + tambah --}}
    <div class="flex flex-wrap items-center gap-2.5">
        <form method="GET" action="{{ route('admin.categories.index') }}" role="search"
              class="h-11 w-full md:w-80 px-5 rounded-full border border-border bg-white flex items-center gap-2.5 focus-within:border-primary">
            <span class="msr text-on-surface-muted">search</span>
            <label for="catSearch" class="sr-only">Cari kategori</label>
            <input id="catSearch" type="search" name="q" value="{{ $search }}" placeholder="Cari kategori…"
                   class="flex-1 min-w-0 border-0 outline-none bg-transparent text-[15px] text-on-surface placeholder:text-on-surface-muted">
        </form>
        <button type="button" x-data @click="$dispatch('category-form', {})"
                class="h-11 px-[22px] w-full md:w-auto md:ml-auto justify-center rounded-full bg-primary hover:bg-primary-hover text-white flex items-center gap-1.5 text-[14px] font-semibold whitespace-nowrap">
            <span class="msr">add</span>Tambah Kategori
        </button>
    </div>
    <div class="pt-10 pb-8">
        <div class="text-[11px] leading-[1.2] font-bold tracking-[0.06em] text-primary">ADMINISTRASI</div>
        <div class="flex flex-wrap items-center gap-x-3.5 gap-y-2 mt-2.5">
            <h1 class="m-0 text-[26px] sm:text-[32px] leading-[1.15] tracking-[-0.02em] font-bold">Kategori Jasa</h1>
        </div>
        <p class="mt-2.5 text-[15px] leading-normal text-on-surface-muted">Kategori dipakai penyedia saat menambah jasa dan oleh pencari saat menyaring.</p>
    </div>
@endsection

@section('content')
<div x-data="categoryAdmin(@js($reopen))" @category-form.window="open($event.detail)" @keydown.escape.window="closeAll()">

    @if($total === 0)
        {{-- p05-3: keadaan kosong --}}
        <div class="min-h-[420px] rounded-3xl bg-white border border-border flex flex-col items-center justify-center text-center gap-2.5 p-8">
            <span class="w-16 h-16 rounded-full bg-tint text-primary grid place-items-center"><span class="msr" style="font-size:30px">category</span></span>
            <div class="mt-2 text-[18px] leading-[1.3] font-semibold">Belum ada kategori</div>
            <p class="max-w-[280px] text-[14px] leading-normal text-on-surface-muted">Kategori yang kamu buat akan muncul di sini dan di form tambah jasa.</p>
            <button type="button" @click="open({})" class="h-11 px-[22px] mt-2.5 rounded-full bg-primary hover:bg-primary-hover text-white flex items-center gap-1.5 text-[14px] font-semibold"><span class="msr">add</span>Tambah Kategori</button>
        </div>
    @else
        {{-- p05-1: tabel kategori --}}
        <div class="bg-white border border-border rounded-3xl shadow-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse" style="min-width:760px;table-layout:fixed">
                    <colgroup><col style="width:24%"><col style="width:48%"><col style="width:14%"><col style="width:14%"></colgroup>
                    <thead>
                        <tr class="h-11 bg-surface border-b border-border">
                            <th scope="col" class="{{ $th }}">NAMA KATEGORI</th>
                            <th scope="col" class="{{ $th }}">DESKRIPSI</th>
                            <th scope="col" class="{{ $th }}">JUMLAH JASA</th>
                            <th scope="col" class="{{ $th }}" style="text-align:right">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $i => $c)
                            <tr class="hover:bg-surface {{ $i ? 'border-t border-border' : '' }}">
                                <td class="px-4 pl-6 py-4">
                                    <span class="flex items-center gap-3 min-w-0 text-[15px] font-semibold">
                                        <span class="w-10 h-10 flex-none rounded-full bg-tint text-primary grid place-items-center"><span class="msr" aria-hidden="true">{{ $c->icon }}</span></span>
                                        <span class="truncate">{{ $c->nama_kategori }}</span>
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-[14px] leading-[1.45] text-on-surface-variant">{{ $c->deskripsi ?: '—' }}</td>
                                <td class="px-4 py-4 text-[15px] font-semibold whitespace-nowrap">{{ $c->services_count }} <span class="text-[13px] font-normal text-on-surface-muted">jasa</span></td>
                                <td class="px-4 pr-6 py-4">
                                    <div class="flex justify-end gap-1.5">
                                        <button type="button" class="{{ $ibtn }} text-on-surface hover:border-secondary" aria-label="Ubah {{ $c->nama_kategori }}" @click="open(@js($toForm($c)))"><span class="msr" style="font-size:18px">edit</span></button>
                                        <button type="button" class="{{ $ibtn }} text-[#B91C1C] hover:bg-error-container" aria-label="Hapus {{ $c->nama_kategori }}"
                                                @click="del = @js(['name' => $c->nama_kategori, 'used' => $c->services_count, 'url' => route('admin.categories.destroy', $c)])"><span class="msr" style="font-size:18px">delete</span></button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-10 py-10 text-center text-[14px] leading-normal text-on-surface-muted">Tidak ada kategori yang cocok dengan "{{ $search }}".</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($categories->hasPages())
                <div class="flex flex-wrap justify-between items-center gap-3 px-6 py-3.5 border-t border-border text-[13px] text-on-surface-muted">
                    <span>Menampilkan {{ $categories->count() }} dari {{ $categories->total() }} kategori</span>
                    <nav class="flex gap-1.5" aria-label="Halaman">
                        @foreach(range(1, $categories->lastPage()) as $p)
                            @if($p === $categories->currentPage())
                                <span class="w-11 h-11 rounded-full bg-primary text-white grid place-items-center font-semibold" aria-current="page">{{ $p }}</span>
                            @else
                                <a href="{{ $categories->url($p) }}" class="w-11 h-11 rounded-full border border-border grid place-items-center hover:border-secondary">{{ $p }}</a>
                            @endif
                        @endforeach
                    </nav>
                </div>
            @endif
        </div>
    @endif

    {{-- p05-2: slide-over tambah/ubah --}}
    <div x-show="drawer" x-cloak x-transition.opacity class="fixed inset-0 z-50" style="background:rgba(15,23,42,0.32)" @click="closeAll()"></div>
    <aside x-show="drawer" x-cloak x-trap.noscroll.inert="drawer" role="dialog" aria-modal="true" aria-labelledby="catFormTitle"
           class="fixed top-0 right-0 bottom-0 z-[51] w-full max-w-[480px] bg-white sm:rounded-l-3xl overflow-y-auto" style="box-shadow:0 20px 35px -8px rgba(63,116,156,0.16)"
           x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">
        <form method="POST" :action="form.update || @js(route('admin.categories.store'))" action="{{ route('admin.categories.store') }}" data-validate
              class="min-h-full flex flex-col gap-5 px-6 py-8 sm:px-10 sm:py-9">
            @csrf
            <input type="hidden" name="_method" :value="form.id ? 'PUT' : 'POST'" value="POST">
            <input type="hidden" name="_category_id" :value="form.id">
            <input type="hidden" name="q" value="{{ $search }}">

            <div class="flex justify-between items-start gap-4">
                <div>
                    <div class="text-[11px] leading-[1.2] font-bold tracking-[0.06em] text-primary" x-text="form.id ? 'UBAH KATEGORI' : 'TAMBAH KATEGORI'">TAMBAH KATEGORI</div>
                    <h2 id="catFormTitle" class="mt-2 text-[24px] leading-[1.25] tracking-[-0.01em] font-bold" x-text="form.id ? form.orig : 'Kategori Baru'">Kategori Baru</h2>
                </div>
                <button type="button" class="circ" aria-label="Tutup" @click="closeAll()"><span class="msr">close</span></button>
            </div>

            <div class="fld">
                <label for="c_name">Nama Kategori</label>
                <input id="c_name" name="nama_kategori" class="inp" x-model="form.name" x-ref="first" required maxlength="100" autocomplete="off"
                       placeholder="Contoh: Fotografi" aria-describedby="c_name_err" aria-invalid="{{ $bag->has('nama_kategori') ? 'true' : 'false' }}"
                       data-msg-required="Nama kategori wajib diisi.">
                <p class="err" id="c_name_err" @unless($bag->has('nama_kategori')) hidden @endunless><span class="msr">error</span><span data-err-text>{{ $bag->first('nama_kategori') }}</span></p>
            </div>
            <div class="fld">
                <label for="c_desc">Deskripsi <span class="opt">(opsional)</span></label>
                <textarea id="c_desc" name="deskripsi" rows="5" maxlength="500" x-model="form.desc"
                          placeholder="Contoh: Foto produk UMKM, foto wisuda, dan dokumentasi acara kampus."
                          aria-describedby="c_desc_err" aria-invalid="{{ $bag->has('deskripsi') ? 'true' : 'false' }}"
                          class="px-5 py-3.5 rounded-[20px] border border-border bg-white text-[15px] leading-normal text-on-surface outline-none resize-none focus:border-primary focus:ring-[3px] focus:ring-tint-strong"></textarea>
                <p class="err" id="c_desc_err" @unless($bag->has('deskripsi')) hidden @endunless><span class="msr">error</span><span data-err-text>{{ $bag->first('deskripsi') }}</span></p>
            </div>

            <div class="mt-auto grid grid-cols-2 gap-2.5">
                <button type="button" class="h-12 rounded-full border border-border bg-white text-on-surface text-[14px] font-bold" @click="closeAll()">Batal</button>
                <button type="submit" class="h-12 rounded-full bg-primary hover:bg-primary-hover text-white text-[14px] font-bold" data-loading="Menyimpan…">Simpan</button>
            </div>
        </form>
    </aside>

    {{-- p05-3: hapus kategori (ditolak bila masih dipakai jasa) --}}
    <div x-show="del" x-cloak class="fixed inset-0 z-[52] grid place-items-center p-4" style="background:rgba(15,23,42,0.32)" @click.self="closeAll()">
        <div x-trap.noscroll.inert="del" role="alertdialog" aria-modal="true" aria-labelledby="delCatTitle" aria-describedby="delCatDesc"
             class="w-full max-w-[420px] bg-white rounded-3xl p-7 flex flex-col gap-2.5" style="box-shadow:0 20px 35px -8px rgba(63,116,156,0.16)">
            <span class="w-[52px] h-[52px] rounded-full bg-error-container text-[#B91C1C] grid place-items-center"><span class="msr" style="font-size:26px">delete</span></span>
            <h3 id="delCatTitle" class="mt-2.5 text-[20px] leading-[1.3] font-bold">Hapus kategori "<span x-text="del?.name"></span>"?</h3>
            <div id="delCatDesc">
                <template x-if="del?.used > 0">
                    <div class="flex gap-2.5 items-start px-3.5 py-3 rounded-[18px] bg-[#FEF3C7] text-[#B45309] text-[13px] leading-[1.45] font-semibold">
                        <span class="msr">warning</span>
                        <span>Kategori ini masih dipakai <span x-text="del?.used"></span> jasa. Pindahkan jasa tersebut ke kategori lain sebelum menghapus.</span>
                    </div>
                </template>
                <template x-if="!(del?.used > 0)">
                    <p class="text-[14px] leading-normal text-on-surface-muted">Kategori akan hilang dari form tambah jasa dan filter pencarian. Tindakan ini tidak bisa dibatalkan.</p>
                </template>
            </div>
            <form method="POST" :action="del?.url" class="grid grid-cols-2 gap-2.5 mt-3.5">
                @csrf @method('DELETE')
                <button type="button" class="h-[46px] rounded-full border border-border bg-white text-on-surface text-[14px] font-bold" @click="closeAll()">Batal</button>
                <button type="submit" class="h-[46px] rounded-full text-white text-[14px] font-bold"
                        :class="del?.used > 0 ? 'bg-[#FCA5A5] cursor-not-allowed' : 'bg-error'" :disabled="del?.used > 0" :aria-disabled="(del?.used > 0).toString()"
                        data-loading="Menghapus…">Hapus</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('categoryAdmin', (reopen) => ({
            drawer: false,
            del: null,
            form: {},
            init() { if (reopen) this.open(reopen); },
            open(c) {
                c = c || {};
                this.form = { id: c.id || '', name: c.name || '', orig: c.name || '', desc: c.desc || '', update: c.update || null };
                this.drawer = true;
                this.$nextTick(() => setTimeout(() => this.$refs.first && this.$refs.first.focus(), 60));
            },
            closeAll() { this.drawer = false; this.del = null; },
        }));
    });
</script>
@endpush
