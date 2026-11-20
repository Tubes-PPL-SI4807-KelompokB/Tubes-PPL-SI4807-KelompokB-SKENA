<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Masuk — SKENA</title>
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
    <div class="flex flex-col w-full">
        <div class="w-full max-w-6xl mx-auto my-auto">
            {{-- Main Split Auth Card --}}
            <div class="w-full bg-neutral rounded-xl border border-border shadow-card overflow-hidden grid grid-cols-1 lg:grid-cols-12 p-3 lg:p-4 gap-6 relative">

                {{-- Left Column: Gradient Brand Panel --}}
                <div class="lg:col-span-5 relative overflow-hidden rounded-[24px] bg-gradient-to-br from-hero-start to-primary p-8 lg:p-10 flex flex-col justify-between text-on-primary min-h-[520px] shadow-sm">
                    {{-- Ambient decorative --}}
                    <svg class="absolute bottom-0 left-0 right-0 w-full opacity-15 pointer-events-none" preserveAspectRatio="none" viewBox="0 0 500 150">
                        <path d="M0,40 C150,90 300,10 500,60 L500,150 L0,150 Z" fill="#FFFFFF"></path>
                    </svg>

                    {{-- Top badge --}}
                    <div class="relative z-10 flex flex-col gap-6">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/20 font-label-md text-label-md tracking-wide text-white">
                                <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                                PORTAL MAHASISWA
                            </span>
                            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                                <span class="material-symbols-outlined text-white text-[20px]">school</span>
                            </div>
                        </div>
                        <div class="flex flex-col gap-3 mt-4">
                            <h1 class="font-display text-display-mobile text-white tracking-tight leading-[1.1]">
                                Kembali ke<br/>
                                <span class="text-white/80">Kampus Digitalmu</span>
                            </h1>
                            <p class="font-body-md text-body-md text-white/90 leading-relaxed max-w-sm mt-1">
                                Akses ratusan jasa mikro mahasiswa: Desain PPT, Olah Data Excel, Foto Produk, hingga Tutor Koding.
                            </p>
                        </div>
                    </div>

                    {{-- Stats card --}}
                    <div class="relative z-10 my-6 bg-white/15 rounded-2xl p-4 flex flex-col gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-white/30 flex items-center justify-center text-white overflow-hidden shadow-inner">
                                <span class="material-symbols-outlined text-[22px]">verified</span>
                            </div>
                            <div class="flex flex-col min-w-0">
                                <span class="font-label-lg text-label-lg text-white truncate">Ekosistem Peer-to-Peer</span>
                                <span class="font-body-sm text-body-sm text-white/80">Khusus Civitas Akademika Aktif</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between pt-2">
                            <div class="flex items-center gap-2">
                                <span class="font-headline-sm text-headline-sm text-white font-bold tracking-tight">4.200+</span>
                                <span class="font-body-sm text-body-sm text-white/80">Proyek Selesai</span>
                            </div>
                            <span class="px-2.5 py-1 rounded-full bg-white/25 text-white font-label-sm text-label-sm">Telkom • ITB • UI</span>
                        </div>
                    </div>

                    {{-- Feature pills --}}
                    <div class="relative z-10 flex flex-wrap gap-2 pt-2">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/20 font-label-md text-label-md text-white">
                            <span class="material-symbols-outlined text-[16px] text-white">verified_user</span>
                            100% Mahasiswa Asli
                        </div>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/20 font-label-md text-label-md text-white">
                            <span class="material-symbols-outlined text-[16px] text-white">lock</span>
                            Escrow & Garansi Uang
                        </div>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/20 font-label-md text-label-md text-white">
                            <span class="material-symbols-outlined text-[16px] text-white">star</span>
                            Rating Jujur
                        </div>
                    </div>
                </div>

                {{-- Right Column: Login Form --}}
                <div class="lg:col-span-7 flex flex-col justify-center px-4 py-6 sm:px-8 sm:py-8 lg:px-12 lg:py-10">
                    {{-- Header --}}
                    <div class="flex flex-col gap-2 mb-6">
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 rounded-full bg-surface-container-high text-primary font-label-sm text-label-sm tracking-wide uppercase">
                                MASUK AKUN
                            </span>
                        </div>
                        <h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight">
                            Masuk ke SKENA
                        </h2>
                        <p class="font-body-md text-body-md text-on-surface-variant">
                            Gunakan email kampus terverifikasimu untuk memulai transaksi aman.
                        </p>
                    </div>

                    {{-- Error / Success messages --}}
                    @if ($errors->any() || session('error'))
                        <div class="w-full mb-6 p-4 rounded-2xl bg-error-container text-on-error-container flex items-start gap-3" id="error-banner">
                            <span class="material-symbols-outlined text-error text-[20px] mt-0.5 shrink-0" style="font-variation-settings: 'FILL' 1;">error</span>
                            <div class="flex-1 flex flex-col">
                                <p class="font-label-lg text-label-lg font-bold text-on-error-container">Gagal Masuk</p>
                                @if(session('error'))
                                    <p class="font-body-sm text-body-sm text-on-error-container/90">{{ session('error') }}</p>
                                @else
                                    @foreach($errors->all() as $error)
                                        <p class="font-body-sm text-body-sm text-on-error-container/90">{{ $error }}</p>
                                    @endforeach
                                @endif
                            </div>
                            <button class="text-on-error-container/70 hover:text-on-error-container transition-colors" onclick="document.getElementById('error-banner').classList.add('hidden')" type="button">
                                <span class="material-symbols-outlined text-[18px]">close</span>
                            </button>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="w-full mb-6 p-4 rounded-2xl bg-green-50 text-green-800 flex items-start gap-3">
                            <span class="material-symbols-outlined text-green-600 text-[20px] mt-0.5 shrink-0" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                            <p class="font-body-sm text-body-sm">{{ session('success') }}</p>
                        </div>
                    @endif

                    {{-- Demo quick-login buttons --}}
                    <div class="mb-5 p-3.5 rounded-2xl bg-surface-container-low border border-surface-container-high">
                        <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">🧪 Demo Akun Cepat</p>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" onclick="fillDemo('admin@skena.id','password123')"
                                class="px-3 py-1.5 rounded-full bg-white border border-surface-container-highest text-on-surface font-label-sm text-label-sm hover:bg-surface-container transition-colors">
                                Admin
                            </button>
                            <button type="button" onclick="fillDemo('earliyanza@student.telkomuniversity.ac.id','password123')"
                                class="px-3 py-1.5 rounded-full bg-white border border-surface-container-highest text-on-surface font-label-sm text-label-sm hover:bg-surface-container transition-colors">
                                Freelancer ✓
                            </button>
                            <button type="button" onclick="fillDemo('anindya@student.telkomuniversity.ac.id','password123')"
                                class="px-3 py-1.5 rounded-full bg-white border border-surface-container-highest text-on-surface font-label-sm text-label-sm hover:bg-surface-container transition-colors">
                                Mahasiswa (Pending)
                            </button>
                            <button type="button" onclick="fillDemo('rizal@kreatifstudio.com','password123')"
                                class="px-3 py-1.5 rounded-full bg-white border border-surface-container-highest text-on-surface font-label-sm text-label-sm hover:bg-surface-container transition-colors">
                                Client
                            </button>
                        </div>
                    </div>

                    {{-- Divider --}}
                    <div class="flex items-center gap-4 mb-5">
                        <div class="flex-1 h-px bg-surface-container-highest"></div>
                        <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider">atau masuk manual</span>
                        <div class="flex-1 h-px bg-surface-container-highest"></div>
                    </div>

                    {{-- Login Form --}}
                    <form class="flex flex-col gap-5" method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- Email --}}
                        <div class="flex flex-col gap-1.5">
                            <label class="font-label-md text-label-md text-on-surface flex items-center justify-between">
                                <span>Email Kampus Resmi</span>
                                <span class="text-on-surface-variant text-[11px] font-normal font-body-sm">Format: .ac.id atau email terdaftar</span>
                            </label>
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined absolute left-4 text-outline text-[20px] pointer-events-none">alternate_email</span>
                                <input class="w-full h-12 pl-11 pr-5 rounded-full bg-surface-container-lowest text-on-surface font-body-md text-body-md placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-primary shadow-sm transition-all @error('email') ring-2 ring-error @enderror"
                                    id="email"
                                    name="email"
                                    placeholder="nama@student.telkomuniversity.ac.id"
                                    type="email"
                                    value="{{ old('email') }}"
                                    autocomplete="email"
                                    required/>
                            </div>
                            @error('email')
                                <p class="font-body-sm text-body-sm text-error ml-4">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="flex flex-col gap-1.5">
                            <label class="font-label-md text-label-md text-on-surface" for="password-input">
                                Kata Sandi
                            </label>
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined absolute left-4 text-outline text-[20px] pointer-events-none">lock</span>
                                <input class="w-full h-12 pl-11 pr-12 rounded-full bg-surface-container-lowest text-on-surface font-body-md text-body-md placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-primary shadow-sm transition-all @error('password') ring-2 ring-error @enderror"
                                    id="password-input"
                                    name="password"
                                    placeholder="Masukkan kata sandi"
                                    type="password"
                                    autocomplete="current-password"
                                    required/>
                                <button class="absolute right-4 w-8 h-8 flex items-center justify-center text-outline hover:text-on-surface transition-colors" id="toggle-password" title="Tampilkan / Sembunyikan Kata Sandi" type="button">
                                    <span class="material-symbols-outlined text-[20px]" id="eye-icon">visibility</span>
                                </button>
                            </div>
                            @error('password')
                                <p class="font-body-sm text-body-sm text-error ml-4">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Remember me + forgot password --}}
                        <div class="flex items-center justify-between py-1">
                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                <input class="w-4 h-4 rounded text-primary focus:ring-primary focus:ring-offset-0 bg-surface-container cursor-pointer accent-primary" name="remember" type="checkbox"/>
                                <span class="font-body-md text-body-md text-on-surface-variant">Ingat saya</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a class="font-label-md text-label-md text-primary-container hover:text-primary transition-colors underline decoration-transparent hover:decoration-current" href="{{ route('password.request') }}">
                                    Lupa kata sandi?
                                </a>
                            @endif
                        </div>

                        {{-- Submit --}}
                        <button class="w-full h-12 px-7 rounded-full bg-primary hover:bg-primary-hover active:scale-[0.99] text-on-primary font-label-lg text-label-lg flex items-center justify-center gap-2.5 shadow-md hover:shadow-lg transition-all duration-200" type="submit">
                            <span>Masuk ke Akun</span>
                            <span class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[16px] text-white">arrow_forward</span>
                            </span>
                        </button>
                    </form>

                    {{-- Register link --}}
                    <div class="mt-8 pt-6 border-t border-surface-container-highest flex items-center justify-center gap-1.5">
                        <span class="font-body-md text-body-md text-on-surface-variant">Belum punya akun mahasiswa?</span>
                        <a class="font-label-lg text-label-lg text-primary-container hover:text-primary transition-colors font-bold flex items-center gap-1" href="{{ route('register') }}">
                            <span>Daftar Sekarang</span>
                            <span class="material-symbols-outlined text-[16px]">north_east</span>
                        </a>
                    </div>

                    {{-- Domain notice --}}
                    <div class="mt-6 flex items-center justify-center gap-2 text-on-surface-variant/70 font-body-sm text-body-sm">
                        <span class="material-symbols-outlined text-[16px] text-primary">verified</span>
                        <span>Hanya domain @student atau @*.ac.id yang dapat mengakses portal mahasiswa</span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>

<footer class="w-full py-space-md px-6 text-center">
    <p class="font-body-sm text-body-sm text-on-surface-variant">© {{ date('Y') }} SKENA — Skill Exchange Network. Khusus Mahasiswa Aktif.</p>
</footer>

<script>
    // Toggle password visibility
    (function() {
        const toggleBtn = document.getElementById('toggle-password');
        const pwdInput = document.getElementById('password-input');
        const eyeIcon = document.getElementById('eye-icon');
        if (toggleBtn && pwdInput && eyeIcon) {
            toggleBtn.addEventListener('click', function() {
                const isPassword = pwdInput.getAttribute('type') === 'password';
                pwdInput.setAttribute('type', isPassword ? 'text' : 'password');
                eyeIcon.textContent = isPassword ? 'visibility_off' : 'visibility';
            });
        }
    })();

    // Fill demo credentials
    function fillDemo(email, password) {
        document.getElementById('email').value = email;
        document.getElementById('password-input').value = password;
    }
</script>
</body>
</html>
