@extends('layouts.app')
@section('title', 'Tambah Jasa — SKENA')

{{-- PBI-04 p04-2: tambah jasa --}}
@section('content')
    @include('services._form', [
        'action' => route('services.store'),
        'method' => 'POST',
        'kicker' => 'TAMBAH JASA',
        'desc'   => 'Isi detail jasa. Pratinjau di kanan menunjukkan tampilan kartu bagi pencari jasa.',
    ])
@endsection
