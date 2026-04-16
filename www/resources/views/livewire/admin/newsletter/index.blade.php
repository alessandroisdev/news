<div>
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h3 class="fw-bolder text-dark mb-0" style="font-family: 'Outfit', sans-serif;">Gestor de Mailing & Disparos</h3>
        <button wire:click="createDraft" class="btn btn-primary fw-bold px-4 shadow-sm">
            <i class="bi bi-envelope-plus me-2"></i>Montar Nova Edição
        </button>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 border-start border-5 border-success mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <span class="fw-semibold">{{ session('message') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0 border-start border-5 border-danger mb-4" role="alert">
            <i class="bi bi-x-circle-fill me-2 fs-5"></i>
            <span class="fw-semibold">{{ session('error') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-header bg-white border-bottom p-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0 fw-bold text-secondary"><i class="bi bi-collection text-primary me-2"></i>Histórico Corporativo</h5>
                </div>
                <div class="col-md-6 mt-3 mt-md-0">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control bg-light border-start-0 ps-0 form-control-lg shadow-none" placeholder="Buscar edições antigas...">
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">
                    <tr>
                        <th class="ps-4 py-3">Assunto (Subject)</th>
                        <th class="py-3">Status</th>
                        <th class="py-3">Data Envio</th>
                        <th class="text-end pe-4 py-3">Ações Administrativas</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse ($newsletters as $n)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="fw-bold text-dark fs-6">{{ $n->subject }}</div>
                                <div class="text-muted small"><i class="bi bi-info-circle me-1"></i>{{ $n->title }}</div>
                            </td>
                            <td class="py-3">
                                @if($n->status === 'draft')
                                    <span class="badge bg-secondary opacity-75 fw-normal px-3 py-2 rounded-pill"><i class="bi bi-pencil me-1"></i> No Rascunho</span>
                                @elseif($n->status === 'sending')
                                    <span class="badge bg-warning text-dark fw-bold px-3 py-2 rounded-pill"><i class="bi bi-rocket-takeoff me-1"></i> Disparando...</span>
                                @else
                                    <span class="badge bg-success bg-gradient fw-bold px-3 py-2 rounded-pill"><i class="bi bi-check2-all me-1"></i> Entregue</span>
                                @endif
                            </td>
                            <td class="py-3 text-muted">
                                {{ $n->sent_at ? $n->sent_at->format('d/m/Y \à\s H:i') : '-- / --' }}
                            </td>
                            <td class="text-end pe-4 py-3">
                                @if($n->status === 'draft')
                                    <a href="{{ route('admin.newsletter.builder', $n->id) }}" class="btn btn-sm btn-primary rounded-circle border-0 shadow-sm me-1" style="width: 35px; height: 35px;" title="Painel Drop">
                                        <i class="bi bi-hammer mt-1 d-block"></i>
                                    </a>
                                @endif
                                
                                <button wire:click="delete({{ $n->id }})" wire:confirm="Essa ação arranca esta versão do servidor. Tem certeza?" class="btn btn-sm btn-danger rounded-circle border-0 shadow-sm" style="width: 35px; height: 35px;" title="Excluir">
                                    <i class="bi bi-trash3 mt-1 d-block"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                                O motor editorial não possui campanhas. Comece agora.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($newsletters->hasPages())
        <div class="card-footer bg-white p-3 border-top">
            {{ $newsletters->links() }}
        </div>
        @endif
    </div>
</div>
