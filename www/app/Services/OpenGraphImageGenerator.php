<?php

namespace App\Services;

use App\Models\News;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class OpenGraphImageGenerator
{
    public static function generate(News $news)
    {
        try {
            $manager = new ImageManager(new Driver());

            $width = 1200;
            $height = 630;

            // Capa Original (ou fundo limpo se não houver capa)
            if ($news->cover_image && file_exists(storage_path('app/public/images/' . $news->cover_image))) {
                $image = $manager->read(storage_path('app/public/images/' . $news->cover_image));
                // Redimensiona preenchendo 1200x630 (Cover fit)
                $image->cover($width, $height);
            } else {
                $image = $manager->create($width, $height)->fill('323232');
            }

            // Aplicar película geométrica translúcida preta na base pra dar leitura no titulo
            $image->drawRectangle(0, $height / 2, function ($rectangle) {
                $rectangle->size(1200, 315);
                $rectangle->background('rgba(0, 0, 0, 0.7)');
            });
            // Adicional pelicula inteira pra escurecer um pouco mais
            $image->drawRectangle(0, 0, function ($rectangle) {
                $rectangle->size(1200, 630);
                $rectangle->background('rgba(0, 0, 0, 0.4)');
            });

            // Fonte
            $fontPath = storage_path('app/fonts/Outfit-Bold.ttf');
            
            // Branding Logo Texto no Canto Superior
            if (file_exists($fontPath)) {
                $image->text('PORTAL NEWS', 60, 80, function ($font) use ($fontPath) {
                    $font->file($fontPath);
                    $font->size(30);
                    $font->color('#dc3545'); // danger color / brand
                    $font->align('left');
                    $font->valign('top');
                });
            }

            // Título (Quebra automática de linha simples estilo pipeline)
            $title = $news->title;
            $words = explode(' ', $title);
            $lines = [];
            $currentLine = '';

            // Engine de Envelopamento do Título (Wrapper)
            foreach ($words as $word) {
                if (strlen($currentLine . $word) > 42) {
                    $lines[] = trim($currentLine);
                    $currentLine = $word . ' ';
                } else {
                    $currentLine .= $word . ' ';
                }
            }
            if (!empty($currentLine)) {
                $lines[] = trim($currentLine);
            }

            // Apenas renderiza no máx 3 linhas para ficar elegante e não vazar
            $lines = array_slice($lines, 0, 3);
            
            $y = 350; // Começa a desenhar na metade inferior
            if (file_exists($fontPath)) {
                foreach ($lines as $line) {
                    $image->text($line, 60, $y, function ($font) use ($fontPath) {
                        $font->file($fontPath);
                        $font->size(60);
                        $font->color('#ffffff');
                        $font->align('left');
                        $font->valign('top');
                        $font->lineHeight(1.2);
                    });
                    $y += 75; 
                }
            }

            // Etiqueta Final Categoria
            $catName = $news->category ? strtoupper($news->category->name) : 'NEWS';
            if (file_exists($fontPath)) {
                $image->text($catName, 60, $y + 40, function ($font) use ($fontPath) {
                    $font->file($fontPath);
                    $font->size(25);
                    $font->color('#aaaaaa');
                    $font->align('left');
                    $font->valign('top');
                });
            }

            // Salvar
            $ogPath = storage_path('app/public/og/' . $news->id . '_' . $news->slug . '.jpg');
            $image->save($ogPath, 85);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erro ao gerar OG Image: ' . $e->getMessage());
        }
    }
}
