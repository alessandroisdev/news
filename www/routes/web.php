<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Encurtador de Link (Base62)
// Encurtador de Link (Base62)
Route::get('/link/{hash}', [\App\Http\Controllers\LinkShortenerController::class, 'redirect'])->name('link.redirect');

// Telas Principais do Frontend
Route::get('/ultimas', \App\Livewire\Frontend\LatestNews::class)->name('frontend.latest');
Route::get('/colunistas', \App\Livewire\Frontend\ColumnistsIndex::class)->name('frontend.columnists');

// Roteador Dinâmico On the Fly de Mídias (Glide)
Route::get('/images/{slug}/{width?}/{height?}', [\App\Http\Controllers\MediaController::class, 'image'])->name('media.image');
Route::get('/videos/{slug}', [\App\Http\Controllers\MediaController::class, 'video'])->name('media.video');

// Áreas de Autenticação utilizando o Componente Base do Livewire e Sessão
Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');

Route::middleware('auth')->group(function () {
    // Espaço da Gestão (Totalmente integrado no Livewire em tempo real)
    Route::get('/admin/dashboard', \App\Livewire\Admin\Dashboard::class)->name('admin.dashboard');
    
    // Módulos CRUD do Painel Administrativo
    Route::get('/admin/news', \App\Livewire\Admin\News\Index::class)->name('admin.news.index');
    Route::get('/admin/news/create', \App\Livewire\Admin\News\Create::class)->name('admin.news.create');
    Route::get('/admin/news/{news}/edit', \App\Livewire\Admin\News\Edit::class)->name('admin.news.edit');
    Route::get('/admin/categories', \App\Livewire\Admin\Category\Index::class)->name('admin.categories.index');
    Route::get('/admin/users', \App\Livewire\Admin\Users\Index::class)->name('admin.users.index');
    Route::get('/admin/users/trash', \App\Livewire\Admin\Users\Trash::class)->name('admin.users.trash');
    
    // Espaço do Assinante
    Route::get('/assinante', \App\Livewire\Subscriber\Dashboard::class)->name('subscriber.dashboard');
    Route::get('/assinante/perfil', \App\Livewire\Subscriber\Profile::class)->name('subscriber.profile');
    Route::get('/assinante/pagamentos', \App\Livewire\Subscriber\Payments::class)->name('subscriber.payments');
    Route::get('/assinante/configuracoes', \App\Livewire\Subscriber\Settings::class)->name('subscriber.settings');
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
    ->where('slug', '^(?!images|videos|link|api|login|admin|assinante|logout|ultimas|colunistas).*$')
    ->name('dynamic.slug');
