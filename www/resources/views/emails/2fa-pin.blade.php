<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 20px;">
    <div style="max-width: 500px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-top: 5px solid #dc3545;">
        <h2 style="margin-top: 0; color: #212529;">Alerta de Segurança / Zero Trust</h2>
        <p>Olá, {{ $user->name }}.</p>
        <p>Detectamos um login utilizando suas credenciais em um cargo Administrativo da corporação. Para prosseguir e montar seu Workspace, digite o código de 6 dígitos abaixo no portal.</p>
        
        <div style="background-color: #f1f3f5; padding: 20px; font-size: 32px; font-weight: bold; letter-spacing: 5px; text-align: center; margin: 30px 0; border-radius: 8px; color: #dc3545;">
            {{ $user->two_factor_code }}
        </div>
        
        <p style="font-size: 13px; color: #6c757d;">Este código expira em 15 minutos.</p>
        <p style="font-size: 13px; color: #6c757d;">Se você não tentou acessar o sistema agora, redefina sua senha imediatamente.</p>
    </div>
</body>
</html>
