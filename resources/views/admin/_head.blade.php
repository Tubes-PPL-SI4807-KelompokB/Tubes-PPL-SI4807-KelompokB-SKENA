{{--
    Judul halaman admin — persis Claude Design (TIM-D/E/F):
    kicker, lencana "TIM", judul, "Sprint 1", deskripsi.
    Param: $kicker, $title, $desc (opsional), $pad (kelas padding, bawaan "pt-10 pb-8").
--}}
<div class="{{ $pad ?? 'pt-10 pb-8' }}">
    <div class="text-[11px] leading-[1.2] font-bold tracking-[0.06em] text-primary">{{ $kicker }}</div>
    <div class="flex flex-wrap items-center gap-x-3.5 gap-y-2 mt-2.5">
        <span class="h-[30px] px-3.5 rounded-full bg-primary text-white text-[13px] leading-[30px] font-bold">TIM</span>
        <h1 class="m-0 text-[26px] sm:text-[32px] leading-[1.15] tracking-[-0.02em] font-bold">{{ $title }}</h1>
        <span class="text-[14px] font-semibold text-on-surface-muted">Sprint 1</span>
    </div>
    @if(!empty($desc))<p class="mt-2.5 text-[15px] leading-normal text-on-surface-muted">{{ $desc }}</p>@endif
</div>
