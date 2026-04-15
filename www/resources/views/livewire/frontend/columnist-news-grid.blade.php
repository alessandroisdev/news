<div>
    <div class="row g-4">
        @forelse ($newsGrid as $news)
            <!-- Mesma estrutura interativa da categoria, porem mais densa -->
            <div class="col-md-6 col-lg-4">
                <a href="/{{ $news->slug }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden" style="transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)';" onmouseout="this.style.transform='translateY(0)';">
                        @if($news->cover_image)
                        <img src="/images/{{ $news->cover_image }}/400x250" class="card-img-top w-100 bg-light object-fit-cover" style="height: 180px;" alt="{{ $news->title }}">
                        @endif
                        <div class="card-body">
                            <span class="badge bg-primary mb-2">{{ $news->category->name ?? 'Geral' }}</span>
                            <h5 class="card-title fw-bold text-dark lh-sm" style="font-size: 1.05rem;">{{ $news->title }}</h5>
                            <p class="card-text text-muted small mt-2">
                                <i class="bi bi-clock me-1"></i> {{ $news->published_at ? $news->published_at->format('d M, Y') : '' }}
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12 text-center text-muted py-5">
                <i class="bi bi-journal-x fs-1 text-secondary mb-3 d-block"></i>
                O colunista ainda não publicou artigos reflexivos.
            </div>
        @endforelse
    </div>

    @if ($newsGrid->hasPages())
        <div class="mt-5 d-flex justify-content-center">
            {{ $newsGrid->links(data: ['scrollTo' => false]) }}
        </div>
    @endif
</div>
