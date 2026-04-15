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
      <livewire:frontend.navbar-search />
    </div>
  </div>
</nav>
