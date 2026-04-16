<?php

namespace App\Livewire\Admin\Settings;

use Livewire\Component;
use App\Models\Setting;

class Index extends Component
{
    public $activeTab = 'general'; // general, smtp

    // Vars de General
    public $site_name, $site_phone, $site_address, $site_facebook, $site_instagram, $site_twitter;
    
    // Vars de SMTP
    public $smtp_host, $smtp_port, $smtp_username, $smtp_password, $smtp_encryption;
    public $mail_from_address, $mail_from_name, $contact_receiver_email;

    public function mount()
    {
        // General
        $this->site_name = Setting::get('site_name', config('app.name'));
        $this->site_phone = Setting::get('site_phone');
        $this->site_address = Setting::get('site_address');
        $this->site_facebook = Setting::get('site_facebook');
        $this->site_instagram = Setting::get('site_instagram');
        $this->site_twitter = Setting::get('site_twitter');

        // SMTP
        $this->smtp_host = Setting::get('smtp_host', config('mail.mailers.smtp.host'));
        $this->smtp_port = Setting::get('smtp_port', config('mail.mailers.smtp.port', 587));
        $this->smtp_username = Setting::get('smtp_username', config('mail.mailers.smtp.username'));
        $this->smtp_password = Setting::get('smtp_password', config('mail.mailers.smtp.password'));
        $this->smtp_encryption = Setting::get('smtp_encryption', config('mail.mailers.smtp.encryption', 'tls'));
        
        $this->mail_from_address = Setting::get('mail_from_address', config('mail.from.address'));
        $this->mail_from_name = Setting::get('mail_from_name', config('mail.from.name'));
        $this->contact_receiver_email = Setting::get('contact_receiver_email', config('mail.from.address'));
    }

    public function saveGeneral()
    {
        Setting::set('site_name', $this->site_name);
        Setting::set('site_phone', $this->site_phone);
        Setting::set('site_address', $this->site_address);
        Setting::set('site_facebook', $this->site_facebook);
        Setting::set('site_instagram', $this->site_instagram);
        Setting::set('site_twitter', $this->site_twitter);

        session()->flash('success', 'Configurações gerais salvas com sucesso!');
    }

    public function saveSmtp()
    {
        Setting::set('smtp_host', $this->smtp_host);
        Setting::set('smtp_port', $this->smtp_port);
        Setting::set('smtp_username', $this->smtp_username);
        Setting::set('smtp_password', $this->smtp_password);
        Setting::set('smtp_encryption', $this->smtp_encryption);
        
        Setting::set('mail_from_address', $this->mail_from_address);
        Setting::set('mail_from_name', $this->mail_from_name);
        Setting::set('contact_receiver_email', $this->contact_receiver_email);

        session()->flash('success', 'SMTP atualizado. O sistema passa a utilizar estas configurações imediatamente!');
    }

    public function render()
    {
        return view('livewire.admin.settings.index')->layout('layouts.admin');
    }
}
