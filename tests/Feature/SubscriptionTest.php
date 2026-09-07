<?php

namespace Tests\Feature;

use App\Enums\SubscriptionStatus;
use App\Models\CreatorProfile;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionTest extends TestCase
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

    public function test_subscribe_starts_pending_subscription_for_verified_creator(): void
    {
        $creator = $this->factoryCreator();
        $subscriber = User::factory()->create();

        $this->actingAs($subscriber)
            ->post('/creator/'.$creator->username.'/subscribe')
            ->assertRedirect(route('subscriptions.index'));

        $subscription = Subscription::where('user_id', $subscriber->id)
            ->where('creator_id', $creator->id)
            ->firstOrFail();

        $this->assertSame(SubscriptionStatus::Pending, $subscription->status);
        $this->assertSame(1990, $subscription->value_cents);
    }

    public function test_subscribe_is_rejected_for_unverified_creator(): void
    {
        $creator = User::factory()->create();
        $subscriber = User::factory()->create();

        $this->actingAs($subscriber)
            ->post('/creator/'.$creator->username.'/subscribe')
            ->assertStatus(422);
    }

    public function test_user_cannot_subscribe_to_own_profile(): void
    {
        $creator = $this->factoryCreator();

        $this->actingAs($creator)
            ->post('/creator/'.$creator->username.'/subscribe')
            ->assertForbidden();
    }

    public function test_active_subscription_is_not_duplicated(): void
    {
        $creator = $this->factoryCreator();
        $subscriber = User::factory()->create();

        Subscription::factory()->create([
            'user_id' => $subscriber->id,
            'creator_id' => $creator->id,
            'value_cents' => 1990,
            'status' => SubscriptionStatus::Active->value,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addMonth(),
        ]);

        $this->actingAs($subscriber)
            ->post('/creator/'.$creator->username.'/subscribe')
            ->assertRedirect(route('subscriptions.index'));

        $this->assertSame(
            1,
            Subscription::where('user_id', $subscriber->id)
                ->where('creator_id', $creator->id)
                ->count()
        );
    }

    public function test_subscriber_can_cancel_own_active_subscription(): void
    {
        $creator = $this->factoryCreator();
        $subscriber = User::factory()->create();
        $subscription = Subscription::factory()->active()->create([
            'user_id' => $subscriber->id,
            'creator_id' => $creator->id,
            'value_cents' => 1990,
        ]);

        $this->actingAs($subscriber)
            ->delete('/subscriptions/'.$subscription->id)
            ->assertRedirect();

        $this->assertSame(SubscriptionStatus::Cancelled, $subscription->fresh()->status);
    }
}