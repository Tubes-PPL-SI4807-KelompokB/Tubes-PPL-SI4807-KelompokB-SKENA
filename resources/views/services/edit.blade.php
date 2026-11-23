@extends('layouts.app')
@section('title', 'Ubah Jasa — SKENA')

{{-- PBI-04 p04-2: ubah jasa --}}
@section('content')
    @include('services._form', [
        'action' => route('services.update', $service),
        'method' => 'PUT',
        'kicker' => 'UBAH JASA',
        'desc'   => 'Perbarui detail jasa. Pratinjau di kanan menunjukkan tampilan kartu bagi pencari jasa.',
    ])
@endsection
