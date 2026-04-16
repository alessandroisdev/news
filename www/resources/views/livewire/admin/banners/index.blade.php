<div>
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <h3 class="fw-bolder text-dark mb-1" style="font-family: 'Outfit', sans-serif;">Gestão de AdTech (Banners)</h3>
            <p class="text-muted small mb-0">Controle, agendamento e programação de parceiros e anúncios patrocinados.</p>
        </div>
    </div>
    
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible bg-success bg-opacity-10 text-success border-success border-opacity-25 fade show rounded-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Coluna Formulário -->
        <div class="col-lg-4 mb-4">
            <div class="card border border-primary border-opacity-25 shadow-sm rounded-4 bg-light">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4"><i class="bi bi-megaphone me-2 text-primary"></i>Novo Patrocínio</h5>
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Título Interno</label>
                            <input type="text" wire:model="title" class="form-control" placeholder="Campanha Ouro">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Arte / Imagem (Slider)</label>
                            <input type="file" wire:model="image" class="form-control border-primary" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Link Externo de Destino</label>
                            <input type="url" wire:model="target_url" class="form-control" placeholder="https://site-do-patrocinador.com">
                        </div>
                        <div class="mb-4 position-relative">
                            <label class="form-label small fw-bold">Ou vincule a uma Notícia</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                                <input type="text" wire:model.live.debounce.300ms="newsSearch" class="form-control border-start-0 ps-0" placeholder="Digite para buscar pautas...">
                            </div>
                            
                            @if(!empty($newsSearchResults))
                            <ul class="list-group position-absolute w-100 z-3 shadow-lg border-primary border-opacity-25 mt-1" style="max-height: 250px; overflow-y: auto;">
                                @foreach($newsSearchResults as $res)
                                    <button type="button" wire:click="selectNews({{ $res['id'] }}, '{{ addslashes($res['title']) }}')" class="list-group-item list-group-item-action text-start py-2 px-3 small border-0 border-bottom border-light">
                                        <i class="bi bi-file-text text-muted me-2"></i> {{ \Illuminate\Support\Str::limit($res['title'], 55) }}
                                    </button>
                                @endforeach
                            </ul>
                            @elseif(strlen($newsSearch) >= 3 && !$news_id)
                                <div class="position-absolute w-100 z-3 shadow-sm border mt-1 bg-white p-2 text-center text-muted small rounded-bottom">
                                    Nenhuma notícia encontrada.
                                </div>
                            @endif
                            
                            @if($news_id)
                                <div class="mt-2 small fw-bold text-success d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill me-2 fs-6"></i> Notícia Acoplada
                                    <button type="button" class="btn btn-link btn-sm text-danger text-decoration-none p-0 ms-auto" wire:click="$set('newsSearch', '')">Desvincular</button>
                                </div>
                            @endif
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Dias Ativos (Agendamento)</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach([1=>'Seg', 2=>'Ter', 3=>'Qua', 4=>'Qui', 5=>'Sex', 6=>'Sáb', 0=>'Dom'] as $val => $label)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="active_days" value="{{ $val }}" id="day{{ $val }}">
                                    <label class="form-check-label small" for="day{{ $val }}">{{ $label }}</label>
                                </div>
                                @endforeach
                            </div>
                            <small class="text-muted d-block mt-1">Deixe tudo vazio para aparecer todos os dias.</small>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm">Programar Publicação</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Coluna Tabela -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3 px-4 text-muted small fw-bold">BANNER/ARTE</th>
                                    <th class="py-3 px-4 text-muted small fw-bold text-center">STATUS</th>
                                    <th class="py-3 px-4 text-muted small fw-bold text-center">VIEWS x CLIQUES</th>
                                    <th class="py-3 px-4 text-muted small fw-bold text-end">AÇÕES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($banners as $banner)
                                    <tr>
                                        <td class="py-3 px-4">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('storage/' . $banner->image_path) }}" class="rounded shadow-sm me-3 object-fit-cover" width="60" height="40" alt="Banner">
                                                <div>
                                                    <div class="fw-bold text-dark lh-sm mb-1">{{ $banner->title }}</div>
                                                    <small class="text-muted"><i class="bi bi-clock me-1"></i>Cronograma JSON</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            @if($banner->is_active)
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success">Ativo na Rede</span>
                                            @else
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary">Pausado</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <span class="fw-bold text-primary">{{ $banner->views }}</span> <span class="text-muted mx-1">x</span> <span class="fw-bold text-success">{{ $banner->clicks }}</span>
                                        </td>
                                        <td class="py-3 px-4 text-end">
                                            <button wire:click="toggleStatus({{ $banner->id }})" class="btn btn-sm btn-light border">Alternar Status</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{ $banners->links() }}
        </div>
    </div>
</div>
