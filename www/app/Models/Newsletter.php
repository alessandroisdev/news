<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Newsletter extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'title',
        'body',
        'status',
        'sent_at'
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
        ];
    }

    public function news(): BelongsToMany
    {
        // Mapeando a tabela Pivot, ordenando pelo pivot extra `order_index`
        return $this->belongsToMany(News::class, 'newsletter_news')
                    ->withPivot('order_index')
                    ->withTimestamps()
                    ->orderByPivot('order_index', 'asc');
    }
}
