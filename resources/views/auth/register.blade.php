<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Daftar Akun — SKENA</title>
    @include('layouts.partials.design-tokens', ['designRadius' => true])
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,0&display=swap" rel="stylesheet">
    <style>
        @layer base { html,body { margin:0; padding:0; } body { overscroll-behavior:none; } }
        ::-webkit-scrollbar { display:none; }
        * { font-family: 'Outfit', sans-serif; }
    </style>

</head>
<body style="background:linear-gradient(180deg,#E3E4E6 0,#E3E4E6 38%,#D6E8F7 100%)" class="font-body-md text-on-surface antialiased min-h-screen flex flex-col justify-between">

{{-- Header: wordmark di tengah (DESIGN.md) --}}
<header class="w-full px-6 lg:px-12">
    <div class="max-w-6xl mx-auto h-[100px] grid grid-cols-[1fr_auto_1fr] items-center">
        <a href="{{ route('home') }}" class="justify-self-start inline-flex items-center gap-2 text-label-md text-on-surface-variant hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span><span class="hidden sm:inline">Beranda</span>
        </a>
        <a href="{{ route('home') }}" class="text-[32px] leading-none font-bold tracking-[-0.02em] text-primary" aria-label="SKENA">SKENA</a>
        <span class="justify-self-end text-label-md text-on-surface-variant hidden sm:block">Skill Exchange Network</span>
    </div>
</header>

<main class="w-full flex-1 flex items-center justify-center px-6 pb-space-xl">
    <div class="flex flex-col w-full max-w-7xl mx-auto px-2 sm:px-6">

        {{-- Registration Shell --}}
        <div class="w-full bg-surface-container-lowest rounded-[28px] shadow-sm overflow-hidden p-3 lg:p-4 my-2">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 lg:gap-6 min-h-[700px] items-stretch">

                {{-- LEFT BRAND PANEL --}}
                <div class="lg:col-span-5 relative rounded-[24px] bg-gradient-to-br from-hero-start to-primary overflow-hidden p-8 lg:p-10 flex flex-col justify-between text-on-primary">
                    <svg class="absolute bottom-0 right-0 w-full h-48 opacity-20 pointer-events-none" fill="none" preserveAspectRatio="none" viewBox="0 0 500 220">
                        <path d="M0,120 C150,220 300,40 500,140 L500,220 L0,220 Z" fill="#FFFFFF"></path>
                    </svg>

                    <div class="relative z-10">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/20 text-on-primary text-label-sm font-label-sm tracking-wide uppercase shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                            Koneksi Kampus Terpercaya
                        </div>
                        <div class="mt-8 space-y-3">
                            <h1 class="font-display text-display-mobile text-on-primary leading-tight font-bold tracking-tight">
                                Ubah Skill<br/>Jadi Penghasilan
                            </h1>
                            <p class="font-body-lg text-body-lg text-on-primary/90 max-w-sm">
                                Marketplace peer-to-peer jasa mikro, barter skill, dan kolaborasi kreatif khusus mahasiswa aktif se-Indonesia.
                            </p>
                        </div>
                        {{-- Feature Chips --}}
                        <div class="mt-8 flex flex-wrap gap-2.5">
                            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/95 text-on-background font-label-md text-label-md shadow-sm">
                                <span class="material-symbols-outlined text-primary-container text-[18px]">payments</span>
                                <span>Harga Ramah Mahasiswa</span>
                            </div>
                            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/95 text-on-background font-label-md text-label-md shadow-sm">
                                <span class="material-symbols-outlined text-primary-container text-[18px]">verified_user</span>
                                <span>Komunitas Tepercaya</span>
                            </div>
                            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/95 text-on-background font-label-md text-label-md shadow-sm">
                                <span class="material-symbols-outlined text-primary-container text-[18px]">star_rate</span>
                                <span>Portofolio & Rating Real</span>
                            </div>
                        </div>
                    </div>

                    {{-- University Badges --}}
                    <div class="relative z-10 pt-10 mt-8">
                        <p class="font-label-sm text-label-sm uppercase tracking-wider text-on-primary/80 mb-2.5">
                            Didukung Mahasiswa Kampus Unggulan
                        </p>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="px-3 py-1 rounded-full bg-white/20 text-label-sm font-label-sm text-on-primary">Telkom Univ</span>
                            <span class="px-3 py-1 rounded-full bg-white/20 text-label-sm font-label-sm text-on-primary">Univ. Indonesia</span>
                            <span class="px-3 py-1 rounded-full bg-white/20 text-label-sm font-label-sm text-on-primary">ITB</span>
                            <span class="px-3 py-1 rounded-full bg-white/20 text-label-sm font-label-sm text-on-primary">UGM</span>
                            <span class="px-3 py-1 rounded-full bg-white/20 text-label-sm font-label-sm text-on-primary">+45 Kampus Lain</span>
                        </div>
                    </div>
                </div>

                {{-- RIGHT FORM --}}
                <div class="lg:col-span-7 flex flex-col justify-center px-4 sm:px-8 lg:px-12 py-6">
                    {{-- Form Header --}}
                    <div class="mb-6">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <h2 class="font-headline-lg text-headline-lg text-on-background tracking-tight">Buat Akun Mahasiswa</h2>
                                <p class="font-body-md text-body-md text-on-surface-variant mt-1">
                                    Gunakan email resmi kampusmu untuk bergabung di SKENA.
                                </p>
                            </div>
                            <div class="hidden sm:flex w-11 h-11 rounded-full bg-surface-container-low items-center justify-center text-primary-container">
                                <span class="material-symbols-outlined text-[24px]">school</span>
                            </div>
                        </div>
                        {{-- Info pill --}}
                        <div class="mt-4 p-3.5 rounded-[16px] bg-surface-container-low flex items-start gap-3">
                            <span class="material-symbols-outlined text-primary-container text-[20px] mt-0.5">swap_horiz</span>
                            <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
                                <span class="font-semibold text-primary">Satu akun multifungsi:</span> Kamu otomatis bisa membuka jasa keahlian sendiri sekaligus menyewa bantuan dari mahasiswa lain.
                            </p>
                        </div>
                    </div>

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="mb-5 p-4 rounded-2xl bg-error-container text-on-error-container flex items-start gap-3">
                            <span class="material-symbols-outlined text-error text-[20px] mt-0.5 shrink-0" style="font-variation-settings: 'FILL' 1;">error</span>
                            <div>
                                <p class="font-label-lg text-label-lg font-bold mb-1">Mohon perbaiki kesalahan berikut:</p>
                                @foreach($errors->all() as $error)
                                    <p class="font-body-sm text-body-sm">• {{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Registration Form --}}
                    <form class="space-y-4" id="registerForm" method="POST" action="{{ route('register') }}" enctype="multipart/form-data"
                        x-data="{
                            role: '{{ old('role', 'client') }}',
                            showPassword: false,
                            showConfirm: false,
                            passwordStrength: 0,
                            checkStrength(val) {
                                let s = 0;
                                if (val.length >= 8) s++;
                                if (/[A-Z]/.test(val)) s++;
                                if (/[0-9]/.test(val)) s++;
                                if (/[^A-Za-z0-9]/.test(val)) s++;
                                this.passwordStrength = s;
                            }
                        }">
                        @csrf

                        {{-- Role selector --}}
                        <div class="space-y-1.5">
                            <label class="block font-label-md text-label-md text-on-surface">Daftar Sebagai</label>
                            <div class="grid grid-cols-3 gap-2">
                                <button type="button" @click="role='student'"
                                    :class="role==='student' ? 'bg-primary-container text-on-primary shadow-sm' : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container'"
                                    class="flex flex-col items-center gap-1 p-3 rounded-2xl transition-all">
                                    <span class="material-symbols-outlined text-[22px]">school</span>
                                    <span class="font-label-sm text-label-sm">Mahasiswa</span>
                                </button>
                                <button type="button" @click="role='freelancer'"
                                    :class="role==='freelancer' ? 'bg-primary-container text-on-primary shadow-sm' : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container'"
                                    class="flex flex-col items-center gap-1 p-3 rounded-2xl transition-all">
                                    <span class="material-symbols-outlined text-[22px]">work</span>
                                    <span class="font-label-sm text-label-sm">Freelancer</span>
                                </button>
                                <button type="button" @click="role='client'"
                                    :class="role==='client' ? 'bg-primary-container text-on-primary shadow-sm' : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container'"
                                    class="flex flex-col items-center gap-1 p-3 rounded-2xl transition-all">
                                    <span class="material-symbols-outlined text-[22px]">person_search</span>
                                    <span class="font-label-sm text-label-sm">Client</span>
                                </button>
                            </div>
                            <input type="hidden" name="role" :value="role"/>
                        </div>

                        {{-- Nama Lengkap --}}
                        <div class="space-y-1.5">
                            <label class="block font-label-md text-label-md text-on-surface" for="fullname">Nama Lengkap</label>
                            <div class="relative flex items-center">
                                <span class="absolute left-4 text-on-surface-variant material-symbols-outlined text-[20px]">person</span>
                                <input class="w-full h-12 pl-12 pr-4 rounded-full bg-surface-container-lowest text-on-surface font-body-md text-body-md shadow-sm placeholder:text-outline-variant focus:outline-none focus:ring-2 focus:ring-primary-container transition-all @error('name') ring-2 ring-error @enderror"
                                    id="fullname" name="name" placeholder="Contoh: Muhammad Farhan Pratama"
                                    type="text" value="{{ old('name') }}" required/>
                            </div>
                            @error('name') <p class="font-body-sm text-body-sm text-error ml-4">{{ $message }}</p> @enderror
                        </div>

                        {{-- Email --}}
                        <div class="space-y-1.5">
                            <label class="block font-label-md text-label-md text-on-surface" for="email">Email</label>
                            <div class="relative flex items-center">
                                <span class="absolute left-4 text-on-surface-variant material-symbols-outlined text-[20px]">alternate_email</span>
                                <input class="w-full h-12 pl-12 pr-4 rounded-full bg-surface-container-lowest text-on-surface font-body-md text-body-md shadow-sm placeholder:text-outline-variant focus:outline-none focus:ring-2 focus:ring-primary-container transition-all @error('email') ring-2 ring-error @enderror"
                                    id="email" name="email" placeholder="nama@student.telkomuniversity.ac.id"
                                    type="email" value="{{ old('email') }}" required/>
                            </div>
                            @error('email') <p class="font-body-sm text-body-sm text-error ml-4">{{ $message }}</p> @enderror
                        </div>

                        {{-- NIM (hanya jika student/freelancer) --}}
                        <div class="space-y-1.5" x-show="role === 'student' || role === 'freelancer'" x-transition>
                            <label class="block font-label-md text-label-md text-on-surface" for="nim">NIM (Nomor Induk Mahasiswa)</label>
                            <div class="relative flex items-center">
                                <span class="absolute left-4 text-on-surface-variant material-symbols-outlined text-[20px]">badge</span>
                                <input class="w-full h-12 pl-12 pr-4 rounded-full bg-surface-container-lowest text-on-surface font-body-md text-body-md shadow-sm placeholder:text-outline-variant focus:outline-none focus:ring-2 focus:ring-primary-container transition-all"
                                    id="nim" name="nim" placeholder="1301210000"
                                    type="text" value="{{ old('nim') }}"/>
                            </div>
                            @error('nim') <p class="font-body-sm text-body-sm text-error ml-4">{{ $message }}</p> @enderror
                        </div>

                        {{-- Universitas (hanya jika student/freelancer) --}}
                        <div class="space-y-1.5" x-show="role === 'student' || role === 'freelancer'" x-transition>
                            <label class="block font-label-md text-label-md text-on-surface" for="university">Universitas</label>
                            <div class="relative flex items-center">
                                <span class="absolute left-4 text-on-surface-variant material-symbols-outlined text-[20px]">account_balance</span>
                                <input class="w-full h-12 pl-12 pr-4 rounded-full bg-surface-container-lowest text-on-surface font-body-md text-body-md shadow-sm placeholder:text-outline-variant focus:outline-none focus:ring-2 focus:ring-primary-container transition-all"
                                    id="university" name="university" placeholder="Telkom University"
                                    type="text" value="{{ old('university') }}"/>
                            </div>
                        </div>

                        {{-- KTM Upload (hanya jika student/freelancer) --}}
                        <div class="space-y-1.5" x-show="role === 'student' || role === 'freelancer'" x-transition>
                            <label class="block font-label-md text-label-md text-on-surface" for="ktm">
                                Kartu Tanda Mahasiswa (KTM)
                                <span class="text-outline font-normal">(Opsional, untuk verifikasi lebih cepat)</span>
                            </label>
                            <div class="relative flex items-center">
                                <span class="absolute left-4 text-on-surface-variant material-symbols-outlined text-[20px]">id_card</span>
                                <input class="w-full h-12 pl-12 pr-4 rounded-full bg-surface-container-lowest text-on-surface font-body-md text-body-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-container transition-all file:border-0 file:bg-transparent file:text-sm file:font-medium"
                                    id="ktm" name="ktm_image" type="file" accept="image/*,.pdf"/>
                            </div>
                            @error('ktm_image') <p class="font-body-sm text-body-sm text-error ml-4">{{ $message }}</p> @enderror
                        </div>

                        {{-- Kata Sandi --}}
                        <div class="space-y-1.5">
                            <label class="block font-label-md text-label-md text-on-surface" for="password">Kata Sandi</label>
                            <div class="relative flex items-center">
                                <span class="absolute left-4 text-on-surface-variant material-symbols-outlined text-[20px]">lock</span>
                                <input class="w-full h-12 pl-12 pr-12 rounded-full bg-surface-container-lowest text-on-surface font-body-md text-body-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-container transition-all @error('password') ring-2 ring-error @enderror"
                                    id="password" name="password" placeholder="Min. 8 karakter"
                                    :type="showPassword ? 'text' : 'password'"
                                    @input="checkStrength($event.target.value)"
                                    required/>
                                <button aria-label="Toggle password visibility" @click="showPassword = !showPassword"
                                    class="absolute right-3.5 text-on-surface-variant hover:text-primary transition-colors flex items-center justify-center p-1 rounded-full hover:bg-surface-container-low" type="button">
                                    <span class="material-symbols-outlined text-[20px]" x-text="showPassword ? 'visibility_off' : 'visibility'">visibility_off</span>
                                </button>
                            </div>
                            {{-- Password Strength Bar --}}
                            <div class="pt-1.5 px-1 space-y-1">
                                <div class="flex items-center justify-between text-label-sm font-label-sm">
                                    <span class="text-on-surface-variant">Kekuatan Sandi</span>
                                    <span class="font-bold flex items-center gap-1"
                                        :class="passwordStrength < 2 ? 'text-error' : passwordStrength < 3 ? 'text-yellow-500' : 'text-green-600'">
                                        <span class="w-1.5 h-1.5 rounded-full"
                                            :class="passwordStrength < 2 ? 'bg-error' : passwordStrength < 3 ? 'bg-yellow-500' : 'bg-green-600'"></span>
                                        <span x-text="passwordStrength < 2 ? 'Lemah' : passwordStrength < 3 ? 'Sedang' : passwordStrength < 4 ? 'Kuat' : 'Sangat Kuat'">-</span>
                                    </span>
                                </div>
                                <div class="w-full h-1.5 bg-surface-container-high rounded-full overflow-hidden flex gap-1">
                                    <div class="h-full rounded-full transition-all" :class="passwordStrength >= 1 ? (passwordStrength < 2 ? 'bg-error' : passwordStrength < 3 ? 'bg-yellow-500' : 'bg-green-600') : 'bg-transparent'" style="width:25%"></div>
                                    <div class="h-full rounded-full transition-all" :class="passwordStrength >= 2 ? (passwordStrength < 3 ? 'bg-yellow-500' : 'bg-green-600') : 'bg-surface-container-high'" style="width:25%"></div>
                                    <div class="h-full rounded-full transition-all" :class="passwordStrength >= 3 ? 'bg-green-600' : 'bg-surface-container-high'" style="width:25%"></div>
                                    <div class="h-full rounded-full transition-all" :class="passwordStrength >= 4 ? 'bg-green-600' : 'bg-surface-container-high'" style="width:25%"></div>
                                </div>
                            </div>
                            @error('password') <p class="font-body-sm text-body-sm text-error ml-4">{{ $message }}</p> @enderror
                        </div>

                        {{-- Konfirmasi Kata Sandi --}}
                        <div class="space-y-1.5">
                            <label class="block font-label-md text-label-md text-on-surface" for="password_confirmation">Konfirmasi Kata Sandi</label>
                            <div class="relative flex items-center">
                                <span class="absolute left-4 text-on-surface-variant material-symbols-outlined text-[20px]">lock_reset</span>
                                <input class="w-full h-12 pl-12 pr-12 rounded-full bg-surface-container-lowest text-on-surface font-body-md text-body-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-container transition-all"
                                    id="password_confirmation" name="password_confirmation" placeholder="Ulangi kata sandi"
                                    :type="showConfirm ? 'text' : 'password'"
                                    required/>
                                <button @click="showConfirm = !showConfirm"
                                    class="absolute right-3.5 text-on-surface-variant hover:text-primary transition-colors flex items-center justify-center p-1 rounded-full" type="button">
                                    <span class="material-symbols-outlined text-[20px]" x-text="showConfirm ? 'visibility_off' : 'visibility'">visibility_off</span>
                                </button>
                            </div>
                        </div>

                        {{-- Agreement --}}
                        <div class="pt-2 flex items-start gap-3">
                            <input class="w-4 h-4 mt-0.5 rounded accent-primary-container cursor-pointer focus:ring-0" id="terms" required type="checkbox"/>
                            <label class="font-body-sm text-body-sm text-on-surface-variant cursor-pointer select-none" for="terms">
                                Saya setuju dengan <a class="text-primary font-semibold hover:underline" href="#">Syarat & Ketentuan</a> serta <a class="text-primary font-semibold hover:underline" href="#">Kebijakan Privasi SKENA</a>.
                            </label>
                        </div>

                        {{-- Submit Button --}}
                        <div class="pt-2">
                            <button class="group w-full h-12 rounded-full bg-primary hover:bg-primary-hover text-on-primary font-label-lg text-label-lg flex items-center justify-center gap-2 shadow-sm hover:shadow-md transition-all active:scale-[0.99]" type="submit">
                                <span>Daftar Sekarang</span>
                                <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center group-hover:translate-x-1 transition-transform">
                                    <span class="material-symbols-outlined text-[16px] text-on-primary">arrow_forward</span>
                                </div>
                            </button>
                        </div>
                    </form>

                    {{-- Login Link --}}
                    <div class="mt-6 pt-4 text-center">
                        <p class="font-body-md text-body-md text-on-surface-variant">
                            Sudah punya akun SKENA?
                            <a class="text-primary font-semibold hover:underline inline-flex items-center gap-0.5 ml-1" href="{{ route('login') }}">
                                Masuk Sekarang
                                <span class="material-symbols-outlined text-[16px]">north_east</span>
                            </a>
                        </p>
                    </div>
                </div>

            </div>
        </div>

        {{-- Bottom Feature Row --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 px-2 py-4">
            <div class="bg-surface-container-lowest rounded-2xl p-3.5 shadow-sm flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-surface-container-low flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[20px]">shield</span>
                </div>
                <div class="min-w-0">
                    <p class="font-label-md text-label-md text-on-surface truncate">Escrow Mahasiswa</p>
                    <p class="font-body-sm text-body-sm text-on-surface-variant truncate">Dana aman hingga tugas tuntas</p>
                </div>
            </div>
            <div class="bg-surface-container-lowest rounded-2xl p-3.5 shadow-sm flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-surface-container-low flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[20px]">workspace_premium</span>
                </div>
                <div class="min-w-0">
                    <p class="font-label-md text-label-md text-on-surface truncate">Portofolio Sahih</p>
                    <p class="font-body-sm text-body-sm text-on-surface-variant truncate">Karya riil antar rekan kampus</p>
                </div>
            </div>
            <div class="bg-surface-container-lowest rounded-2xl p-3.5 shadow-sm flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-surface-container-low flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[20px]">currency_exchange</span>
                </div>
                <div class="min-w-0">
                    <p class="font-label-md text-label-md text-on-surface truncate">Tukar Keterampilan</p>
                    <p class="font-body-sm text-body-sm text-on-surface-variant truncate">Dukungan opsi barter skill</p>
                </div>
            </div>
            <div class="bg-surface-container-lowest rounded-2xl p-3.5 shadow-sm flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-surface-container-low flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[20px]">bolt</span>
                </div>
                <div class="min-w-0">
                    <p class="font-label-md text-label-md text-on-surface truncate">Verifikasi Cepat</p>
                    <p class="font-body-sm text-body-sm text-on-surface-variant truncate">Validasi KTM & email kampus</p>
                </div>
            </div>
        </div>

    </div>
</main>

<footer class="w-full py-space-md px-6 text-center">
    <p class="font-body-sm text-body-sm text-on-surface-variant">© {{ date('Y') }} SKENA — Skill Exchange Network. Khusus Mahasiswa Aktif.</p>
</footer>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
