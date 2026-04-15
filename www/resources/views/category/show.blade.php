@extends('layouts.app')

@section('title', $category->name . ' - Portal de Notícias')

{{-- Injetando dinamicamente a cor tema da categoria ao layout mestre --}}
@section('dynamic_css_vars')
    @if($category->theme_color)
    <style>
        :root {
            --portal-primary: {{ $category->theme_color }} !important;
        }
    </style>
    @endif
@endsection

@section('content')
<div class="container-fluid px-0">
    <!-- Banner Header da Categoria -->
    <section class="category-header text-white d-flex align-items-end position-relative pb-5 shadow-sm" style="background-color: var(--portal-primary); min-height: 35vh;">
        <div class="container position-relative z-1 mt-auto">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="/" class="text-white-50 text-decoration-none">Início</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">{{ $category->name }}</li>
              </ol>
            </nav>
            <h1 class="display-3 fw-bolder" style="font-family: 'Outfit', sans-serif; text-shadow: 1px 1px 4px rgba(0,0,0,0.2);">{{ $category->name }}</h1>
            @if($category->description)
            <p class="lead fw-light mb-0 text-white-50">{{ $category->description }}</p>
            @endif
        </div>
        <div class="position-absolute bottom-0 start-0 w-100" style="height: 50%; background: linear-gradient(to top, rgba(0,0,0,0.2), transparent);"></div>
    </section>

    <div class="container mt-5">
        <!-- Livewire Component: Category News Grid (Paginação via Cursor na lógica) -->
        <livewire:frontend.category-news-grid :category="$category" />
    </div>
</div>
@endsection
