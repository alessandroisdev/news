<?php

namespace Tests\Feature;

use App\Enums\UserRoleEnum;
use App\Enums\SubscriptionStatusEnum;
use App\Enums\PaymentStatusEnum;
use App\Models\User;
use App\Models\Payment;
use App\Livewire\Subscriber\Dashboard as SubscriberDashboard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SubscriptionBillingTest extends TestCase
{
    use RefreshDatabase;

    public function test_overdue_subscriber_sees_paywall()
    {
        $user = User::factory()->create([
            'role' => UserRoleEnum::SUBSCRIBER->value,
            'subscription_status' => SubscriptionStatusEnum::OVERDUE->value,
            'theme_color' => '#000'
        ]);

        Livewire::actingAs($user)
            ->test(SubscriberDashboard::class)
            ->assertSee('Acesso Restrito: Paywall Asaas')
            ->assertDontSee('Curadoria Especial');
    }

    public function test_active_subscriber_bypasses_paywall()
    {
        $user = User::factory()->create([
            'role' => UserRoleEnum::SUBSCRIBER->value,
            'subscription_status' => SubscriptionStatusEnum::ACTIVE->value,
            'theme_color' => '#000'
        ]);

        Livewire::actingAs($user)
            ->test(SubscriberDashboard::class)
            ->assertDontSee('Paywall Asaas')
            ->assertSee('Curadoria Especial');
    }

    public function test_webhook_approves_payment_and_liberates_access()
    {
        $user = User::factory()->create([
            'role' => UserRoleEnum::SUBSCRIBER->value,
            'subscription_status' => SubscriptionStatusEnum::PENDING->value,
            'theme_color' => '#000'
        ]);

        $payment = Payment::create([
            'user_id' => $user->id,
            'amount' => 29.90,
            'asaas_payment_id' => 'pay_asaas_unique_123',
            'status' => PaymentStatusEnum::PENDING->value,
        ]);

        // Dispara o payload do Webhook simulando os Data Centers do Asaas batendo na nossa API
        $response = $this->postJson('/api/webhooks/asaas', [
            'event' => 'PAYMENT_RECEIVED',
            'payment' => ['id' => 'pay_asaas_unique_123']
        ]);

        $response->assertStatus(200);

        // Verifica na base de dados se as modificações financeiras locais foram concluídas
        $this->assertEquals(PaymentStatusEnum::RECEIVED->value, $payment->fresh()->status);
        $this->assertEquals(SubscriptionStatusEnum::ACTIVE->value, $user->fresh()->subscription_status);
    }
}
