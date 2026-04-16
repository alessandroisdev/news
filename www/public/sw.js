const CACHE_NAME = 'portal-pwa-v1';

// Recursos de vida-e-morte a serem baixados logo na primeira vez que o cara instala
const INITIAL_ASSETS_TO_CACHE = [
    '/offline',
    '/?source=pwa',
    'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@600;700;800;900&display=swap',
    'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css'
];

// Evento: Instalação Inicial
self.addEventListener('install', (event) => {
    // Força o novo worker a assumir as rédeas imediatamente ao invés de esperar o fechamento da aba
    self.skipWaiting();
    
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('[PWA Service Worker] Guardando Core Assets...');
                return cache.addAll(INITIAL_ASSETS_TO_CACHE);
            })
    );
});

// Evento: Ativação (Limpeza de resíduos mortos)
self.addEventListener('activate', (event) => {
    event.waitUntil(
        // Pega controle de imediato
        clients.claim()
        .then(() => {
            return caches.keys().then(cacheNames => {
                return Promise.all(
                    cacheNames.filter(cache => cache !== CACHE_NAME)
                              .map(cache => caches.delete(cache))
                );
            });
        })
    );
});

// Evento: Fogo! O Cara tenta navegar. Network First, se cair vai para o Cache/Offline.
self.addEventListener('fetch', (event) => {
    // Escapa rotas Livewire assíncronas mutáveis que não devem ser Cacheadas puramente (apenas Offline Fallback em GET).
    if (event.request.method !== 'GET' || event.request.url.includes('/livewire/')) {
        return;
    }

    event.respondWith(
        fetch(event.request)
            .then((networkResponse) => {
                // Deu certo baixar da net. Clonar a resposta pra guardar pro Offline!
                if(networkResponse && networkResponse.status === 200 && networkResponse.type === 'basic') {
                    const responseClone = networkResponse.clone();
                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(event.request, responseClone);
                    });
                }
                return networkResponse;
            })
            .catch(() => {
                // FALHOU. INTERNET CAIU.
                // Tenta puxar o cache antigo da Matéria/Página que ele está.
                return caches.match(event.request)
                    .then((cachedResponse) => {
                        if (cachedResponse) {
                            return cachedResponse; // Rende a página do Metrô!
                        }
                        // Se não tem cache da página, joga a View genérica de Quarentena.
                        if (event.request.mode === 'navigate') {
                            return caches.match('/offline');
                        }
                    });
            })
    );
});

// Evento: Escuta Silenciosa de Breaking News (Web Push Nativo)
self.addEventListener('push', function(e) {
    if (!(self.Notification && self.Notification.permission === 'granted')) {
        return;
    }

    if (e.data) {
        let payload = e.data.json();
        
        const options = {
            body: payload.body || 'As manchetes esquentaram! Você tem uma novidade.',
            icon: '/images/pwa/icon-192x192.png',
            vibrate: [200, 100, 200, 100, 200, 100, 200],
            data: {
                dateOfArrival: Date.now(),
                url: payload.url || '/'
            },
            actions: [
                {action: 'explore', title: 'Ler Matéria Completa'}
            ]
        };

        // Custom image do PWA Push se existir
        if (payload.image) {
            options.image = payload.image;
        }

        e.waitUntil(
            self.registration.showNotification(payload.title || 'Breaking News', options)
        );
    }
});

// Evento: O clique furtivo na placa do Web Push
self.addEventListener('notificationclick', function(e) {
    let notification = e.notification;
    let url = notification.data.url;
    let action = e.action;

    if (action === 'close') {
        notification.close();
    } else {
        clients.openWindow(url);
        notification.close();
    }
});
