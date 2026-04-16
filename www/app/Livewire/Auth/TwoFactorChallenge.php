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
        $google2fa = new \PragmaRX\Google2FA\Google2FA();

        $valid = $google2fa->verifyKey($user->two_factor_secret, $this->code);

        if ($valid) {
            // Sucesso Absoluto
            session()->put('2fa_passed', true);
            return redirect()->intended('/admin/dashboard');
        }

        $this->addError('code', 'O código fornecido é inválido. Tente novamente.');
    }

    public function render()
    {
        return view('livewire.auth.two-factor-challenge')->layout('layouts.auth');
    }
}
