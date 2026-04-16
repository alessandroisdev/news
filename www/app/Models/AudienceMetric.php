<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AudienceMetric extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_profile_id',
        'trackable_type',
        'trackable_id',
        'type',
    ];

    /**
     * Retorna ao que este log pertence (Pode ser Notícia, Autor ou Categoria)
     */
    public function trackable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * A identidade rica de quem gerou o visualização
     */
    public function visitorProfile(): BelongsTo
    {
        return $this->belongsTo(VisitorProfile::class);
    }
}
