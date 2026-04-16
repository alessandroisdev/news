<div>
    <div class="comments-section bg-white p-4 rounded-4 shadow-sm border mt-4">
        <h4 class="fw-bold mb-4" style="font-family: 'Outfit', sans-serif;"><i class="bi bi-chat-left-text-fill text-primary me-2"></i> {{ $comments->count() }} Comentários</h4>

        @if(session()->has('error'))
            <div class="alert alert-danger bg-danger bg-opacity-10 text-danger border-0 rounded-3 mb-4">
                <i class="bi bi-x-circle-fill me-2"></i> {{ session('error') }}
            </div>
        @endif

        @if(session()->has('success_comment'))
            <div class="alert alert-success bg-success bg-opacity-10 text-success border-0 py-2 rounded-3 mb-4 fade show">
                <i class="bi bi-check-circle-fill me-1"></i> Comentário adicionado.
            </div>
        @endif

        @auth
            @if(!auth()->user()->banned_from_comments)
                <div class="d-flex mb-5 pb-4 border-bottom">
                    <img src="/images/{{ auth()->user()->avatar ?? 'default.jpg' }}/45x45" class="rounded-circle me-3 border shadow-sm" style="width:45px; height:45px; object-fit:cover;" alt="Avatar">
                    <div class="w-100 flex-grow-1">
                        <textarea wire:model="newComment" class="form-control bg-light border-0 mb-2" rows="3" placeholder="O que você achou dessa notícia? Participe da discussão..."></textarea>
                        <div class="d-flex justify-content-end">
                            <button wire:click="postComment" class="btn btn-primary fw-bold px-4 rounded-pill">
                                <span wire:loading.remove wire:target="postComment">Comentar</span>
                                <span wire:loading wire:target="postComment">Enviando...</span>
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-dark bg-dark text-white border-0 rounded-3 mb-5 text-center p-4">
                    <i class="bi bi-shield-slash-fill fs-2 text-danger mb-2"></i>
                    <h5 class="fw-bold">Comentários Desativados</h5>
                    <p class="mb-0 text-white-50">Sua conta perdeu o privilégio de interagir no portal devido a violações graves de nossas Políticas de Comunidade.</p>
                </div>
            @endif
        @else
            <div class="alert bg-light border text-center p-4 rounded-4 mb-5">
                <h6 class="fw-bold text-dark">Participe da conversa!</h6>
                <p class="text-muted small mb-3">Você precisa estar logado para comentar nas notícias.</p>
                <a href="{{ route('login') }}" class="btn btn-outline-primary fw-bold rounded-pill px-4">Fazer Login ou Assinar</a>
            </div>
        @endauth

        <!-- Lista de Comentários Root -->
        <div class="comments-list">
            @forelse($comments as $comment)
                <!-- Componente Individual (Pai) -->
                <div class="comment-item d-flex mb-4">
                    <img src="/images/{{ $comment->user->avatar ?? 'default.jpg' }}/45x45" class="rounded-circle me-3 border" style="width:45px; height:45px; object-fit:cover;" alt="">
                    <div class="w-100">
                        <div class="bg-light p-3 rounded-4 {{ $comment->is_shadowbanned ? 'border border-warning border-opacity-50' : '' }}">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="fw-bold mb-0 text-dark d-flex align-items-center">
                                    {{ $comment->user->name }}
                                    @if(in_array($comment->user->role, [\App\Enums\UserRoleEnum::ADMIN->value, \App\Enums\UserRoleEnum::COLUMNIST->value, \App\Enums\UserRoleEnum::MANAGER->value]))
                                        <span class="badge bg-warning text-dark ms-2 rounded-pill" style="font-size: 0.65rem;">Autor(a)</span>
                                    @endif
                                </h6>
                                <small class="text-muted" style="font-size:0.75rem;">{{ $comment->created_at->diffForHumans() }}</small>
                            </div>
                            
                            @if($comment->is_shadowbanned)
                                <div class="badge bg-warning text-dark mb-2 px-2 py-1 shadow-sm"><i class="bi bi-exclamation-triangle-fill"></i> Seu comentário acionou nossos filtros. Evite utilizar linguagem ofensiva (Strike emitido).</div>
                            @endif

                            <p class="mb-0 text-dark" style="font-size: 0.95rem; line-height: 1.5;">{{ $comment->content }}</p>
                        </div>
                        
                        <div class="mt-1 ps-2">
                            @auth
                                @if(!auth()->user()->banned_from_comments)
                                    <button wire:click="setReplyingTo({{ $comment->id }})" class="btn btn-link text-decoration-none text-muted p-0 small fw-semibold me-3"><i class="bi bi-reply-fill"></i> Responder</button>
                                @endif
                            @endauth
                        </div>

                        <!-- Form de Resposta -->
                        @if($replyingTo === $comment->id)
                            <div class="d-flex mt-3 bg-white p-3 rounded-3 border">
                                <img src="/images/{{ auth()->user()->avatar ?? 'default.jpg' }}/32x32" class="rounded-circle me-2" style="width:32px; height:32px; object-fit:cover;" alt="">
                                <div class="w-100">
                                    <textarea wire:model="replyComment" class="form-control bg-light border-0 mb-2 p-2 fs-6" rows="2" placeholder="Escreva uma resposta..."></textarea>
                                    <div class="d-flex justify-content-end gap-2">
                                        <button wire:click="cancelReply" class="btn btn-sm btn-outline-secondary rounded-pill">Cancelar</button>
                                        <button wire:click="postReply({{ $comment->id }})" class="btn btn-sm btn-primary rounded-pill px-3">Responder</button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Respostas Filhas (Threads) -->
                        @if($comment->replies->count() > 0)
                            <div class="replies mt-3 ps-2 ms-3 border-start border-2">
                                @foreach($comment->replies as $reply)
                                    <div class="d-flex mb-3 ms-3">
                                        <img src="/images/{{ $reply->user->avatar ?? 'default.jpg' }}/32x32" class="rounded-circle me-2 border" style="width:32px; height:32px; object-fit:cover;" alt="">
                                        <div class="w-100">
                                            <div class="bg-light p-2 px-3 rounded-4 {{ $reply->is_shadowbanned ? 'border border-warning border-opacity-50' : '' }}">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.85rem;">
                                                        {{ $reply->user->name }}
                                                        @if(in_array($reply->user->role, [\App\Enums\UserRoleEnum::ADMIN->value, \App\Enums\UserRoleEnum::COLUMNIST->value, \App\Enums\UserRoleEnum::MANAGER->value]))
                                                            <span class="badge bg-warning text-dark ms-2 rounded-pill" style="font-size: 0.6rem;">Autor(a)</span>
                                                        @endif
                                                    </h6>
                                                    <small class="text-muted" style="font-size:0.7rem;">{{ $reply->created_at->diffForHumans() }}</small>
                                                </div>
                                                
                                                @if($reply->is_shadowbanned)
                                                    <div class="badge bg-warning text-dark mb-1 d-inline-block px-1" style="font-size:0.65rem;"><i class="bi bi-exclamation-triangle-fill"></i> Aviso de Filtro. Strike Acionado.</div>
                                                @endif
                                                
                                                <p class="mb-0 text-dark text-break" style="font-size: 0.90rem;">{{ $reply->content }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center p-5 border rounded-4 bg-light text-muted">
                    <i class="bi bi-chat-square-dots fs-1"></i>
                    <p class="mt-2 text-dark fw-bold">Ninguém comentou ainda.</p>
                    <p class="small">Seja o primeiro a compartilhar sua opinião sobre esta matéria!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
