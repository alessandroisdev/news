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
            'code' => 'required',
        ]);

        $user = Auth::user();
        $google2fa = new \PragmaRX\Google2FA\Google2FA();

        // 1. Tentar validar como TOTP Numérico Tradicional
        if (is_numeric($this->code) && strlen($this->code) <= 6) {
            $valid = $google2fa->verifyKey($user->two_factor_secret, $this->code);
            if ($valid) {
                session()->put('2fa_passed', true);
                return redirect()->intended('/admin/dashboard');
            }
        }

        // 2. Tentar validar como Código de Recuperação Alpha (Fallback)
        if ($user->two_factor_recovery_codes) {
            $recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true) ?? [];
            $inputCode = strtoupper(trim($this->code));

            $key = array_search($inputCode, $recoveryCodes);
            if ($key !== false) {
                // Código encontrado! Destruir ele imediatamente do array
                unset($recoveryCodes[$key]);
                $user->two_factor_recovery_codes = encrypt(json_encode(array_values($recoveryCodes)));
                $user->save();

                session()->put('2fa_passed', true);
                return redirect()->intended('/admin/dashboard');
            }
        }

        $this->addError('code', 'O código fornecido é inválido. Tente novamente.');
    }

    public function render()
    {
        return view('livewire.auth.two-factor-challenge')->layout('layouts.auth');
    }
}
