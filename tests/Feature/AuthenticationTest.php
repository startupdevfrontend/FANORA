<?php

namespace Tests\Feature;

use App\Models\Consent;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_creates_account_profile_and_consents(): void
    {
        $response = $this->post('/register', [
            'name' => 'Novo Usuário',
            'username' => 'novousuario',
            'email' => 'novo@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'birth_date' => '2000-05-10',
            'terms' => 'yes',
            'privacy' => 'yes',
        ]);

        $response->assertRedirect(route('home'));

        $user = User::where('email', 'novo@example.com')->firstOrFail();

        $this->assertSame('novousuario', $user->username);
        $this->assertTrue($user->isActive());
        $this->assertDatabaseHas('profiles', ['user_id' => $user->id]);
    }

    public function test_registration_is_rejected_when_data_is_invalid(): void
    {
        $this->post('/register', [
            'name' => 'Sem Maioridade',
            'username' => 'menor',
            'email' => 'menor@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'birth_date' => now()->subYears(15)->toDateString(),
            'terms' => 'yes',
            'privacy' => 'yes',
        ])->assertSessionHasErrors('birth_date');

        $this->assertDatabaseMissing('users', ['email' => 'menor@example.com']);
    }

    public function test_login_succeeds_with_valid_credentials(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect();

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])->assertSessionHasErrors();

        $this->assertGuest();
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/logout')
            ->assertRedirect();

        $this->assertGuest();
    }
}