<div class="row g-5">
    <div class="col-12 col-lg-8">
        <h3 class="fw-bold mb-4 border-bottom border-warning pb-2" style="font-family: 'Outfit', sans-serif; border-bottom-width: 4px !important; display: inline-block;">Últimas Notícias</h3>
        
        <div class="row g-4">
            @forelse($recommended as $news)
                <div class="col-md-6">
                    <!-- Respeita a cor dinâmica da categoria se houver senão fallback prq o tema -->
                    <a href="/{{ $news->slug }}" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden" style="transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 20px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 .125rem .25rem rgba(0,0,0,.075)';">
                            
                            <!-- Imagem vindo do Glide on-the-fly baseado no slug da noticia -->
                            <div style="height: 220px; background: url('/images/{{ $news->slug }}/500/300') center/cover no-repeat;" class="bg-light w-100 border-bottom"></div>
                            
                            <div class="card-body">
                                <span class="badge mb-3 px-3 py-2 rounded-pill shadow-sm" style="background-color: {{ $news->category->theme_color ?? 'var(--portal-primary)' }}; color: #fff;">
                                    {{ rtrim($news->category->name, ' -0123456789') }}
                                </span>
                                <h5 class="card-title fw-bold text-dark lh-sm" style="font-family: 'Outfit', sans-serif;">{{ \Illuminate\Support\Str::limit($news->title, 60) }}</h5>
                                <p class="card-text text-muted small mt-2 mb-0">{{ \Illuminate\Support\Str::limit(strip_tags($news->content), 100) }}</p>
                                <div class="mt-3 pt-3 border-top d-flex align-items-center text-muted" style="font-size: 0.75rem;">
                                    <i class="bi bi-person-circle me-1"></i> {{ $news->author->name }} 
                                    <span class="mx-2">•</span> 
                                    <i class="bi bi-clock me-1"></i> {{ $news->published_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12 py-5 text-center text-muted">
                    <i class="bi bi-journal-x fs-1 opacity-50 mb-3 d-block"></i>
                    A redação ainda não publicou conteúdo hoje.
                </div>
            @endforelse
        </div>
    </div>
    
    <div class="col-12 col-lg-4">
        <h3 class="fw-bold mb-4 border-bottom border-primary pb-2" style="font-family: 'Outfit', sans-serif; border-bottom-width: 4px !important; display: inline-block;">Em Alta (Top 5)</h3>
        <div class="d-flex flex-column gap-3">
            @foreach($trending as $index => $trend)
                <a href="/{{ $trend->slug }}" class="text-decoration-none p-3 bg-white shadow-sm rounded-3 d-flex justify-content-between align-items-start" style="transition: all 0.2s; border-left: 5px solid {{ $trend->category->theme_color ?? 'var(--portal-primary)' }};" onmouseover="this.style.transform='translateX(5px)';" onmouseout="this.style.transform='translateX(0)';">
                    <div class="pe-3">
                        <h6 class="fw-bolder mb-1" style="color: {{ $trend->category->theme_color ?? 'var(--portal-primary)' }}; font-size: 0.8rem; text-transform: uppercase;">
                            {{ call_user_func(function($name) { return trim(preg_replace('/[0-9\-]+$/', '', $name)); }, $trend->category->name) }}
                        </h6>
                        <p class="mb-0 small text-dark fw-bold lh-sm">{{ \Illuminate\Support\Str::limit($trend->title, 55) }}</p>
                        <small class="text-muted d-block mt-2" style="font-size: 0.7rem;"><i class="bi bi-eye text-secondary me-1"></i>{{ rand(100, 5000) }} visualizações</small>
                    </div>
                    <span class="badge bg-light text-muted border fs-5 fw-light">#{{ $index + 1 }}</span>
                </a>
            @endforeach
        </div>
        
        <!-- Bloco Publi Secundario Real -->
        <div class="mt-5 p-4 bg-dark rounded-4 text-center text-white position-relative overflow-hidden shadow">
            <div class="position-absolute end-0 top-0 text-white opacity-10" style="transform: translate(20%, -20%);"><i class="bi bi-lightning-charge-fill" style="font-size: 8rem;"></i></div>
            <span class="badge bg-warning text-dark mb-2">PROMOÇÃO</span>
            <h5 class="fw-bolder">Assinatura Premium</h5>
            <p class="small text-white-50">Não perca o melhor conteúdo. Assine agora mesmo para liberar análises exclusivas do seu Portal.</p>
            <a href="/assinante" class="btn btn-outline-light btn-sm fw-bold">Assinar Agora</a>
        </div>
    </div>
</div>
