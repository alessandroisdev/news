<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\TwoFactorPinMail;
use Livewire\Component;

class TwoFactorChallenge extends Component
{
    public $code = '';

    public function mount()
    {
        if (session('2fa_passed', false)) {
            return redirect()->route('admin.dashboard');
        }
    }

    public function verify()
    {
        $this->validate([
            'code' => 'required|numeric|digits:6',
        ]);

        $user = Auth::user();

        if ($user->two_factor_code === $this->code && $user->two_factor_expires_at->isFuture()) {
            // Sucesso Absoluto
            $user->resetTwoFactorCode();
            session()->put('2fa_passed', true);
            
            return redirect()->intended('/admin/dashboard');
        }

        $this->addError('code', 'O código fornecido é inválido ou expirou. Tente reenviar.');
    }

    public function resend()
    {
        $user = Auth::user();
        $user->generateTwoFactorCode();
        
        Mail::to($user->email)->send(new TwoFactorPinMail($user));
        
        session()->flash('message', 'Um novo código militar foi enviado ao seu E-mail.');
    }

    public function render()
    {
        return view('livewire.auth.two-factor-challenge')->layout('layouts.auth');
    }
}
