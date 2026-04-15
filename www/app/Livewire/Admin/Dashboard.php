<?php

namespace App\Livewire\Admin;

use App\Enums\NewsStateEnum;
use App\Models\News;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $user = Auth::user();
        
        // Verificações robustas utilizando as máquinas de estados injetando escopos dinâmicos baseados no tipo do usuário
        $totalNews = 0;
        $publishedNews = 0;
        $pendingReviews = 0;
        
        if ($user) {
            $totalNews = News::when(
                $user->role === \App\Enums\UserRoleEnum::COLUMNIST->value, 
                fn($q) => $q->where('author_id', $user->id)
            )->count();
            
            $publishedNews = News::when(
                $user->role === \App\Enums\UserRoleEnum::COLUMNIST->value, 
                fn($q) => $q->where('author_id', $user->id)
            )->where('state', NewsStateEnum::PUBLISHED->value)->count();
            
            $pendingReviews = News::where('state', NewsStateEnum::REVIEW->value)->count();
        }
        
        $categories = Category::count();

        return view('livewire.admin.dashboard', [
            'totalNews' => $totalNews,
            'publishedNews' => $publishedNews,
            'pendingReviews' => $pendingReviews,
            'categories' => $categories,
            'userRole' => optional($user)->role
        ])->layout('layouts.admin');
    }
}
