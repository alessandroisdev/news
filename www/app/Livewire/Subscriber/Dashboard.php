<?php

namespace App\Livewire\Subscriber;

use App\Enums\NewsStateEnum;
use App\Models\News;
use App\Models\Payment;
use App\Services\AsaasPaymentService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public $activePayment = null;

    public function mount()
    {
        $this->loadPendingPayment();
    }

    public function loadPendingPayment()
    {
        $this->activePayment = Payment::where('user_id', Auth::id())
            ->where('status', \App\Enums\PaymentStatusEnum::PENDING->value)
            ->latest()
            ->first();
    }

    public function generateSubscription(AsaasPaymentService $payer)
    {
        $user = Auth::user();
        if ($user->subscription_status !== \App\Enums\SubscriptionStatusEnum::ACTIVE->value) {
            $this->activePayment = $payer->generatePixCharge($user, 29.90);
            session()->flash('message', 'Fatura Asaas gerada com sucesso! Aguardando compensação.');
        }
    }

    public function simulatePaymentWebhook(AsaasPaymentService $payer)
    {
        // Easter Egg: Mock Button pra você testar o Webhook na própria View Front sem disparar Postman!
        if ($this->activePayment) {
            $payload = [
                'event' => 'PAYMENT_RECEIVED',
                'payment' => ['id' => $this->activePayment->asaas_payment_id]
            ];
            $payer->handleWebhook($payload);
            
            return redirect()->route('subscriber.dashboard');
        }
    }

    public function render()
    {
        $user = Auth::user();
        $premiumNews = [];

        // O Famoso Paywall de Conteúdo
        $isActive = ($user->subscription_status === \App\Enums\SubscriptionStatusEnum::ACTIVE->value);

        if ($isActive) {
            // Se asaas/webhook emitiu ativo, a query caríssima exibe os dados.
            $premiumNews = News::with('category', 'author')
                ->where('state', NewsStateEnum::PUBLISHED->value)
                ->latest()
                ->take(6)
                ->get();
        }

        return view('livewire.subscriber.dashboard', [
            'user' => $user,
            'premiumNews' => $premiumNews,
            'isActive' => $isActive
        ])->layout('layouts.subscriber');
    }
}
