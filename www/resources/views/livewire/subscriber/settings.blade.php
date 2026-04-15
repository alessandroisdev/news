<div class="container py-5">
    <div class="row">
        @include('layouts.partials.subscriber-sidebar')

        <div class="col-lg-9 ms-lg-1">
            
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
