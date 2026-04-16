<?php

namespace App\Http\Controllers;

use App\Enums\NewsStateEnum;
use App\Enums\UserRoleEnum;
use App\Models\Category;
use App\Models\News;
use App\Models\User;
use Illuminate\Http\Request;

class FallbackRouteController extends Controller
{
    /**
     * Resolve acessos ao URI raiz por precedência (Categoria > Coluna > Notícia)
     */
    public function resolve(string $slug)
    {
        $category = Category::where('slug', $slug)->first();
        if ($category) {
            // Track de Perfil 
            $profile = request()->attributes->get('visitor_profile');
            if ($profile) {
                // Tracking de Preferencia Semântica (Muda o Peso)
                $scores = $profile->preferences_score ?? [];
                $scores[$category->id] = ($scores[$category->id] ?? 0) + 2; // +2 por explorar a trilha
                $profile->preferences_score = $scores;
                $profile->save();
                
                // Gravar PageView Real
                \App\Models\AudienceMetric::create([
                    'visitor_profile_id' => $profile->id,
                    'trackable_type' => Category::class,
                    'trackable_id' => $category->id,
                    'type' => 'view'
                ]);
            }
            return view('category.show', compact('category'));
        }

        // 2. Colunista (Role COLUMNIST)
        $columnist = User::where('slug', $slug)
            ->where('role', UserRoleEnum::COLUMNIST->value)
            ->first();
        
        if ($columnist) {
            // Track de Perfil (Colunista)
            $profile = request()->attributes->get('visitor_profile');
            if ($profile) {
                \App\Models\AudienceMetric::create([
                    'visitor_profile_id' => $profile->id,
                    'trackable_type' => User::class,
                    'trackable_id' => $columnist->id,
                    'type' => 'view'
                ]);
            }
            return view('columnist.show', compact('columnist'));
        }

        // 3. Notícia (Apenas status PUBLISHED)
        $news = News::where('slug', $slug)
            ->where('state', NewsStateEnum::PUBLISHED->value)
            ->first();
            
        if ($news) {
            // Track de Perfil 
            $profile = request()->attributes->get('visitor_profile');
            if ($profile) {
                $scores = $profile->preferences_score ?? [];
                $scores[$news->category_id] = ($scores[$news->category_id] ?? 0) + 1; // +1 por ler uma matéria
                $profile->preferences_score = $scores;
                $profile->save();
                
                // Gravar PageView Real para a Notícia
                \App\Models\AudienceMetric::create([
                    'visitor_profile_id' => $profile->id,
                    'trackable_type' => News::class,
                    'trackable_id' => $news->id,
                    'type' => 'view'
                ]);
            }

            $relatedNews = News::where('category_id', $news->category_id)
                ->where('id', '!=', $news->id)
                ->where('state', NewsStateEnum::PUBLISHED->value)
                ->latest('published_at')
                ->take(4)
                ->get();
                
            // === METERED PAYWALL LOGIC ===
            $isVIP = auth()->check() && 
                    auth()->user()->role == UserRoleEnum::SUBSCRIBER->value && 
                    auth()->user()->subscription_status == 'active';
            
            $isBlocked = false;
            $meteredReads = 0;
            $freeReadsLimit = 3;

            if ($news->is_premium && !$isVIP) {
                if ($profile) {
                    $meteredReads = \App\Models\AudienceMetric::where('visitor_profile_id', $profile->id)
                        ->where('trackable_type', News::class)
                        ->whereHasMorph('trackable', [News::class], function($q) {
                            $q->where('is_premium', true);
                        })
                        ->whereYear('created_at', now()->year)
                        ->whereMonth('created_at', now()->month)
                        ->distinct('trackable_id')
                        ->count('trackable_id');

                    if ($meteredReads > $freeReadsLimit) {
                        $isBlocked = true; // Bateu no teto da cota. Cai a lâmina.
                    }
                } else {
                    // Navegação extrema sem cookie/incógnito forte? Bloquear por segurança.
                    $isBlocked = true;
                }
            }
                
            return view('news.show', compact('news', 'relatedNews', 'isBlocked', 'meteredReads', 'freeReadsLimit', 'isVIP'));
        }

        abort(404, 'Conteúdo não encontrado');
    }
}
