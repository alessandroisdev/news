<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Recovery Codes - Portal News</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; color: #333; padding: 40px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 20px; margin-bottom: 40px; }
        .title { font-size: 24px; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; }
        .subtitle { font-size: 14px; color: #666; margin-top: 10px; }
        .codes-container { margin: 0 auto; width: 80%; }
        .code-item { font-family: "Courier New", Courier, monospace; font-size: 20px; text-align: center; padding: 15px; border: 1px dashed #ccc; margin-bottom: 15px; background-color: #fcfcfc; font-weight: bold; letter-spacing: 4px; }
        .footer { margin-top: 60px; font-size: 12px; text-align: center; color: #999; border-top: 1px solid #eee; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Códigos de Recuperação 2FA</div>
        <div class="subtitle">Guarde este documento em um local seguro (Cofre ou HD Externo).</div>
    </div>

    <p style="text-align: center; margin-bottom: 40px; line-height: 1.6;">
        Estes códigos garantem seu acesso ao workspace do Portal News em caso de perda do dispositivo Autenticador.<br>
        <strong>Atenção:</strong> Cada código é descartável e perde a validade instantaneamente após o seu uso.
    </p>

    <div class="codes-container">
        @foreach($codes as $code)
            <div class="code-item">{{ $code }}</div>
        @endforeach
    </div>

    <div class="footer">
        Gerado em {{ now()->format('d/m/Y H:i:s') }} pelo Hub de Segurança.<br>
        Documento Confidencial.
    </div>
</body>
</html>
