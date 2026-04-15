@extends('layouts.app')

@section('title', 'Home - Portal de Notícias')

@section('content')
<div class="container-fluid px-0">
    <!-- Hero Slider Automático -->
    <section class="hero-section text-white d-flex align-items-center justify-content-center position-relative overflow-hidden" style="background: linear-gradient(135deg, var(--portal-primary) 0%, var(--portal-secondary) 100%); min-height: 60vh;">
        <div class="text-center position-relative z-1">
            <h1 class="display-3 fw-bolder shadow-sm" style="font-family: 'Outfit', sans-serif;">O Portal Definitivo</h1>
            <p class="lead fw-light">A informação ágil, moderna e com a credibilidade que você merece.</p>
        </div>
        <!-- Efeito visual (Micro-interação background) -->
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(0,0,0,0) 70%);"></div>
    </section>

    <div class="container mt-5">
        <!-- Espaço Publicitário -->
        <div class="ad-space w-100 bg-light text-center py-4 mb-5 border rounded-3 position-relative overflow-hidden group" style="border-style: dashed !important; border-color: #ced4da;">
            <p class="text-muted mb-0 fw-semibold"><i class="bi bi-badge-ad me-2 text-primary"></i>Espaço Publicitário Premium (Hero Banner)</p>
        </div>

        <!-- Seção Dinâmica por Cookies/Meilisearch (Sugestões via Livewire) -->
        <div class="row g-5">
            <div class="col-12 col-lg-8">
                <h3 class="fw-bold mb-4 border-bottom border-warning pb-2" style="font-family: 'Outfit', sans-serif; border-bottom-width: 4px !important; display: inline-block;">Recomendado para Você</h3>
                
                <!-- Grid Notícias Inteligente -->
                <div class="row g-4">
                    <!-- Placeholder Card (Interativo) -->
                    <div class="col-md-6">
                        <a href="#" class="text-decoration-none">
                            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden" style="transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 20px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 .125rem .25rem rgba(0,0,0,.075)';">
                                <div class="bg-secondary p-5 text-center text-white d-flex align-items-center justify-content-center" style="height: 220px;">
                                    <i class="bi bi-image" style="font-size: 3rem; opacity: 0.5;"></i>
                                </div>
                                <div class="card-body">
                                    <span class="badge bg-primary mb-3 px-3 py-2 rounded-pill">Arquitetura</span>
                                    <h5 class="card-title fw-bold text-dark">Portal arquitetado com Meilisearch e Octane entra no ar</h5>
                                    <p class="card-text text-muted small mt-2">Um sistema escalável, sem starter kits complexos, utilizando Laravel 12 nativo, permitindo resiliência em falhas e consultas semânticas poderosas.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-lg-4">
                <h3 class="fw-bold mb-4 border-bottom border-primary pb-2" style="font-family: 'Outfit', sans-serif; border-bottom-width: 4px !important; display: inline-block;">Em Alta</h3>
                <div class="d-flex flex-column gap-3">
                    <a href="#" class="text-decoration-none p-3 bg-white shadow-sm rounded-3" style="transition: all 0.2s; border-left: 5px solid var(--portal-primary);" onmouseover="this.style.transform='translateX(5px)'; " onmouseout="this.style.transform='translateX(0)';">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="fw-bold text-primary mb-1">Engenharia de Software</h6>
                                <p class="mb-0 small text-dark fw-semibold">Nova máquina de estado (State Machine) substitui lógicas obscuras.</p>
                            </div>
                            <span class="badge bg-light text-muted border">#1</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
