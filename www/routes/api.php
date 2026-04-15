<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Services\AsaasPaymentService;

/*
|--------------------------------------------------------------------------
| Application API Routes
|--------------------------------------------------------------------------
*/

// Segurança nativa: Rotas declaradas aqui não exigem CSRF token pois lidam em Server-Side Stateless.
Route::post('/webhooks/asaas', function (Request $request, AsaasPaymentService $payer) {
    // Processamento reativo do Webhook de confirmação PIX
    $payload = $request->all();
    $payer->handleWebhook($payload);
    
    return response()->json(['status' => 'webhook_handled_successfully'], 200);
});
