@extends('layouts.app')
@section('title', 'Ajukan Request — SKENA')

{{-- PBI-10 p10-2 – Ajukan Request Jasa --}}
@section('content')
    @include('requests._form', [
        'item'      => null,
        'action'    => route('requests.store'),
        'method'    => 'POST',
        'desc'      => 'Jelaskan kebutuhanmu. Penyedia akan menerima atau menolak request ini.',
        'submit'    => 'Kirim Request',
        'cancelUrl' => (function () {
            // Kembali ke halaman sebelumnya, kecuali halaman login/daftar/form ini sendiri.
            $prev = url()->previous();
            $skip = [url()->current(), route('login'), route('register')];

            return in_array(strtok($prev, '?'), $skip, true) || $prev === url()->full() ? route('home') : $prev;
        })(),
    ])
@endsection
