<div>
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <h3 class="fw-bolder text-dark mb-1" style="font-family: 'Outfit', sans-serif;">Usuários e Acessos</h3>
            <p class="text-muted small mb-0">Auditoria, promoção de colunistas e gestão de privilégios de todo o sistema.</p>
        </div>
        <button wire:click="create" class="btn btn-primary fw-bold shadow-sm d-flex align-items-center">
            <i class="bi bi-person-plus-fill me-2"></i> Adicionar Manualmente
        </button>
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

    @if($isCreating)
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-primary bg-opacity-10 border border-primary border-opacity-25">
        <div class="card-header bg-transparent border-bottom-0 p-4 pb-0">
            <h6 class="fw-bold text-primary mb-0"><i class="bi bi-person-plus-fill me-2"></i>Forjar Nova Identidade Oficial</h6>
        </div>
        <div class="card-body p-4">
            <form wire:submit.prevent="store">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-secondary text-uppercase small">Nome/Apelido Público</label>
                        <input type="text" wire:model="newName" class="form-control bg-white border-0 shadow-sm" placeholder="Ex: Jornalista Rápido">
                        @error('newName') <span class="text-danger small fw-bold">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-secondary text-uppercase small">E-mail de Login</label>
                        <input type="email" wire:model="newEmail" class="form-control bg-white border-0 shadow-sm" placeholder="email@vazamentonews.com">
                        @error('newEmail') <span class="text-danger small fw-bold">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-secondary text-uppercase small">Senha Temporária</label>
                        <input type="password" wire:model="newPassword" class="form-control bg-white border-0 shadow-sm" placeholder="Mínimo 6 chars">
                        @error('newPassword') <span class="text-danger small fw-bold">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-secondary text-uppercase small">Patente (Role)</label>
                        <select wire:model="newRole" class="form-select bg-white border-0 shadow-sm text-primary fw-bold">
                            <option value="subscriber">Assinante VIP</option>
                            <option value="columnist">Colunista / Escritor</option>
                            <option value="manager">Gestor de Conteúdo</option>
                            <option value="admin">Administrador Global</option>
                        </select>
                        @error('newRole') <span class="text-danger small fw-bold">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-12 mt-4 text-end border-top pt-3 border-primary border-opacity-10">
                        <button type="button" wire:click="cancelCreate" class="btn btn-light border text-danger fw-bold px-4 me-2">Cancelar</button>
                        <button type="submit" class="btn btn-primary fw-bold shadow px-5">Liberar Crachá (Criar)</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif

    @if($editingId)
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-bottom-0 p-4 pb-0">
            <h6 class="fw-bold text-primary mb-0"><i class="bi bi-pencil-square me-2"></i>Editar Identidade: {{ $name }}</h6>
        </div>
        <div class="card-body p-4">
            <form wire:submit.prevent="save">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-secondary text-uppercase small">Nome Público</label>
                        <input type="text" wire:model="name" class="form-control bg-light border-0 shadow-none">
                        @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-secondary text-uppercase small">E-mail Corporativo</label>
                        <input type="email" wire:model="email" class="form-control bg-light border-0 shadow-none">
                        @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-secondary text-uppercase small">Status Assinatura</label>
                        <select wire:model="subscription_status" class="form-select bg-light border-0 shadow-none">
                            <option value="active">Ativo (VIP)</option>
                            <option value="inactive">Inativo (Bloqueado/Sem plano)</option>
                        </select>
                        @error('subscription_status') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-12 mt-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-bold px-4">Salvar Alterações</button>
                        <button type="button" wire:click="cancelEdit" class="btn btn-light border text-danger fw-bold px-4">Cancelar</button>
                    </div>
                </div>
            </form>
        </div>
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
                            <tr wire:key="user-row-{{ $user->id }}-{{ $user->updated_at }}">
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
                                    <select wire:change="updateRole({{ $user->id }}, $event.target.value)" class="form-select form-select-sm shadow-none {{ $user->role === 'admin' ? 'border-primary text-primary fw-bold' : 'border-light' }}" style="width: 150px;">
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
                                    
                                    <div class="btn-group ms-3 shadow-sm">
                                        <button wire:click="edit({{ $user->id }})" class="btn btn-sm btn-light border text-primary position-relative" title="Editar Usuário">
                                            <span wire:loading.remove wire:target="edit({{ $user->id }})"><i class="bi bi-pencil-square"></i></span>
                                            <span wire:loading wire:target="edit({{ $user->id }})" class="spinner-border spinner-border-sm"></span>
                                        </button>
                                        <button wire:click="deleteUser({{ $user->id }})" wire:confirm="Tem absoluta certeza? Essa identidade será exterminada do banco." class="btn btn-sm btn-light border text-danger" title="Exterminar Conta"><i class="bi bi-trash3"></i></button>
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
