<?php

namespace App\Livewire\Subscriber;

use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Payments extends Component
{
    use WithPagination;

    public function render()
    {
        $payments = Payment::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('livewire.subscriber.payments', [
            'payments' => $payments
        ])->layout('layouts.subscriber');
    }
}
