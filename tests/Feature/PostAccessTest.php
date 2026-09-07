<?php

namespace Tests\Feature;

use App\Enums\PostVisibility;
use App\Enums\SubscriptionStatus;
use App\Models\Block;
use App\Models\CreatorProfile;
use App\Models\Post;
use App\Models\Subscription;
use App\Models\User;
use App\Policies\PostPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_post_is_visible_to_guests(): void
    {
        $creator = User::factory()->create(['username' => 'criadorab']);
        $post = Post::factory()->public()->create([
            'user_id' => $creator->id,
            'body' => 'Conteúdo aberto para todo mundo.',
        ]);

        $this->get('/posts/'.$creator->username.'/'.$post->id)
            ->assertOk()
            ->assertSee('Conteúdo aberto para todo mundo.');
    }

    public function test_exclusive_post_is_locked_for_non_subscribers(): void
    {
        $creator = User::factory()->create(['username' => 'premiumcreator']);
        $post = Post::factory()->exclusive()->create([
            'user_id' => $creator->id,
            'body' => 'Conteúdo secreto exclusivo.',
        ]);

        $this->get('/posts/'.$creator->username.'/'.$post->id)
            ->assertOk()
            ->assertSee('Conteúdo exclusivo')
            ->assertDontSee('Conteúdo secreto exclusivo.');
    }

    public function test_exclusive_post_is_unlocked_for_active_subscribers(): void
    {
        $creator = User::factory()->create(['username' => 'premiumcreator']);
        $post = Post::factory()->exclusive()->create([
            'user_id' => $creator->id,
            'body' => 'Conteúdo secreto exclusivo.',
        ]);

        $subscriber = User::factory()->create();
        Subscription::factory()->create([
            'user_id' => $subscriber->id,
            'creator_id' => $creator->id,
            'value_cents' => 1990,
            'status' => SubscriptionStatus::Active->value,
            'starts_at' => now()->subDays(2),
            'ends_at' => now()->addMonth(),
        ]);

        $this->actingAs($subscriber)
            ->get('/posts/'.$creator->username.'/'.$post->id)
            ->assertOk()
            ->assertSee('Conteúdo secreto exclusivo.');
    }

    public function test_draft_posts_return_404_for_non_admins(): void
    {
        $creator = User::factory()->create(['username' => 'editoragata']);
        $post = Post::factory()->create([
            'user_id' => $creator->id,
            'status' => 'draft',
            'visibility' => PostVisibility::Public->value,
        ]);

        $this->get('/posts/'.$creator->username.'/'.$post->id)->assertNotFound();
    }

    public function test_blocked_pair_cannot_view_each_others_posts(): void
    {
        $creator = User::factory()->create(['username' => 'alvo']);
        $blocked = User::factory()->create();

        Block::create(['blocker_id' => $blocked->id, 'blocked_id' => $creator->id]);

        $publicPost = Post::factory()->public()->create(['user_id' => $creator->id]);

        $this->assertFalse((new PostPolicy)->view($blocked, $publicPost));
        $this->assertTrue((new PostPolicy)->view(null, $publicPost));
    }
}