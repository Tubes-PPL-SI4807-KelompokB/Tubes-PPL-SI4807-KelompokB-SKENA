{{-- Halaman error mandiri (tanpa layout utama agar tetap tampil walau sesi/route tidak tersedia). --}}
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — SKENA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,300,0,0&display=block">
    <style>
        *{box-sizing:border-box}
        body{margin:0;min-height:100vh;background:linear-gradient(180deg,#E2E2E2 0%,#D7EEFD 100%);font-family:Outfit,system-ui,sans-serif;color:#0F172A;display:grid;place-items:center;padding:16px}
        .card{width:100%;max-width:480px;background:#fff;border:1px solid #E2E8F0;border-radius:24px;padding:40px 28px;text-align:center;box-shadow:0 10px 30px -10px rgba(63,116,156,.10)}
        .ic{width:64px;height:64px;border-radius:999px;background:#EEF4F9;color:#3F749C;display:inline-grid;place-items:center;font-family:'Material Symbols Rounded';font-size:30px}
        .code{margin-top:16px;font-size:12px;font-weight:700;letter-spacing:.06em;color:#3F749C}
        h1{margin:8px 0 0;font-size:24px;line-height:1.25}
        p{margin:10px auto 0;max-width:340px;font-size:15px;line-height:1.5;color:#64748B}
        .act{margin-top:24px;display:flex;gap:10px;justify-content:center;flex-wrap:wrap}
        .btn{height:46px;padding:0 22px;border-radius:999px;display:inline-flex;align-items:center;font-size:14px;font-weight:700;text-decoration:none}
        .pri{background:#3F749C;color:#fff}.pri:hover{background:#346284}
        .sec{border:1px solid #E2E8F0;color:#0F172A;background:#fff}
    </style>
</head>
<body>
    <main class="card">
        <span class="ic" aria-hidden="true">@yield('icon')</span>
        <div class="code">ERROR @yield('code')</div>
        <h1>@yield('heading')</h1>
        <p>@yield('message')</p>
        <div class="act">
            <a class="btn sec" href="javascript:history.back()">Kembali</a>
            <a class="btn pri" href="{{ url('/') }}">Ke Beranda</a>
        </div>
    </main>
</body>
</html>
