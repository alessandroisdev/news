<?php

namespace App\Livewire\Subscriber;

use App\Enums\NewsStateEnum;
use App\Models\News;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $user = Auth::user();
        
        // Exemplo da área de assinante filtrando um curadoria específica Premium.
        // Simulando que ele veja todas as publicações exclusivas do momento:
        $premiumNews = News::with('category', 'author')
            ->where('state', NewsStateEnum::PUBLISHED->value)
            ->latest()
            ->take(6)
            ->get();

        return view('livewire.subscriber.dashboard', [
            'user' => $user,
            'premiumNews' => $premiumNews
        ])->layout('layouts.subscriber');
    }
}
