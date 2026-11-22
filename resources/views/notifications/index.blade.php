@extends('layouts.app')

@section('title', 'Notifikasi | SKENA')

@section('content')
<div class="container mx-auto px-4 py-6">

    <h1 class="text-2xl font-bold mb-6">Notifikasi</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @forelse($notifications as $notification)

        <div class="border rounded-lg p-4 mb-3
            {{ $notification->is_read ? 'bg-white' : 'bg-blue-50' }}">

            <h2 class="font-bold">
                {{ $notification->title }}
            </h2>

            <p class="text-gray-600 mt-2">
                {{ $notification->message }}
            </p>

            <p class="text-sm text-gray-400 mt-2">
                {{ $notification->created_at->format('d M Y H:i') }}
            </p>

            @if(!$notification->is_read)
                <form action="{{ route('notifications.update', $notification->id) }}"
                      method="POST" class="mt-3">
                    @csrf
                    @method('PATCH')

                    <button type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded">
                        Tandai Sudah Dibaca
                    </button>
                </form>
            @else
                <span class="text-green-600 text-sm">
                    Sudah dibaca
                </span>
            @endif

        </div>

    @empty

        <p class="text-gray-500">
            Belum ada notifikasi.
        </p>

    @endforelse

</div>
@endsection

