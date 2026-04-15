<?php

namespace App\Livewire\Subscriber;

use Illuminate\Support\Facades\Auth;
use App\Enums\SubscriptionStatusEnum;
use Livewire\Component;

class Settings extends Component
{
    public $confirmingDeletion = false;

    public function confirmCancelation()
    {
        $this->confirmingDeletion = true;
    }

    public function cancelSubscription()
    {
        $user = Auth::user();
        if ($user->subscription_status === SubscriptionStatusEnum::ACTIVE->value) {
            $user->subscription_status = SubscriptionStatusEnum::INACTIVE->value;
            $user->save();
            
            session()->flash('success', 'Assinatura cancelada com sucesso. Você deixará de receber faturas.');
            $this->confirmingDeletion = false;
        } else {
            session()->flash('error', 'Sua assinatura já está inativa.');
        }
    }

    public function render()
    {
        return view('livewire.subscriber.settings')->layout('layouts.subscriber');
    }
}
