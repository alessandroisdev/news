<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $newsletter->subject }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f8f9fa; color: #212529; padding: 20px 0; margin: 0; }
        .wrapper { width: 100%; max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .header { background-color: #0d6efd; color: #ffffff; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; letter-spacing: -0.5px; }
        .content { padding: 30px; }
        .greeting { font-size: 16px; line-height: 1.6; margin-bottom: 25px; border-left: 4px solid #0d6efd; padding-left: 15px; color: #495057; font-style: italic; }
        .news-card { margin-bottom: 30px; border: 1px solid #e9ecef; border-radius: 6px; overflow: hidden; }
        .news-img { width: 100%; height: auto; display: block; border-bottom: 1px solid #e9ecef;}
        .news-body { padding: 20px; }
        .news-title { font-size: 18px; font-weight: bold; margin: 0 0 15px 0; color: #212529; line-height: 1.3;}
        .btn { display: inline-block; padding: 10px 20px; background-color: transparent; border: 2px solid #0d6efd; color: #0d6efd; text-decoration: none; border-radius: 30px; font-weight: bold; font-size: 14px; }
        .footer { background-color: #f1f3f5; padding: 20px; text-align: center; font-size: 12px; color: #6c757d; }
    </style>
</head>
<body>
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td align="center">
                <div class="wrapper">
                    <!-- Cabecalho -->
                    <div class="header">
                        <h1>{{ $newsletter->title ?: 'Boletim de Notícias' }}</h1>
                    </div>
                    
                    <!-- Corpo -->
                    <div class="content">
                        <p style="font-size: 15px; color: #6c757d;">Olá, <strong>{{ explode(' ', trim($user->name))[0] }}</strong>!</p>
                        
                        @if($newsletter->body)
                            <div class="greeting">
                                {{ $newsletter->body }}
                            </div>
                        @endif

                        <!-- Loop de Notícias -->
                        @foreach($pinnedNews as $news)
                            <div class="news-card">
                                @if($news->cover_image)
                                    <!-- Renderiza um Thumb Reduzido de Alta Resolução via Glide Fake -->
                                    <img src="{{ config('app.url') }}/images/{{ $news->slug }}/600x300" class="news-img" alt="Capa">
                                @else
                                    <div style="background-color: #e9ecef; height: 120px; width: 100%;"></div>
                                @endif
                                
                                <div class="news-body">
                                    <h2 class="news-title">{{ $news->title }}</h2>
                                    <table width="100%">
                                        <tr>
                                            <td align="left">
                                                <a href="{{ config('app.url') }}/{{ $news->slug }}" class="btn">Ler Matéria Completa &rarr;</a>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Roda pé -->
                    <div class="footer">
                        <p>Você está recebendo este e-mail por ser leitor VIP do nosso Portal.</p>
                        <p>&copy; {{ date('Y') }} Todos os direitos reservados.</p>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
