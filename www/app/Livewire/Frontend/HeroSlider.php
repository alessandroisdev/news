<?php

namespace App\Livewire\Frontend;

use App\Models\Banner;
use App\Models\News;
use App\Enums\NewsStateEnum;
use Livewire\Component;

class HeroSlider extends Component
{
    public function render()
    {
        // 1. Extração do Machine Learning/Score Profiling
        $visitor = request()->attributes->get('visitor_profile');
        $preferredCategoryIds = [];
        
        if ($visitor && is_array($visitor->preferences_score)) {
            $scores = $visitor->preferences_score;
            arsort($scores); // Ordenar pelas categorias com maior pontuação para este usuário
            $preferredCategoryIds = array_slice(array_keys($scores), 0, 3); // Pega o top 3 gostos
        }

        // 2. Extração das Top 10 Notícias Segmentadas (Se não tiver score, pega as recentes globais)
        $newsQuery = News::where('state', NewsStateEnum::PUBLISHED->value)
            ->with(['author', 'category']);
        
        if (!empty($preferredCategoryIds)) {
            // Dá preferência absoluta para o gosto do cliente nas 10 mais novas
            $newsQuery->orderByRaw("FIELD(category_id, " . implode(',', $preferredCategoryIds) . ") DESC, published_at DESC");
        } else {
            $newsQuery->latest('published_at');
        }
        $heroNews = $newsQuery->take(10)->get();

        // 3. Extração Dinâmica da AdTech (Banners Agendados)
        $currentDay = now()->dayOfWeek; // 0 (Domingo) a 6 (Sábado)
        $currentHour = now()->format('H:i');

        $activeBanners = Banner::where('is_active', true)->get()->filter(function ($b) use ($currentDay, $currentHour) {
            // Verificar dia
            if (!empty($b->active_days) && !in_array((string)$currentDay, $b->active_days)) {
                return false;
            }
            // Verificar hora
            if (!empty($b->active_hours)) {
                $timeMatch = false;
                foreach ($b->active_hours as $range) {
                    [$start, $end] = explode('-', $range);
                    if ($currentHour >= trim($start) && $currentHour <= trim($end)) {
                        $timeMatch = true;
                        break;
                    }
                }
                if (!$timeMatch) return false;
            }
            return true;
        });

        // 4. Algoritmo de Mescla Híbrida (Merge Sort)
        // Regra imposta: A cada 2 notícias exibidas, tentamos injetar 1 Banner
        $slides = collect();
        $newsIndex = 0;
        $bannerIndex = 0;

        while($newsIndex < $heroNews->count() || $bannerIndex < $activeBanners->count()) {
            if ($newsIndex < $heroNews->count()) {
                $slides->push(['type' => 'news', 'data' => $heroNews[$newsIndex]]);
                $newsIndex++;
            }
            if ($newsIndex < $heroNews->count()) {
                $slides->push(['type' => 'news', 'data' => $heroNews[$newsIndex]]);
                $newsIndex++;
            }
            if ($bannerIndex < $activeBanners->count()) {
                $slides->push(['type' => 'banner', 'data' => $activeBanners->values()[$bannerIndex]]);
                $bannerIndex++;
            }
        }

        return view('livewire.frontend.hero-slider', [
            'slides' => $slides
        ]);
    }
}
