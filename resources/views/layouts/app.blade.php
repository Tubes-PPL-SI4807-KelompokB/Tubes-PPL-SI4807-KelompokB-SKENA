<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SKENA — Marketplace Jasa Kreatif Mahasiswa')</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              brand: {
                blue:  '#2C99D6',
                red:   '#EB2E33',
                light: '#F8FAFC',
                dark:  '#0F2942',
              },
            },
            fontFamily: {
              sans: ['"Plus Jakarta Sans"', 'Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
          },
        },
      }
    </script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-brand-light text-gray-900 min-h-screen flex flex-col font-sans antialiased">

    @include('layouts.partials.navbar')

    {{-- Flash Messages --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="fixed top-4 right-4 z-50 max-w-sm bg-white border-l-4 border-brand-blue shadow-lg rounded-lg px-4 py-3 flex items-start gap-3">
        <svg class="w-5 h-5 text-brand-blue mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <p class="text-sm text-gray-700">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="fixed top-4 right-4 z-50 max-w-sm bg-white border-l-4 border-brand-red shadow-lg rounded-lg px-4 py-3 flex items-start gap-3">
        <svg class="w-5 h-5 text-brand-red mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <p class="text-sm text-gray-700">{{ session('error') }}</p>
    </div>
    @endif

    <main class="flex-1">
        @yield('content')
    </main>

    @include('layouts.partials.footer')

    {{-- Alpine.js for small interactions --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')
</body>
</html>
