<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Block;
use App\Models\Category;
use App\Models\Follow;
use App\Models\Post;
use App\Models\Profile;
use App\Models\CreatorProfile;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Seeds demo data. It is never executed in production.
 */
class FanoraDatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        abort_if(app()->environment('production'), 403, 'Não execute seeders de demonstração em produção.');

        // ------------------------------------------------------------------
        // Categories
        // ------------------------------------------------------------------
        $index = 0;
        $categories = collect([
            ['Lifestyle', 'Lifestyle'],
            ['Fitness', 'Fitness'],
            ['Moda', 'Moda'],
            ['Música', 'Música'],
            ['Gaming', 'Gaming'],
            ['Creator', 'Creator'],
            ['Conteúdo exclusivo', 'Conteúdo exclusivo'],
        ])->map(function ($item) use (&$index) {
            $category = Category::firstOrCreate(
                ['slug' => strtolower(Str::slug($item[0]))],
                ['name' => $item[0], 'description' => $item[1], 'is_active' => true, 'sort' => $index],
            );
            $index++;

            return $category;
        });

        // ------------------------------------------------------------------
        // Admin
        // ------------------------------------------------------------------
        $admin = User::firstOrCreate(
            ['email' => 'admin@fanora.app'],
            [
                'name' => 'Administração FANORA',
                'username' => 'fanora',
                'password' => Hash::make('password'),
                'birth_date' => '1990-01-01',
                'age_confirmed' => true,
                'role' => UserRole::Admin->value,
                'email_verified_at' => now(),
            ]
        );

        Profile::firstOrCreate(['user_id' => $admin->id], [
            'bio' => 'Conta administrativa oficial da FANORA.',
        ]);

        // ------------------------------------------------------------------
        // Demo creators + users
        // ------------------------------------------------------------------
        $creators = [];
        $creatorSeed = [
            ['Aurora Lane', 'aurora', 'Solar', 1990],
            ['Dani Vega', 'danivega', 'Creator', 1990],
            ['Leo Martins', 'leonegro', 'Fitness', 1990],
            ['Mia Reyes', 'miareyes', 'Moda', 1990],
            ['Theo Bloom', 'theobloom', 'Gaming', 1990],
        ];

        $cities = ['São Paulo', 'Rio de Janeiro', 'Belo Horizonte', 'Curitiba', 'Porto Alegre', 'Salvador'];

        foreach ($creatorSeed as [$name, $username, $category, $birthYear]) {
            $user = User::firstOrCreate(
                ['email' => "{$username}@example.com"],
                [
                    'name' => $name,
                    'username' => $username,
                    'password' => Hash::make('password'),
                    'birth_date' => "{$birthYear}-06-15",
                    'age_confirmed' => true,
                    'role' => UserRole::User->value,
                    'email_verified_at' => now(),
                ]
            );

            $profile = Profile::firstOrCreate(['user_id' => $user->id], [
                'bio' => "Meu perfil oficial na FANORA — {$category}.",
                'location' => $cities[array_search($username, array_column($creatorSeed, 1)) % count($cities)],
            ]);

            $creatorProfile = CreatorProfile::firstOrCreate(['user_id' => $user->id], [
                'display_name' => $name,
                'tagline' => "{$category} • contenido exclusivo",
                'subscription_price_cents' => 1990,
                'verification_status' => 'approved',
                'is_featured' => true,
                'subscriber_count' => 0,
            ]);

            $cat = strtolower($category) === 'conteúdo exclusivo'
                ? $categories->last()
                : $categories->firstWhere(fn ($c) => $c->name === $category) ?? $categories->first();

            $creatorProfile->categories()->sync([$cat->id]);

            $creators[] = $user;
        }

        // Demo regular users
        $subscribers = [];
        $demoNames = ['Carlos Pereira', 'Mariana Souza', 'Rafael Alves', 'Beatriz Lima', 'Eduardo Rocha', 'Fernanda Costa'];

        foreach (range(1, 6) as $i) {
            $user = User::firstOrCreate(
                ['email' => "demo{$i}@example.com"],
                [
                    'name' => $demoNames[$i - 1],
                    'username' => "demo{$i}",
                    'password' => Hash::make('password'),
                    'birth_date' => '1994-03-22',
                    'age_confirmed' => true,
                    'role' => UserRole::User->value,
                    'email_verified_at' => now(),
                ]
            );

            Profile::firstOrCreate(['user_id' => $user->id], ['bio' => "Usuário demo {$i} — conta de teste da FANORA."]);
            $subscribers[] = $user;
        }

        // ------------------------------------------------------------------
        // Posts (public + exclusive)
        // ------------------------------------------------------------------
        foreach ($creators as $creator) {
            $count = Post::where('user_id', $creator->id)->count();

            if ($count > 0) {
                continue;
            }

            foreach (range(1, 4) as $i) {
                Post::create([
                    'user_id' => $creator->id,
                    'body' => "Publicação de demonstração {$i} — conteúdo {$category} criado para o ambiente de testes FANORA. Compartilhando ideias, bastidores e novidades com a comunidade.",
                    'visibility' => $i % 2 === 0 ? 'subscribers_only' : 'public',
                    'is_premium_paid' => false,
                    'status' => 'published',
                    'published_at' => now()->subDays($i * 2),
                ]);
            }
        }

        // ------------------------------------------------------------------
        // Follows
        // ------------------------------------------------------------------
        foreach ($subscribers as $subscriber) {
            foreach ($creators as $creator) {
                if ($subscriber->id === $creator->id) {
                    continue;
                }

                Follow::firstOrCreate([
                    'follower_id' => $subscriber->id,
                    'following_id' => $creator->id,
                ]);
                $creator->creatorProfile->increment('subscriber_count');
            }
        }

        // ------------------------------------------------------------------
        // Sandbox-only demo subscriptions (active, marked sandbox)
        // ------------------------------------------------------------------
        if (config('payment.env') === 'sandbox') {
            foreach ($subscribers as $index => $subscriber) {
                $creator = $creators[$index % count($creators)];

                Subscription::firstOrCreate(
                    ['user_id' => $subscriber->id, 'creator_id' => $creator->id],
                    [
                        'value_cents' => 1990,
                        'status' => 'active',
                        'starts_at' => now()->subDays(15),
                        'ends_at' => now()->addDays(15),
                        'gateway_transaction_id' => 'sandbox_seed_'.($index + 1),
                    ]
                );
            }
        }

        $this->command->info('FANORA seeder concluído.');
    }
}