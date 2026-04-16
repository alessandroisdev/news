@extends('layouts.app')

@section('title', 'Home - Portal de Notícias')

@section('content')
<div class="container-fluid px-0">
    <!-- Hero Slider Inteligente (Swiper + Profiling) -->
    <livewire:frontend.hero-slider />

    <div class="container mt-5">
        <!-- Seção Dinâmica e Real Baseada no Banco -->
        <livewire:frontend.home />
    </div>
    </div>
</div>
@endsection
