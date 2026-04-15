<?php

namespace App\Livewire\Frontend;

use App\Models\News;
use App\Enums\NewsStateEnum;
use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        // Puxa as 6 notícias mais recentes que estejam públicas para o Grid Principal "Recomendado"
        $recommended = News::with(['category', 'author'])
            ->where('state', NewsStateEnum::PUBLISHED->value)
            ->latest('published_at')
            ->take(6)
            ->get();

        // Puxa 5 notícias sequenciais para compor a barra lateral "Em Alta" estruturando tráfego simulado
        $trending = News::with(['category'])
            ->where('state', NewsStateEnum::PUBLISHED->value)
            ->latest('published_at')
            ->skip(6)
            ->take(5)
            ->get();

        return view('livewire.frontend.home', [
            'recommended' => $recommended,
            'trending' => $trending
        ]);
    }
}
