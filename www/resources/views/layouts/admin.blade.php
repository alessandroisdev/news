<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Workspace | Portal Premium</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@700;800&display=swap" rel="stylesheet">
    <!-- Assets via Vite -->
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
    <style>
        body { background-color: #f8f9fc; }
        .sidebar { min-height: 100vh; width: 280px; position: fixed; box-shadow: 2px 0 10px rgba(0,0,0,0.03); }
        .main-content { margin-left: 280px; min-height: 100vh; transition: margin-left 0.3s ease; }
        @media (max-width: 991.98px) {
            .sidebar { position: fixed; transform: translateX(-100%); z-index: 1045; transition: transform 0.3s ease-in-out; }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
        .nav-link.active { 
            background-color: var(--bs-primary); 
            color: white !important; 
            border-radius: 0.5rem; 
            font-weight: 600;
        }
        .nav-link:hover:not(.active) {
            background-color: rgba(var(--bs-primary-rgb), 0.05);
            color: var(--bs-primary) !important;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar Workspace -->
        <nav class="sidebar bg-white flex-column d-flex overflow-auto z-3">
            <div class="p-4 text-center">
                <h3 class="fw-bold text-primary mb-0" style="font-family: 'Outfit', sans-serif;"><i class="bi bi-box-fill me-2 text-warning"></i>WORK<span class="text-secondary">SPACE</span></h3>
                <div class="badge bg-dark mt-3 w-100 py-2 fs-6 fw-normal">{{ optional(auth()->user())->role ?? 'Administrator' }}</div>
            </div>
            
            <ul class="nav flex-column mb-auto py-3 px-3 gap-2">
                <li class="nav-item">
                    <a href="/admin/dashboard" class="nav-link text-secondary px-3 py-2 border-0 {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 me-2"></i> Painel Geral
                    </a>
                </li>
                
                <li class="nav-item mt-3 mb-1">
                    <small class="text-muted fw-bold text-uppercase px-3" style="font-size: 0.75rem; letter-spacing: 1px;">Gestão</small>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.news.index') }}" class="nav-link text-secondary px-3 py-2 border-0 {{ request()->is('admin/news*') ? 'active' : '' }}">
                        <i class="bi bi-journal-text me-2"></i> Minhas Notícias
                    </a>
                </li>
                @if(in_array(optional(auth()->user())->role, [\App\Enums\UserRoleEnum::ADMIN->value, \App\Enums\UserRoleEnum::MANAGER->value]))
                <li class="nav-item">
                    <a href="{{ route('admin.categories.index') }}" class="nav-link text-secondary px-3 py-2 border-0 {{ request()->is('admin/categories*') ? 'active' : '' }}">
                        <i class="bi bi-tags me-2"></i> Categorias SEO
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/admin/banners" class="nav-link text-secondary px-3 py-2 border-0 {{ request()->is('admin/banners*') ? 'active' : '' }}">
                        <i class="bi bi-megaphone me-2"></i> AdTech (Banners)
                    </a>
                </li>
                @endif
                
                @if(optional(auth()->user())->role === \App\Enums\UserRoleEnum::ADMIN->value)
                <li class="nav-item border-top mt-3 pt-3">
                    <a href="{{ route('admin.users.index') }}" class="nav-link text-secondary px-3 py-2 border-0 {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                        <i class="bi bi-people-fill me-2"></i> Usuários e Acessos
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.users.trash') }}" class="nav-link text-danger px-3 py-2 border-0 {{ request()->routeIs('admin.users.trash') ? 'bg-danger bg-opacity-10 fw-bold rounded-3' : '' }}">
                        <i class="bi bi-trash-fill me-2"></i> Lixeira Central
                    </a>
                </li>
                <li class="nav-item border-top mt-3 pt-3">
                    <a href="/admin/analytics" class="nav-link text-success px-3 py-2 border-0 {{ request()->is('admin/analytics') ? 'active bg-success bg-opacity-10 fw-bold rounded-3' : '' }}">
                        <i class="bi bi-pie-chart-fill me-2"></i> Engine Analytics
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.audits.index') }}" class="nav-link text-warning px-3 py-2 border-0 {{ request()->routeIs('admin.audits.index') ? 'active bg-warning bg-opacity-10 fw-bold rounded-3' : '' }}">
                        <i class="bi bi-eye-fill me-2"></i> Auditoria de Eventos
                    </a>
                </li>
                @endif
            </ul>
            
            <div class="p-4 mt-auto border-top text-center">
                <span class="text-muted small fw-semibold">Workspace Engine v1.0<br>Powered by Sanctum</span>
            </div>
        </nav>

        <!-- Main Content Area -->
        <div class="main-content flex-grow-1">
            <!-- Header Mobile / Top Nav -->
            <header class="bg-white px-4 py-3 border-bottom d-flex align-items-center justify-content-between sticky-top z-2">
                <div class="d-flex align-items-center">
                    <button class="btn btn-light d-lg-none me-3 shadow-sm border" id="sidebarToggle"><i class="bi bi-list fs-5"></i></button>
                    <h5 class="mb-0 fw-bold d-none d-md-block text-dark" style="font-family: 'Outfit', sans-serif;">Gestão Estratégica</h5>
                </div>
                <div>
                    <div class="dropdown">
                      <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle fw-semibold" id="dropdownUser1" data-bs-toggle="dropdown">
                        <div class="bg-light border rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-person text-secondary fs-5"></i>
                        </div>
                        <span class="d-none d-sm-inline">{{ optional(auth()->user())->name ?? 'Corpo Administrativo' }}</span>
                      </a>
                      <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                        <li><a class="dropdown-item py-2" target="_blank" href="/"><i class="bi bi-box-arrow-up-right me-2 text-primary"></i>Ver o Portal</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="/logout">
                                @csrf
                                <button class="dropdown-item text-danger py-2 fw-semibold" type="submit"><i class="bi bi-door-open-fill me-2"></i>Desconectar</button>
                            </form>
                        </li>
                      </ul>
                    </div>
                </div>
            </header>

            <main class="p-4 p-md-5">
                @if(isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </main>
        </div>
    </div>
    
    @livewireScripts
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.getElementById('sidebarToggle');
            const sidebar = document.querySelector('.sidebar');
            if(toggle && sidebar) {
                toggle.addEventListener('click', () => {
                    sidebar.classList.toggle('show');
                });
            }
        });
    </script>
    @livewire('admin.media.media-library-modal')
    @stack('scripts')
</body>
</html>
