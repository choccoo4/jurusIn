<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'JurusIn — Temukan Jurusan yang Cocok')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('head')
</head>
<body style="background:#f5f4ff; color:#1e1b4b; margin:0;">

    <x-navbar />

    <main>
        @yield('content')
    </main>

    <x-footer />

    @stack('scripts')
</body>
</html>
