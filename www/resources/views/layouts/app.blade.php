<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-from-validation">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('meta_description', 'Portal de Notícias Profissional')">
    <title>@yield('title', 'Portal')</title>

    <!-- Preloads & Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&family=Outfit:wght@400;700;900&display=swap" rel="stylesheet">

    <!-- CSS Base & Vendor via Vite -->
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    
    <!-- Dynamic CSS Injection (CSS Vars baseadas na Categoria ou Colunista) -->
    @yield('dynamic_css_vars')
    
    @livewireStyles
    @stack('styles')
</head>
<body>
    @include('layouts.partials.navbar')

    <main class="main-content">
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    @include('layouts.partials.footer')

    @livewireScripts
    @stack('scripts')
</body>
</html>
