<div>
    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
        <style>
            .hero-swiper {
                width: 100%;
                min-height: 60vh;
            }
            .hero-swiper-slide {
                width: 100%;
                position: relative;
                overflow: hidden;
            }
            .slide-bg-overlay {
                position: absolute;
                inset: 0;
                background: linear-gradient(to top, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0.4) 50%, rgba(0,0,0,0.1) 100%);
                z-index: 1;
            }
            .slide-content-wrapper {
                position: relative;
                z-index: 2;
                height: 100%;
                display: flex;
                flex-direction: column;
                justify-content: flex-end;
                padding-bottom: 5rem;
            }
            .swiper-pagination-bullet {
                background: #ffffff;
                opacity: 0.5;
            }
            .swiper-pagination-bullet-active {
                opacity: 1;
                background: var(--portal-primary);
                width: 24px;
                border-radius: 4px;
            }
        </style>
    @endpush

    <!-- Hero Slider Automático Swiper.js -->
    <div class="swiper hero-swiper shadow-lg">
        <div class="swiper-wrapper">
            @forelse($slides as $slide)
                @if($slide['type'] === 'news')
                    <!-- SLIDE TIPO NOTÍCIA (Recomendação por Score) -->
                    @php $n = $slide['data']; @endphp
                    <div class="swiper-slide hero-swiper-slide" style="background: url('{{ $n->cover_image ? asset('storage/'.$n->cover_image) : 'https://images.unsplash.com/photo-1585829365295-ab7cd400c167?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80' }}') no-repeat center center / cover;">
                        <div class="slide-bg-overlay"></div>
                        <div class="slide-content-wrapper container">
                            <div class="col-md-8 col-lg-7 text-white">
                                <span class="badge bg-primary fs-6 mb-3 rounded-0 px-3 py-2 fw-semibold" style="letter-spacing: 1px;"><i class="bi bi-tag-fill me-1"></i>{{ optional($n->category)->name ?? 'Geral' }}</span>
                                <h1 class="display-4 fw-bolder mb-3 lh-sm" style="font-family: 'Outfit', sans-serif;">{{ $n->title }}</h1>
                                <div class="d-flex align-items-center gap-4 text-white text-opacity-75 fw-semibold small">
                                    <span><i class="bi bi-person-circle me-1"></i> {{ optional($n->author)->name }}</span>
                                    <span><i class="bi bi-calendar-event me-1"></i> {{ $n->published_at ? $n->published_at->diffForHumans() : $n->created_at->diffForHumans() }}</span>
                                </div>
                                <a href="{{ route('dynamic.slug', $n->slug) }}" class="btn btn-light rounded-pill btn-lg mt-4 fw-bold shadow-sm px-4">Ler Matéria Completa</a>
                            </div>
                        </div>
                    </div>
                @elseif($slide['type'] === 'banner')
                    <!-- SLIDE TIPO AD/BANNER (Agendamento Targetado) -->
                    @php $b = $slide['data']; @endphp
                    @php
                       // Lógica de URL se for pra reportagem interna ou link externo
                       $targetLink = $b->target_url;
                       if ($b->news_id && \App\Models\News::find($b->news_id)) {
                           $targetLink = route('dynamic.slug', \App\Models\News::find($b->news_id)->slug);
                       }
                    @endphp
                    <div class="swiper-slide hero-swiper-slide" style="background: url('{{ asset('storage/'.$b->image_path) }}') no-repeat center center / cover;">
                        <div class="slide-bg-overlay" style="background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0) 80%);"></div>
                        <div class="slide-content-wrapper container align-items-end text-end pb-3"> <!-- Bottom right alignment para o botão de ação da publicidade -->
                            <div class="mb-3">
                                <span class="badge bg-dark bg-opacity-75 border text-uppercase mb-2"><i class="bi bi-badge-ad-fill text-warning me-1"></i> Patrocinado</span>
                            </div>
                            @if($targetLink)
                                <a href="{{ $targetLink }}" class="btn btn-warning rounded-0 border-white border text-dark fw-bold btn-lg target-ad-click shadow-lg" target="_blank">{{ $b->title }} <i class="bi bi-arrow-up-right ms-2"></i></a>
                            @endif
                        </div>
                    </div>
                @endif
            @empty
                <!-- Fallback se não houver banco populado ainda -->
                <div class="swiper-slide hero-swiper-slide d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, var(--portal-primary) 0%, var(--portal-secondary) 100%);">
                    <div class="text-center position-relative z-2 text-white">
                        <h1 class="display-3 fw-bolder shadow-sm" style="font-family: 'Outfit', sans-serif;">Semântica Machine Learning Vazia</h1>
                        <p class="lead fw-light">O Crawler ainda não popularizou matérias para o seu perfil e não há anúncios vigentes nessa hora.</p>
                    </div>
                </div>
            @endforelse
        </div>
        <div class="swiper-pagination mb-2"></div>
        <div class="swiper-button-prev text-white"></div>
        <div class="swiper-button-next text-white"></div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const swiper = new Swiper('.hero-swiper', {
                    loop: true,
                    effect: 'fade', // Super profissional
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                });
            });
        </script>
    @endpush
</div>
