<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'SKENA'))</title>

    <!-- Tailwind CDN for instant preview without npm build -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen flex flex-col font-sans antialiased">
    @include('layouts.partials.navbar')

    <main class="flex-1">
        @yield('content')
    </main>

    @include('layouts.partials.footer')
</body>
</html>
