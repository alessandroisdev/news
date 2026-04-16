<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modo Offline | Portal</title>
    <!-- Busca da memoria interna armazenada pelo Service Worker -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; min-height: 100vh; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative;}
        .bg-pattern { position: absolute; width: 100%; height: 100%; background-image: radial-gradient(#ced4da 1px, transparent 1px); background-size: 20px 20px; opacity: 0.4; z-index: 1;}
        .content-box { position: relative; z-index: 10; background: white; padding: 3rem; border-radius: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.05); text-align: center; max-width: 500px; width: 90%; border-top: 5px solid #0d6efd; }
        .icon { font-size: 5rem; color: #6c757d; margin-bottom: 1rem; animation: pulse 2s infinite; }
        @keyframes pulse {
            0% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.95); }
            100% { opacity: 1; transform: scale(1); }
        }
        h1 { font-family: 'Outfit', sans-serif; font-weight: 800; color: #212529; }
        .refresh-btn { margin-top: 2rem; background-color: #0d6efd; color: white; border: none; padding: 12px 30px; font-weight: 600; border-radius: 50px; text-decoration: none; display: inline-flex; align-items: center; transition: all 0.2s;}
        .refresh-btn:hover { background-color: #0b5ed7; transform: translateY(-2px); color: white;}
    </style>
</head>
<body>
    <div class="bg-pattern"></div>
    <div class="content-box">
        <i class="bi bi-wifi-off icon text-secondary"></i>
        <h1 class="mb-3">Modo Sem Conexão</h1>
        <p class="text-muted mb-4 fs-5">Você adentrou uma "zona cega" de GPS (Sem rede 4G ou Wi-Fi conectável).</p>
        
        <p class="text-muted small mb-0">No entanto, este app guardou algumas pautas antigas em seu Cachê de segurança na memória do seu aparelho.</p>
        
        <a href="/" class="refresh-btn">
            <i class="bi bi-arrow-clockwise me-2 fs-5"></i> Tentar Reconectar Servidor
        </a>
    </div>
    
    <script>
        // Escuta ativamente a volta da placa de rede.
        window.addEventListener('online', () => {
            window.location.reload();
        });
    </script>
</body>
</html>
