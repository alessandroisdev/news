<?php

namespace App\Services;

use App\Models\User;
use App\Models\Payment;
use App\Enums\PaymentStatusEnum;
use Illuminate\Support\Str;

class AsaasPaymentService
{
    // API KEY e Configuração Oculta por Motivos de Sandbox Base
    // Simulando Transição de Contratos

    /**
     * Gera uma transação PIX Reativa no Mock do Asaas Billing.
     */
    public function generatePixCharge(User $user, float $amount = 29.90): Payment
    {
        // Simulando que o Payload voltou 200 OK do Http::withToken()
        $mockAsaasId = 'pay_' . Str::random(12);
        
        $payment = Payment::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'asaas_payment_id' => $mockAsaasId,
            'billing_type' => 'PIX',
            'status' => PaymentStatusEnum::PENDING->value,
            'due_date' => now()->addDays(3),
            // Mock puro da Hash bancária
            'pix_copy_paste' => '00020101021226870014br.gov.bcb.pix...', 
            'pix_qr_code' => 'R2VuZXJhdGVkIEZha2UgUVIgQ29kZQ==' 
        ]);
        
        return $payment;
    }

    /**
     * Ouve a porta API /webhooks verificando liquidez.
     */
    public function handleWebhook(array $payload)
    {
        // Se a transação for recebida na plataforma Asaas
        if (($payload['event'] ?? '') === 'PAYMENT_RECEIVED' && isset($payload['payment']['id'])) {
            $payment = Payment::where('asaas_payment_id', $payload['payment']['id'])->first();
            
            if ($payment && $payment->status !== PaymentStatusEnum::RECEIVED->value) {
                
                // Consolida financeiramente Local
                $payment->update([
                    'status' => PaymentStatusEnum::RECEIVED->value,
                    'payment_date' => now(),
                ]);
                
                // MÁGICA: Concede instantaneamente nível de acesso para as Views fechadas do Laravel
                $payment->user->update([
                    'subscription_status' => \App\Enums\SubscriptionStatusEnum::ACTIVE->value,
                    'subscription_expires_at' => now()->addMonth(), // Rollback plano recorrente mensal
                ]);
            }
        }
    }
}
