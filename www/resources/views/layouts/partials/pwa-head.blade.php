<!-- Configuração Avançada do PWA (Progressive Web App) -->
<link rel="manifest" href="/manifest.json">
<meta name="theme-color" content="#0d6efd">
<link rel="apple-touch-icon" href="/images/pwa/icon-192.png">

<!-- Bootstrap do Service Worker (Offline Engine) -->
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js')
                .then((reg) => console.log('PWA: Motor Offline Blindado!', reg.scope))
                .catch((err) => console.log('PWA: Falha ao carregar o Service Worker.', err));
        });
    }
</script>
