@extends('layouts.app')

@section('title', $news->title)

@section('dynamic_css_vars')
    @if($news->category && $news->category->theme_color)
    <style>
        :root {
            --portal-primary: {{ $news->category->theme_color }} !important;
        }
    </style>
    @endif
@endsection

@section('content')
<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <!-- Corpo Principal da Notícia -->
        <div class="col-lg-8">
            <a href="/{{ $news->category->slug ?? '#' }}" class="badge bg-primary mb-3 py-2 px-3 text-decoration-none hover-shadow">{{ $news->category->name ?? 'Sem Categoria' }}</a>
            <h1 class="display-4 fw-bolder mb-3 text-dark" style="font-family: 'Outfit', sans-serif; line-height: 1.2;">{{ $news->title }}</h1>
            
            <div class="d-flex align-items-center text-muted small mb-4 pb-3 border-bottom">
                <div class="me-4 d-flex align-items-center">
                    <img src="/images/{{ $news->author->avatar ?? 'default.jpg' }}/32x32" class="rounded-circle me-2" alt="">
                    @if($news->author && $news->author->slug)
                        <span>Por <a href="/{{ $news->author->slug }}" class="text-decoration-none fw-semibold text-primary">{{ $news->author->name }}</a></span>
                    @else
                        <span>Por <span class="fw-semibold" style="color: var(--portal-primary);">{{ $news->author->name ?? 'Redação' }}</span></span>
                    @endif
                </div>
                <div>
                    <i class="bi bi-calendar3 me-1"></i> Publicado em {{ $news->published_at ? $news->published_at->format('d/m/Y \à\s H:i') : 'N/A' }}
                </div>
            </div>

            @if($news->cover_image)
            <div class="mb-5 rounded-4 overflow-hidden shadow-sm position-relative placeholder-glow">
                <!-- Media Controller: Dimensão exata com crop inteligente (16:9) do Glide interceptando a Request -->
                <img src="/images/{{ $news->cover_image }}/800x450" class="img-fluid w-100 object-fit-cover" style="max-height: 450px;" alt="{{ $news->title }}">
                <span class="position-absolute bottom-0 end-0 bg-dark text-white opacity-75 px-2 py-1 small rounded-top-start">Foto: Divulgação</span>
            </div>
            @endif

            <article class="news-content fs-5 text-dark" style="line-height: 1.8; font-family: 'Inter', sans-serif;">
                {!! $news->content !!}
            </article>
            
            <!-- Propaganda no corpo da noticia (Adicionada ergonomicamente no final pro leitor continuar ali) -->
            <div class="ad-space w-100 bg-light text-center py-5 my-5 border rounded-3 transition-hover" style="border-style: dashed !important; border-color: #ced4da;">
                <p class="text-muted mb-0 fw-semibold"><i class="bi bi-badge-ad me-2 text-primary"></i>Anúncio Sugerido - In Article</p>
            </div>
            
            <!-- Motor de Respostas e Comentários -->
            @php $allowGlobally = \App\Models\Setting::get('allow_comments_globally', '1') == '1'; @endphp
            @if($allowGlobally && $news->allow_comments)
                <livewire:frontend.news-comments :newsId="$news->id" />
            @elseif(!$allowGlobally)
                <div class="alert alert-secondary mt-5 py-4 text-center border-0 rounded-4">
                    <i class="bi bi-chat-square-text fs-3 text-muted mb-2 d-block"></i>
                    <p class="mb-0 fw-semibold">Os comentários estão temporariamente desativados em todo o portal.</p>
                </div>
            @else
                <div class="alert alert-secondary mt-5 py-4 text-center border-0 rounded-4">
                    <i class="bi bi-chat-square-text fs-3 text-muted mb-2 d-block"></i>
                    <p class="mb-0 fw-semibold">Os comentários para esta notícia foram desativados pelo autor.</p>
                </div>
            @endif
        </div>
        
        <!-- Sidebar Inteligente -->
        <div class="col-lg-4 mt-5 mt-lg-0">
             <div class="sticky-top" style="top: 100px;">
                 <h4 class="fw-bold mb-4 border-bottom border-primary pb-2" style="font-family: 'Outfit', sans-serif; border-bottom-width: 4px !important; display: inline-block;">Leia Também</h4>
                 
                 <div class="d-flex flex-column gap-3">
                     @forelse($relatedNews as $related)
                     <a href="/{{ $related->slug }}" class="text-decoration-none p-3 shadow-sm rounded-3 border bg-white d-flex" style="transition: all 0.2s;" onmouseover="this.classList.add('border-primary');" onmouseout="this.classList.remove('border-primary');">
                         <div style="width: 80px; height: 80px; flex-shrink: 0;" class="bg-light rounded overflow-hidden me-3">
                             <img src="/images/{{ $related->slug }}/80x80" class="w-100 h-100 object-fit-cover" alt="">
                         </div>
                         <div>
                             <h6 class="fw-bold text-dark mb-1 lh-sm" style="font-size: 0.85rem;">{{ \Illuminate\Support\Str::limit($related->title, 55) }}</h6>
                             <small class="text-muted" style="font-size: 0.70rem;"><i class="bi bi-clock me-1"></i>{{ $related->published_at->diffForHumans() }}</small>
                         </div>
                     </a>
                     @empty
                     <p class="text-muted small">Nenhuma listagem adicional.</p>
                     @endforelse
                 </div>
             </div>
        </div>
    </div>
</div>
@endsection
