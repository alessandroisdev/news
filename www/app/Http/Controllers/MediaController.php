<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use League\Glide\ServerFactory;
use League\Glide\Responses\LaravelResponseFactory;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    /**
     * Intercepta acessos à imagem suportando redimensionamento e crop.
     * URI: /images/{slug}/100 ou /images/{slug}/100x100
     */
    public function image(Request $request, string $slug, ?string $width = null, ?string $height = null)
    {
        $server = ServerFactory::create([
            'response' => new LaravelResponseFactory(app('request')),
            'source' => Storage::disk('public')->getDriver(),
            'cache' => Storage::disk('local')->getDriver(),
            'cache_path_prefix' => '.glide-cache',
            'base_url' => 'images',
        ]);

        $params = $request->all();
        
        // Suporte ao formato /100x100 
        if ($width !== null && str_contains($width, 'x')) {
            $parts = explode('x', $width);
            $width = $parts[0];
            $height = $parts[1] ?? null;
        }

        if ($width) $params['w'] = $width;
        if ($height) $params['h'] = $height;

        return $server->getImageResponse($slug, $params);
    }

    public function video(Request $request, string $slug)
    {
        return response()->file(storage_path("app/public/videos/{$slug}"));
    }
}
