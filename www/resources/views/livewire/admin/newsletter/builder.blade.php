<div>
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h3 class="fw-bolder text-dark mb-0" style="font-family: 'Outfit', sans-serif;">Gestor de Mailing / Editor Visual</h3>
        <a href="{{ route('admin.newsletter.index') }}" class="btn btn-light border fw-bold shadow-sm">
            <i class="bi bi-arrow-left me-2"></i>Voltar
        </a>
    </div>

    @if (session()->has('warning'))
        <div class="alert alert-warning alert-dismissible fade show rounded-3 shadow-sm border-0 border-start border-5 border-warning mb-4 fw-bold" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Lado Esquerdo: Ferramentas de Seleção -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 bg-white">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 text-primary"><i class="bi bi-gear-fill me-2"></i>Variáveis Dinâmicas</h5>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary small text-uppercase">Assunto Matador do E-mail</label>
                        <input type="text" wire:model.live.debounce.500ms="subject" class="form-control bg-light border-0 py-2 shadow-none" placeholder="Isso vai na caixa de entrada do cara...">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary small text-uppercase">Título Interno H1</label>
                        <input type="text" wire:model.live.debounce.500ms="title" class="form-control bg-light border-0 py-2 shadow-none" placeholder="O titulo ao abrir a folha do email...">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary small text-uppercase">Saudação de Capa (Opcional)</label>
                        <textarea wire:model.live.debounce.500ms="body" class="form-control bg-light border-0 shadow-none" rows="3" placeholder="Mensagem escrita pelos editores da semana."></textarea>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 text-dark"><i class="bi bi-search me-2 text-primary"></i>Puxar Matérias Prontas</h5>
                    
                    <div class="input-group mb-4">
                        <span class="input-group-text bg-light border-0 text-muted"><i class="bi bi-newspaper"></i></span>
                        <input type="text" wire:model.live.debounce.300ms="searchNews" class="form-control bg-light border-0 shadow-none" placeholder="Pesquise por título...">
                    </div>

                    <div class="list-group list-group-flush border-top">
                        @forelse($availableNews as $news)
                            @php $isPinned = in_array($news->id, $selectedNews); @endphp
                            <div class="list-group-item px-0 py-3 d-flex align-items-center bg-transparent {{ $isPinned ? 'opacity-50' : '' }}" style="transition: all 0.2s;">
                                <div style="width: 50px; height: 50px; flex-shrink: 0;" class="bg-light rounded overflow-hidden shadow-sm me-3">
                                    <img src="/images/{{ $news->slug }}/50x50" class="w-100 h-100 object-fit-cover" alt="">
                                </div>
                                <div class="flex-grow-1 pe-2">
                                    <h6 class="mb-1 text-dark fw-bold lh-sm" style="font-size: 0.85rem;">{{ \Illuminate\Support\Str::limit($news->title, 45) }}</h6>
                                </div>
                                <button wire:click="toggleNews({{ $news->id }})" class="btn btn-sm btn-{{ $isPinned ? 'danger' : 'primary' }} rounded-circle shadow-sm" style="width: 32px; height: 32px;">
                                    <i class="bi bi-{{ $isPinned ? 'x' : 'plus-lg' }} fw-bold"></i>
                                </button>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted small">
                                Nenhuma publicação recente encontrada.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Lado Direito: Preview e Render do E-mail -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 bg-light overflow-hidden" style="border: 2px dashed #dee2e6 !important;">
                <div class="card-header bg-dark text-white p-3 d-flex justify-content-between align-items-center">
                    <span class="fw-semibold small"><i class="bi bi-display me-2"></i>Preview ao Vivo do Boletim</span>
                    <span class="badge bg-primary rounded-pill">{{ count($selectedNews) }}/5 Pinados</span>
                </div>
                
                <div class="card-body p-0 d-flex justify-content-center bg-light">
                    <!-- Falso Corpo do Email -->
                    <div class="bg-white my-4 shadow-sm w-100 rounded" style="max-width: 600px; min-height: 500px;">
                        
                        <div class="bg-primary p-4 text-center text-white">
                            <h2 class="fw-bolder mb-0" style="font-family: 'Outfit', sans-serif;">{{ $title ?: 'Boletim Notícias' }}</h2>
                        </div>
                        
                        <div class="p-4">
                            @if($body)
                                <div class="text-dark mb-4 fst-italic" style="font-size: 0.95rem; line-height: 1.6; border-left: 3px solid #0d6efd; padding-left: 15px;">
                                    {{ $body }}
                                </div>
                            @endif

                            @if(count($pinnedNews) === 0)
                                <div class="text-center py-5 text-muted">
                                    <i class="bi bi-box-arrow-in-right fs-1 opacity-25 d-block mb-3"></i>
                                    Arraste e pine as matérias aqui no grid vazio.
                                </div>
                            @else
                                <div class="d-flex flex-column gap-4 mt-2">
                                    @foreach($pinnedNews as $pin)
                                        <div class="border rounded-3 overflow-hidden shadow-sm position-relative">
                                            <div class="position-absolute top-0 end-0 m-2">
                                                <button wire:click="toggleNews({{ $pin->id }})" class="btn btn-sm btn-danger rounded-circle opacity-75 hover-opacity-100" style="width: 25px; height: 25px; display:flex; align-items:center; justify-content:center;"><i class="bi bi-x"></i></button>
                                            </div>
                                            <!-- Mini Banner -->
                                            <div style="height: 140px;" class="bg-light">
                                                <img src="/images/{{ $pin->slug }}/600x200" class="w-100 h-100 object-fit-cover" alt="">
                                            </div>
                                            <div class="p-3 bg-white">
                                                <h5 class="fw-bolder text-dark lh-sm mb-2" style="font-size: 1.1rem;">{{ $pin->title }}</h5>
                                                <div class="btn btn-sm btn-outline-primary fw-bold px-3 py-1 mt-1 rounded-pill" style="pointer-events: none;">Ler Completo &rarr;</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        
                        <div class="bg-light p-4 text-center text-muted small border-top">
                            Você está recebendo este e-mail por ser Assinante do Portal News VIP.<br>
                            &copy; 2026 Todos os direitos reservados.
                        </div>
                    </div>
                </div>
                
                <div class="card-footer bg-white p-4 border-top">
                     <button wire:click="dispatchCampaign" wire:confirm="Injetar a campanha massiva nas filas Worker de envio. Tem certeza? Ninguém pode cancelar uma vez engatilhada." class="btn btn-dark btn-lg w-100 fw-bold shadow position-relative transition-hover" {{ count($pinnedNews) == 0 ? 'disabled' : '' }}>
                        <span wire:loading.remove wire:target="dispatchCampaign">
                            <i class="bi bi-rocket-takeoff-fill me-2 text-warning"></i> Enviar Massa para Assinantes Atuais
                        </span>
                        <span wire:loading wire:target="dispatchCampaign">
                            <span class="spinner-border spinner-border-sm me-2"></span> Transferindo Lote JSON...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
