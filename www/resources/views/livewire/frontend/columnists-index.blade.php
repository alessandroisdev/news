<div class="container py-5">
    
    <div class="text-center mb-5">
        <span class="badge bg-warning text-dark mb-2 border border-warning shadow-sm"><i class="bi bi-star-fill me-1"></i> OPINIÃO ESPECIALIZADA</span>
        <h1 class="display-5 fw-bolder text-dark mb-3" style="font-family: 'Outfit', sans-serif;">Nossos Colunistas</h1>
        <p class="lead text-muted col-md-8 mx-auto">Conheça o time de especialistas que trazem as melhores análises e os pontos de vista mais contundentes do país nas mais diversas áreas de cobertura.</p>
    </div>

    <div class="row g-4">
        @forelse($columnists as $columnist)
            <div class="col-md-6 col-lg-4 col-xl-3">
                <a href="/{{ $columnist->slug }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm rounded-4 text-center p-4 hover-shadow-lg transition-all" style="background-color: #fff; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-5px)';" onmouseout="this.style.transform='translateY(0)';">
                        <!-- Avatar Simulado com Cor de Fundo do Próprio Tema do Colunista -->
                        <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center text-white mb-3 shadow-sm" style="width: 100px; height: 100px; background-color: {{ $columnist->theme_color ?? 'var(--portal-primary)' }}; font-size: 2.5rem; font-family: 'Outfit', sans-serif;">
                            {{ substr(explode(' ', $columnist->name)[0], 0, 1) }}
                        </div>
                        
                        <h5 class="fw-bolder text-dark mb-1" style="font-family: 'Outfit', sans-serif;">{{ $columnist->name }}</h5>
                        <p class="small text-muted mb-3"><i class="bi bi-pen me-1"></i> {{ $columnist->news_count }} Artigos Publicados</p>
                        
                        <p class="text-muted small mb-0 lh-sm">{{ \Illuminate\Support\Str::limit($columnist->bio, 80) }}</p>
                        
                        <div class="mt-4 pt-3 border-top w-100">
                            <span class="fw-bold text-uppercase" style="color: {{ $columnist->theme_color ?? 'var(--portal-primary)' }}; font-size: 0.75rem; letter-spacing: 1px;">Acessar Coluna <i class="bi bi-arrow-right ms-1"></i></span>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12 py-5 text-center text-muted">
                <i class="bi bi-people fs-1 opacity-50 d-block mb-3"></i>
                Nenhum colunista foi escalado ainda.
            </div>
        @endforelse
    </div>
</div>
