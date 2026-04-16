<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VisitorProfiler
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ignorar rotas de backend corporativo, botões de livewire e midias
        if ($request->is('admin*') || $request->is('livewire*') || $request->is('images*') || $request->is('stream*') || $request->is('logout')) {
            return $next($request);
        }

        try {
            $sessionId = session()->getId() ?? $request->ip();

            if (!$sessionId) {
                return $next($request);
            }

            $profile = \App\Models\VisitorProfile::firstWhere('session_id', $sessionId);

            if (!$profile) {
                // Primeira visita: Geolocalização Passiva
                $locationData = 'Unknown';
                if ($request->ip() && $request->ip() !== '127.0.0.1') {
                    try {
                        $geo = \Illuminate\Support\Facades\Http::timeout(2)
                            ->get("http://ip-api.com/json/" . $request->ip())
                            ->json();
                        if (isset($geo['status']) && $geo['status'] === 'success') {
                            $locationData = $geo['city'] . ', ' . $geo['regionName'] . ' - ' . $geo['countryCode'];
                        }
                    } catch (\Exception $e) {}
                }

                $profile = \App\Models\VisitorProfile::create([
                    'session_id' => $sessionId,
                    'ip_address' => $request->ip(),
                    'device_type' => $this->detectDevice($request->userAgent()),
                    'browser' => $this->detectBrowser($request->userAgent()),
                    'os' => $this->detectOS($request->userAgent()),
                    'location_data' => $locationData,
                    'preferences_score' => [],
                ]);
            }

            // Sincronizar Autenticação se logou recentemente
            if (auth()->check() && !$profile->user_id) {
                $profile->user_id = auth()->id();
            }

            // Atualizar carimbo numérico sempre (limite a 1/minuto para performance se desejado)
            $profile->last_active_at = now();
            $profile->save();
            
            // Injetar para uso das Views / Controllers globalmente
            $request->attributes->set('visitor_profile', $profile);

        } catch (\Exception $e) {
            // Falha silenciosa para não quebrar a navegação do usuário se o redis ou db oscilar
        }

        return $next($request);
    }

    private function detectDevice($agent)
    {
        if (!$agent) return 'Unknown';
        if (preg_match('/Mobile|Android|BlackBerry|iPhone|Windows Phone/i', $agent)) return 'Mobile';
        if (preg_match('/iPad|Tablet/i', $agent)) return 'Tablet';
        return 'Desktop';
    }

    private function detectBrowser($agent)
    {
        if (!$agent) return 'Unknown';
        if (preg_match('/Firefox/i', $agent)) return 'Firefox';
        if (preg_match('/MSIE/i', $agent) || preg_match('/Trident/i', $agent)) return 'Internet Explorer';
        if (preg_match('/Edge/i', $agent)) return 'Edge';
        if (preg_match('/Chrome/i', $agent)) return 'Chrome';
        if (preg_match('/Safari/i', $agent)) return 'Safari';
        if (preg_match('/Opera/i', $agent)) return 'Opera';
        return 'Other';
    }

    private function detectOS($agent)
    {
        if (!$agent) return 'Unknown';
        if (preg_match('/windows|win32/i', $agent)) return 'Windows';
        if (preg_match('/macintosh|mac os x/i', $agent)) return 'Mac OS';
        if (preg_match('/linux/i', $agent)) return 'Linux';
        if (preg_match('/android/i', $agent)) return 'Android';
        if (preg_match('/iphone|ipad|ipod/i', $agent)) return 'iOS';
        return 'Other';
    }
}
