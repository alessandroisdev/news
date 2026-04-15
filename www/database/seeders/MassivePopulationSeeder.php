<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use App\Models\User;
use App\Models\Category;
use App\Models\News;
use App\Enums\UserRoleEnum;
use App\Enums\NewsStateEnum;
use App\Enums\SubscriptionStatusEnum;

class MassivePopulationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('pt_BR');
        $password = Hash::make('password'); // Cache do Hash p/ não explodir a CPU rodando 1000 bcrypts

        $this->command->info('População de Usuários (Administradores, Gestores, Assinantes)...');

        // 1. 3 Administradores
        for ($i = 1; $i <= 3; $i++) {
            User::updateOrCreate(
                ['email' => "admin{$i}@portal.com"],
                [
                    'name' => "Administrador Supremo {$i}",
                    'password' => $password,
                    'role' => UserRoleEnum::ADMIN->value,
                    'theme_color' => $faker->hexColor,
                ]
            );
        }

        // 2. 5 Gestores (Managers)
        for ($i = 1; $i <= 5; $i++) {
            User::updateOrCreate(
                ['email' => "gestor{$i}@portal.com"],
                [
                    'name' => "Editor Geral {$i}",
                    'password' => $password,
                    'role' => UserRoleEnum::MANAGER->value,
                    'theme_color' => $faker->hexColor,
                ]
            );
        }

        $this->command->info('Escalando 50 Colunistas Profissionais...');
        // 3. 50 Colunistas
        $colunistasIds = [];
        for ($i = 1; $i <= 50; $i++) {
            $user = User::updateOrCreate(
                ['email' => "colunista{$i}@portal.com"],
                [
                    'name' => $faker->name,
                    'password' => $password,
                    'role' => UserRoleEnum::COLUMNIST->value,
                    'bio' => $faker->text(200),
                    'theme_color' => $faker->hexColor,
                ]
            );
            $colunistasIds[] = $user->id;
        }

        // 4. 25 Assinantes Fake
        for ($i = 1; $i <= 25; $i++) {
            User::updateOrCreate(
                ['email' => "assinante{$i}@portal.com"],
                [
                    'name' => "Leitor Assinante VIP {$i}",
                    'password' => $password,
                    'role' => UserRoleEnum::SUBSCRIBER->value,
                    'subscription_status' => $faker->randomElement([SubscriptionStatusEnum::ACTIVE->value, SubscriptionStatusEnum::OVERDUE->value]),
                    'theme_color' => $faker->hexColor,
                ]
            );
        }

        $this->command->info('Estruturando 20 Categorias Editoriais...');
        // 5. 20 Categorias
        $categoriasIds = [];
        $categoriaNomes = [
            'Política', 'Tecnologia', 'Economia', 'Esportes', 'Mundo', 'Cotidiano', 'Saúde',
            'Educação', 'Ciência', 'Entretenimento', 'Cultura', 'Agro', 'Meio Ambiente',
            'Veículos', 'Moda', 'Gastronomia', 'Turismo', 'Games', 'Literatura', 'Comportamento'
        ];

        foreach ($categoriaNomes as $nomeCat) {
            try {
                $cat = Category::updateOrCreate(
                    ['name' => $nomeCat],
                    ['theme_color' => $faker->hexColor]
                );
                $categoriasIds[] = $cat->id;
            } catch (\Exception $e) {
                $cat = Category::where('slug', Str::slug($nomeCat))->first();
                if ($cat) $categoriasIds[] = $cat->id;
            }
        }

        $this->command->info('Injetando 1000 Notícias... Apertem os cintos!');
        // 6. 1000 Notícias
        // Otimização: O Array e o bulk insert poderiam ser feitos, mas o usuário quer uso expresso do updateOrCreate.
        for ($i = 1; $i <= 1000; $i++) {
            $title = rtrim($faker->sentence(rand(4, 8)), '.') . " - " . uniqid(); // Força titulo totalmente unico
            
            try {
                News::updateOrCreate(
                    ['slug' => Str::slug($title)],
                    [
                    'title' => $title,
                    'content' => "<p><strong>" . $faker->paragraph(2) . "</strong></p><p>" . implode("</p><p>", $faker->paragraphs(3)) . "</p><p><em>Colaboração de Redação</em></p>",
                    'state' => $faker->randomElement([NewsStateEnum::PUBLISHED->value, NewsStateEnum::DRAFT->value, NewsStateEnum::IN_REVIEW->value]),
                    'category_id' => $faker->randomElement($categoriasIds),
                    'author_id' => $faker->randomElement($colunistasIds),
                    'published_at' => clone $faker->dateTimeBetween('-2 years', 'now')
                ]
            );
            } catch (\Exception $e) {
                // Ignore minor collisions over 1000 items
            }
            
            if ($i % 250 === 0) {
                $this->command->info("Atingido marco de $i notícias...");
            }
        }
        
        $this->command->info('Seed Massivo Concluído e Otimizado! Roteamento, Meilisearch e Frontend prontos para teste de estresse.');
    }
}
