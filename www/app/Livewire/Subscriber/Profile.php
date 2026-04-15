<?php

namespace App\Livewire\Subscriber;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Profile extends Component
{
    public $name;
    public $email;
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        $user = Auth::user();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->save();

        session()->flash('profile-updated', 'Seus dados de identidade foram atualizados.');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required|min:4',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'A senha atual está incorreta.');
            return;
        }

        $user->password = Hash::make($this->new_password);
        $user->save();

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        session()->flash('password-updated', 'Segurança reforçada. Senha alterada com sucesso.');
    }

    public function render()
    {
        return view('livewire.subscriber.profile')->layout('layouts.subscriber');
    }
}
