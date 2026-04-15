<div class="d-flex ms-lg-4 position-relative">
    <div class="position-relative w-100">
        <!-- Input de busca reativo conectado ao servidor -->
        <input wire:model.live.debounce.150ms="query" class="form-control rounded-pill bg-light border-0 px-4 py-2 w-100 shadow-none focus-ring focus-ring-primary" style="min-width: 280px;" type="search" placeholder="Busca semântica avançada...">
        
        <i wire:loading.remove wire:target="query" class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
        <div wire:loading wire:target="query" class="spinner-border spinner-border-sm position-absolute top-50 end-0 translate-middle-y me-3 text-primary" role="status"></div>
    </div>

    <!-- Dropdown inteligente com cache do Meilisearch -->
    @if(strlen($query) >= 2)
        <div class="position-absolute top-100 mt-2 start-0 w-100 bg-white shadow px-0 pt-2 pb-2 rounded-4 overflow-hidden border" style="z-index: 1050 !important; animation: fadeIn 0.2s ease-in-out;">
            @forelse($results as $news)
                <a href="/{{ $news->slug }}" class="d-flex flex-column text-decoration-none px-3 py-2 border-bottom hover-shadow-sm transition-all text-dark" style="background-color: transparent;" onmouseover="this.style.backgroundColor='#f8f9fa';" onmouseout="this.style.backgroundColor='transparent';">
                    <span class="fw-bold mb-1 lh-sm" style="font-size: 0.9rem;">{{ \Illuminate\Support\Str::limit($news->title, 55) }}</span>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-primary fw-semibold" style="font-size: 0.75rem;"><i class="bi bi-tag-fill me-1"></i>{{ $news->category->name ?? 'Destaque' }}</small>
                        <small class="text-muted" style="font-size: 0.7rem;">{{ $news->published_at->diffForHumans() }}</small>
                    </div>
                </a>
            @empty
                <div class="p-4 text-center text-muted small fw-semibold">
                    <i class="bi bi-emoji-frown fs-3 d-block mb-2 opacity-50"></i>
                    A Inteligência não encontrou resultados cruzados para "<span class="text-dark">{{ $query }}</span>".
                </div>
            @endforelse
            
            @if(count($results) >= 5)
            <div class="text-center pt-2">
                <a href="#" class="small fw-bolder text-primary text-decoration-none">Ver todos os resultados</a>
            </div>
            @endif
        </div>
    @endif
</div>
