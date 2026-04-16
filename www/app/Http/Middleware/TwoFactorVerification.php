<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorVerification
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Verifica se a Rota atual não é a própria tela do desafio, para não criar Redirecionamento Infinito
        if ($request->routeIs('admin.2fa.challenge') || $request->routeIs('logout')) {
            return $next($request);
        }

        if ($user && $user->two_factor_enabled && in_array($user->role, [
            \App\Enums\UserRoleEnum::ADMIN->value, 
            \App\Enums\UserRoleEnum::MANAGER->value, 
            \App\Enums\UserRoleEnum::COLUMNIST->value
        ])) {
            if (!session('2fa_passed', false)) {
                return redirect()->route('admin.2fa.challenge');
            }
        }

        return $next($request);
    }
}
