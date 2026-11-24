@extends('layouts.app')
@section('title', 'Profil Saya — SKENA')

{{--
    PBI-01 Manajemen Profil Pengguna — persis Claude Design "PBI-01 Profil":
    p01-1 kelola profil, p01-2 slide-over tambah/ubah pengalaman, p01-3 hapus/kosong/toast.
    Halaman ini sekaligus form ubah profil (PUT profile.update) via tombol "Simpan Perubahan".
--}}

@php
    $profile   = $user->profile;
    $available = (bool) old('is_available', $profile?->is_available ?? true);
    $years     = collect(range((int) date('Y'), (int) date('Y') - 7))->push($user->angkatan)->filter()->unique()->sortDesc();
    $subtitle  = collect([$user->major ?: $user->university, $user->angkatan ? 'Angkatan ' . $user->angkatan : null])->filter()->implode(' · ')
                 ?: ($user->role?->display_name ?? 'Pengguna');
    $experiences = $user->experiences->map(fn ($x) => [
        'id' => $x->id, 'name' => $x->name, 'role' => $x->role, 'start_period' => $x->start_period, 'end_period' => $x->end_period,
        'description' => $x->description, 'update' => route('experiences.update', $x), 'destroy' => route('experiences.destroy', $x),
    ])->values();
    $expErrors = $errors->getBag('experience');
    $reopen = $expErrors->any() ? [
        'id' => old('_experience_id'), 'name' => old('experience.name'), 'role' => old('experience.role'),
        'start_period' => old('experience.start_period'), 'end_period' => old('experience.end_period'), 'description' => old('experience.description'),
        'update' => old('_experience_id') ? route('experiences.update', old('_experience_id')) : null,
    ] : null;
    $inp = fn ($f) => 'inp' . ($errors->has($f) ? '" aria-invalid="true' : '');
    $kicker = 'text-[11px] leading-[1.2] font-bold tracking-[0.06em] text-primary';
@endphp

@section('content')
<div x-data="experiences(@js($reopen))" @keydown.escape.window="close()">

    {{-- Toolbar --}}
    <div class="flex flex-wrap items-center gap-2.5">
        <form method="GET" action="{{ route('home') }}" role="search" class="h-11 w-full sm:w-80 px-5 rounded-full border border-border bg-white flex items-center gap-2.5 focus-within:border-primary">
            <span class="msr text-on-surface-muted">search</span>
            <label for="profileSearch" class="sr-only">Cari jasa</label>
            <input id="profileSearch" type="search" name="q" placeholder="Cari jasa…" class="flex-1 min-w-0 border-0 outline-none bg-transparent text-[15px] text-on-surface placeholder:text-on-surface-muted">
        </form>
        <button type="submit" form="profileForm" class="h-11 px-6 sm:ml-auto w-full sm:w-auto rounded-full bg-primary hover:bg-primary-hover text-white text-[14px] font-semibold whitespace-nowrap">Simpan Perubahan</button>
    </div>

    {{-- Judul --}}
    <div class="pt-10 pb-8">
        <div class="{{ $kicker }}">AKUN SAYA</div>
        <div class="flex flex-wrap items-center gap-x-3.5 gap-y-2 mt-2.5">
            <h1 class="m-0 text-[26px] sm:text-[32px] leading-[1.15] tracking-[-0.02em] font-bold">Profil Saya</h1>
        </div>
        <p class="mt-2.5 text-[15px] leading-normal text-on-surface-muted">Profil ini terlihat oleh pencari dan penyedia jasa lain.</p>
    </div>

    @if($errors->any())
        <p class="mb-[18px] text-[14px] text-on-error-container bg-error-container rounded-[18px] px-[18px] py-3.5" role="alert">{{ $errors->first() }}</p>
    @endif

    <form id="profileForm" method="POST" action="{{ route('profile.update', $user->id) }}" enctype="multipart/form-data"
          x-data="{ preview: null, on: @js($available) }" class="grid grid-cols-1 lg:grid-cols-12 gap-card-gap items-start">
        @csrf @method('PUT')

        {{-- Kartu identitas --}}
        <div class="lg:col-span-4 bg-white border border-border rounded-3xl shadow-card p-8 flex flex-col items-center text-center gap-3.5">
            <img src="{{ $user->avatar_url }}" :src="preview || @js($user->avatar_url)" alt="Foto profil {{ $user->name }}"
                 class="w-32 h-32 rounded-full object-cover bg-tint border-4 border-white" style="box-shadow:0 0 0 1px #E2E8F0">
            <div class="min-w-0 max-w-full">
                <div class="text-[24px] leading-[1.25] tracking-[-0.01em] font-bold break-words">{{ $user->name }}</div>
                <div class="mt-1 text-[14px] leading-[1.4] text-on-surface-muted">{{ $subtitle }}</div>
            </div>
            <label class="h-10 px-[18px] rounded-full bg-tint text-primary hover:bg-primary hover:text-white flex items-center gap-1.5 text-[14px] font-semibold cursor-pointer transition-colors focus-within:ring-2 focus-within:ring-secondary">
                <span class="msr" style="font-size:18px">photo_camera</span>Ubah Foto
                <input type="file" name="avatar" accept=".jpg,.jpeg,.png,.webp" class="sr-only"
                       @change="const f = $event.target.files[0]; preview = f ? URL.createObjectURL(f) : null">
            </label>
            <div class="w-full h-px bg-border my-2.5"></div>
            <div class="w-full flex justify-between items-center text-left">
                <div>
                    <div class="text-[15px] leading-[1.2] font-semibold" id="availLabel">Terima Pesanan</div>
                    <div class="mt-1.5 flex items-center gap-1.5 text-[13px] leading-none font-semibold" :style="on ? 'color:#047857' : 'color:#475569'">
                        <span class="w-[7px] h-[7px] rounded-full" :style="on ? 'background:#10B981' : 'background:#64748B'"></span>
                        <span x-text="on ? 'Tersedia' : 'Tidak Tersedia'">{{ $available ? 'Tersedia' : 'Tidak Tersedia' }}</span>
                    </div>
                </div>
                <input type="hidden" name="is_available" :value="on ? 1 : 0" value="{{ $available ? 1 : 0 }}">
                <button type="button" class="switch" role="switch" aria-labelledby="availLabel" :aria-checked="on.toString()" aria-checked="{{ $available ? 'true' : 'false' }}" @click="on = !on"></button>
            </div>
        </div>

        {{-- Form data profil --}}
        <div class="lg:col-span-8 bg-white border border-border rounded-3xl shadow-card p-6 sm:p-8 grid grid-cols-1 sm:grid-cols-2 gap-x-card-gap gap-y-5">
            <div class="fld">
                <label for="name">Nama</label>
                <input id="name" name="name" class="{!! $inp('name') !!}" value="{{ old('name', $user->name) }}" required maxlength="255" autocomplete="name">
            </div>
            <div class="fld">
                <label for="university">Kampus</label>
                <input id="university" name="university" class="{!! $inp('university') !!}" value="{{ old('university', $user->university) }}" maxlength="255">
            </div>
            <div class="fld sm:col-span-2">
                <label for="bio">Bio</label>
                <textarea id="bio" name="bio" rows="3" maxlength="1000" class="{!! $inp('bio') !!}">{{ old('bio', $profile?->bio) }}</textarea>
            </div>
            <div class="fld">
                <label for="major">Program Studi</label>
                <input id="major" name="major" class="{!! $inp('major') !!}" value="{{ old('major', $user->major) }}" maxlength="255">
            </div>
            <div class="fld">
                <label for="angkatan">Angkatan</label>
                <select id="angkatan" name="angkatan" class="inp">
                    <option value="">Pilih tahun</option>
                    @foreach($years as $y)<option value="{{ $y }}" @selected((int) old('angkatan', $user->angkatan) === (int) $y)>{{ $y }}</option>@endforeach
                </select>
            </div>
            <div class="fld">
                <label for="phone">Kontak</label>
                <input id="phone" name="phone" type="tel" class="{!! $inp('phone') !!}" value="{{ old('phone', $user->phone) }}" maxlength="20" autocomplete="tel">
            </div>
            <div class="fld">
                <label for="instagram">Instagram</label>
                <input id="instagram" name="instagram" class="{!! $inp('instagram') !!}" value="{{ old('instagram', $profile?->instagram) }}" maxlength="100">
            </div>
            <div class="fld">
                <label for="linkedin">LinkedIn</label>
                <input id="linkedin" name="linkedin" class="{!! $inp('linkedin') !!}" value="{{ old('linkedin', $profile?->linkedin) }}" maxlength="100">
            </div>
        </div>
    </form>

    {{-- Portofolio diri --}}
    <div class="mt-14 flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4">
        <div>
            <div class="{{ $kicker }}">PORTOFOLIO DIRI</div>
            <h2 class="mt-2 text-[24px] leading-[1.25] tracking-[-0.01em] font-bold">Riwayat Pengalaman &amp; Organisasi</h2>
        </div>
        <button type="button" @click="open(null)" class="self-start sm:self-auto h-10 px-[18px] rounded-full bg-tint text-primary hover:bg-primary hover:text-white flex items-center gap-1.5 text-[14px] font-semibold transition-colors">
            <span class="msr" style="font-size:18px">add</span>Tambah Pengalaman
        </button>
    </div>

    @if($user->experiences->isEmpty())
        <div class="mt-6 min-h-[320px] bg-white border border-border rounded-3xl flex flex-col items-center justify-center text-center gap-2.5 p-8">
            <span class="w-16 h-16 rounded-full bg-tint text-primary grid place-items-center"><span class="msr" style="font-size:30px">workspace_premium</span></span>
            <div class="mt-2 text-[18px] leading-[1.3] font-semibold">Belum ada pengalaman</div>
            <p class="max-w-[280px] text-[14px] leading-normal text-on-surface-muted">Pengalaman kerja, kepanitiaan, dan organisasi yang kamu tambahkan akan tampil di profil.</p>
            <button type="button" @click="open(null)" class="h-11 px-[22px] mt-2.5 rounded-full bg-primary hover:bg-primary-hover text-white flex items-center gap-1.5 text-[14px] font-semibold"><span class="msr">add</span>Tambah Pengalaman</button>
        </div>
    @else
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-card-gap">
            @foreach($user->experiences as $i => $x)
                <article class="bg-white border border-border rounded-3xl shadow-card p-6 flex flex-col gap-3 min-w-0">
                    <div class="flex justify-between items-start">
                        <span class="w-[42px] h-[42px] rounded-full bg-tint text-primary grid place-items-center"><span class="msr" style="font-size:21px">{{ $x->icon }}</span></span>
                        <span class="flex gap-1.5">
                            <button type="button" class="w-9 h-9 rounded-full border border-border grid place-items-center text-on-surface" aria-label="Ubah {{ $x->name }}" @click="open(@js($experiences[$i]))"><span class="msr" style="font-size:18px">edit</span></button>
                            <button type="button" class="w-9 h-9 rounded-full border border-border grid place-items-center text-on-error-container" aria-label="Hapus {{ $x->name }}" @click="del = @js($experiences[$i])"><span class="msr" style="font-size:18px">delete</span></button>
                        </span>
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-[17px] leading-[1.3] font-semibold break-words">{{ $x->name }}</h3>
                        <div class="mt-1 text-[14px] leading-[1.4] text-primary break-words">{{ $x->role }} · {{ $x->period }}</div>
                    </div>
                    @if($x->description)<p class="text-[14px] leading-normal text-on-surface-muted break-words">{{ $x->description }}</p>@endif
                </article>
            @endforeach
        </div>
    @endif

    {{-- p01-2: slide-over pengalaman --}}
    <div x-show="drawer" x-cloak x-transition.opacity class="fixed inset-0 z-50" style="background:rgba(15,23,42,0.32)" @click="close()"></div>
    <aside x-show="drawer" x-cloak x-trap.noscroll.inert="drawer" role="dialog" aria-modal="true" aria-labelledby="expTitle"
           class="fixed top-0 right-0 bottom-0 z-[51] w-full max-w-[480px] bg-white sm:rounded-l-3xl overflow-y-auto" style="box-shadow:0 20px 35px -8px rgba(63,116,156,0.16)"
           x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">
        <form method="POST" :action="action" action="{{ route('experiences.store') }}" class="min-h-full flex flex-col gap-5 px-6 py-8 sm:px-10 sm:py-9">
            @csrf
            <input type="hidden" name="_method" :value="form.id ? 'PUT' : 'POST'" value="POST">
            <input type="hidden" name="_experience_id" :value="form.id">
            <div class="flex justify-between items-start gap-4">
                <div>
                    <div class="{{ $kicker }}" x-text="form.id ? 'UBAH PENGALAMAN' : 'TAMBAH PENGALAMAN'">TAMBAH PENGALAMAN</div>
                    <h2 id="expTitle" class="mt-2 text-[24px] leading-[1.25] tracking-[-0.01em] font-bold">Pengalaman &amp; Organisasi</h2>
                </div>
                <button type="button" class="circ" aria-label="Tutup" @click="close()"><span class="msr">close</span></button>
            </div>
            @if($expErrors->any())
                <p class="text-[13px] text-on-error-container bg-error-container rounded-2xl px-4 py-3" role="alert">{{ $expErrors->first() }}</p>
            @endif
            <div class="fld">
                <label for="x_name">Nama Pengalaman/Organisasi</label>
                <input id="x_name" name="experience[name]" class="inp" x-model="form.name" x-ref="first" required maxlength="255">
            </div>
            <div class="fld">
                <label for="x_role">Peran</label>
                <input id="x_role" name="experience[role]" class="inp" x-model="form.role" required maxlength="255">
            </div>
            <div class="fld">
                <span class="text-[14px] leading-[1.2] font-semibold" id="periodLabel">Periode</span>
                <div class="grid grid-cols-2 gap-2.5" role="group" aria-labelledby="periodLabel">
                    <input name="experience[start_period]" class="inp" x-model="form.start_period" required maxlength="20" placeholder="Sep 2023" aria-label="Mulai">
                    <input name="experience[end_period]" class="inp" x-model="form.end_period" maxlength="20" placeholder="Agu 2024" aria-label="Selesai">
                </div>
            </div>
            <div class="fld">
                <label for="x_desc">Deskripsi</label>
                <textarea id="x_desc" name="experience[description]" rows="5" maxlength="1000" class="inp" x-model="form.description"></textarea>
            </div>
            <div class="mt-auto grid grid-cols-2 gap-2.5">
                <button type="button" class="h-12 rounded-full border border-border bg-white text-on-surface text-[14px] font-bold" @click="close()">Batal</button>
                <button type="submit" class="h-12 rounded-full bg-primary hover:bg-primary-hover text-white text-[14px] font-bold">Simpan</button>
            </div>
        </form>
    </aside>

    {{-- p01-3: hapus pengalaman --}}
    <div x-show="del" x-cloak class="fixed inset-0 z-[52] grid place-items-center p-4" style="background:rgba(15,23,42,0.32)" @click.self="close()">
        <div x-trap.noscroll.inert="del" role="alertdialog" aria-modal="true" aria-labelledby="delTitle" aria-describedby="delDesc"
             class="w-full max-w-[400px] bg-white rounded-3xl p-7 flex flex-col gap-2.5" style="box-shadow:0 20px 35px -8px rgba(63,116,156,0.16)">
            <span class="w-[52px] h-[52px] rounded-full bg-error-container text-on-error-container grid place-items-center"><span class="msr" style="font-size:26px">delete</span></span>
            <h3 id="delTitle" class="mt-2.5 text-[20px] leading-[1.3] font-bold">Hapus pengalaman ini?</h3>
            <p id="delDesc" class="text-[14px] leading-normal text-on-surface-muted">"<span x-text="del?.name"></span>" akan dihapus dari profilmu dan tidak bisa dikembalikan.</p>
            <form method="POST" :action="del?.destroy" class="grid grid-cols-2 gap-2.5 mt-3.5">
                @csrf @method('DELETE')
                <button type="button" class="h-[46px] rounded-full border border-border bg-white text-on-surface text-[14px] font-bold" @click="close()">Batal</button>
                <button type="submit" class="h-[46px] rounded-full bg-error text-white text-[14px] font-bold">Hapus</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('experiences', (reopen) => ({
            drawer: false,
            del: null,
            action: @js(route('experiences.store')),
            form: {},
            init() { if (reopen) this.open(reopen); },
            open(x) {
                const blank = { id: '', name: '', role: '', start_period: '', end_period: '', description: '' };
                this.form = Object.assign({}, blank, x || {});
                Object.keys(this.form).forEach(k => { if (this.form[k] === null) this.form[k] = ''; });
                this.action = x && x.id ? x.update : @js(route('experiences.store'));
                this.drawer = true;
                this.$nextTick(() => setTimeout(() => this.$refs.first && this.$refs.first.focus(), 60));
            },
            close() { this.drawer = false; this.del = null; },
        }));
    });
</script>
@endpush
