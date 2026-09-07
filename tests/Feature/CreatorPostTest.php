<?php

namespace Tests\Feature;

use App\Enums\PostVisibility;
use App\Models\CreatorProfile;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreatorPostTest extends TestCase
{
    use RefreshDatabase;

    public function factoryCreator(): User
    {
        $creator = User::factory()->create();
        CreatorProfile::factory()->approved()->create([
            'user_id' => $creator->id,
            'subscription_price_cents' => 1990,
        ]);

        return $creator;
    }

    public function test_verified_creator_can_create_a_post(): void
    {
        $creator = $this->factoryCreator();

        $this->actingAs($creator)
            ->post('/creator/posts', [
                'body' => 'Nova publicação para os seguidores.',
                'visibility' => 'public',
            ])
            ->assertRedirect(route('creator.posts.index'));

        $post = Post::where('user_id', $creator->id)->firstOrFail();

        $this->assertSame('Nova publicação para os seguidores.', $post->body);
        $this->assertSame(PostVisibility::Public, $post->visibility);
        $this->assertSame('published', $post->status);
    }

    public function test_regular_user_cannot_create_posts(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/creator/posts', [
                'body' => 'Tentativa sem permissão.',
                'visibility' => 'public',
            ])
            ->assertForbidden();

        $this->assertSame(0, Post::where('user_id', $user->id)->count());
    }

    public function test_creator_can_update_own_post(): void
    {
        $creator = $this->factoryCreator();
        $post = Post::factory()->public()->create([
            'user_id' => $creator->id,
            'body' => 'Versão original.',
        ]);

        $this->actingAs($creator)
            ->put('/creator/posts/'.$post->id, [
                'body' => 'Versão revisada.',
                'visibility' => 'subscribers_only',
            ])
            ->assertRedirect(route('creator.posts.index'));

        $post->refresh();

        $this->assertSame('Versão revisada.', $post->body);
        $this->assertSame(PostVisibility::SubscribersOnly, $post->visibility);
    }

    public function test_creator_cannot_update_another_creators_post(): void
    {
        $owner = $this->factoryCreator();
        $other = $this->factoryCreator();
        $post = Post::factory()->public()->create([
            'user_id' => $owner->id,
            'body' => 'Conteúdo de outro.',
        ]);

        $this->actingAs($other)
            ->put('/creator/posts/'.$post->id, [
                'body' => 'Roubo de conteúdo.',
                'visibility' => 'public',
            ])
            ->assertForbidden();

        $this->assertSame('Conteúdo de outro.', $post->fresh()->body);
    }
}