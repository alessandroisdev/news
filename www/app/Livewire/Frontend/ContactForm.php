<?php

namespace App\Livewire\Frontend;

use App\Models\ContactMessage;
use App\Models\Setting;
use App\Mail\ContactMessageToRedaction;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class ContactForm extends Component
{
    public $name;
    public $email;
    public $subject;
    public $message;
    
    // Campo Honeypot invisível
    public $last_name;

    public $site_phone;
    public $site_address;
    public $site_facebook;
    public $site_instagram;
    public $site_twitter;

    public $successMessage = false;

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email',
        'subject' => 'nullable|string|max:150',
        'message' => 'required|min:10',
        'last_name' => 'nullable', // honeypot
    ];

    public function mount()
    {
        $this->site_phone = Setting::get('site_phone');
        $this->site_address = Setting::get('site_address');
        $this->site_facebook = Setting::get('site_facebook');
        $this->site_instagram = Setting::get('site_instagram');
        $this->site_twitter = Setting::get('site_twitter');
    }

    public function submit()
    {
        // 1. Defesa Honeypot: Se foi preenchido, é bot. Retorna silêncio falso.
        if (!empty($this->last_name)) {
            $this->resetForm();
            $this->successMessage = true; // Bot acha que deu certo
            return;
        }

        // 2. Rate Limiting: Máximo 3 contatos por hora via IP
        $key = 'contact-form:' . request()->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            $this->addError('submit', 'Muitas tentativas. Tente novamente em ' . ceil($seconds / 60) . ' minutos.');
            return;
        }
        
        $this->validate();

        // 3. Salva no Inbox Interno (DB)
        $contact = ContactMessage::create([
            'name' => $this->name,
            'email' => $this->email,
            'subject' => $this->subject,
            'message' => $this->message,
            'ip_address' => request()->ip(),
            'is_read' => false,
        ]);

        RateLimiter::hit($key, 3600); // Bloqueia slot por 1 hora

        // 4. Dispara e-mail para a Redação se configurado
        $receiver = Setting::get('contact_receiver_email');
        if ($receiver) {
            try {
                Mail::to($receiver)->send(new ContactMessageToRedaction($contact));
            } catch (\Exception $e) {
                // Falha silenciosa no envio do email. A mensagem já está a salvo no Inbox do DB!
            }
        }

        $this->resetForm();
        $this->successMessage = true;
    }

    private function resetForm()
    {
        $this->reset(['name', 'email', 'subject', 'message', 'last_name']);
    }

    public function render()
    {
        return view('livewire.frontend.contact-form')->layout('layouts.app');
    }
}
