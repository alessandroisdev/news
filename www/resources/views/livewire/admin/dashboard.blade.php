<div>
    <h2 class="fw-bolder text-dark mb-4" style="font-family: 'Outfit', sans-serif;">Visão Geral do Workspace</h2>
    
    <div class="row g-4 mb-5">
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden" style="transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-5px)';" onmouseout="this.style.transform='translateY(0)';">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">
                            <i class="bi bi-newspaper fs-4"></i>
                        </div>
                        <span class="badge bg-primary px-3 py-2 rounded-pill">Total</span>
                    </div>
                    <h5 class="text-muted fw-semibold mb-1 fs-6">Arquivos Registrados</h5>
                    <h2 class="fw-bolder mb-0 display-6">{{ $totalNews }}</h2>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden" style="transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-5px)';" onmouseout="this.style.transform='translateY(0)';">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">
                            <i class="bi bi-check-circle-fill fs-4"></i>
                        </div>
                        <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-pill">No Ar</span>
                    </div>
                    <h5 class="text-muted fw-semibold mb-1 fs-6">Artigos Publicados</h5>
                    <h2 class="fw-bolder mb-0 display-6 text-success">{{ $publishedNews }}</h2>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden" style="transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-5px)';" onmouseout="this.style.transform='translateY(0)';">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">
                            <i class="bi bi-hourglass-split fs-4"></i>
                        </div>
                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill shadow-sm">Urgente</span>
                    </div>
                    <h5 class="text-muted fw-semibold mb-1 fs-6">Revisões Pendentes</h5>
                    <h2 class="fw-bolder mb-0 display-6">{{ $pendingReviews }}</h2>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden" style="background: linear-gradient(135deg, var(--portal-primary) 0%, var(--portal-secondary) 100%); transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-5px)';" onmouseout="this.style.transform='translateY(0)';">
                <div class="card-body p-4 text-white">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">
                            <i class="bi bi-tags-fill fs-4 text-white"></i>
                        </div>
                    </div>
                    <h5 class="text-white-50 fw-semibold mb-1 fs-6">Categorias Híbridas</h5>
                    <h2 class="fw-bolder mb-0 display-6 text-white">{{ $categories }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Seção Workspace Setup Analysis -->
    <div class="card border-0 shadow-sm rounded-4 mt-4">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
            <h5 class="fw-bold text-dark"><i class="bi bi-shield-check text-success me-2"></i>Análise do Workspace</h5>
        </div>
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-lg-8 border-end-lg">
                    <p class="text-muted mb-4 fs-5">A estrutura do portal está funcionando primorosamente através da interdição direta das Abilities Nativas.</p>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-light text-dark border p-2"><i class="bi bi-check-circle-fill text-success me-1"></i> Middleware de Injeção Isolada</span>
                        <span class="badge bg-light text-dark border p-2"><i class="bi bi-check-circle-fill text-success me-1"></i> Roteamento Assíncrono</span>
                        <span class="badge bg-light text-dark border p-2"><i class="bi bi-check-circle-fill text-success me-1"></i> Livewire UI Bindings</span>
                        <span class="badge bg-light text-dark border p-2"><i class="bi bi-check-circle-fill text-success me-1"></i> Proteção Sanctum XSRF</span>
                    </div>
                </div>
                <div class="col-lg-4 text-center mt-4 mt-lg-0">
                    <div class="p-3 bg-light rounded-4 border">
                        <span class="d-block small text-muted text-uppercase fw-bold mb-1">Seu Nível de Acesso Nativamente</span>
                        <h4 class="fw-bolder text-primary mb-0">{{ $userRole ?? 'ADMINISTRADOR' }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
