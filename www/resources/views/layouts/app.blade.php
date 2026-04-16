<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-from-validation">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'Portal de Notícias Profissional')">
    
    <!-- Meta Dynamic SEO / OpenGraph Support -->
    @yield('meta_seo')

    <title>@yield('title', config('app.name', 'Portal News'))</title>

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
    @include('layouts.partials.pwa-head')
</head>
<body>
    @include('layouts.partials.navbar')

    <main class="main-content">
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    <script>
        window.initWebPush = async function() {
            if (!('serviceWorker' in navigator) || !('PushManager' in window)) return;
            try {
                const reg = await navigator.serviceWorker.ready;
                const permission = await Notification.requestPermission();
                if (permission !== 'granted') return;

                const vapidPublicKey = '{{ config("webpush.vapid.public_key") }}';
                
                // Conversão de VAPID (Base64 URL) para Uint8Array Exigido pelo Chrome
                const padding = '='.repeat((4 - vapidPublicKey.length % 4) % 4);
                const base64 = (vapidPublicKey + padding).replace(/\-/g, '+').replace(/_/g, '/');
                const rawData = window.atob(base64);
                const outputArray = new Uint8Array(rawData.length);
                for (let i = 0; i < rawData.length; ++i) {
                    outputArray[i] = rawData.charCodeAt(i);
                }

                const sub = await reg.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: outputArray
                });
                
                fetch('{{ route("webpush.subscribe") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify(sub)
                }).then(() => console.log('✅ Push Web Shield Ativado!'));
            } catch (err) {
                console.error('Falha no WebPush:', err);
            }
        };
    </script>

    @include('layouts.partials.footer')

    @livewireScripts
    @stack('scripts')
</body>
</html>
