{{--
    Layout umum (beranda dan halaman PBI) = layout lembar + utilitas Tailwind.
    Kerangka (header, drawer, footer, toast) ada di layouts/sheet.blade.php;
    token warna Tailwind disamakan dengan DESIGN.md di partials/design-tokens.
--}}
@extends('layouts.sheet')

@push('styles')
    @include('layouts.partials.design-tokens')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
@endpush
