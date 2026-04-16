<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:6',
    ];

    public function authenticate()
    {
        $this->validate();

        // Tentativa de Autenticação pelo Sanctum com Sessão Controlada
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            
            // Redirecionamento dinâmico baseado na Role do Sanctum/User (Obrigatório pela modelagem do projeto)
            $user = Auth::user();
            $role = $user->role;
            
            if ($role === \App\Enums\UserRoleEnum::SUBSCRIBER->value) {
                return redirect()->intended('/assinante');
            }
            
            if ($user->two_factor_enabled) {
                // Gestores -> Dispara Barreira 2FA Zero Trust se Habilitados!
                // Criptografia TOTP Google Autenticator Exigida
                session()->put('2fa_passed', false);
                return redirect()->route('admin.2fa.challenge');
            }
            
            // Passa direto se 2FA desativado na sua conta
            return redirect()->intended('/admin/dashboard');
        }

        $this->addError('email', 'As credenciais fornecidas não conferem ou você não tem acesso.');
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.auth');
    }
}
