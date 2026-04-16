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
    @include('layouts.partials.pwa-head')
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
            
            <ul class="nav flex-column mb-auto py-3 px-3 gap-2" style="font-family: 'Inter', sans-serif;">
                <li class="nav-item">
                    <a href="/admin/dashboard" class="nav-link px-3 py-2 border-0 rounded-3 {{ request()->is('admin/dashboard') ? 'active bg-primary fw-bold text-white shadow-sm' : 'text-secondary' }}">
                        <i class="bi bi-speedometer2 me-2"></i> Visão Geral
                    </a>
                </li>
                
                <!-- CONTEÚDO E REDAÇÃO -->
                <li class="nav-item mt-3 mb-1">
                    <small class="text-muted fw-bolder text-uppercase px-3" style="font-size: 0.70rem; letter-spacing: 1px;">Redação</small>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.news.index') }}" class="nav-link px-3 py-2 border-0 rounded-3 {{ request()->is('admin/news*') ? 'active bg-primary bg-opacity-10 fw-bold text-primary' : 'text-secondary' }}">
                        <i class="bi bi-journal-richtext me-2"></i> Matérias / Artigos
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.comments.moderation') }}" class="nav-link px-3 py-2 border-0 rounded-3 {{ request()->routeIs('admin.comments.moderation') ? 'active bg-primary bg-opacity-10 fw-bold text-primary' : 'text-secondary' }}">
                        <i class="bi bi-chat-square-quote-fill me-2"></i> Moderação Fórum
                    </a>
                </li>
                @if(in_array(optional(auth()->user())->role, [\App\Enums\UserRoleEnum::ADMIN->value, \App\Enums\UserRoleEnum::MANAGER->value]))
                <li class="nav-item">
                    <a href="{{ route('admin.categories.index') }}" class="nav-link px-3 py-2 border-0 rounded-3 {{ request()->is('admin/categories*') ? 'active bg-primary bg-opacity-10 fw-bold text-primary' : 'text-secondary' }}">
                        <i class="bi bi-tags-fill me-2"></i> Mapas e Categorias
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.newsletter.index') }}" class="nav-link px-3 py-2 border-0 rounded-3 {{ request()->routeIs('admin.newsletter.*') ? 'active bg-info bg-opacity-10 fw-bold text-info' : 'text-secondary' }}">
                        <i class="bi bi-envelope-paper-heart-fill me-2"></i> Edições de Mailing
                    </a>
                </li>
                @endif
                
                <!-- PERFORMANCE E ADTECH -->
                @if(in_array(optional(auth()->user())->role, [\App\Enums\UserRoleEnum::ADMIN->value, \App\Enums\UserRoleEnum::MANAGER->value]))
                <li class="nav-item mt-3 mb-1">
                    <small class="text-muted fw-bolder text-uppercase px-3" style="font-size: 0.70rem; letter-spacing: 1px;">Crescimento</small>
                </li>
                <li class="nav-item">
                    <a href="/admin/analytics" class="nav-link px-3 py-2 border-0 rounded-3 {{ request()->routeIs('admin.analytics.dashboard') ? 'active bg-success bg-opacity-10 fw-bold text-success' : 'text-secondary' }}">
                        <i class="bi bi-pie-chart-fill me-2"></i> Analytics Hype
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.analytics.content') }}" class="nav-link px-3 py-2 border-0 rounded-3 {{ request()->routeIs('admin.analytics.content') ? 'active bg-success bg-opacity-10 fw-bold text-success' : 'text-secondary' }}">
                        <i class="bi bi-graph-up-arrow me-2"></i> Auditor Content
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/admin/banners" class="nav-link px-3 py-2 border-0 rounded-3 {{ request()->is('admin/banners*') ? 'active bg-warning bg-opacity-10 fw-bold text-dark' : 'text-secondary' }}">
                        <i class="bi bi-megaphone-fill me-2"></i> Gestor AdTech
                    </a>
                </li>
                @endif
                
                <!-- GESTÃO EMPRESARIAL -->
                @if(optional(auth()->user())->role === \App\Enums\UserRoleEnum::ADMIN->value)
                <li class="nav-item border-top mt-3 pt-3 mb-1">
                    <small class="text-muted fw-bolder text-uppercase px-3" style="font-size: 0.70rem; letter-spacing: 1px;">Corporativo</small>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="nav-link px-3 py-2 border-0 rounded-3 {{ request()->routeIs('admin.users.index') ? 'active bg-primary bg-opacity-10 fw-bold text-primary' : 'text-secondary' }}">
                        <i class="bi bi-people-fill me-2"></i> Credenciais e Acessos
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.inbox.index') }}" class="nav-link px-3 py-2 border-0 rounded-3 {{ request()->routeIs('admin.inbox.index') ? 'active bg-info bg-opacity-10 fw-bold text-info' : 'text-secondary' }} d-flex justify-content-between align-items-center">
                        <div><i class="bi bi-inbox-fill me-2"></i> Inbox Assessoria</div>
                        @php $unread = \App\Models\ContactMessage::where('is_read', false)->count(); @endphp
                        @if($unread > 0)
                            <span class="badge bg-danger rounded-pill">{{ $unread }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.audits.index') }}" class="nav-link px-3 py-2 border-0 rounded-3 {{ request()->routeIs('admin.audits.index') ? 'active bg-secondary bg-opacity-10 fw-bold text-dark' : 'text-secondary' }}">
                        <i class="bi bi-fingerprint me-2 fs-5"></i> Rastros (Logs)
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.users.trash') }}" class="nav-link px-3 py-2 border-0 rounded-3 {{ request()->routeIs('admin.users.trash') ? 'active bg-danger bg-opacity-10 fw-bold text-danger' : 'text-danger fw-semibold' }}">
                        <i class="bi bi-trash3-fill me-2"></i> Lixeira Secreta
                    </a>
                </li>
                
                <!-- SISTEMA -->
                <li class="nav-item border-top mt-3 pt-3 mb-1">
                    <small class="text-muted fw-bolder text-uppercase px-3" style="font-size: 0.70rem; letter-spacing: 1px;">Sistema</small>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.settings.index') }}" class="nav-link px-3 py-2 border-0 rounded-3 {{ request()->routeIs('admin.settings.index') ? 'active bg-dark fw-bold text-white shadow-sm' : 'text-dark fw-semibold' }}">
                        <i class="bi bi-gear-wide-connected me-2"></i> Base Settings
                    </a>
                </li>
                @endif
            </ul>
            
            <div class="mt-auto">
                <a href="{{ route('admin.help.index') }}" class="d-block px-4 py-3 border-top text-decoration-none {{ request()->routeIs('admin.help.index') ? 'bg-warning text-dark fw-bold' : 'text-dark' }}" style="background-color: #f1f5f9;">
                    <i class="bi bi-info-circle-fill me-2 text-primary"></i> <span class="fw-bold">Manuais e Wiki</span>
                </a>
                <div class="p-4 text-center border-top shadow-sm" style="background-color: #fafbfc;">
                    <span class="text-muted small fw-semibold">Workspace Engine v1.0<br>Powered by Sanctum</span>
                </div>
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
