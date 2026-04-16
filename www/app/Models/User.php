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

#[Fillable(['name', 'email', 'password', 'role', 'slug', 'bio', 'avatar', 'theme_color', 'social_links', 'asaas_customer_id', 'subscription_status', 'subscription_expires_at', 'two_factor_enabled', 'two_factor_secret', 'two_factor_recovery_codes'])]
#[Hidden(['password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes'])]
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
            'two_factor_enabled' => 'boolean',
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
     * Engine de Zero Trust: Google Authenticator (TOTP)
     */
    public function enableTwoFactorSecret()
    {
        if (empty($this->two_factor_secret)) {
            $google2fa = new \PragmaRX\Google2FA\Google2FA();
            $this->two_factor_secret = $google2fa->generateSecretKey();
            
            // Gerar 8 códigos aleatórios de 10 caracteres (ex: A4B2Z9K1M3)
            $recoveryCodes = [];
            for ($i = 0; $i < 8; $i++) {
                $recoveryCodes[] = strtoupper(substr(bin2hex(random_bytes(5)), 0, 10));
            }
            
            // Grava em Hash o JSON dos códigos 
            $this->two_factor_recovery_codes = encrypt(json_encode($recoveryCodes));
            
            $this->save();
        }
    }
}
