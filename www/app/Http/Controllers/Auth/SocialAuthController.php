<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;
use App\Enums\UserRoleEnum;
use Illuminate\Support\Facades\File;

class SocialAuthController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Ocorreu um erro ao conectar com ' . ucfirst($provider));
        }

        // Verifica se o usuário já existe
        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            // Funde a conta caso exista e faz update do provider
            if (!$user->oauth_provider) {
                $user->update([
                    'oauth_provider' => $provider,
                    'oauth_id' => $socialUser->getId(),
                ]);
            }
            Auth::login($user, true);
        } else {
            // Cadastra Novo Assinante (Subscriber)
            
            // Tratamento do Avatar (Baixa localmente)
            $avatarName = null;
            if ($socialUser->getAvatar()) {
                try {
                    $avatarName = md5($socialUser->getEmail()) . '.jpg';
                    $avatarPath = storage_path('app/public/images/avatars/');
                    if (!File::exists($avatarPath)) {
                        File::makeDirectory($avatarPath, 0755, true);
                    }
                    $contents = file_get_contents($socialUser->getAvatar());
                    File::put($avatarPath . $avatarName, $contents);
                    $avatarName = 'avatars/' . $avatarName;
                } catch (\Exception $e) {
                    $avatarName = null;
                }
            }

            $user = User::create([
                'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                'email' => $socialUser->getEmail(),
                'oauth_provider' => $provider,
                'oauth_id' => $socialUser->getId(),
                'role' => UserRoleEnum::SUBSCRIBER->value, 
                // Assinantes ganham role base e devem usar ASAAS pr virar VIP
                'slug' => Str::slug($socialUser->getName() . '-' . rand(1000, 9999)),
                'avatar' => $avatarName
            ]);

            Auth::login($user, true);
        }

        return redirect()->intended('/assinante/painel');
    }
}
