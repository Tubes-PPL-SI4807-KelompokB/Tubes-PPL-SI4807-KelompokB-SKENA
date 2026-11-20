{{--
    SKENA design tokens untuk utilitas Tailwind — sumber: PPL DESIGN CONSEP/DESIGN.md.
    Komponen lembar (header, drawer, kartu, hero) ada di public/css/skena-sheet.css;
    partial ini menyamakan warna, font, dan radius utilitas Tailwind dengan file itu.

    Pakai:  @include('layouts.partials.design-tokens')
            @include('layouts.partials.design-tokens', ['designRadius' => true])

    designRadius = true menimpa skala rounded Tailwind dengan skala DESIGN.md
    (DEFAULT 18px, lg 24px, xl 28px). Dipakai halaman auth.
--}}
<style>
    @font-face{font-family:"Outfit";font-weight:400;font-display:swap;src:url("{{ asset('fonts/outfit-400.woff2') }}") format("woff2")}
    @font-face{font-family:"Outfit";font-weight:600;font-display:swap;src:url("{{ asset('fonts/outfit-600.woff2') }}") format("woff2")}
    @font-face{font-family:"Outfit";font-weight:700;font-display:swap;src:url("{{ asset('fonts/outfit-700.woff2') }}") format("woff2")}
</style>
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    // DESIGN.md
                    "primary": "#3F749C",
                    "primary-hover": "#346284",
                    "on-primary": "#FFFFFF",
                    "secondary": "#73A5CA",
                    "hero-start": "#7FB0D4",
                    "tint": "#EEF4F9",
                    "tint-strong": "#E0ECF6",
                    "canvas-top": "#E2E2E2",
                    "canvas-bottom": "#D7EEFD",
                    "neutral": "#F8F9FB",
                    "surface": "#F8F9FB",
                    "on-surface": "#0F172A",
                    "on-surface-variant": "#475569",
                    "on-surface-muted": "#64748B",
                    "border": "#E2E8F0",
                    "success": "#10B981",
                    "success-container": "#DCFCE7",
                    "on-success-container": "#047857",
                    "warning": "#F59E0B",
                    "warning-container": "#FEF3C7",
                    "on-warning-container": "#B45309",
                    "error": "#EF4444",
                    "error-container": "#FEE2E2",
                    "on-error-container": "#B91C1C",
                    "on-error": "#FFFFFF",

                    // Alias nama lama (Material 3) → nilai DESIGN.md, agar markup lama ikut palet baru
                    "primary-container": "#3F749C",
                    "on-primary-container": "#FFFFFF",
                    "primary-fixed": "#E0ECF6",
                    "on-primary-fixed": "#0F172A",
                    "on-primary-fixed-variant": "#346284",
                    "surface-tint": "#3F749C",
                    "secondary-container": "#E0ECF6",
                    "on-secondary-container": "#346284",
                    "secondary-fixed": "#D7EEFD",
                    "on-secondary": "#FFFFFF",
                    "background": "#F8F9FB",
                    "on-background": "#0F172A",
                    "surface-bright": "#FFFFFF",
                    "surface-dim": "#E2E8F0",
                    "surface-variant": "#E2E8F0",
                    "surface-container-lowest": "#FFFFFF",
                    "surface-container-low": "#F8F9FB",
                    "surface-container": "#EEF4F9",
                    "surface-container-high": "#E0ECF6",
                    "surface-container-highest": "#E0ECF6",
                    "inverse-surface": "#0F172A",
                    "inverse-on-surface": "#F8F9FB",
                    "outline": "#64748B",
                    "outline-variant": "#E2E8F0",
                    brand: {
                        blue:  "#3F749C",
                        red:   "#EF4444",
                        light: "#F8F9FB",
                        dark:  "#0F172A",
                    },
                },
                fontFamily: {
                    sans: ["Outfit", "ui-sans-serif", "system-ui", "sans-serif"],
                    display: ["Outfit"], "display-mobile": ["Outfit"],
                    "headline-lg": ["Outfit"], "headline-lg-mobile": ["Outfit"],
                    "headline-md": ["Outfit"], "headline-sm": ["Outfit"], title: ["Outfit"],
                    "body-lg": ["Outfit"], "body-md": ["Outfit"], "body-sm": ["Outfit"],
                    "label-lg": ["Outfit"], "label-md": ["Outfit"], "label-sm": ["Outfit"],
                },
                fontSize: {
                    display: ["54px", { lineHeight: "1.04", letterSpacing: "-0.035em", fontWeight: "700" }],
                    "display-mobile": ["38px", { lineHeight: "1.08", letterSpacing: "-0.03em", fontWeight: "700" }],
                    "headline-lg": ["32px", { lineHeight: "1.15", letterSpacing: "-0.02em", fontWeight: "700" }],
                    "headline-lg-mobile": ["26px", { lineHeight: "1.2", letterSpacing: "-0.02em", fontWeight: "700" }],
                    "headline-md": ["24px", { lineHeight: "1.25", letterSpacing: "-0.01em", fontWeight: "700" }],
                    "headline-sm": ["20px", { lineHeight: "1.3", letterSpacing: "-0.01em", fontWeight: "600" }],
                    title: ["18px", { lineHeight: "1.28", letterSpacing: "-0.01em", fontWeight: "600" }],
                    "stat-number": ["40px", { lineHeight: "1.05", letterSpacing: "-0.02em", fontWeight: "700" }],
                    "body-lg": ["17px", { lineHeight: "1.5", fontWeight: "400" }],
                    "body-md": ["15px", { lineHeight: "1.5", fontWeight: "400" }],
                    "body-sm": ["13px", { lineHeight: "1.45", fontWeight: "400" }],
                    "label-lg": ["15px", { lineHeight: "1.2", fontWeight: "600" }],
                    "label-md": ["14px", { lineHeight: "1.2", fontWeight: "600" }],
                    "label-sm": ["11px", { lineHeight: "1.2", letterSpacing: "0.06em", fontWeight: "700" }],
                },
                spacing: {
                    "space-xs": "4px", "space-sm": "8px", "space-md": "16px",
                    "space-lg": "24px", "space-xl": "32px", "space-xxl": "56px",
                    section: "56px", "card-gap": "18px",
                    gutter: "24px", "gutter-sm": "16px", margin: "32px", "margin-mobile": "16px",
                },
                boxShadow: {
                    card: "0 10px 30px -10px rgba(63,116,156,0.10)",
                    "card-hover": "0 20px 35px -8px rgba(63,116,156,0.16)",
                },
                @if(!empty($designRadius))
                borderRadius: { DEFAULT: "18px", sm: "8px", md: "18px", lg: "24px", xl: "28px", full: "9999px" },
                @endif
            },
        },
    }
</script>
