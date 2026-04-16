<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Novo Contato via Portal</title>
</head>
<body style="background-color: #f8f9fa; font-family: Arial, sans-serif; padding: 30px;">
    <div style="max-width: 600px; background-color: #ffffff; padding: 30px; border-radius: 8px; margin: 0 auto; border: 1px solid #e9ecef;">
        <h2 style="color: #333333; margin-top: 0; border-bottom: 2px solid #0d6efd; padding-bottom: 10px;">
            Novo contato de Leitor
        </h2>
        
        <p style="color: #6c757d; font-size: 14px;">Um leitor acaba de preencher o formulário de contato do site.</p>
        
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <tr>
                <td style="padding: 10px 0; border-bottom: 1px solid #eee; width: 30%; color: #888;"><strong>Nome:</strong></td>
                <td style="padding: 10px 0; border-bottom: 1px solid #eee; color: #333;">{{ $messageData->name }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 0; border-bottom: 1px solid #eee; color: #888;"><strong>E-mail:</strong></td>
                <td style="padding: 10px 0; border-bottom: 1px solid #eee; color: #333;"><a href="mailto:{{ $messageData->email }}">{{ $messageData->email }}</a></td>
            </tr>
            <tr>
                <td style="padding: 10px 0; border-bottom: 1px solid #eee; color: #888;"><strong>Assunto:</strong></td>
                <td style="padding: 10px 0; border-bottom: 1px solid #eee; color: #333;">{{ $messageData->subject ?? 'Não informado' }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 0; border-bottom: 1px solid #eee; color: #888;"><strong>IP de Origem:</strong></td>
                <td style="padding: 10px 0; border-bottom: 1px solid #eee; color: #333;">{{ $messageData->ip_address }}</td>
            </tr>
        </table>
        
        <div style="margin-top: 30px;">
            <p style="color: #888; font-weight: bold; margin-bottom: 10px;">Mensagem:</p>
            <div style="background-color: #f8f9fa; padding: 20px; border-radius: 4px; color: #333; line-height: 1.6; white-space: pre-wrap;">{{ $messageData->message }}</div>
        </div>
        
        <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; font-size: 12px; color: #aaa; text-align: center;">
            Este é um e-mail automático gerado pelo sistema. Para responder diretamente ao leitor, use a função "Responder" do seu cliente de e-mail.
        </div>
    </div>
</body>
</html>
