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

#[Fillable(['name', 'email', 'password', 'role', 'slug', 'bio', 'avatar', 'theme_color', 'social_links', 'asaas_customer_id', 'subscription_status', 'subscription_expires_at', 'two_factor_code', 'two_factor_expires_at'])]
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

    /**
     * Check se o usuário tem poderes amplos de vip (Assinante Ativo ou Staff)
     */
    public function isVIP(): bool
    {
        return in_array($this->role, [
            \App\Enums\UserRoleEnum::ADMIN->value,
            \App\Enums\UserRoleEnum::MANAGER->value,
            \App\Enums\UserRoleEnum::COLUMNIST->value,
        ]) || $this->subscription_status === 'active';
    }

    /**
     * Engine de Zero Trust: Autenticação de 6 Dígitos
     */
    public function generateTwoFactorCode()
    {
        $this->timestamps = false;
        $this->two_factor_code = rand(100000, 999999);
        $this->two_factor_expires_at = now()->addMinutes(15);
        $this->save();
    }

    public function resetTwoFactorCode()
    {
        $this->timestamps = false;
        $this->two_factor_code = null;
        $this->two_factor_expires_at = null;
        $this->save();
    }
}
