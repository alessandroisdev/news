<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 fw-bolder"><i class="bi bi-inbox-fill me-2 text-primary"></i> Caixa de Entrada</h1>
            <p class="text-muted mt-1">Gerenciamento de contatos enviados pelos leitores do Portal</p>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm">
            <i class="bi bi-info-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Lista de Mensagens -->
        <div class="col-md-{{ $viewingMessage ? '5' : '12' }}">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom p-3">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input wire:model.live.debounce.300ms="search" type="text" class="form-control bg-light border-start-0" placeholder="Buscar por remetente, email ou assunto...">
                    </div>
                </div>
                <div class="list-group list-group-flush" style="max-height: 600px; overflow-y: auto;">
                    @forelse($messages as $msg)
                        <button wire:click="viewMessage({{ $msg->id }})" class="list-group-item list-group-item-action p-3 {{ $viewingMessage && $viewingMessage->id === $msg->id ? 'bg-primary bg-opacity-10 border-primary border-start border-5' : '' }} {{ !$msg->is_read ? 'bg-light' : '' }}">
                            <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                <h6 class="mb-0 {{ !$msg->is_read ? 'fw-bold text-dark' : 'text-secondary' }} text-truncate" style="max-width: 70%;">{{ $msg->name }}</h6>
                                <small class="text-muted" style="font-size: 0.75rem;">{{ $msg->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <p class="mb-0 text-truncate text-muted small" style="max-width: 80%;">
                                    @if(!$msg->is_read)<span class="badge bg-danger rounded-circle p-1 me-1" style="width:8px;height:8px;"><span class="visually-hidden">Nova</span></span>@endif
                                    {{ $msg->subject ?? 'Sem Assunto' }}
                                </p>
                            </div>
                        </button>
                    @empty
                        <div class="p-5 text-center text-muted">    
                            <i class="bi bi-mailbox fs-1 text-light"></i>
                            <p class="mt-3">A caixa de entrada está vazia.</p>
                        </div>
                    @endforelse
                </div>
                <div class="card-footer bg-white p-3 border-top">
                    {{ $messages->links() }}
                </div>
            </div>
        </div>

        <!-- Leitor de Mensagem (View Panel) -->
        @if($viewingMessage)
        <div class="col-md-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 fw-bold text-dark">{{ $viewingMessage->subject ?? 'Sem Assunto' }}</h4>
                        <div class="d-flex align-items-center text-muted small">
                            <span class="fw-semibold text-dark me-2">{{ $viewingMessage->name }}</span> 
                            <span>&lt;{{ $viewingMessage->email }}&gt;</span>
                            <span class="mx-2">•</span>
                            <span>{{ $viewingMessage->created_at->format('d/m/Y H:i') }}</span>
                            <span class="mx-2">•</span>
                            <span>IP: {{ $viewingMessage->ip_address ?? 'Oculto' }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4" style="font-size: 1.05rem; line-height: 1.6; white-space: pre-wrap;">{{ $viewingMessage->message }}</div>
                <div class="card-footer bg-light p-3 border-top d-flex justify-content-between rounded-bottom-4">
                    <button wire:click="closeMessage" class="btn btn-outline-secondary"><i class="bi bi-x-circle me-1"></i> Fechar Leitor</button>
                    <div>
                        <a href="mailto:{{ $viewingMessage->email }}" class="btn btn-primary me-2"><i class="bi bi-reply-fill me-1"></i> Responder Email</a>
                        <button wire:click="deleteMessage({{ $viewingMessage->id }})" class="btn btn-outline-danger"><i class="bi bi-trash-fill"></i></button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
