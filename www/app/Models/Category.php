<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use App\Traits\Auditable;

class Category extends Model
{
    use Auditable;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'theme_color',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
        
        static::saved(function ($category) {
            // Regra de precedência OBRIGATÓRIA (Plano de Arquitetura): 
            // Se houver notícia com o mesmo slug, devemos alterar o slug da notícia
            $conflictNews = News::where('slug', $category->slug)->get();
            foreach ($conflictNews as $news) {
                $news->update(['slug' => $news->slug . '-' . time()]);
            }
        });
    }

    public function news(): HasMany
    {
        return $this->hasMany(News::class);
    }
}
