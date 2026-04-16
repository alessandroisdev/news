<div>
    <div class="container py-5 mt-4">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h1 class="fw-bolder display-5" style="font-family: 'Outfit', sans-serif;">Fale com a Redação</h1>
                <p class="text-muted lead">Tem uma denúncia, sugestão de pauta ou dúvida? Envie pra nós.</p>
            </div>
        </div>

        <div class="row g-5">
            <!-- Informações Institucionais do BD -->
            <div class="col-md-4">
                <div class="bg-light p-4 rounded-4 h-100 border-0 shadow-sm">
                    <h4 class="fw-bold mb-4">Informações de Contato</h4>
                    
                    @if($site_address)
                    <div class="d-flex mb-4">
                        <div class="text-primary me-3 fs-3"><i class="bi bi-geo-alt-fill"></i></div>
                        <div>
                            <h6 class="fw-bold mb-1">Endereço</h6>
                            <p class="text-muted mb-0">{{ $site_address }}</p>
                        </div>
                    </div>
                    @endif

                    @if($site_phone)
                    <div class="d-flex mb-4">
                        <div class="text-primary me-3 fs-3"><i class="bi bi-telephone-fill"></i></div>
                        <div>
                            <h6 class="fw-bold mb-1">Telefone / WhatsApp</h6>
                            <p class="text-muted mb-0">{{ $site_phone }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="d-flex mb-4">
                        <div class="text-primary me-3 fs-3"><i class="bi bi-envelope-fill"></i></div>
                        <div>
                            <h6 class="fw-bold mb-1">Central de Atendimento</h6>
                            <p class="text-muted mb-0">via formulário direto</p>
                        </div>
                    </div>

                    <hr class="my-4 text-muted">
                    
                    <h6 class="fw-bold mb-3">Nossas Redes</h6>
                    <div class="d-flex gap-2">
                        @if($site_facebook)
                        <a href="{{ $site_facebook }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-circle" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;"><i class="bi bi-facebook"></i></a>
                        @endif
                        @if($site_instagram)
                        <a href="{{ $site_instagram }}" target="_blank" class="btn btn-outline-danger btn-sm rounded-circle" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;"><i class="bi bi-instagram"></i></a>
                        @endif
                        @if($site_twitter)
                        <a href="{{ $site_twitter }}" target="_blank" class="btn btn-outline-dark btn-sm rounded-circle" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;"><i class="bi bi-twitter-x"></i></a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Fomulário Interativo Seguro -->
            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-4 p-md-5">
                        @if($successMessage)
                            <div class="text-center py-5">
                                <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                                <h3 class="fw-bold mt-4">Mensagem Recebida!</h3>
                                <p class="text-muted">Nossa equipe de redação lerá seu contato em breve. Agradecemos a colaboração com nosso jornalismo independente!</p>
                                <button wire:click="$set('successMessage', false)" class="btn btn-primary mt-3 px-4 rounded-pill">Enviar nova mensagem</button>
                            </div>
                        @else
                            <form wire:submit.prevent="submit">
                                
                                @error('submit')
                                    <div class="alert alert-danger bg-danger bg-opacity-10 text-danger border-0 rounded-3 mb-4">
                                        <i class="bi bi-shield-lock-fill me-2"></i> {{ $message }}
                                    </div>
                                @enderror

                                <div class="row g-3">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Seu Nome Completo *</label>
                                        <input wire:model="name" type="text" class="form-control bg-light border-0 py-2 @error('name') is-invalid @enderror">
                                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- Campo Honeypot Oculto (Anti-Bot Pattern) -->
                                    <div class="col-md-6 mb-3" style="display:none !important; position:absolute; left:-9999px;">
                                        <label class="form-label">Sobrenome</label>
                                        <input wire:model="last_name" type="text" class="form-control" tabindex="-1" autocomplete="off">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Seu E-mail *</label>
                                        <input wire:model="email" type="email" class="form-control bg-light border-0 py-2 @error('email') is-invalid @enderror">
                                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label class="form-label fw-semibold">Assunto</label>
                                        <input wire:model="subject" type="text" class="form-control bg-light border-0 py-2 @error('subject') is-invalid @enderror" placeholder="Denúncia, Dúvida ou Sugestão de Pauta...">
                                        @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-12 mb-4">
                                        <label class="form-label fw-semibold">Sua Mensagem *</label>
                                        <textarea wire:model="message" class="form-control bg-light border-0 py-2 @error('message') is-invalid @enderror" rows="5" placeholder="Escreva os detalhes com o máximo de informações possível..."></textarea>
                                        @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold rounded-3 shadow-sm py-3 d-flex justify-content-center align-items-center">
                                            <span wire:loading.remove wire:target="submit"><i class="bi bi-send-fill me-2"></i> Enviar Mensagem Segura</span>
                                            <span wire:loading wire:target="submit">
                                                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Processando via Secure Server...
                                            </span>
                                        </button>
                                        <p class="text-center text-muted small mt-3 mb-0"><i class="bi bi-shield-check text-success me-1"></i> Seus dados estão protegidos por criptografia de ponta a ponta e auditoria Anti-Spam.</p>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
