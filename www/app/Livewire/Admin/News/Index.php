<?php

namespace App\Livewire\Admin\News;

use App\Models\News;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public function render()
    {
        $user = Auth::user();
        
        // Relacionamento limpo limitando escopo de visualização se não for um High-Level Administrator
        $news = News::with(['author', 'category'])
            ->when(
                $user->role === \App\Enums\UserRoleEnum::COLUMNIST->value, 
                fn($q) => $q->where('author_id', $user->id)
            )
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('livewire.admin.news.index', ['newsList' => $news])->layout('layouts.admin');
    }
}
