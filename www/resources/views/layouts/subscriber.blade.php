<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Espaço Premium - Seu Portal Exclusivo</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@700;800&display=swap" rel="stylesheet">
    <!-- Assets via Vite -->
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    @livewireStyles
    <style>
        body { background-color: #f4f6fa; }
        .premium-nav { 
            background: linear-gradient(135deg, #111827 0%, #1f2937 100%); 
            border-bottom: 1px solid rgba(255,255,255,0.05); 
        }
        .hover-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .hover-lift:hover { transform: translateY(-5px); box-shadow: 0 1rem 3rem rgba(0,0,0,0.1) !important; }
    </style>
</head>
<body>
    <!-- Premium Global Head -->
    <nav class="navbar navbar-dark premium-nav py-3 px-3 shadow-lg z-3">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="/" class="navbar-brand fw-bold mb-0 d-flex align-items-center gap-2" style="font-family: 'Outfit', sans-serif; letter-spacing: -0.5px;">
                <div class="bg-warning text-dark p-2 rounded-3 d-flex"><i class="bi bi-gem"></i></div>
                <span>PORTAL <span class="fw-normal opacity-50 ms-1">| Premium Mode</span></span>
            </a>
            
            <div class="dropdown">
                <a href="#" class="text-white text-decoration-none dropdown-toggle d-flex align-items-center fw-semibold mt-1" data-bs-toggle="dropdown">
                    <!-- Dinâmica de avatar visual nativo -->
                    <div class="bg-primary text-white d-flex align-items-center justify-content-center fw-bolder rounded-circle me-2" style="width: 38px; height: 38px;">
                        {{ substr(optional(auth()->user())->name ?? 'A', 0, 1) }}
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-3 p-2 rounded-3">
                    <li class="px-3 py-2">
                        <span class="d-block text-dark fw-bold">{{ optional(auth()->user())->name }}</span>
                        <span class="d-block text-muted small">{{ optional(auth()->user())->email }}</span>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item py-2 fw-semibold text-secondary rounded" href="/"><i class="bi bi-box-arrow-up-right me-2 text-primary"></i> Voltar à Home normal</a></li>
                    <li>
                        <form method="POST" action="/logout">
                            @csrf
                            <button class="dropdown-item text-danger py-2 fw-bold rounded mt-1" type="submit"><i class="bi bi-door-open-fill me-2"></i> Deslogar com Segurança</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <main class="min-vh-100">
        @if(isset($slot))
            {{ $slot }}
        @else
            @yield('content')
        @endif
    </main>
    
    <footer class="text-center py-5 mt-5 bg-white border-top">
        <div class="container">
            <p class="text-muted small fw-semibold mb-1">© {{ date('Y') }} Portal Engineering. Todos os direitos blindados.</p>
            <p class="text-muted opacity-50" style="font-size: 0.75rem;">Protegido pelas Abilities em Laravel Sanctum e Criptografia em Trânsito.</p>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
