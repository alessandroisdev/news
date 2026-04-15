@extends('layouts.app')

@section('title', 'Coluna de ' . $columnist->name)

@section('dynamic_css_vars')
    @if($columnist->theme_color)
    <style>
        :root {
            --portal-primary: {{ $columnist->theme_color }} !important;
        }
    </style>
    @endif
@endsection

@section('content')
<div class="container-fluid px-0">
    <!-- Header Personalizado do Escritor -->
    <section class="columnist-header py-5 text-center text-white shadow-sm position-relative overflow-hidden" style="background-color: var(--portal-primary);">
        <div class="container position-relative z-1 mt-4">
            <div class="mb-4 d-inline-block p-1 bg-white rounded-circle shadow-lg" style="transition: transform 0.3s; cursor:pointer;" onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">
                <!-- Imagem Redimensionada Automaticamente pelo Media Controller com Crop Inteligente de 180x180 -->
                <img src="/images/{{ $columnist->avatar ?? 'default.jpg' }}/180x180" class="rounded-circle object-fit-cover" width="180" height="180" alt="{{ $columnist->name }}">
            </div>
            <h1 class="display-4 fw-bolder mb-2" style="font-family: 'Outfit', sans-serif;">{{ $columnist->name }}</h1>
            @if($columnist->bio)
            <p class="lead w-75 mx-auto fw-light mt-2" style="opacity: 0.9;">{{ $columnist->bio }}</p>
            @endif
            
            @if($columnist->social_links)
            <div class="mt-4 fs-4 d-flex justify-content-center gap-4">
                {{-- Aqui decodificaremos o links em JSON --}}
                <a href="#" class="text-white text-decoration-none opacity-75 hover-opacity-100"><i class="bi bi-twitter-x"></i></a>
                <a href="#" class="text-white text-decoration-none opacity-75 hover-opacity-100"><i class="bi bi-instagram"></i></a>
            </div>
            @endif
        </div>
        <div class="position-absolute w-100 h-100 top-0 start-0 opacity-25" style="background: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </section>

    <div class="container mt-5">
        <h3 class="fw-bold mb-4 border-bottom border-primary pb-2" style="font-family: 'Outfit', sans-serif; border-bottom-width: 4px !important; display: inline-block;">Últimos Artigos</h3>
        <p class="text-muted">Explore todos os pensamentos e noticias escritas por {{ $columnist->name }}.</p>
        <livewire:frontend.columnist-news-grid :columnist="$columnist" />
    </div>
</div>
@endsection
