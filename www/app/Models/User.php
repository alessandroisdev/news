<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Traits\Auditable;

#[Fillable(['name', 'email', 'password', 'role', 'slug', 'bio', 'theme_color', 'social_links', 'asaas_customer_id', 'subscription_status', 'subscription_expires_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, Auditable;

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($user) {
            if (empty($user->slug) && $user->role === \App\Enums\UserRoleEnum::COLUMNIST->value) {
                $user->slug = \Illuminate\Support\Str::slug($user->name) . '-' . uniqid();
            }
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function news()
    {
        return $this->hasMany(\App\Models\News::class, 'author_id');
    }

    /**
     * Motor de Auditoria: Tráfego recebido nas colunas do Autor
     */
    public function audienceViews(): MorphMany
    {
        return $this->morphMany(AudienceMetric::class, 'trackable')->where('type', 'view');
    }
}
