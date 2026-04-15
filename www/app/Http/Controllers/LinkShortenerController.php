<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LinkShortenerController extends Controller
{
    /**
     * Intercepta links externos curtos em base62 injetados nas notícias
     * e redireciona efetuando possíveis dispatches pro Redis asíncronamente
     */
    public function redirect(string $hash)
    {
        // Neste controlador integraremos o rastreio base62 pro link real externo.
        // Simulador de Mock
        $externalUrl = 'https://google.com?q=' . $hash;

        // Despacha Job Assíncrono via Redis para rastrear o clique silenciosamente
        // ClickTrackingJob::dispatch($hash);

        return redirect()->away($externalUrl);
    }
}
