<?php

namespace App\Livewire\Frontend;

use App\Enums\NewsStateEnum;
use App\Models\User;
use App\Models\News;
use Livewire\Component;
use Livewire\WithPagination;

class ColumnistNewsGrid extends Component
{
    use WithPagination;

    public User $columnist;

    public function render()
    {
        $news = News::where('author_id', $this->columnist->id)
            ->where('state', NewsStateEnum::PUBLISHED->value)
            ->orderByDesc('published_at')
            ->cursorPaginate(9);

        return view('livewire.frontend.columnist-news-grid', [
            'newsGrid' => $news
        ]);
    }
}
