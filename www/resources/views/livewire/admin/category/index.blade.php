<div>
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <h3 class="fw-bolder text-dark mb-1" style="font-family: 'Outfit', sans-serif;">Gestão de Árvore (Categorias)</h3>
            <p class="text-muted small mb-0">Estrutura que dita a precedência principal do roteamento Fallback na raiz do projeto (Ex: meusite.com/categoria).</p>
        </div>
    </div>
    
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible bg-success bg-opacity-10 text-success border-success border-opacity-25 fade show rounded-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible bg-danger bg-opacity-10 text-danger border-danger border-opacity-25 fade show rounded-3" role="alert">
            <i class="bi bi-shield-x me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-4">
            <!-- Formulario Reativo (Embutido para dinamismo direto) -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h6 class="fw-bold text-primary mb-0">
                        <i class="bi bi-tag-fill me-2"></i>
                        {{ $editingId ? 'Editar Entidade SEO' : 'Nova Entidade SEO' }}
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary text-uppercase small">Nome Público</label>
                            <input type="text" wire:model="name" class="form-control bg-light border-0 shadow-none @error('name') is-invalid @enderror" placeholder="Ex: Economia Prática">
                            @error('name') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary text-uppercase small">Cor Tema Dinâmica</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-palette text-primary"></i></span>
                                <input type="color" wire:model="theme_color" class="form-control form-control-color bg-light border-0 shadow-none" title="Escolha sua cor p/ Frontend">
                            </div>
                            <small class="text-muted" style="font-size: 0.75rem;">Obriga que a interface do leitor transmute a estampa padrão visual pelas cores escolhidas aqui.</small>
                            @error('theme_color') <div class="text-danger small mt-1 fw-bold">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary text-uppercase small">Resumo / Meta Descrição SEO</label>
                            <textarea wire:model="description" class="form-control bg-light border-0 shadow-none" rows="3" placeholder="Foque no engajamento..."></textarea>
                        </div>

                        <div class="d-flex flex-column gap-2">
                            <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm d-flex justify-content-center align-items-center" style="height: 50px;">
                                <span wire:loading.remove wire:target="save">{{ $editingId ? 'Atualizar Categoria' : 'Integrar Categoria' }}</span>
                                <span wire:loading wire:target="save"><span class="spinner-border spinner-border-sm me-2"></span>Registrando...</span>
                            </button>
                            @if($editingId)
                                <button type="button" wire:click="cancelEdit" class="btn btn-light border text-danger w-100 fw-bold shadow-sm">Cancelar Edição</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-0">
                    <div class="table-responsive h-100">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3 px-4 border-bottom-0 text-secondary small text-uppercase fw-bold">Nome Final (SEO)</th>
                                    <th class="py-3 px-4 border-bottom-0 text-secondary small text-uppercase fw-bold">URI Nativa Global</th>
                                    <th class="py-3 px-4 border-bottom-0 text-secondary small text-uppercase fw-bold">Design Inject</th>
                                    <th class="py-3 px-4 border-bottom-0 text-end"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                    <tr>
                                        <td class="py-3 px-4 fw-bold text-dark">{{ $category->name }}</td>
                                        <td class="py-3 px-4 text-muted"><code class="text-primary bg-light p-1 rounded-2 shadow-sm border border-light">/{{ $category->slug }}</code></td>
                                        <td class="py-3 px-4">
                                            <div class="d-flex align-items-center bg-light d-inline-flex p-1 px-2 rounded-pill border">
                                                <div class="rounded-circle me-2 shadow-sm" style="width: 14px; height: 14px; background-color: {{ $category->theme_color ?? '#000' }};"></div>
                                                <small class="fw-semibold text-muted font-monospace">{{ $category->theme_color }}</small>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 text-end">
                                            <button wire:click="edit({{ $category->id }})" class="btn btn-sm btn-light border text-primary hover-shadow me-2" title="Editar"><i class="bi bi-pencil-square"></i></button>
                                            <button wire:click="confirmDelete({{ $category->id }})" class="btn btn-sm btn-light border text-danger hover-shadow" title="Mover p/ Lixeira"><i class="bi bi-trash3"></i></button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="bi bi-tags ms-2 fs-2 opacity-50 d-block mb-3"></i>
                                            Nenhuma estrutura de tags registrada nas tabelas centrais.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        {{ $categories->links() }}
    </div>

    @if($confirmingDeletionId)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.6); backdrop-filter: blur(3px);">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
          <div class="modal-header border-bottom-0 pb-0">
            <h5 class="modal-title fw-bolder text-danger"><i class="bi bi-exclamation-octagon-fill me-2"></i>Supressão Estrutural</h5>
            <button type="button" wire:click="cancelDelete" class="btn-close" aria-label="Close"></button>
          </div>
          <div class="modal-body p-4 text-center">
            <i class="bi bi-tags text-danger opacity-25" style="font-size: 4rem;"></i>
            <p class="mt-3 mb-0 text-muted" style="font-size: 1.1rem;">
                Você tem plena certeza de que essa raiz taxonômica deve sumir permanentemente do sistema de URLs e Arquivos?
            </p>
          </div>
          <div class="modal-footer border-top-0 pt-0 justify-content-center gap-2 pb-4">
            <button type="button" wire:click="cancelDelete" class="btn btn-light fw-bold text-secondary px-4 border shadow-sm">Cancelar</button>
            <button type="button" wire:click="deleteCategory" class="btn btn-danger fw-bold shadow-sm px-4">
                <span wire:loading.remove wire:target="deleteCategory"><i class="bi bi-check2-circle me-1"></i> Sim, Apagar Categoria</span>
                <span wire:loading wire:target="deleteCategory"><span class="spinner-border spinner-border-sm me-2"></span>Confirmando...</span>
            </button>
          </div>
        </div>
      </div>
    </div>
    @endif
</div>
