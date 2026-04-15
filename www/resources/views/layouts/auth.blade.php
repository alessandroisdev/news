<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acesso Seguro - Portal</title>
    <!-- Preloads & Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&family=Outfit:wght@400;700;900&display=swap" rel="stylesheet">
    
    <!-- CSS Base & Vendor via Vite -->
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    
    @livewireStyles
</head>
<body class="bg-light d-flex align-items-center" style="min-height: 100vh; background-image: radial-gradient(circle at center, rgba(0,86,179,0.05) 0%, rgba(248,249,250,1) 70%);">
    
    @if(isset($slot))
        {{ $slot }}
    @else
        @yield('content')
    @endif
    
    @livewireScripts
</body>
</html>
