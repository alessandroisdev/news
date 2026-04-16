<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title', 'image_path', 'target_url', 'news_id', 
        'is_active', 'active_days', 'active_hours',
        'views', 'clicks'
    ];

    protected $casts = [
        'active_days' => 'array',
        'active_hours' => 'array',
        'is_active' => 'boolean',
    ];

    public function news()
    {
        return $this->belongsTo(News::class);
    }
}
