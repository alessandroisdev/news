<?php

namespace App\Livewire\Frontend;

use App\Enums\NewsStateEnum;
use App\Models\Category;
use App\Models\News;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryNewsGrid extends Component
{
    use WithPagination;

    public Category $category;

    public function render()
    {
        // Paginação por Cursor como requerido pela arquitetura para ultra escalabilidade e sem offset pesado
        $news = News::where('category_id', $this->category->id)
            ->where('state', NewsStateEnum::PUBLISHED->value)
            ->orderByDesc('published_at')
            ->cursorPaginate(12);

        return view('livewire.frontend.category-news-grid', [
            'newsGrid' => $news
        ]);
    }
}
