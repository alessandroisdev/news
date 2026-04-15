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

// Roteador Global (Abaixo de todas as rotas declaradas)
Route::get('/{slug}', [\App\Http\Controllers\FallbackRouteController::class, 'resolve'])
    ->where('slug', '^(?!images|videos|link|api).*$')
    ->name('dynamic.slug');
