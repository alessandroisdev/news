<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 fw-bolder"><i class="bi bi-shield-check me-2 text-primary"></i> Moderação de Comentários</h1>
            <p class="text-muted mt-1">
                @if(auth()->user()->role === \App\Enums\UserRoleEnum::COLUMNIST->value)
                    Monitore e audite as opiniões feitas exclusivamente nas suas publicações.
                @else
                    Central Tática Anti-Troll e moderação global do Portal.
                @endif
            </p>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible bg-success bg-opacity-10 text-success border-0 fade show shadow-sm">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible bg-danger bg-opacity-10 text-danger border-0 fade show shadow-sm">
            <i class="bi bi-x-circle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
            <ul class="nav nav-pills text-uppercase" style="font-size: 0.85rem; font-weight: bold;">
                <li class="nav-item me-2">
                    <button wire:click="$set('filter', 'all')" class="nav-link border px-3 {{ $filter === 'all' ? 'active shadow-sm' : 'text-dark bg-light' }}">Todos</button>
                </li>
                <li class="nav-item me-2">
                    <button wire:click="$set('filter', 'shadowbanned')" class="nav-link border border-warning px-3 {{ $filter === 'shadowbanned' ? 'active bg-warning text-dark shadow-sm' : 'text-warning bg-light' }}"><i class="bi bi-robot me-1"></i> Barrados pelo Robô</button>
                </li>
                <li class="nav-item">
                    <button wire:click="$set('filter', 'hidden')" class="nav-link border border-danger px-3 {{ $filter === 'hidden' ? 'active bg-danger shadow-sm text-white' : 'text-danger bg-light' }}"><i class="bi bi-eye-slash-fill me-1"></i> Ocultados Manualmente</button>
                </li>
            </ul>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase fw-bolder text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    <tr>
                        <th class="ps-4">Autor / Strike</th>
                        <th>Comentário</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Triagem (Ação)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($comments as $comment)
                    <tr>
                        <td class="ps-4" style="width: 25%;">
                            <div class="d-flex align-items-center">
                                <img src="/images/{{ $comment->user->avatar ?? 'default.jpg' }}/35x35" class="rounded-circle me-3" alt="">
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark">{{ $comment->user->name }}</h6>
                                    <span class="text-muted" style="font-size: 0.75rem;">
                                        Strikes: <span class="{{ $comment->user->comment_strikes >= 3 ? 'text-danger fw-bold' : 'text-warning' }}">{{ $comment->user->comment_strikes }}/3</span>
                                    </span>
                                    @if($comment->user->banned_from_comments)
                                        <span class="badge bg-danger ms-1" style="font-size: 0.6rem;">AMORDAÇADO</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td style="width: 45%;">
                            <div class="mb-1 text-muted" style="font-size: 0.75rem;">
                                <i class="bi bi-newspaper"></i> Em: <a href="/{{ $comment->news->slug }}" target="_blank" class="fw-semibold text-primary">{{ \Illuminate\Support\Str::limit($comment->news->title, 40) }}</a>
                            </div>
                            <div class="p-2 bg-light rounded-3 text-dark text-break" style="font-size: 0.9rem; line-height: 1.4;">
                                {{ $comment->content }}
                            </div>
                            @if($comment->shadowban_reason)
                                <div class="mt-1 text-danger fw-bold" style="font-size: 0.70rem;"><i class="bi bi-exclamation-triangle"></i> {{ $comment->shadowban_reason }}</div>
                            @endif
                        </td>
                        <td>
                            @if($comment->is_hidden)
                                <span class="badge bg-dark">Ocultado Manualmente</span>
                            @elseif($comment->is_shadowbanned)
                                <span class="badge bg-warning text-dark border border-warning shadow-sm"><i class="bi bi-eye-slash-fill me-1"></i>ShadowBanned</span>
                            @else
                                <span class="badge bg-success bg-opacity-10 text-success border"><i class="bi bi-check-circle-fill me-1"></i>Público</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <!-- Ações Rápidas Dropdown -->
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light border dropdown-toggle fw-bold" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Ações
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                    @if($comment->is_shadowbanned || $comment->is_hidden)
                                        <li><button wire:click="approveComment({{ $comment->id }})" class="dropdown-item text-success fw-bold"><i class="bi bi-check-circle me-2"></i> Aprovar / Restaurar</button></li>
                                    @else
                                        <li><button wire:click="hideComment({{ $comment->id }})" class="dropdown-item text-warning fw-bold"><i class="bi bi-eye-slash me-2"></i> Ocultar do Público</button></li>
                                    @endif
                                    
                                    <li><hr class="dropdown-divider"></li>
                                    
                                    <li><button wire:click="deleteComment({{ $comment->id }})" class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i> Excluir Comentário</button></li>
                                    
                                    <li><hr class="dropdown-divider"></li>
                                    
                                    @if($comment->user->banned_from_comments)
                                        <li><button wire:click="unbanUser({{ $comment->user_id }})" class="dropdown-item text-info bg-light"><i class="bi bi-unlock-fill me-2"></i> Perdoar Usuário</button></li>
                                    @else
                                        <li><button wire:click="banUser({{ $comment->user_id }})" class="dropdown-item text-white bg-danger"><i class="bi bi-shield-lock-fill me-2"></i> Amordaçar Conta (Banir)</button></li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="bi bi-clipboard-check fs-1 text-light"></i>
                            <h5 class="mt-3">Nenhum comentário na fila.</h5>
                            <p class="mb-0 small">A área de moderação está limpa.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white border-top p-3">
            {{ $comments->links() }}
        </div>
    </div>
</div>
