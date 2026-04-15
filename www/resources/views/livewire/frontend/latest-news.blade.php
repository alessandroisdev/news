<div class="container py-5">
    <div class="d-flex align-items-center mb-5">
        <h2 class="fw-bolder mb-0 text-dark" style="font-family: 'Outfit', sans-serif;"><i class="bi bi-clock-history text-primary me-2"></i>Últimas Atualizações</h2>
        <hr class="flex-grow-1 ms-3 border-secondary opacity-25">
    </div>

    <div class="row g-4 mb-5">
        @forelse($newsList as $news)
            <div class="col-md-6 col-lg-4">
                <a href="/{{ $news->slug }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden" style="transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 20px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 .125rem .25rem rgba(0,0,0,.075)';">
                        
                        <div style="height: 200px; background: url('/images/{{ $news->slug }}/400/250') center/cover no-repeat;" class="bg-light w-100 border-bottom"></div>
                        
                        <div class="card-body p-4 d-flex flex-column">
                            <Small class="fw-bold mb-2 text-uppercase" style="color: {{ $news->category->theme_color ?? 'var(--portal-primary)' }}; font-size: 0.75rem; letter-spacing: 1px;">
                                {{ call_user_func(function($name) { return trim(preg_replace('/[0-9\-]+$/', '', $name)); }, $news->category->name) }}
                            </Small>
                            <h5 class="card-title fw-bold text-dark lh-sm" style="font-family: 'Outfit', sans-serif;">{{ \Illuminate\Support\Str::limit($news->title, 70) }}</h5>
                            
                            <div class="mt-auto pt-3 border-top d-flex align-items-center text-muted" style="font-size: 0.75rem;">
                                <i class="bi bi-person-circle me-1"></i> {{ $news->author->name }} 
                                <span class="mx-2">•</span> 
                                <i class="bi bi-calendar3 me-1"></i> {{ $news->published_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12 text-center text-muted py-5">
                <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                <p>Nenhuma notícia publicada ainda.</p>
            </div>
        @endforelse
    </div>

    <!-- Paginação Infinita Server-Side do Livewire -->
    <div class="d-flex justify-content-center">
        {{ $newsList->links() }}
    </div>
</div>
