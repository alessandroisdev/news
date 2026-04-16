<?php

namespace App\Livewire\Admin;

use App\Enums\NewsStateEnum;
use App\Models\News;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public $twoFactorEnabled = false;
    public $qrCodeSvg = '';
    public $recoveryCodes = [];

    public function mount()
    {
        $user = Auth::user();
        $this->twoFactorEnabled = $user->two_factor_enabled ?? false;
        
        if ($user && $user->two_factor_secret) {
            $this->generateQrCodeSvg();
            $this->loadRecoveryCodes();
        }
    }

    protected function generateQrCodeSvg()
    {
        $user = Auth::user();
        if (!$user || !$user->two_factor_secret) return;

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

        if ($value) {
            $user->enableTwoFactorSecret();
        }

        $user->two_factor_enabled = $value;
        $user->save();
        
        if ($value && $user->two_factor_secret) {
            $this->generateQrCodeSvg();
            $this->loadRecoveryCodes();
        }
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
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'recovery-codes.pdf');
    }

    public function emailRecoveryCodes()
    {
        $this->loadRecoveryCodes();
        if (empty($this->recoveryCodes)) return;
        
        \Illuminate\Support\Facades\Mail::to(Auth::user()->email)
            ->send(new \App\Mail\TwoFactorRecoveryCodesMail(Auth::user(), $this->recoveryCodes));
            
        // Podemos usar um simples alerta javascript ou session auth normal aqui
        session()->flash('success_2fa_mail', 'Enviado com sucesso!');
    }

    public function render()
    {
        $user = Auth::user();
        
        // Verificações robustas utilizando as máquinas de estados injetando escopos dinâmicos baseados no tipo do usuário
        $totalNews = 0;
        $publishedNews = 0;
        $pendingReviews = 0;
        
        if ($user) {
            $totalNews = News::when(
                $user->role === \App\Enums\UserRoleEnum::COLUMNIST->value, 
                fn($q) => $q->where('author_id', $user->id)
            )->count();
            
            $publishedNews = News::when(
                $user->role === \App\Enums\UserRoleEnum::COLUMNIST->value, 
                fn($q) => $q->where('author_id', $user->id)
            )->where('state', NewsStateEnum::PUBLISHED->value)->count();
            
            $pendingReviews = News::where('state', NewsStateEnum::IN_REVIEW->value)->count();
        }
        
        $categories = Category::count();

        return view('livewire.admin.dashboard', [
            'totalNews' => $totalNews,
            'publishedNews' => $publishedNews,
            'pendingReviews' => $pendingReviews,
            'categories' => $categories,
            'userRole' => optional($user)->role
        ])->layout('layouts.admin');
    }
}
