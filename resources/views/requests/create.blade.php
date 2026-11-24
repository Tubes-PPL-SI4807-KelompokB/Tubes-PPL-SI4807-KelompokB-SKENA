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
        'cancelUrl' => url()->previous() !== url()->current() ? url()->previous() : route('home'),
    ])
@endsection
