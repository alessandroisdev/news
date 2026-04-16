<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;

class SettingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Apenas rodar override se tiver DB rodando e a tabela settings existir
        try {
            if (Schema::hasTable('settings')) {
                // Reescreve o config() do mail.smtp.host por exemplo pelo Setting
                if ($smtpHost = Setting::get('smtp_host')) config(['mail.mailers.smtp.host' => $smtpHost]);
                if ($smtpPort = Setting::get('smtp_port')) config(['mail.mailers.smtp.port' => $smtpPort]);
                if ($smtpUser = Setting::get('smtp_username')) config(['mail.mailers.smtp.username' => $smtpUser]);
                if ($smtpPass = Setting::get('smtp_password')) config(['mail.mailers.smtp.password' => $smtpPass]);
                if ($smtpEnc = Setting::get('smtp_encryption')) config(['mail.mailers.smtp.encryption' => $smtpEnc]);

                // Sobrescreve o E-mail global e Nome global remetente de envio padrão
                if ($mailFrom = Setting::get('mail_from_address')) {
                    config(['mail.from.address' => $mailFrom]);
                    config(['mail.from.name' => Setting::get('mail_from_name', config('app.name'))]);
                }
            }
        } catch (\Exception $e) {
            // Ignorar em caso de build ou servidor sem DB configurado ainda (Artisan fallback)
        }
    }
}
