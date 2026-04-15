<div>
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <h3 class="fw-bolder text-danger mb-1" style="font-family: 'Outfit', sans-serif;"><i class="bi bi-trash3 me-2"></i>Lixeira do Sistema (Restritos)</h3>
            <p class="text-muted small mb-0">Contas bloqueadas ou inativadas brutalmente via "SoftDeletes". Somente Admins podem visualizá-las e interceder aqui.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-light fw-bold shadow-sm">
            <i class="bi bi-arrow-left me-2"></i>Voltar
        </a>
    </div>
    
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible bg-success bg-opacity-10 text-success border-success border-opacity-25 fade show rounded-3" role="alert">
            <i class="bi bi-person-up me-2"></i> {{ session('message') }}
        </div>
    @endif

    <div class="card border border-danger border-opacity-25 shadow-sm rounded-4 h-100 bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-danger bg-opacity-10 text-danger">
                        <tr>
                            <th class="py-3 px-4 border-bottom-0 small text-uppercase fw-bold">Identidade Apagada</th>
                            <th class="py-3 px-4 border-bottom-0 small text-uppercase fw-bold">Cargo Antigo</th>
                            <th class="py-3 px-4 border-bottom-0 small text-uppercase fw-bold text-end">Ação (Rollback)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td class="py-3 px-4">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm me-3 bg-secondary opacity-50" style="width: 36px; height: 36px;">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark text-decoration-line-through">{{ $user->name }}</div>
                                            <div class="text-muted small">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="badge bg-secondary bg-opacity-10 text-muted fw-bold border">{{ strtoupper($user->role) }}</span>
                                    <div class="small text-danger fw-semibold mt-1"><i class="bi bi-clock-history me-1"></i>Deletado: {{ $user->deleted_at->diffForHumans() }}</div>
                                </td>
                                <td class="py-3 px-4 text-end">
                                    <button wire:click="confirmRestore({{ $user->id }})" class="btn btn-sm btn-success fw-bold shadow-sm">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i> RESGATAR
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-5 text-muted">
                                    <div class="bg-light d-inline-flex justify-content-center align-items-center rounded-circle mb-3" style="width: 80px; height: 80px;">
                                        <i class="bi bi-flower1 fs-1 text-success opacity-50"></i>
                                    </div>
                                    <h5 class="fw-bolder">Lixeira totalmente limpa</h5>
                                    <p class="small opacity-75 mb-0">Ninguém foi banido recentemente.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        {{ $users->links() }}
    </div>

    @if($confirmingRestoreId)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.6); backdrop-filter: blur(3px);">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
          <div class="modal-header border-bottom-0 pb-0">
            <h5 class="modal-title fw-bolder text-success"><i class="bi bi-person-up me-2"></i>Atenção: Resgate Imediato</h5>
            <button type="button" wire:click="cancelRestore" class="btn-close" aria-label="Close"></button>
          </div>
          <div class="modal-body p-4 text-center">
            <i class="bi bi-arrow-counterclockwise text-success opacity-25" style="font-size: 4rem;"></i>
            <p class="mt-3 mb-0 text-muted" style="font-size: 1.1rem;">
                Deseja confirmar a reativação desta Conta no portal? A Identidade voltará aos painéis regulares de uso com os mesmos privilégios antigos.
            </p>
          </div>
          <div class="modal-footer border-top-0 pt-0 justify-content-center gap-2 pb-4">
            <button type="button" wire:click="cancelRestore" class="btn btn-light fw-bold text-secondary px-4 border shadow-sm">Cancelar</button>
            <button type="button" wire:click="restoreUser" class="btn btn-success fw-bold shadow-sm px-4">
                <span wire:loading.remove wire:target="restoreUser"><i class="bi bi-check2-circle me-1"></i> Salvar e Resgatar</span>
                <span wire:loading wire:target="restoreUser"><span class="spinner-border spinner-border-sm me-2"></span>Restaurando...</span>
            </button>
          </div>
        </div>
      </div>
    </div>
    @endif
</div>
