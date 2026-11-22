{{--
    Panel brand kiri halaman masuk/daftar — persis Claude Design (TIM-A ta-1, TIM-B tb-1).
    $photo, $alt, $minH (640/760), $photoH (220/300), $photoTop (44/48) opsional.
--}}
<div class="hidden lg:block relative rounded-[28px] overflow-hidden text-white"
     style="background:linear-gradient(160deg,#7FB0D4 0%,#3F749C 100%);min-height:{{ $minH ?? 640 }}px">
    <div class="absolute left-14 right-14 top-[72px]">
        <div class="flex items-center gap-3.5 text-[12px] leading-none font-bold tracking-[0.14em]">
            <span class="w-8 h-px bg-white/70"></span>SELAMAT DATANG DI SKENA
        </div>
        <h1 class="mt-6 text-[54px] leading-[1.04] tracking-[-0.035em] font-bold">Ubah Skill<br>Jadi Penghasilan</h1>
        <p class="mt-5 text-[17px] leading-normal font-semibold">Marketplace jasa kecil khusus mahasiswa</p>
        <figure class="rounded-[22px] border-[5px] border-white/90 overflow-hidden -rotate-2 bg-tint"
                style="margin-top:{{ $photoTop ?? 44 }}px;height:{{ $photoH ?? 220 }}px;box-shadow:0 18px 30px -12px rgba(15,23,42,0.35)">
            <img src="{{ asset('images/dashboard/' . ($photo ?? 'hero-tutor.jpg')) }}" alt="{{ $alt ?? 'Mahasiswa di kampus' }}"
                 class="w-full h-full object-cover" style="object-position:70% 30%">
        </figure>
    </div>
    <div class="absolute left-12 right-12 bottom-0 h-[84px] bg-surface rounded-t-[28px] flex items-center justify-center gap-2">
        <span class="absolute -left-7 bottom-0 w-7 h-7" style="background:radial-gradient(circle at 0 0,transparent 27.5px,#F8F9FB 28px)"></span>
        <span class="absolute -right-7 bottom-0 w-7 h-7" style="background:radial-gradient(circle at 100% 0,transparent 27.5px,#F8F9FB 28px)"></span>
        @foreach([['sell', 'Harga Ramah Mahasiswa'], ['verified_user', 'Komunitas Tepercaya'], ['star', 'Portofolio & Rating']] as [$ic, $label])
            <span class="h-9 px-3.5 rounded-full bg-white border border-border text-on-surface flex items-center gap-1.5 text-[13px] leading-none font-semibold whitespace-nowrap">
                <span class="msr text-primary" style="font-size:18px">{{ $ic }}</span>{{ $label }}
            </span>
        @endforeach
    </div>
</div>
