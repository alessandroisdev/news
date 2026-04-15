<?php

namespace App\Livewire\Frontend;

use App\Models\News;
use App\Enums\NewsStateEnum;
use Livewire\Component;
use Livewire\WithPagination;

class LatestNews extends Component
{
    use WithPagination;

    public function render()
    {
        $newsList = News::with(['category', 'author'])
            ->where('state', NewsStateEnum::PUBLISHED->value)
            ->latest('published_at')
            ->cursorPaginate(12);

        return view('livewire.frontend.latest-news', [
            'newsList' => $newsList
        ])->layout('layouts.app');
    }
}
