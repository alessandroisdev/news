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

        <!-- Seção Dinâmica e Real Baseada no Banco -->
        <livewire:frontend.home />
    </div>
</div>
@endsection
