<?php

namespace Tests\Feature;

use App\Models\CreatorProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiV1Test extends TestCase
{
    use RefreshDatabase;

    public function test_public_catalog_is_accessible_without_token(): void
    {
        $creator = User::factory()->create(['username' => 'apicreator']);
        CreatorProfile::factory()->approved()->create(['user_id' => $creator->id]);

        $this->getJson('/api/v1/creators')
            ->assertOk()
            ->assertJsonStructure(['data']);

        $this->getJson('/api/v1/creators/apicreator')->assertOk();
    }

    public function test_login_returns_bearer_token(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertOk()->assertJsonStructure(['token', 'user']);
        $this->assertNotEmpty($response->json('token'));
    }

    public function test_login_rejects_invalid_credentials(): void
    {
        $user = User::factory()->create();

        $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'not-the-password',
        ])->assertUnauthorized();
    }

    public function test_authenticated_user_can_list_own_subscriptions(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('api')->plainTextToken;

        $this->withToken($token)
            ->getJson('/api/v1/subscriptions')
            ->assertOk()
            ->assertJsonStructure(['data']);
    }

    public function test_protected_endpoint_rejects_missing_token(): void
    {
        $this->getJson('/api/v1/subscriptions')->assertUnauthorized();
    }

    public function test_logout_revokes_current_token(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('api')->plainTextToken;

        $this->withToken($token)
            ->postJson('/api/v1/logout')
            ->assertOk();

        $this->app['auth']->forgetGuards();

        $this->withToken($token)
            ->getJson('/api/v1/subscriptions')
            ->assertUnauthorized();
    }
}