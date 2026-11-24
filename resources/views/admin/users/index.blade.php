@extends('layouts.admin')
@section('page-title', 'Pengguna')

{{--
    TIM-E Manajemen Pengguna/Akun (FR-05) — persis Claude Design:
    te-1 daftar, te-2 slide-over tambah/ubah, te-3 hapus & keadaan kosong.
--}}

@php
    $filters  = \App\Http\Controllers\Admin\UserController::FILTERS;
    $bag      = $errors->getBag('user');
    $editing  = request('ubah') ? \App\Models\User::find((int) request('ubah')) : null;
    $defaultRole = (string) ($roles->firstWhere('name', 'freelancer')?->id ?? '');
    $toForm   = fn ($u) => [
        'id' => $u->id, 'name' => $u->name, 'email' => $u->email, 'nim' => $u->nim, 'role_id' => $u->role_id,
        'verified' => $u->student_verification_status === 'verified', 'active' => $u->status === 'active',
        'update' => route('admin.users.update', $u),
    ];
    $reopen = $bag->any() ? [
        'id' => old('_user_id'), 'name' => old('name'), 'email' => old('email'), 'nim' => old('nim'), 'role_id' => old('role_id'),
        'verified' => (bool) old('verified'), 'active' => (bool) old('active', 1),
        'update' => old('_user_id') ? route('admin.users.update', old('_user_id')) : null,
    ] : ($editing ? $toForm($editing) : (request('tambah') ? [] : null));
    $initials = fn ($name) => collect(preg_split('/\s+/', trim($name)))->filter()->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->take(3)->implode('');
    $th = 'px-4 first:pl-6 last:pr-6 text-left text-[11px] leading-[1.2] font-bold tracking-[0.06em] text-on-surface-muted';
    $ibtn = 'w-9 h-9 rounded-full border border-border bg-white grid place-items-center';
@endphp

@section('page-head')
    {{-- Toolbar: cari, filter, tambah --}}
    <div class="flex flex-wrap items-center gap-2.5">
        <form method="GET" action="{{ route('admin.users.index') }}" role="search"
              class="h-11 w-full md:w-[320px] px-5 rounded-full border border-border bg-white flex items-center gap-2.5 focus-within:border-primary">
            <span class="msr text-on-surface-muted">search</span>
            <label for="userSearch" class="sr-only">Cari nama, email, atau NIM</label>
            <input id="userSearch" type="search" name="search" value="{{ $search }}" placeholder="Cari nama, email, atau NIM…"
                   class="flex-1 min-w-0 border-0 outline-none bg-transparent text-[15px] text-on-surface placeholder:text-on-surface-muted">
            @if($filter !== 'semua')<input type="hidden" name="filter" value="{{ $filter }}">@endif
        </form>
        <div class="flex gap-2 md:ml-auto max-w-full overflow-x-auto">
            @foreach($filters as $key => $label)
                <a href="{{ route('admin.users.index', array_filter(['filter' => $key === 'semua' ? null : $key, 'search' => $search])) }}"
                   class="h-11 px-5 rounded-full border text-[14px] font-semibold whitespace-nowrap flex items-center {{ $filter === $key ? 'bg-primary border-primary text-white' : 'bg-white border-border text-on-surface-muted hover:text-on-surface' }}"
                   @if($filter === $key) aria-current="page" @endif>{{ $label }}</a>
            @endforeach
        </div>
        <button type="button" x-data @click="$dispatch('user-form', {})"
                class="h-11 px-[22px] rounded-full bg-primary hover:bg-primary-hover text-white flex items-center gap-1.5 text-[14px] font-semibold whitespace-nowrap">
            <span class="msr">add</span>Tambah Pengguna
        </button>
    </div>
    @include('admin._head', [
        'kicker' => 'ADMINISTRASI',
        'title'  => 'Pengguna',
        'desc'   => number_format($total, 0, ',', '.') . ' pengguna terdaftar · ' . $users->total() . ' ditampilkan dengan filter "' . $filters[$filter] . '"' . ($search ? ' dan pencarian "' . $search . '"' : '') . '.',
    ])
@endsection

@section('content')
<div x-data="userAdmin(@js($reopen), @js($defaultRole))" @user-form.window="open($event.detail)" @keydown.escape.window="closeAll()">

    <div class="bg-white border border-border rounded-3xl shadow-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse" style="min-width:1100px;table-layout:fixed">
                <colgroup><col style="width:17.5%"><col style="width:21.6%"><col style="width:10.3%"><col style="width:12.4%"><col style="width:13.4%"><col style="width:10.4%"><col style="width:14.4%"></colgroup>
                <thead>
                    <tr class="h-11 bg-surface border-b border-border">
                        <th scope="col" class="{{ $th }}">NAMA</th>
                        <th scope="col" class="{{ $th }}">EMAIL KAMPUS</th>
                        <th scope="col" class="{{ $th }}">NIM</th>
                        <th scope="col" class="{{ $th }}">ROLE</th>
                        <th scope="col" class="{{ $th }}">STATUS VERIFIKASI</th>
                        <th scope="col" class="{{ $th }}">STATUS AKUN</th>
                        <th scope="col" class="{{ $th }}" style="text-align:right">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $i => $u)
                        @php($verified = $u->student_verification_status === 'verified')
                        @php($active = $u->status === 'active')
                        @php($self = $u->id === auth()->id())
                        <tr class="hover:bg-surface {{ $i ? 'border-t border-border' : '' }}">
                            <td class="px-4 pl-6 py-3.5">
                                <div class="flex items-center gap-3 min-w-0">
                                    <span class="w-[38px] h-[38px] flex-none rounded-full bg-tint text-primary grid place-items-center text-[13px] font-bold">{{ $initials($u->name) }}</span>
                                    <div class="min-w-0">
                                        <div class="text-[14px] leading-[1.3] font-semibold truncate">{{ $u->name }}</div>
                                        <div class="text-[12px] leading-[1.4] text-on-surface-muted truncate">{{ collect([$u->major, $u->angkatan])->filter()->implode(' ') ?: ($u->university ?: '—') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 text-[14px] leading-[1.3] text-on-surface-variant"><span class="block truncate" title="{{ $u->email }}">{{ $u->email }}</span></td>
                            <td class="px-4 py-3.5 text-[14px] text-on-surface-variant">{{ $u->nim ?: '—' }}</td>
                            <td class="px-4 py-3.5"><span class="inline-block max-w-full truncate h-7 px-3 rounded-full bg-tint text-[#346284] text-[13px] leading-7 whitespace-nowrap">{{ $u->role?->display_name ?? 'Tanpa role' }}</span></td>
                            <td class="px-4 py-3.5">
                                <span class="h-8 px-3.5 rounded-full inline-flex items-center gap-2 text-[13px] font-semibold whitespace-nowrap {{ $verified ? 'bg-success-container text-on-success-container' : 'bg-warning-container text-on-warning-container' }}">
                                    <span class="w-[7px] h-[7px] rounded-full {{ $verified ? 'bg-success' : 'bg-warning' }}"></span>{{ $verified ? 'Terverifikasi' : 'Belum Verifikasi' }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5">
                                <form method="POST" action="{{ route('admin.users.status', $u->id) }}" class="flex items-center gap-2.5">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="switch" role="switch" aria-checked="{{ $active ? 'true' : 'false' }}" aria-label="Status akun {{ $u->name }}"
                                            @if($self) disabled style="opacity:.5;cursor:not-allowed" @endif></button>
                                    <span class="text-[13px] font-semibold text-on-surface-variant">{{ $active ? 'Aktif' : 'Nonaktif' }}</span>
                                </form>
                            </td>
                            <td class="px-4 pr-6 py-3.5">
                                <div class="flex justify-end gap-1.5">
                                    <a href="{{ route('admin.users.show', $u) }}" class="{{ $ibtn }}" aria-label="Lihat {{ $u->name }}"><span class="msr" style="font-size:18px">visibility</span></a>
                                    <button type="button" class="{{ $ibtn }}" aria-label="Ubah {{ $u->name }}" @click="open(@js($toForm($u)))"><span class="msr" style="font-size:18px">edit</span></button>
                                    <form method="POST" action="{{ route('admin.users.status', $u->id) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="{{ $ibtn }}" aria-label="{{ $active ? 'Nonaktifkan' : 'Aktifkan' }} {{ $u->name }}" @if($self) disabled style="opacity:.5;cursor:not-allowed" @endif><span class="msr" style="font-size:18px">block</span></button>
                                    </form>
                                    <button type="button" class="{{ $ibtn }} text-on-error-container" aria-label="Hapus {{ $u->name }}" @if($self) disabled style="opacity:.5;cursor:not-allowed" @else @click="del = @js(['name' => $u->name, 'url' => route('admin.users.destroy', $u)])" @endif><span class="msr" style="font-size:18px">delete</span></button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="flex flex-col items-center justify-center text-center gap-2.5 px-8 py-16">
                                    <span class="w-16 h-16 rounded-full bg-tint text-primary grid place-items-center"><span class="msr" style="font-size:30px">group</span></span>
                                    <div class="mt-2 text-[18px] leading-[1.3] font-semibold">Belum ada pengguna</div>
                                    <p class="max-w-[280px] text-[14px] leading-normal text-on-surface-muted">Pengguna yang mendaftar atau kamu tambahkan akan muncul di sini.</p>
                                    <button type="button" @click="open({})" class="h-11 px-[22px] mt-2.5 rounded-full bg-primary hover:bg-primary-hover text-white flex items-center gap-1.5 text-[14px] font-semibold"><span class="msr">add</span>Tambah Pengguna</button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex flex-wrap justify-between items-center gap-3 px-6 py-3.5 border-t border-border text-[13px] text-on-surface-muted">
            <span>Menampilkan {{ $users->count() }} dari {{ number_format($users->total(), 0, ',', '.') }} pengguna</span>
            <nav class="flex gap-1.5" aria-label="Halaman">
                @foreach(range(1, max(1, $users->lastPage())) as $p)
                    @if($p === $users->currentPage())
                        <span class="w-[34px] h-[34px] rounded-full bg-primary text-white grid place-items-center font-semibold" aria-current="page">{{ $p }}</span>
                    @else
                        <a href="{{ $users->url($p) }}" class="w-[34px] h-[34px] rounded-full border border-border grid place-items-center hover:border-secondary">{{ $p }}</a>
                    @endif
                @endforeach
            </nav>
        </div>
    </div>

    {{-- te-2: slide-over tambah/ubah --}}
    <div x-show="drawer" x-cloak x-transition.opacity class="fixed inset-0 z-50" style="background:rgba(15,23,42,0.32)" @click="closeAll()"></div>
    <aside x-show="drawer" x-cloak x-trap.noscroll.inert="drawer" role="dialog" aria-modal="true" aria-labelledby="userFormTitle"
           class="fixed top-0 right-0 bottom-0 z-[51] w-full max-w-[500px] bg-white sm:rounded-l-3xl overflow-y-auto" style="box-shadow:0 20px 35px -8px rgba(63,116,156,0.16)"
           x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">
        <form method="POST" :action="form.update || @js(route('admin.users.store'))" action="{{ route('admin.users.store') }}" data-validate
              class="min-h-full flex flex-col gap-5 px-6 py-8 sm:px-10 sm:py-9">
            @csrf
            <input type="hidden" name="_method" :value="form.id ? 'PUT' : 'POST'" value="POST">
            <input type="hidden" name="_user_id" :value="form.id">
            <input type="hidden" name="filter" value="{{ $filter === 'semua' ? '' : $filter }}">
            <input type="hidden" name="search" value="{{ $search }}">

            <div class="flex justify-between items-start gap-4">
                <div>
                    <div class="text-[11px] leading-[1.2] font-bold tracking-[0.06em] text-primary" x-text="form.id ? 'UBAH PENGGUNA' : 'TAMBAH PENGGUNA'">TAMBAH PENGGUNA</div>
                    <h2 id="userFormTitle" class="mt-2 text-[24px] leading-[1.25] tracking-[-0.01em] font-bold" x-text="form.id ? form.orig : 'Pengguna Baru'">Pengguna Baru</h2>
                </div>
                <button type="button" class="circ" aria-label="Tutup" @click="closeAll()"><span class="msr">close</span></button>
            </div>

            @if($bag->any())
                <p class="text-[13px] text-on-error-container bg-error-container rounded-2xl px-4 py-3" role="alert">{{ $bag->first() }}</p>
            @endif

            <div class="fld">
                <label for="u_name">Nama</label>
                <input id="u_name" name="name" class="inp" x-model="form.name" x-ref="first" required maxlength="255" autocomplete="off"
                       aria-describedby="u_name_err" aria-invalid="{{ $bag->has('name') ? 'true' : 'false' }}" data-msg-required="Masukkan nama pengguna.">
                <p class="err" id="u_name_err" hidden><span data-err-text></span></p>
            </div>
            <div class="fld">
                <label for="u_email">Email Kampus</label>
                <input id="u_email" name="email" type="email" class="inp" x-model="form.email" required maxlength="255" autocomplete="off"
                       aria-describedby="u_email_err" aria-invalid="{{ $bag->has('email') ? 'true' : 'false' }}"
                       data-msg-required="Masukkan email pengguna." data-msg-type="Tulis email lengkap, misalnya nama@student.telkomuniversity.ac.id.">
                <p class="err" id="u_email_err" hidden><span data-err-text></span></p>
            </div>
            <div class="grid grid-cols-2 gap-card-gap">
                <div class="fld">
                    <label for="u_nim">NIM</label>
                    <input id="u_nim" name="nim" class="inp" x-model="form.nim" maxlength="50" inputmode="numeric" autocomplete="off">
                </div>
                <div class="fld">
                    <label for="u_role">Role</label>
                    <select id="u_role" name="role_id" class="inp" x-model="form.role_id" required data-msg-required="Pilih role pengguna.">
                        @foreach($roles as $r)<option value="{{ $r->id }}">{{ $r->display_name }}</option>@endforeach
                    </select>
                </div>
            </div>

            <fieldset class="flex flex-col gap-2">
                <legend class="text-[14px] leading-[1.2] font-semibold mb-2">Status Verifikasi</legend>
                <div class="flex p-1 rounded-full bg-surface border border-border">
                    <label class="flex-1 h-[38px] rounded-full grid place-items-center text-[14px] font-semibold cursor-pointer" :class="form.verified ? 'bg-primary text-white' : 'text-on-surface-muted'">
                        <input type="radio" name="verified" value="1" class="sr-only" :checked="form.verified" @change="form.verified = true">Terverifikasi
                    </label>
                    <label class="flex-1 h-[38px] rounded-full grid place-items-center text-[14px] font-semibold cursor-pointer" :class="!form.verified ? 'bg-primary text-white' : 'text-on-surface-muted'">
                        <input type="radio" name="verified" value="0" class="sr-only" :checked="!form.verified" @change="form.verified = false">Belum Verifikasi
                    </label>
                </div>
            </fieldset>

            <div class="flex justify-between items-center gap-4 px-5 py-4 rounded-[18px] border border-border">
                <div>
                    <div class="text-[14px] leading-[1.2] font-semibold" id="u_active_lbl">Status Akun</div>
                    <div class="mt-0.5 text-[13px] leading-[1.4] text-on-surface-muted" x-text="form.active ? 'Aktif, pengguna bisa masuk' : 'Nonaktif, pengguna tidak bisa masuk'"></div>
                </div>
                <input type="hidden" name="active" :value="form.active ? 1 : 0">
                <button type="button" class="switch" role="switch" :aria-checked="form.active.toString()" aria-labelledby="u_active_lbl" @click="form.active = !form.active"></button>
            </div>

            <div class="mt-auto grid grid-cols-2 gap-2.5">
                <button type="button" class="h-12 rounded-full border border-border bg-white text-on-surface text-[14px] font-bold" @click="closeAll()">Batal</button>
                <button type="submit" class="h-12 rounded-full bg-primary hover:bg-primary-hover text-white text-[14px] font-bold" data-loading="Menyimpan…">Simpan</button>
            </div>
        </form>
    </aside>

    {{-- te-3: hapus akun --}}
    <div x-show="del" x-cloak class="fixed inset-0 z-[52] grid place-items-center p-4" style="background:rgba(15,23,42,0.32)" @click.self="closeAll()">
        <div x-trap.noscroll.inert="del" role="alertdialog" aria-modal="true" aria-labelledby="delUserTitle" aria-describedby="delUserDesc"
             class="w-full max-w-[420px] bg-white rounded-3xl p-7 flex flex-col gap-2.5" style="box-shadow:0 20px 35px -8px rgba(63,116,156,0.16)">
            <span class="w-[52px] h-[52px] rounded-full bg-error-container text-on-error-container grid place-items-center"><span class="msr" style="font-size:26px">delete</span></span>
            <h3 id="delUserTitle" class="mt-2.5 text-[20px] leading-[1.3] font-bold">Hapus akun <span x-text="del?.name"></span>?</h3>
            <p id="delUserDesc" class="text-[14px] leading-normal text-on-surface-muted">Akun ini belum punya pesanan atau transaksi. Data akun akan dihapus permanen dan tidak bisa dikembalikan.</p>
            <form method="POST" :action="del?.url" class="grid grid-cols-2 gap-2.5 mt-3.5">
                @csrf @method('DELETE')
                <button type="button" class="h-[46px] rounded-full border border-border bg-white text-on-surface text-[14px] font-bold" @click="closeAll()">Batal</button>
                <button type="submit" class="h-[46px] rounded-full bg-error text-white text-[14px] font-bold" data-loading="Menghapus…">Hapus</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('userAdmin', (reopen, defaultRole) => ({
            drawer: false,
            del: null,
            form: { active: true, verified: false },
            init() { if (reopen) this.open(reopen); },
            open(u) {
                u = u || {};
                this.form = {
                    id: u.id || '', name: u.name || '', orig: u.name || '', email: u.email || '', nim: u.nim || '',
                    role_id: u.role_id ? String(u.role_id) : defaultRole, verified: !!u.verified,
                    active: u.active === undefined ? true : !!u.active, update: u.update || null,
                };
                this.drawer = true;
                this.$nextTick(() => setTimeout(() => this.$refs.first && this.$refs.first.focus(), 60));
            },
            closeAll() { this.drawer = false; this.del = null; },
        }));
    });
</script>
@endpush
