<?php

namespace App\Livewire\Subscriber;

use Illuminate\Support\Facades\Auth;
use App\Enums\SubscriptionStatusEnum;
use Livewire\Component;

class Settings extends Component
{
    public $confirmingDeletion = false;
    public $twoFactorEnabled = false;
    public $qrCodeSvg = '';
    public $recoveryCodes = [];

    public function mount()
    {
        $this->twoFactorEnabled = Auth::user()->two_factor_enabled;
        if (Auth::user()->two_factor_secret) {
            $this->generateQrCodeSvg();
            $this->loadRecoveryCodes();
        }
    }

    protected function generateQrCodeSvg()
    {
        $user = Auth::user();
        if (!$user->two_factor_secret) return;

        $google2fa = new \PragmaRX\Google2FA\Google2FA();
        $qrCodeUrl = $google2fa->getQRCodeUrl('Portal News', $user->email, $user->two_factor_secret);

        $renderer = new \BaconQrCode\Renderer\ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(200),
            new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
        );
        $writer = new \BaconQrCode\Writer($renderer);
        $this->qrCodeSvg = (string) $writer->writeString($qrCodeUrl);
    }

    public function updatedTwoFactorEnabled($value)
    {
        $user = Auth::user();
        
        if ($value && empty($user->two_factor_secret)) {
            $user->enableTwoFactorSecret();
        }

        $user->two_factor_enabled = $value;
        $user->save();
        
        if ($value && $user->two_factor_secret) {
            $this->generateQrCodeSvg();
            $this->loadRecoveryCodes();
        }

        $msg = $value ? 'Segurança Google Auth (2FA) ATIVADA!' : 'Proteção Desativada.';
        session()->flash('success', $msg);
    }

    protected function loadRecoveryCodes()
    {
        $user = Auth::user();
        if ($user && $user->two_factor_recovery_codes) {
            $this->recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true) ?? [];
        }
    }

    public function downloadRecoveryCodes()
    {
        $this->loadRecoveryCodes();
        if (empty($this->recoveryCodes)) return;
        
        $pdf = app('dompdf.wrapper')->loadView('pdf.recovery-codes', ['codes' => $this->recoveryCodes]);
        return response()->streamDownload(fn () => print($pdf->output()), 'recovery-codes.pdf');
    }

    public function emailRecoveryCodes()
    {
        $this->loadRecoveryCodes();
        if (empty($this->recoveryCodes)) return;
        
        \Illuminate\Support\Facades\Mail::to(Auth::user()->email)
            ->send(new \App\Mail\TwoFactorRecoveryCodesMail(Auth::user(), $this->recoveryCodes));
            
        session()->flash('success', 'Códigos de emergência enviados para sua caixa postal segura!');
    }

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
