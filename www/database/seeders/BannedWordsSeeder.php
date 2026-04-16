<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class BannedWordsSeeder extends Seeder
{
    public function run(): void
    {
        // Uma lista base pesada de termos ofensivos e nocivos para o modelo social
        $bannedWords = [
            'porra', 'caralho', 'filho da puta', 'puta', 'merda', 'bosta', 
            'arrombado', 'viado', 'viadinho', 'cuzão', 'foder', 'buceta', 'piroca',
            'macaco', 'preto sujo', 'nazista', 'morte a', 'vão se foder', 'corno', 'vagabunda'
        ];

        // Salvando de forma formatada com vírgulas na tabela genérica de settings
        Setting::set('banned_words', implode(', ', $bannedWords));
        Setting::set('allow_comments_globally', '1'); // Opcionalmente ligado pro sistema todo
    }
}
