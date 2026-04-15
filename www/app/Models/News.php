<?php

namespace App\Models;

use App\Enums\NewsStateEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

class News extends Model
{
    use Searchable;
    protected $fillable = [
        'title',
        'slug',
        'content',
        'cover_image',
        'state',
        'published_at',
        'category_id',
        'author_id',
    ];

    protected function casts(): array
    {
        return [
            'state' => NewsStateEnum::class,
            'published_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($news) {
            if (empty($news->slug)) {
                $news->slug = Str::slug($news->title);
            }
            
            // Garantir que não colide com categorias na criação e tratar unicidade
            $originalSlug = $news->slug;
            $count = 1;
            while (
                Category::where('slug', $news->slug)->exists() || 
                self::where('slug', $news->slug)->exists() || 
                User::where('slug', $news->slug)->exists()
            ) {
                $news->slug = "{$originalSlug}-" . time();
                $count++;
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Mapeamento dos campos que serão indexados pelo Meilisearch
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => strip_tags($this->content),
            'state' => $this->state->value ?? $this->state,
            'category_name' => $this->category ? $this->category->name : '',
        ];
    }
}
