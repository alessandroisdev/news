<div class="col-lg-3 mb-5 mb-lg-0">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden position-sticky" style="top: 100px;">
        <div class="card-body p-0">
            <div class="bg-primary bg-gradient p-4 text-white text-center">
                <div class="rounded-circle d-inline-flex border border-white border-2 align-items-center justify-content-center fw-bolder shadow-sm mb-2" style="width: 60px; height: 60px; font-size: 1.5rem;">
                    {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                </div>
                <h5 class="fw-bold mb-0 lh-sm">{{ auth()->user()->name }}</h5>
                <span class="badge bg-white bg-opacity-25 mt-2 fw-semibold px-3"><i class="bi bi-star-fill text-warning me-1"></i> Membro VIP</span>
            </div>
            
            <div class="nav flex-column nav-pills py-3" role="tablist" aria-orientation="vertical">
                <a href="{{ route('subscriber.dashboard') }}" wire:navigate class="nav-link px-4 py-3 border-bottom {{ request()->routeIs('subscriber.dashboard') ? 'active rounded-0' : 'text-dark' }} fw-semibold rounded-0 d-flex align-items-center" style="transition: all 0.2s;">
                    <i class="bi bi-rocket-takeoff me-3 fs-5 {{ request()->routeIs('subscriber.dashboard') ? 'text-white' : 'text-primary' }}"></i>
                    Visão Geral
                </a>
                
                <a href="{{ route('subscriber.profile') }}" wire:navigate class="nav-link px-4 py-3 border-bottom {{ request()->routeIs('subscriber.profile') ? 'active rounded-0' : 'text-dark' }} fw-semibold rounded-0 d-flex align-items-center" style="transition: all 0.2s;">
                    <i class="bi bi-person-badge me-3 fs-5 {{ request()->routeIs('subscriber.profile') ? 'text-white' : 'text-primary' }}"></i>
                    Minha Identidade
                </a>
                
                <a href="{{ route('subscriber.payments') }}" wire:navigate class="nav-link px-4 py-3 border-bottom {{ request()->routeIs('subscriber.payments') ? 'active rounded-0' : 'text-dark' }} fw-semibold rounded-0 d-flex align-items-center" style="transition: all 0.2s;">
                    <i class="bi bi-credit-card-2-front me-3 fs-5 {{ request()->routeIs('subscriber.payments') ? 'text-white' : 'text-primary' }}"></i>
                    Histórico de Faturas
                </a>
                
                <a href="{{ route('subscriber.settings') }}" wire:navigate class="nav-link px-4 py-3 {{ request()->routeIs('subscriber.settings') ? 'active rounded-0' : 'text-dark' }} fw-semibold rounded-0 d-flex align-items-center" style="transition: all 0.2s;">
                    <i class="bi bi-gear-fill me-3 fs-5 {{ request()->routeIs('subscriber.settings') ? 'text-white' : 'text-primary' }}"></i>
                    Configurações
                </a>
            </div>
        </div>
    </div>
</div>
