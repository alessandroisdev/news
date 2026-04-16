<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background-color: #f4f6fa; padding: 20px; color: #333; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; padding: 40px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .code-box { background: #f8f9fa; padding: 15px; border: 1px solid #e9ecef; border-radius: 8px; font-family: monospace; font-size: 18px; letter-spacing: 2px; text-align: center; margin-bottom: 10px; font-weight: bold; }
        .warning { color: #dc3545; font-size: 14px; font-weight: bold; text-align: center; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <h2 style="margin-top: 0; color: #111827;">E-mail de Segurança Nível A</h2>
        <p>Olá, <strong>{{ $user->name }}</strong>.</p>
        <p>Você ativou a autenticação forte em 2 Fatores. Se você perder o acesso ao seu celular (Google Authenticator), os códigos abaixo são a sua única forma de entrar no painel do Portal News. <strong>Cada código pode ser usado apenas UMA vez.</strong></p>
        
        <div style="margin: 30px 0;">
            @foreach($codes as $code)
                <div class="code-box">{{ $code }}</div>
            @endforeach
        </div>

        <p class="warning">NÃO COMPARTILHE ESTES CÓDIGOS COM NINGUÉM.</p>
    </div>
</body>
</html>
