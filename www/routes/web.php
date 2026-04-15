<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Encurtador de Link (Base62)
Route::get('/link/{hash}', [\App\Http\Controllers\LinkShortenerController::class, 'redirect'])->name('link.redirect');

// Roteador Dinâmico On the Fly de Mídias (Glide)
Route::get('/images/{slug}/{width?}/{height?}', [\App\Http\Controllers\MediaController::class, 'image'])->name('media.image');
Route::get('/videos/{slug}', [\App\Http\Controllers\MediaController::class, 'video'])->name('media.video');

// Áreas de Autenticação utilizando o Componente Base do Livewire e Sessão
Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');

Route::middleware('auth')->group(function () {
    // Espaço da Gestão (Totalmente integrado no Livewire em tempo real)
    Route::get('/admin/dashboard', \App\Livewire\Admin\Dashboard::class)->name('admin.dashboard');
    
    // Espaço do Assinante
    Route::get('/assinante', function () {
        return "Área Exclusiva Assinante (Premium)";
    })->name('subscriber.dashboard');
});

// Logout Helper Session Drop
Route::post('/logout', function (\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');


// Roteador Global Dinâmico da Raiz (Fallback Categoria>Coluna>Notícia)
Route::get('/{slug}', [\App\Http\Controllers\FallbackRouteController::class, 'resolve'])
    ->where('slug', '^(?!images|videos|link|api|login|admin|assinante|logout).*$')
    ->name('dynamic.slug');
