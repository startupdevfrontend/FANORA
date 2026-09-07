<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CreatorProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeExploreTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_renders_featured_and_recent_creators(): void
    {
        $creator = User::factory()->create(['name' => 'Criadora Destaque']);
        CreatorProfile::factory()->approved()->create([
            'user_id' => $creator->id,
            'display_name' => 'Criadora Destaque',
            'is_featured' => true,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Criadora Destaque');
    }

    public function test_explore_searches_by_name_and_username(): void
    {
        $creator = User::factory()->create(['username' => 'estrelagrande', 'name' => 'Estrela Grande']);
        CreatorProfile::factory()->approved()->create([
            'user_id' => $creator->id,
            'display_name' => 'Estrela Grande',
            'tagline' => 'Conteúdo exclusivo de qualidade',
        ]);

        $this->get('/explore?q=estrelagrande')
            ->assertOk()
            ->assertSee('estrelagrande');

        $this->get('/explore?q=qualidade')
            ->assertOk()
            ->assertSee('Estrela Grande');
    }

    public function test_explore_filters_by_category_slug(): void
    {
        $category = Category::factory()->create(['slug' => 'musica', 'is_active' => true]);
        $other = Category::factory()->create(['slug' => 'arte']);

        $inCategory = User::factory()->create(['username' => 'cantorana']);
        $profile = CreatorProfile::factory()->approved()->create(['user_id' => $inCategory->id]);
        $profile->categories()->attach($category);

        $outCategory = User::factory()->create(['username' => 'pintor_xyz']);
        $profileOut = CreatorProfile::factory()->approved()->create(['user_id' => $outCategory->id]);
        $profileOut->categories()->attach($other);

        $this->get('/explore?category=musica')
            ->assertOk()
            ->assertSee('cantorana')
            ->assertDontSee('pintor_xyz');
    }
}