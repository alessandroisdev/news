<div>
    <div class="row g-4">
        @forelse ($newsGrid as $news)
            <div class="col-md-6 col-lg-4">
                <a href="/{{ $news->slug }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden" style="transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)';" onmouseout="this.style.transform='translateY(0)';">
                        <img src="/images/{{ $news->cover_image ?? 'placeholder.jpg' }}/400x250" class="card-img-top w-100 object-fit-cover bg-light" style="height: 200px;" alt="{{ $news->title }}">
                        <div class="card-body">
                            <h5 class="card-title fw-bold text-dark lh-sm" style="font-family: 'Inter', sans-serif; font-size: 1.1rem;">{{ $news->title }}</h5>
                            <p class="card-text text-muted small mt-2 fw-semibold">
                                Por {{ $news->author->name ?? 'Redação' }} &bull; {{ $news->published_at ? $news->published_at->diffForHumans() : '' }}
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-secondary text-center py-5 border-0 rounded-4">
                    <i class="bi bi-info-circle fs-2 d-block mb-2 text-primary"></i>
                    Nenhuma notícia livre cadastrada nesta categoria.
                </div>
            </div>
        @endforelse
    </div>

    @if ($newsGrid->hasPages())
        <div class="mt-5 d-flex justify-content-center">
            {{ $newsGrid->links(data: ['scrollTo' => false]) }}
        </div>
    @endif
</div>
