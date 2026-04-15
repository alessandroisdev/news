<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\News;
use App\Models\User;
use App\Enums\NewsStateEnum;
use App\Enums\UserRoleEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FallbackRouteLogicTest extends TestCase
{
    use RefreshDatabase;

    public function test_fallback_route_renders_category()
    {
        $category = Category::create([
            'name' => 'Tech',
            'slug' => 'tech',
            'theme_color' => '#000000'
        ]);

        $response = $this->get('/tech');
        $response->assertStatus(200);
    }

    public function test_published_news_is_resolved()
    {
        $user = User::factory()->create(['role' => UserRoleEnum::ADMIN->value, 'theme_color'=>'#000']);
        $category = Category::create(['name' => 'Tech', 'slug' => 'tech', 'theme_color' => '#000']);

        $news = News::create([
            'title' => 'Titulo Qualquer',
            'content' => 'Lorem ipsum',
            'slug' => 'titulo-qualquer',
            'state' => NewsStateEnum::PUBLISHED->value,
            'category_id' => $category->id,
            'author_id' => $user->id,
        ]);

        $response = $this->get('/titulo-qualquer');
        $response->assertStatus(200);
    }

    public function test_unpublish_news_rejects_with_404_by_state_machine()
    {
        $user = User::factory()->create(['role' => UserRoleEnum::COLUMNIST->value, 'theme_color'=>'#000']);
        $category = Category::create(['name' => 'Tech', 'slug' => 'tech', 'theme_color' => '#000']);

        $news = News::create([
            'title' => 'Rascunho Teste',
            'content' => 'Lorem ipsum',
            'slug' => 'rascunho-teste',
            'state' => NewsStateEnum::IN_REVIEW->value, // Blocked Status Array
            'category_id' => $category->id,
            'author_id' => $user->id,
        ]);

        $response = $this->get('/rascunho-teste');
        $response->assertStatus(404);
    }
}
