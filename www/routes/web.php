<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Arquitetura RSS 2.0 (Feed Distribuído)
Route::get('/feed', function () {
    $news = \App\Models\News::where('state', \App\Enums\NewsStateEnum::PUBLISHED->value)
        ->latest('published_at')
        ->limit(30)
        ->with(['author', 'category'])
        ->get();
        
    return response()->view('rss-feed', compact('news'))
        ->header('Content-Type', 'text/xml');
})->name('rss.feed');

// Sitemap XML Automático para SEO
Route::get('/sitemap.xml', function () {
    $news = \App\Models\News::where('state', \App\Enums\NewsStateEnum::PUBLISHED->value)->latest('published_at')->limit(30)->get();
    $categories = \App\Models\Category::all();
    $columnists = \App\Models\User::where('role', \App\Enums\UserRoleEnum::COLUMNIST->value)->get();
    
    return response()->view('sitemap-xml', compact('news', 'categories', 'columnists'))
        ->header('Content-Type', 'text/xml');
})->name('sitemap');

// PWA: Rota de Salvaguarda Offline Fallback
Route::view('/offline', 'offline')->name('pwa.offline');

// Encurtador de Link (Base62)
Route::get('/link/{hash}', [\App\Http\Controllers\LinkShortenerController::class, 'redirect'])->name('link.redirect');

// Telas Principais do Frontend
Route::get('/ultimas', \App\Livewire\Frontend\LatestNews::class)->name('frontend.latest');
Route::get('/colunistas', \App\Livewire\Frontend\ColumnistsIndex::class)->name('frontend.columnists');
Route::get('/contato', \App\Livewire\Frontend\ContactForm::class)->name('frontend.contact');

// Roteador Dinâmico On the Fly de Mídias (Glide)
Route::get('/images/{slug}/{width?}/{height?}', [\App\Http\Controllers\MediaController::class, 'image'])->name('media.image');
Route::get('/videos/{slug}', [\App\Http\Controllers\MediaController::class, 'video'])->name('media.video');

// Áreas de Autenticação utilizando o Componente Base do Livewire e Sessão
Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');

Route::middleware('auth')->group(function () {
    // Endpoint Streaming (SSE - Server Sent Events) para Updates In-Place de Tabela
    Route::get('/stream/news', function () {
        return response()->stream(function () {
            try {
                // Liberar lock de sessão IMEDIATAMENTE (vital para as outras abas do admin continuarem funcionando)
                session()->save();
                set_time_limit(0);

                // Headers de keep-alive inicial rápido para estabilizar o EventSource
                echo ":" . str_repeat(" ", 2048) . "\n\n";
                echo "retry: 2000\n\n"; // Dita a regra padrão de reconexão do browser em caso de falha externa
                if (ob_get_level() > 0) ob_flush();
                flush();

                $lastCheck = cache('last_news_update', 0);
                $startTime = microtime(true);

                while (true) {
                    if (connection_aborted() || connection_status() !== CONNECTION_NORMAL) {
                        break; // Quebra do client
                    }
                    
                    // FrankenPHP/Octane Workers não devem ficar presos Ad-Eternum por uma única conexão HTTP.
                    // Reciclamos o canal a cada 45 segundos. O EventSource JS se reconectará sozinho de forma transparente mantendo 1 canal constante.
                    if (microtime(true) - $startTime > 45) {
                        break;
                    }

                    $current = cache('last_news_update', 0);
                    if ($current > $lastCheck) {
                        echo "data: updated\n\n";
                        if (ob_get_level() > 0) ob_flush();
                        flush();
                        $lastCheck = $current;
                    }

                    sleep(1);
                    
                    // Ping inerte (comentário SSE) para o proxy reverso TCP
                    echo ": heartbeat\n\n";
                    if (ob_get_level() > 0) ob_flush();
                    flush();
                }
            } catch (\Throwable $e) {
                // Previne ErrorExceptions secundárias do Octane (Headers Already Sent) se a varredura crachar no worker
            }
        }, 200, [
            'Cache-Control' => 'no-cache, no-transform',
            'Content-Type' => 'text/event-stream',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    })->name('stream.news');

    // Rota Escapada do Middleware (Tela do Desafio)
    Route::get('/admin/2fa', \App\Livewire\Auth\TwoFactorChallenge::class)->name('admin.2fa.challenge');

    // Espaço da Gestão (Totalmente integrado no Livewire em tempo real e Blindado pelo 2FA)
    Route::middleware([\App\Http\Middleware\TwoFactorVerification::class])->group(function() {
        Route::get('/admin/dashboard', \App\Livewire\Admin\Dashboard::class)->name('admin.dashboard');
    
    // Módulos CRUD do Painel Administrativo
    Route::get('/admin/news', \App\Livewire\Admin\News\Index::class)->name('admin.news.index');
    Route::get('/admin/news/create', \App\Livewire\Admin\News\Create::class)->name('admin.news.create');
    Route::get('/admin/news/{news}/edit', \App\Livewire\Admin\News\Edit::class)->name('admin.news.edit');
    Route::get('/admin/categories', \App\Livewire\Admin\Category\Index::class)->name('admin.categories.index');
    Route::get('/admin/banners', \App\Livewire\Admin\Banners\Index::class)->name('admin.banners.index');
    Route::get('/admin/analytics', \App\Livewire\Admin\Analytics\Dashboard::class)->name('admin.analytics.dashboard');
    Route::get('/admin/analytics/content', \App\Livewire\Admin\Analytics\ContentMetrics::class)->name('admin.analytics.content');
    
    // Módulo Settings e Inbox
    Route::get('/admin/inbox', \App\Livewire\Admin\Inbox\Index::class)->name('admin.inbox.index');
    Route::get('/admin/settings', \App\Livewire\Admin\Settings\Index::class)->name('admin.settings.index');
    
    // Módulo de Moderação de Comentários
    Route::get('/admin/comments/moderation', \App\Livewire\Admin\Comments\Moderation::class)->name('admin.comments.moderation');

    Route::get('/admin/users', \App\Livewire\Admin\Users\Index::class)->name('admin.users.index');
        Route::get('/admin/users/trash', \App\Livewire\Admin\Users\Trash::class)->name('admin.users.trash');
        Route::get('/admin/audits', \App\Livewire\Admin\Audits\Index::class)->name('admin.audits.index');
    });

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
    ->where('slug', '^(?!images|videos|link|api|login|admin|assinante|logout|ultimas|colunistas|feed|sitemap\.xml).*$')
    ->name('dynamic.slug');
