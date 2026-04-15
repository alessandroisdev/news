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
                                    <button wire:click="restoreUser({{ $user->id }})" wire:confirm="Essa ação reativará totalmente o acesso desta Identidade no Portal. Autorizar?" class="btn btn-sm btn-success fw-bold shadow-sm">
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
</div>
