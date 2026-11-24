@extends('layouts.app')
@section('title', 'Ubah Request — SKENA')

{{-- PBI-10 p10-2 – Ubah request yang masih menunggu --}}
@section('content')
    @include('requests._form', [
        'action'    => route('requests.update', $item),
        'method'    => 'PUT',
        'desc'      => 'Request masih menunggu jawaban penyedia, jadi kamu masih bisa mengubahnya.',
        'submit'    => 'Simpan Perubahan',
        'cancelUrl' => route('requests.index'),
    ])
@endsection
