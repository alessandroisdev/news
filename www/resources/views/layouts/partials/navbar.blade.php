<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm py-3 sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bolder fs-4" style="font-family: 'Outfit', sans-serif;" href="/">
      <i class="bi bi-globe-americas text-warning me-2"></i>PORTAL
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0 fw-semibold text-uppercase" style="font-family: 'Inter', sans-serif; font-size: 0.85rem; letter-spacing: 0.5px;">
        <li class="nav-item"><a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">Início</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->is('ultimas') ? 'active' : '' }}" href="/ultimas">Últimas</a></li>
        <li class="nav-item"><a class="nav-link text-warning {{ request()->is('colunistas') ? 'fw-bold' : '' }}" href="/colunistas">Colunistas</a></li>
      </ul>
      <div class="d-flex align-items-center gap-3">
        <livewire:frontend.navbar-search />
        <div class="d-flex align-items-center ms-lg-2 mt-3 mt-lg-0">
          @auth
            <div class="dropdown">
              <a href="#" class="btn btn-outline-light btn-sm fw-bold dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle me-2 fs-6"></i> {{ explode(' ', auth()->user()->name)[0] }}
              </a>
              <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                @if(in_array(auth()->user()->role, [\App\Enums\UserRoleEnum::ADMIN->value, \App\Enums\UserRoleEnum::MANAGER->value, \App\Enums\UserRoleEnum::COLUMNIST->value]))
                  <li><a class="dropdown-item fw-semibold" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2 text-primary"></i> Painel Admin</a></li>
                @else
                  <li><a class="dropdown-item fw-semibold" href="{{ route('subscriber.dashboard') }}"><i class="bi bi-star me-2 text-warning"></i> Meu Painel</a></li>
                @endif
                <li><hr class="dropdown-divider"></li>
                <li>
                  <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item fw-bold text-danger"><i class="bi bi-box-arrow-right me-2"></i> Sair</button>
                  </form>
                </li>
              </ul>
            </div>
          @else
            <a href="{{ route('login') }}" class="btn btn-warning btn-sm fw-bold text-dark px-3 shadow-sm rounded-pill"><i class="bi bi-person me-1"></i> Acessar / Assinar</a>
          @endauth
        </div>
      </div>
    </div>
  </div>
</nav>
