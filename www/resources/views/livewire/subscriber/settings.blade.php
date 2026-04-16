<div class="container py-5">
    <div class="row">
        @include('layouts.partials.subscriber-sidebar')

        <div class="col-lg-9">
            
            <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                <i class="bi bi-gear-fill fs-2 text-primary me-3"></i>
                <div>
                    <h3 class="fw-bolder mb-1 text-dark" style="font-family: 'Outfit', sans-serif;">Configurações</h3>
                    <p class="text-muted small mb-0">Decisões drásticas e preferências do sistema (Zona de Perigo).</p>
                </div>
            </div>
            
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible bg-success bg-opacity-10 text-success border-success border-opacity-25 fade show rounded-3 p-3 mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                </div>
            @endif
            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3 p-3 mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                </div>
            @endif

            <!-- Bloco de Segurança Baseada em Risco -->
            <div class="card border border-secondary border-opacity-25 shadow-sm rounded-4 overflow-hidden mb-4 bg-white">
                <div class="card-header bg-light border-bottom-0 p-4 pb-0 text-dark">
                    <h6 class="fw-bold mb-0"><i class="bi bi-shield-lock-fill me-2 text-primary"></i>Segurança Militar (2FA)</h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-secondary small mb-1 fw-bold">Autenticação em Dois Fatores (Opt-In)</p>
                            <p class="text-muted small mb-0 w-75">Sempre que você realizar longin, enviaremos um PIN confidencial pro seu e-mail para barrar invasões à sua conta.</p>
                        </div>
                        <div class="form-check form-switch fs-3 m-0 text-end">
                            <input class="form-check-input shadow-none cursor-pointer" type="checkbox" role="switch" wire:model.live="twoFactorEnabled">
                        </div>
                    </div>

                    @if($twoFactorEnabled && $qrCodeSvg)
                        <div class="mt-4 pt-4 border-top border-light d-flex align-items-center gap-4">
                            <div class="bg-light p-3 rounded-3 shadow-sm d-inline-block">
                                {!! $qrCodeSvg !!}
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">Escaneie o Código com o Celular</h6>
                                <p class="small text-muted mb-2">Abra o <strong>Google Authenticator</strong> ou <strong>Authy</strong> e adicione esta conta utilizando a câmera.</p>
                                <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle-fill me-1"></i> Não Perca Acesso</span>
                            </div>
                        </div>

                        <div class="mt-4 border-top border-light pt-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-key-fill text-primary me-2"></i>Códigos de Backup (Fallback)</h6>
                                    <p class="small text-muted mb-0">Use-os caso você perca seu celular. Cada código só funciona 1 vez.</p>
                                </div>
                                <div class="d-flex gap-2">
                                    <button wire:click="emailRecoveryCodes" class="btn btn-sm btn-outline-secondary fw-bold" title="Enviar p/ E-mail">
                                        <i class="bi bi-envelope-fill mt-1"></i>
                                    </button>
                                    <button wire:click="downloadRecoveryCodes" class="btn btn-sm btn-danger fw-bold shadow-sm">
                                        <i class="bi bi-file-earmark-pdf-fill me-1"></i> PDF
                                    </button>
                                </div>
                            </div>
                            
                            <div class="row g-2">
                                @foreach($recoveryCodes as $code)
                                    <div class="col-6 col-md-3">
                                        <div class="bg-light border border-secondary border-opacity-25 rounded-3 px-2 py-2 text-center font-monospace fw-bolder small text-dark letter-spacing-1">
                                            {{ $code }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card border border-danger border-opacity-25 shadow-sm rounded-4 overflow-hidden mb-4 bg-white">
                <div class="card-header bg-danger bg-opacity-10 border-bottom-0 p-4 pb-0 text-danger">
                    <h6 class="fw-bold mb-0"><i class="bi bi-exclamation-triangle-fill me-2"></i>Zona de Cancelamento</h6>
                </div>
                <div class="card-body p-4">
                    <p class="text-secondary small mb-4">
                        Ao efetuar o encerramento da sua linha de assinatura, nossas integrações com as cobranças automatizadas do Asaas (PIX) são congeladas. Você perderá os seus privilégios de acesso assim que confirmar essa ação.
                    </p>
                    
                    @if(auth()->user()->subscription_status === \App\Enums\SubscriptionStatusEnum::ACTIVE->value)
                        
                        @if($confirmingDeletion)
                            <div class="p-4 bg-light border border-danger rounded-3 text-center">
                                <h6 class="text-danger fw-bolder">Tem certeza absoluta?</h6>
                                <p class="small text-muted mb-3">Esta ação invalidará seu passaporte VIP atual imediatamente.</p>
                                <div class="d-flex justify-content-center gap-2">
                                    <button wire:click="$set('confirmingDeletion', false)" class="btn btn-sm btn-secondary fw-bold px-3">Não, mudei de ideia</button>
                                    <button wire:click="cancelSubscription" class="btn btn-sm btn-danger fw-bold px-3 shadow"><i class="bi bi-x-circle me-1"></i> Sim, Quero Cancelar</button>
                                </div>
                            </div>
                        @else
                            <button wire:click="confirmCancelation" class="btn btn-outline-danger fw-bold px-4">
                                Interromper Assinatura Oficial (Sem Multa)
                            </button>
                        @endif
                        
                    @else
                        <div class="alert alert-secondary border bg-light text-muted small fw-semibold d-inline-block mb-0">
                            <i class="bi bi-info-circle me-2"></i>Sua matrícula atual já se encontra inativa ou sem liquidez.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
