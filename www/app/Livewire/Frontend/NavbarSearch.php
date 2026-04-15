<?php

namespace App\Livewire\Frontend;

use App\Enums\NewsStateEnum;
use App\Models\News;
use Livewire\Component;

class NavbarSearch extends Component
{
    public $query = '';

    public function render()
    {
        $results = [];
        
        if (strlen($this->query) >= 2) {
            // Busca ultrarrápida usando o Algoritmo do Meilisearch via driver Scout, ignorando typos e respeitando State limiters
            $results = News::search($this->query)
                ->where('state', NewsStateEnum::PUBLISHED->value)
                ->take(5)
                ->get();
        }

        return view('livewire.frontend.navbar-search', [
            'results' => $results
        ]);
    }
}
