<div>
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <h3 class="fw-bolder text-dark mb-1" style="font-family: 'Outfit', sans-serif;">Usuários e Acessos</h3>
            <p class="text-muted small mb-0">Auditoria, promoção de colunistas e gestão de privilégios de todo o sistema.</p>
        </div>
    </div>
    
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible bg-success bg-opacity-10 text-success border-success border-opacity-25 fade show rounded-3" role="alert">
            <i class="bi bi-shield-check me-2"></i> {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
            <i class="bi bi-shield-x me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 h-100">
        <div class="card-header bg-white border-bottom-0 p-4 d-flex gap-3">
            <div class="input-group" style="max-width: 300px;">
                <span class="input-group-text bg-light border-0"><i class="bi bi-search text-muted"></i></span>
                <input wire:model.live.debounce.300ms="search" type="text" class="form-control bg-light border-0 shadow-none" placeholder="Buscar por Nome ou E-mail">
            </div>
            <select wire:model.live="roleFilter" class="form-select bg-light border-0 shadow-none w-auto">
                <option value="">Todos os Cargos</option>
                <option value="admin">Administradores</option>
                <option value="manager">Gestores</option>
                <option value="columnist">Colunistas</option>
                <option value="subscriber">Assinantes</option>
            </select>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4 border-bottom-0 text-secondary small text-uppercase fw-bold">Informações Básicas</th>
                            <th class="py-3 px-4 border-bottom-0 text-secondary small text-uppercase fw-bold">Privilégio (Role)</th>
                            <th class="py-3 px-4 border-bottom-0 text-secondary small text-uppercase fw-bold">Assinatura / Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td class="py-3 px-4">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm me-3" style="width: 36px; height: 36px; background-color: {{ $user->theme_color ?? '#adb5bd' }};">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $user->name }}</div>
                                            <div class="text-muted small">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <select wire:model.live="roleFilter" wire:change="updateRole({{ $user->id }}, $event.target.value)" class="form-select form-select-sm shadow-none {{ $user->role === 'admin' ? 'border-primary text-primary fw-bold' : 'border-light' }}" style="width: 150px;">
                                        <option value="subscriber" {{ $user->role === 'subscriber' ? 'selected' : '' }}>Usuário Base</option>
                                        <option value="columnist" {{ $user->role === 'columnist' ? 'selected' : '' }}>Colunista Estrat.</option>
                                        <option value="manager" {{ $user->role === 'manager' ? 'selected' : '' }}>Gestor Moderador</option>
                                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin Global</option>
                                    </select>
                                </td>
                                <td class="py-3 px-4">
                                    @if($user->subscription_status === 'active')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success"><i class="bi bi-star-fill me-1"></i> Ativo</span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-muted"><i class="bi bi-dash"></i> Inativo</span>
                                    @endif
                                    
                                    <div class="btn-group ms-3">
                                        <button class="btn btn-sm btn-light border hover-shadow" title="Bloquear Conta"><i class="bi bi-lock text-danger"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-5 text-muted">A base governamental de Identidades está vazia de filtros.</td>
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
