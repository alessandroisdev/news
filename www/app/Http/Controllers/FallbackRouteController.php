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
        // 1. Categoria
        $category = Category::where('slug', $slug)->first();
        if ($category) {
            return view('category.show', compact('category'));
        }

        // 2. Colunista (Role COLUMNIST)
        $columnist = User::where('slug', $slug)
            ->where('role', UserRoleEnum::COLUMNIST->value)
            ->first();
        
        if ($columnist) {
            return view('columnist.show', compact('columnist'));
        }

        // 3. Notícia (Apenas status PUBLISHED)
        $news = News::where('slug', $slug)
            ->where('state', NewsStateEnum::PUBLISHED->value)
            ->first();
            
        if ($news) {
            $relatedNews = News::where('category_id', $news->category_id)
                ->where('id', '!=', $news->id)
                ->where('state', NewsStateEnum::PUBLISHED->value)
                ->latest('published_at')
                ->take(4)
                ->get();
                
            return view('news.show', compact('news', 'relatedNews'));
        }

        abort(404, 'Conteúdo não encontrado');
    }
}
