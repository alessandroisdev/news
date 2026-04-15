<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\News;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Criando Perfis Base de Autenticação Nativos
        $admin = User::firstOrCreate(['email' => 'admin@portal.com'], [
            'name' => 'Diretor Administrativo',
            'password' => bcrypt('password'),
            'role' => \App\Enums\UserRoleEnum::ADMIN->value,
        ]);

        $colunista = User::firstOrCreate(['email' => 'colunista@portal.com'], [
            'name' => 'Alessandro Santos',
            'password' => bcrypt('password'),
            'role' => \App\Enums\UserRoleEnum::COLUMNIST->value,
            'slug' => 'alessandro-santos',
            'bio' => 'Especialista em desenvolvimento avançado de ponta-a-ponta e tendências em engenharia de software contínua.',
            'theme_color' => '#6f42c1', // Roxo (Cor Injetada pelo Dynamic Frontend)
            'social_links' => json_encode(['twitter' => 'https://x.com/ale', 'instagram' => 'https://ig.com/ale', 'linkedin' => 'https://linkedin.com/in/ale']),
        ]);

        User::firstOrCreate(['email' => 'assinante@portal.com'], [
            'name' => 'Leitor Premium',
            'password' => bcrypt('password'),
            'role' => \App\Enums\UserRoleEnum::SUBSCRIBER->value,
        ]);

        // 2. Criando SEO Categories Puras (Precedência /slug)
        $catTecnologia = Category::firstOrCreate(['slug' => 'tecnologia'], [
            'name' => 'Engenharia & Tech',
            'description' => 'Acompanhe novidades disruptivas sobre inteligência artifical e frameworks web avançados.',
            'theme_color' => '#0d6efd' // Primary Bootstrap Genérico
        ]);

        $catShow = Category::firstOrCreate(['slug' => 'show'], [
            'name' => 'Entretenimento',
            'description' => 'Acompanhe as estreias espetaculares focadas no mundo do cinema!',
            'theme_color' => '#ffc107' // Warning Yellow
        ]);

        $catAgro = Category::firstOrCreate(['slug' => 'agro'], [
            'name' => 'Agronegócio',
            'description' => 'Evolução e desenvolvimento da colheita interativa e sustentável.',
            'theme_color' => '#198754' // Success Green
        ]);

        // 3. Matérias Oficiais da Redação (Publicadas imediatamente pelo StateMachine Level superior)
        News::firstOrCreate(['slug' => 'portal-lancado-em-arquitetura-de-conteineres'], [
            'title' => 'Portal principal é lançado nativamente numa arquitetura de contêineres e Meilisearch e supera limites',
            'content' => '<p class="lead">Acabamos de efetivar a transição das antigas estruturas limitadas e lentas para um docker limpo rodando perfeitamente bem sob servidores Nginx modernos e robustos de autíssima latência.</p>
                          <p>O Livewire garantiu que não utilizássemos DDD desnecessariamente criando reatividade instantânea (SPA-Like).</p>',
            'state' => \App\Enums\NewsStateEnum::PUBLISHED->value,
            'category_id' => $catTecnologia->id,
            'author_id' => $admin->id,
            'published_at' => now()->subHours(2),
        ]);

        News::firstOrCreate(['slug' => 'producao-de-soja-bate-recorde-tecnologico-no-interior'], [
            'title' => 'Produção de Soja bate recorde de qualidade e inovação com o uso de Tratores Autônomos.',
            'content' => '<p>Um artigo denso abordando com os maquinários de última geração se comunicando sem interferência humana garantem colheitas precisas no centro oeste.</p>',
            'state' => \App\Enums\NewsStateEnum::PUBLISHED->value,
            'category_id' => $catAgro->id,
            'author_id' => $admin->id,
            'published_at' => now()->subMinutes(30),
        ]);

        // 4. Matérias Reflexivas do Colunista Respeitando sua View Singular
        News::firstOrCreate(['slug' => 'o-futuro-e-a-descentralizacao-no-livewire'], [
            'title' => 'Voz do Código: Porquê o Livewire 3 revoluciona a forma como trabalhamos!',
            'content' => '<p>Discorrendo longamente neste artigo os paradigmas e as facilidades de manipular a Engine de componentes evitando transições complexas demais.</p>',
            'state' => \App\Enums\NewsStateEnum::PUBLISHED->value,
            'category_id' => $catTecnologia->id,
            'author_id' => $colunista->id,
            'published_at' => now()->subDays(1),
        ]);

        // Mockado para revisão
        News::firstOrCreate(['slug' => 'a-polemica-dos-caminhoneiros'], [
            'title' => 'O impacto obscuro dos bloqueios.',
            'content' => '<p>Este texto não será visível pro leitor ainda.</p>',
            'state' => \App\Enums\NewsStateEnum::IN_REVIEW->value,
            'category_id' => $catAgro->id,
            'author_id' => $colunista->id,
        ]);
        
        $this->command->info('Base Populacional Inicial Concluída com Integridade!');
        $this->command->warn('Contas de Acesso:');
        $this->command->line('- admin@portal.com / password');
        $this->command->line('- colunista@portal.com / password');
        $this->command->line('- assinante@portal.com / password');
    }
}
