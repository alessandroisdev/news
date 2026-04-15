<div class="container py-5">

    <div class="row">
        <!-- Sidebar Navigation (Específica do Assinante VIP) -->
        @include('layouts.partials.subscriber-sidebar')

        <div class="col-lg-9">
            
            @if (session()->has('message'))
                <div class="alert alert-success alert-dismissible bg-success bg-opacity-10 text-success border-success border-opacity-25 fade show rounded-3" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i> {{ session('message') }}
                </div>
            @endif

            <div class="row mb-5 align-items-center bg-white p-4 p-md-5 rounded-4 shadow-sm border position-relative overflow-hidden z-1 ms-lg-1">
                <div class="position-absolute end-0 top-0 text-light opacity-50 pe-none z-n1" style="transform: translate(20%, -30%);">
                    <i class="bi bi-award-fill" style="font-size: 30rem;"></i>
                </div>

                <div class="col-12">
            @if($isActive)
                <div class="d-inline-flex align-items-center bg-warning bg-opacity-25 text-dark mb-3 px-3 py-2 rounded-pill fw-bolder border border-warning shadow-sm" style="letter-spacing: 1.5px; font-size: 0.8rem;">
                    <i class="bi bi-star-fill text-warning me-2"></i> ASSINATURA ATIVA (MENSAL)
                </div>
            @else
                <div class="d-inline-flex align-items-center bg-danger bg-opacity-10 text-danger mb-3 px-3 py-2 rounded-pill fw-bolder border border-danger shadow-sm" style="letter-spacing: 1.5px; font-size: 0.8rem;">
                    <i class="bi bi-lock-fill me-2"></i> ASSINATURA PENDENTE OU VENCIDA
                </div>
            @endif

            <h1 class="fw-bolder display-5 text-dark mb-3 lh-sm" style="font-family: 'Outfit', sans-serif;">
                Bem-vindo à área Premium,<br><span class="text-primary">{{ explode(' ', $user->name)[0] }}</span>!
            </h1>
            <p class="lead text-muted mb-0 fw-normal col-md-10">Esta área não contém anúncios. Consuma informações exclusivas.</p>
        </div>
    </div>

    <!-- Livewire Paywall Condicional -->
    @if(!$isActive)
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-5 border-top border-primary p-2 p-md-4" style="border-top-width: 5px !important;">
            <div class="card-body text-center p-5">
                <i class="bi bi-shield-lock text-primary opacity-50 d-block mb-3" style="font-size: 4rem;"></i>
                <h3 class="fw-bolder text-dark mb-3" style="font-family: 'Outfit', sans-serif;">Acesso Restrito: Paywall Asaas</h3>
                <p class="text-muted mb-5 col-md-8 mx-auto lead">Sua conta não possui uma assinatura vitalícia operante. Geração imediata em ambiente seguro e criptografado de nossa integração com a API Core do Asaas.</p>
                
                @if(!$activePayment)
                    <button wire:click="generateSubscription" class="btn btn-primary btn-lg fw-bold px-5 shadow-sm rounded-pill" style="transition: all 0.2s;">
                        <span wire:loading.remove wire:target="generateSubscription"><i class="bi bi-qr-code-scan me-2"></i>Gerar Fatura PIX (R$ 29,90)</span>
                        <span wire:loading wire:target="generateSubscription"><span class="spinner-border spinner-border-sm me-2"></span>Conectando ao Asaas...</span>
                    </button>
                @else
                    <div class="bg-light p-4 rounded-4 border d-inline-block mx-auto text-start w-100" style="max-width: 450px;">
                        <span class="badge bg-primary mb-3">#{{ $activePayment->asaas_payment_id }}</span>
                        <h5 class="fw-bolder mb-2 text-dark">Escaneie o QR Code</h5>
                        <p class="small text-muted mb-4">Abra o App do seu banco para ler o QR Code abaixo ou utilize a cópia da chave.</p>
                        
                        <div class="bg-white border rounded text-center p-3 mb-4">
                            <i class="bi bi-qr-code text-dark opacity-75" style="font-size: 8rem;"></i>
                        </div>
                        
                        <div class="input-group mb-4">
                            <input type="text" class="form-control" value="{{ $activePayment->pix_copy_paste }}" readonly>
                            <button class="btn btn-secondary border"><i class="bi bi-clipboard"></i> Copiar</button>
                        </div>
                        
                        <!-- BOTÃO MOCK PRA AGILIZAR TESTE MANUAL DE VCS NA TELA SEM TER DE USAR POSTMAN -->
                        <div class="text-center border-top pt-4">
                            <small class="text-muted d-block mb-2">Ação especial (Admin Local Mode):</small>
                            <button wire:click="simulatePaymentWebhook" class="btn btn-sm btn-outline-success fw-bold">
                                <span wire:loading.remove wire:target="simulatePaymentWebhook"> <i class="bi bi-check-circle me-1"></i> Forçar Webhook 'Aprovado' Agora</span>
                                <span wire:loading wire:target="simulatePaymentWebhook"><span class="spinner-border spinner-border-sm me-1"></span> Liquindando fatura...</span>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @else
        <!-- Conteúdo Fechado VIP -->
        <div class="d-flex align-items-center mb-4">
            <h4 class="fw-bold mb-0 text-dark" style="font-family: 'Outfit', sans-serif;"><i class="bi bi-gem text-primary me-2"></i>Curadoria Especial (Semana)</h4>
            <hr class="flex-grow-1 ms-3 border-secondary opacity-25">
        </div>
        
        <div class="row g-4 mb-5">
            @forelse($premiumNews as $news)
                <div class="col-md-6 col-xl-4">
                    <div class="card h-100 border-0 bg-white rounded-4 overflow-hidden text-decoration-none position-relative" style="transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-5px)';" onmouseout="this.style.transform='translateY(0)';">
                        <a href="/{{ $news->slug }}" class="text-decoration-none d-block h-100 d-flex flex-column text-dark">
                            <div style="height: 220px; background: url('/images/{{ $news->slug }}/500/300') center/cover no-repeat;" class="bg-light w-100 border-bottom"></div>
                            
                            <div class="position-absolute top-0 end-0 p-3 z-2">
                                <span class="badge bg-dark bg-opacity-75 text-white shadow backdrop-blur px-3 py-2 rounded-pill border border-light border-opacity-25"><i class="bi bi-lock-fill text-warning me-1"></i> Exclusivo</span>
                            </div>

                            <div class="card-body p-4 p-xl-5 d-flex flex-column flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <small class="text-primary fw-bolder text-uppercase" style="font-size: 0.75rem; letter-spacing: 1.5px;">
                                        <span class="d-inline-block rounded-circle me-1" style="width: 8px; height: 8px; background-color: var(--portal-primary);"></span>
                                        {{ $news->category->name }}
                                    </small>
                                </div>
                                <h5 class="fw-bolder lh-sm mb-3 font-outfit" style="font-size: 1.25rem;">{{ $news->title }}</h5>
                                <div class="mt-auto d-flex align-items-center gap-3 pt-3 border-top border-light">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bolder" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                        {{ substr($news->author->name, 0, 1) }}
                                    </div>
                                    <div class="lh-sm">
                                        <div class="small fw-semibold text-dark">{{ $news->author->name }}</div>
                                        <div class="text-muted" style="font-size: 0.7rem;">{{ $news->published_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-12 py-5 text-center text-muted">
                    <div class="d-inline-flex bg-white rounded-circle shadow-sm p-4 mb-3 border">
                        <i class="bi bi-inbox fs-1 text-primary opacity-50"></i>
                    </div>
                    <h5 class="fw-bolder">Nenhuma atualização no plano.</h5>
                    <p class="small opacity-75">Nossa redação trabalhará nesta semana sobre artigos longos VIP.</p>
                </div>
            @endforelse
        @endif
        
        </div> <!-- End of col-lg-9 -->
    </div> <!-- End of row -->
</div>
