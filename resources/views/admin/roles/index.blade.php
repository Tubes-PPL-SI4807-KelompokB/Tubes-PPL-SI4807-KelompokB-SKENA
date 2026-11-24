@extends('layouts.admin')
@section('page-title', 'Role & Hak Akses')

{{--
    TIM-F Manajemen Role dan Hak Akses (FR-06) — persis Claude Design:
    tf-1 role + matriks hak akses, tf-2 slide-over tambah/ubah role, tf-3 hapus role & toast.
--}}

@php
    $modules = \App\Models\Permission::MODULES;
    $actions = \App\Models\Permission::ACTIONS;
    $core    = \App\Http\Controllers\Admin\RoleController::CORE;
    $granted = $role?->permissions->pluck('id')->all() ?? [];
    $bag     = $errors->getBag('role');
    $icons   = ['admin' => 'admin_panel_settings', 'freelancer' => 'storefront', 'client' => 'person_search'];
    $reopen  = $bag->any() ? ['id' => old('_role_id'), 'display_name' => old('display_name'), 'description' => old('description')]
             : (request('tambah') ? [] : null);
    $isCore  = $role && in_array($role->name, $core, true);
    $total   = count($modules) * count($actions);
    $saveBtn = 'h-11 px-6 rounded-full bg-primary hover:bg-primary-hover text-white text-[14px] font-semibold whitespace-nowrap';
@endphp

@section('page-head')
    @if($role)
        <div class="flex justify-end"><button type="submit" form="permForm" class="{{ $saveBtn }}" data-loading="Menyimpan…">Simpan Perubahan</button></div>
    @endif
    @include('admin._head', [
        'kicker' => 'ADMINISTRASI',
        'title'  => 'Role & Hak Akses',
        'desc'   => 'Pilih role, lalu atur apa saja yang boleh dilihat dan diubah di tiap modul.',
        'pad'    => 'pt-6 pb-8',
    ])
@endsection

@section('content')
<div x-data="roleAdmin(@js($reopen))" @keydown.escape.window="closeAll()" class="grid grid-cols-1 lg:grid-cols-12 gap-card-gap items-start">

    {{-- Daftar role --}}
    <nav class="lg:col-span-4 flex flex-col gap-card-gap" aria-label="Daftar role">
        @foreach($roles as $r)
            @php($on = $role && $r->id === $role->id)
            <a href="{{ route('admin.roles.index', ['role' => $r->id]) }}" @if($on) aria-current="page" @endif
               class="h-[132px] bg-white rounded-3xl p-6 flex flex-col justify-between shadow-card {{ $on ? 'border-2 border-primary' : 'border border-border hover:border-secondary' }}" style="{{ $on ? 'padding:23px' : '' }}">
                <span class="flex justify-between items-center w-full gap-3">
                    <span class="flex gap-3 items-center min-w-0">
                        <span class="w-[42px] h-[42px] flex-none rounded-full grid place-items-center {{ $on ? 'bg-primary text-white' : 'bg-tint text-primary' }}"><span class="msr" style="font-size:21px">{{ $icons[$r->name] ?? 'badge' }}</span></span>
                        <span class="text-[18px] leading-[1.28] font-semibold truncate">{{ $r->display_name }}</span>
                    </span>
                    @if($on)<span class="text-[12px] font-semibold text-primary">Dipilih</span>@endif
                </span>
                <span class="text-[14px] leading-[1.4] text-on-surface-muted">{{ number_format($r->users_count, 0, ',', '.') }} pengguna</span>
            </a>
        @endforeach
        <button type="button" @click="openForm({})"
                class="h-14 rounded-3xl border border-dashed border-[#CBD5E1] flex items-center justify-center gap-1.5 text-[14px] font-semibold text-primary hover:bg-tint">
            <span class="msr">add</span>Tambah Role
        </button>
    </nav>

    {{-- Matriks hak akses --}}
    @if($role)
    <form id="permForm" method="POST" action="{{ route('admin.roles.update', $role) }}"
          class="lg:col-span-8 min-w-0 bg-white border border-border rounded-3xl shadow-card overflow-hidden"
          x-data="{ count: {{ count(array_intersect($granted, $permissions->pluck('id')->all())) }} }"
          @change="count = $el.querySelectorAll('input[name=\'permissions[]\']:checked').length">
        @csrf @method('PUT')
        <div class="flex flex-wrap justify-between items-center gap-4 px-6 py-[22px]">
            <div class="min-w-0">
                <div class="flex items-center gap-1.5">
                    <h2 class="text-[18px] leading-[1.28] font-semibold">Hak Akses: {{ $role->display_name }}</h2>
                    <button type="button" class="w-8 h-8 rounded-full grid place-items-center text-on-surface-muted hover:text-primary hover:bg-tint" aria-label="Ubah role {{ $role->display_name }}"
                            @click="openForm(@js(['id' => $role->id, 'display_name' => $role->display_name, 'description' => $role->description, 'core' => $isCore]))"><span class="msr" style="font-size:18px">edit</span></button>
                </div>
                <div class="mt-0.5 text-[13px] leading-[1.45] text-on-surface-muted"><span x-text="count">{{ count($granted) }}</span> dari {{ $total }} izin aktif</div>
            </div>
            <button type="submit" class="{{ $saveBtn }}" data-loading="Menyimpan…">Simpan Perubahan</button>
        </div>
        <div class="overflow-x-auto">
            <div style="min-width:560px">
                <div class="grid items-center h-11 px-6 bg-surface border-y border-border text-[11px] leading-[1.2] font-bold tracking-[0.06em] text-on-surface-muted" style="grid-template-columns:2fr repeat(4,1fr)">
                    <span>MODUL</span>
                    @foreach($actions as $label)<span class="text-center">{{ mb_strtoupper($label) }}</span>@endforeach
                </div>
                @foreach($modules as $mKey => [$mLabel, $mIcon])
                    <div class="grid items-center px-6 py-3 {{ $loop->first ? '' : 'border-t border-border' }}" style="grid-template-columns:2fr repeat(4,1fr)">
                        <span class="flex gap-3 items-center text-[14px] font-semibold"><span class="w-[34px] h-[34px] rounded-full bg-tint text-primary grid place-items-center"><span class="msr" style="font-size:18px">{{ $mIcon }}</span></span>{{ $mLabel }}</span>
                        @foreach($actions as $aKey => $aLabel)
                            @php($perm = $permissions->get("{$mKey}.{$aKey}"))
                            <span class="flex justify-center">
                                @if($perm)
                                    <label class="sw-check" style="width:44px;height:26px">
                                        <input type="checkbox" name="permissions[]" value="{{ $perm->id }}" role="switch" aria-label="{{ $aLabel }} {{ $mLabel }}" @checked(in_array($perm->id, $granted))>
                                        <span class="switch" aria-hidden="true"></span>
                                    </label>
                                @endif
                            </span>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </form>
    @endif

    {{-- tf-2: slide-over tambah/ubah role --}}
    <div x-show="drawer" x-cloak x-transition.opacity class="fixed inset-0 z-50" style="background:rgba(15,23,42,0.32)" @click="closeAll()"></div>
    <aside x-show="drawer" x-cloak x-trap.noscroll.inert="drawer" role="dialog" aria-modal="true" aria-labelledby="roleFormTitle"
           class="fixed top-0 right-0 bottom-0 z-[51] w-full max-w-[480px] bg-white sm:rounded-l-3xl overflow-y-auto" style="box-shadow:0 20px 35px -8px rgba(63,116,156,0.16)"
           x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">
        <form method="POST" :action="form.id ? @js(url('admin/roles')) + '/' + form.id : @js(route('admin.roles.store'))" action="{{ route('admin.roles.store') }}" data-validate
              class="min-h-full flex flex-col gap-5 px-6 py-8 sm:px-10 sm:py-9">
            @csrf
            <input type="hidden" name="_method" :value="form.id ? 'PUT' : 'POST'" value="POST">
            <input type="hidden" name="_role_id" :value="form.id">
            <div class="flex justify-between items-start gap-4">
                <div>
                    <div class="text-[11px] leading-[1.2] font-bold tracking-[0.06em] text-primary" x-text="form.id ? 'UBAH ROLE' : 'TAMBAH ROLE'">TAMBAH ROLE</div>
                    <h2 id="roleFormTitle" class="mt-2 text-[24px] leading-[1.25] tracking-[-0.01em] font-bold" x-text="form.id ? form.orig : 'Role Baru'">Role Baru</h2>
                </div>
                <button type="button" class="circ" aria-label="Tutup" @click="closeAll()"><span class="msr">close</span></button>
            </div>
            <div class="fld">
                <label for="r_name">Nama Role</label>
                <input id="r_name" name="display_name" class="inp" x-model="form.display_name" x-ref="first" required maxlength="100"
                       aria-describedby="r_name_err" aria-invalid="{{ $bag->has('display_name') ? 'true' : 'false' }}" data-msg-required="Masukkan nama role.">
                <p class="err" id="r_name_err" @unless($bag->has('display_name')) hidden @endunless><span data-err-text>{{ $bag->first('display_name') }}</span></p>
            </div>
            <div class="fld">
                <label for="r_desc">Deskripsi</label>
                <textarea id="r_desc" name="description" class="inp" rows="5" maxlength="255" x-model="form.description"></textarea>
                <p class="hint">Hak akses diatur setelah role disimpan.</p>
            </div>
            <button type="button" x-show="form.id && !form.core" @click="drawer = false; del = true"
                    class="self-start text-[14px] font-semibold text-on-error-container hover:underline">Hapus role ini</button>
            <div class="mt-auto grid grid-cols-2 gap-2.5">
                <button type="button" class="h-12 rounded-full border border-border bg-white text-on-surface text-[14px] font-bold" @click="closeAll()">Batal</button>
                <button type="submit" class="h-12 rounded-full bg-primary hover:bg-primary-hover text-white text-[14px] font-bold" data-loading="Menyimpan…">Simpan</button>
            </div>
        </form>
    </aside>

    {{-- tf-3: hapus role --}}
    @if($role && !$isCore)
    <div x-show="del" x-cloak class="fixed inset-0 z-[52] grid place-items-center p-4" style="background:rgba(15,23,42,0.32)" @click.self="closeAll()">
        <div x-trap.noscroll.inert="del" role="alertdialog" aria-modal="true" aria-labelledby="delRoleTitle"
             class="w-full max-w-[440px] bg-white rounded-3xl p-7 flex flex-col gap-2.5" style="box-shadow:0 20px 35px -8px rgba(63,116,156,0.16)">
            <span class="w-[52px] h-[52px] rounded-full bg-error-container text-on-error-container grid place-items-center"><span class="msr" style="font-size:26px">delete</span></span>
            <h3 id="delRoleTitle" class="mt-2.5 text-[20px] leading-[1.3] font-bold">Hapus role {{ $role->display_name }}?</h3>
            <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" class="flex flex-col gap-2.5" data-validate>
                @csrf @method('DELETE')
                @if($role->users_count)
                    <div class="flex gap-2.5 items-start px-3.5 py-3 rounded-[18px] bg-warning-container text-on-warning-container text-[13px] leading-[1.45] font-semibold">
                        <span class="msr">warning</span>Masih ada {{ $role->users_count }} pengguna dengan role ini. Pindahkan mereka ke role lain sebelum menghapus.
                    </div>
                    <div class="fld mt-1">
                        <label for="move_to">Pindahkan pengguna ke</label>
                        <select id="move_to" name="move_to" class="inp" style="height:46px" required data-msg-required="Pilih role tujuan.">
                            @foreach($roles->where('id', '!=', $role->id) as $r)<option value="{{ $r->id }}">{{ $r->display_name }}</option>@endforeach
                        </select>
                    </div>
                @else
                    <p class="text-[14px] leading-normal text-on-surface-muted">Role ini belum dipakai pengguna mana pun dan akan dihapus permanen.</p>
                @endif
                <div class="grid grid-cols-2 gap-2.5 mt-3.5">
                    <button type="button" class="h-[46px] rounded-full border border-border bg-white text-on-surface text-[14px] font-bold" @click="closeAll()">Batal</button>
                    <button type="submit" class="h-[46px] rounded-full bg-error text-white text-[14px] font-bold" data-loading="Menghapus…">Hapus</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('roleAdmin', (reopen) => ({
            drawer: false,
            del: false,
            form: {},
            init() { if (reopen) this.openForm(reopen); },
            openForm(r) {
                r = r || {};
                this.form = { id: r.id || '', display_name: r.display_name || '', orig: r.display_name || '', description: r.description || '', core: !!r.core };
                this.drawer = true;
                this.$nextTick(() => setTimeout(() => this.$refs.first && this.$refs.first.focus(), 60));
            },
            closeAll() { this.drawer = false; this.del = false; },
        }));
    });
</script>
@endpush
