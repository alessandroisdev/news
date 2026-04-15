<div class="container py-5">
    <!-- Bloco Welcome Ouro -->
    <div class="row mb-5 align-items-center bg-white p-4 p-md-5 rounded-4 shadow-sm border position-relative overflow-hidden z-1">
        <!-- SVG Decorativo Subliminar -->
        <div class="position-absolute end-0 top-0 text-light opacity-50 pe-none z-n1" style="transform: translate(20%, -30%);">
            <i class="bi bi-award-fill" style="font-size: 30rem;"></i>
        </div>

        <div class="col-lg-9">
            <div class="d-inline-flex align-items-center bg-warning bg-opacity-25 text-dark mb-3 px-3 py-2 rounded-pill fw-bolder border border-warning shadow-sm" style="letter-spacing: 1.5px; font-size: 0.8rem;">
                <i class="bi bi-star-fill text-warning me-2"></i> ASSINATURA ATIVA
            </div>
            <h1 class="fw-bolder display-5 text-dark mb-3 lh-sm" style="font-family: 'Outfit', sans-serif;">
                Bem-vindo à leitura profunda,<br><span class="text-primary">{{ explode(' ', $user->name)[0] }}</span>!
            </h1>
            <p class="lead text-muted mb-0 fw-normal col-md-10">Esta é sua área privativa. Acesse análises aprofundadas, matérias sem propaganda nenhuma injetada no backend e conteúdo exclusivo da nossa melhor redação sem limites.</p>
        </div>
    </div>

    <!-- Timeline da Curadoria -->
    <div class="d-flex align-items-center mb-4">
        <h4 class="fw-bold mb-0 text-dark" style="font-family: 'Outfit', sans-serif;"><i class="bi bi-gem text-primary me-2"></i>Curadoria Especial (Semana)</h4>
        <hr class="flex-grow-1 ms-3 border-secondary opacity-25">
    </div>
    
    <div class="row g-4 mb-5">
        @forelse($premiumNews as $news)
            <div class="col-md-6 col-xl-4">
                <div class="card h-100 border-0 bg-white rounded-4 overflow-hidden text-decoration-none hover-lift position-relative">
                    <a href="/{{ $news->slug }}" class="text-decoration-none d-block h-100 d-flex flex-column text-dark">
                        <!-- Imagem recriada perfeitamente na URL do Glide sem comprometer espaço -->
                        <div style="height: 220px; background: url('/images/{{ $news->slug }}/500/300') center/cover no-repeat;" class="bg-light w-100 border-bottom"></div>
                        
                        <!-- VIP Badge Layer -->
                        <div class="position-absolute top-0 end-0 p-3 z-2">
                            <span class="badge bg-dark bg-opacity-75 text-white shadow backdrop-blur px-3 py-2 rounded-pill border border-light border-opacity-25"><i class="bi bi-lock-fill text-warning me-1"></i> Exclusivo</span>
                        </div>

                        <div class="card-body p-4 p-xl-5 d-flex flex-column flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <small class="text-primary fw-bolder text-uppercase" style="font-size: 0.75rem; letter-spacing: 1.5px;">
                                    <span class="d-inline-block rounded-circle me-1" style="width: 8px; height: 8px; background-color: var(--portal-primary);"></span>
                                    {{ $news->category->name }}
                                </small>
                            </div>
                            
                            <h5 class="fw-bolder lh-sm mb-3 font-outfit" style="font-size: 1.25rem;">{{ $news->title }}</h5>
                            
                            <div class="mt-auto d-flex align-items-center gap-3 pt-3 border-top border-light">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bolder" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                    {{ substr($news->author->name, 0, 1) }}
                                </div>
                                <div class="lh-sm">
                                    <div class="small fw-semibold text-dark">{{ $news->author->name }}</div>
                                    <div class="text-muted" style="font-size: 0.7rem;">{{ $news->published_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-12 py-5 text-center text-muted">
                <div class="d-inline-flex bg-white rounded-circle shadow p-4 mb-3">
                    <i class="bi bi-inbox fs-1 text-primary opacity-50"></i>
                </div>
                <h5 class="fw-bold">Nenhum conteúdo premium liberado ainda</h5>
                <p class="small opacity-75">Nossa redação enviará novidades grandiosas em breve na base.</p>
            </div>
        @endforelse
    </div>
</div>
