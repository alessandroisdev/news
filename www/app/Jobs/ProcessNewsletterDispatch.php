<?php

namespace App\Jobs;

use App\Mail\WeeklyDigestMail;
use App\Models\Newsletter;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ProcessNewsletterDispatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $newsletterId;

    /**
     * Set max retries e timeout pra não engarrafar o Worker com SMTP lento
     */
    public $tries = 3;
    public $timeout = 1800; // 30 minutos de folego pra mandar mala direta

    public function __construct($newsletterId)
    {
        $this->newsletterId = $newsletterId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $newsletter = Newsletter::with('news')->find($this->newsletterId);
        
        if (!$newsletter || $newsletter->status !== 'sending') {
            return;
        }

        // Pega todos os VIPs
        // (Na versão corporativa podemos chunkar esse carregamento se houver >50.000 usuários)
        User::whereIn('role', [\App\Enums\UserRoleEnum::ADMIN->value, \App\Enums\UserRoleEnum::COLUMNIST->value])
            ->orWhere('subscription_status', 'active')
            ->chunk(100, function ($users) use ($newsletter) {
                foreach ($users as $user) {
                    try {
                        // Dispara unitariamente aplicando nome próprio no e-mail
                        Mail::to($user->email)->send(new WeeklyDigestMail($newsletter, $user));
                    } catch (\Exception $e) {
                        Log::error("Falha ao entregar Mail ID {$newsletter->id} para {$user->email}: " . $e->getMessage());
                    }
                }
            });

        // Marca a revista como entregue na história
        $newsletter->update([
            'status' => 'completed',
            'sent_at' => now(),
        ]);
    }
}
