# FANORA

Plataforma de conteúdo por assinatura (creator economy) construída com Laravel 13 + Blade + Tailwind. Criadores publicam posts públicos e exclusivos; assinantes pagam para desbloquear conteúdo exclusivo.

## Stack

- **Back-end:** Laravel 13 (PHP 8.4)
- **Front-end:** Blade + Tailwind CSS + Alpine.js (Vite)
- **Banco:** MySQL 8 (principal), SQLite `:memory:` (testes)
- **Cache/Queue:** Redis
- **Servidor:** nginx + php-fpm (Docker Compose)
- **Auth API:** Laravel Sanctum (tokens para o futuro app mobile)
- **PWA:** manifest + service worker + ícones gerados via `php artisan fanora:pwa-icons`

## Subir o ambiente

```sh
docker compose up -d --build
```

O comando de start do container `app` já executa `migrate --force` e `db:seed --force`
(o seeder é idempotente — `firstOrCreate` — e se recusa a rodar em produção).

- Aplicação: http://localhost:8080
- MySQL (host): `localhost:33060`, db `fanora`, user `fanora`, senha `secret`
- Redis: `localhost:6379`

> **Nota (Docker Desktop / 9p):** todo o código é gravado na imagem. Apenas
> `./docker/storage` (arquivos privados) e `./public` (assets estáticos) são
> montados via bind. Alterar código exige `docker compose build app`.

## Contas demo

| Conta | Email | Senha | Perfil |
|---|---|---|---|
| Admin | `admin@fanora.app` | `password` | Painel admin |
| Criador | `aurora@example.com` | `password` | `aurora` (Lifestyle) |
| Criador | `danivega@example.com` | `password` | `danivega` (Creator) |
| Criador | `leonegro@example.com` | `password` | `leonegro` (Fitness) |
| Criador | `miareyes@example.com` | `password` | `miareyes` (Moda) |
| Criador | `theobloom@example.com` | `password` | `theobloom` (Gaming) |
| Assinante | `demo1@example.com` … `demo6@example.com` | `password` | Assinatura sandbox ativa |

## Testes

```sh
# dentro do container app (dependências dev são necessárias)
composer install --no-interaction
php artisan test
```

A suíte usa `phpunit.xml` (sqlite `:memory:`, `APP_ENV=testing`) com
`<server>`/`<env force="true">` para que `runningUnitTests()` seja detectado
(CSRF desativado). O `.env.testing` é versionado e incluído na imagem.

## Pagamentos

O `PaymentGateway` é um stub de sandbox (`config/payment.php`). Assinaturas
criadas ficam `pending`; um webhook de demonstração (`POST /webhooks/payment`)
pode ativá-las. Em ambiente não-produção existe o comando de conveniência
`sandboxActivate` para ativar uma assinatura manualmente.

## Comandos úteis

```sh
php artisan fanora:pwa-icons        # gera public/icons (1024→32 + maskable)
php artisan db:seed --force         # dados demo (idempotente)
php artisan migrate --force         # schema
```

## Estrutura

```
app/
  Enums/                 # SubscriptionStatus, PostVisibility, UserRole, ...
  Http/Controllers/      # Web (Guest, Creator, Dashboard, Admin) + Api/V1 + webhooks
  Http/Requests/         # FormRequests com validação pt-BR
  Models/                # User, CreatorProfile, Post, Subscription, Block, ...
  Policies/              # PostPolicy, SubscriptionPolicy, UserPolicy
  Services/              # SubscriptionService, MediaService, AuditService, SandboxGateway
routes/
  web.php                # rotas públicas + autenticadas + admin + creator
  api.php                # API v1 (Sanctum) — preparada para app mobile
  console.php            # comandos Artisan (fanora:pwa-icons)
resources/views/         # Blade (layouts, componentes, páginas)
tests/Feature/           # 33 testes cobrindo auth, posts, assinaturas, admin e API
```

## Licença

Projeto interno — sem licença pública.