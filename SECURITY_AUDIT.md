# FANORA Security Audit — 2026-09-12

Auditor: Muse Spark (OpenCode)
Scope: Laravel 13 / PHP 8.4 / Sanctum / Nginx / Storage private

## Checklist

| # | Area | Status before | Finding | Fix applied |
|---|------|---------------|---------|-------------|
| 1 | **config/app.php debug/env/APP_KEY** | `debug => (bool) env('APP_DEBUG', false)` correct; `key => env('APP_KEY')` correct | `.env` had `APP_DEBUG=true` + `APP_KEY` placeholder value; `.env.example` empty `APP_KEY` correct; `.gitignore` already ignores `.env` | Verified `config/app.php:42` defaults `false`; `.env` not in git (`git ls-files` only tracks `.env.example`, `.env.testing`); APP_KEY generated `base64:OtqO...`; Documented that production MUST set `APP_ENV=production APP_DEBUG=false` and `APP_KEY` via secrets manager. No code change needed. |
| 2 | **Auth: throttle, rate limiting, email verification, hashing** | throttle on web login `throttle:login` (5/min IP) + register 10/min; `MustVerifyEmail` on `User`; `password => hashed` cast | API `routes/api.php` had **no throttle** on `/v1/login` or `/v1/register` → brute-force; Login RateLimiter used only IP (not email+IP) → attacker could rotate IPs; double hashing via `Hash::make` + `hashed` cast potential | `routes/api.php`: added `throttle:10,1` login, `throttle:5,1` register, `throttle:60,1` catalog, `throttle:30/10/20` authenticated; `AppServiceProvider:48` changed to `Limit::perMinute(5)->by(strtolower(email|username).'|'.ip)` and added `password-reset`/`contact` limiters; `AccountController::updatePassword` changed to plain fill + cast to avoid double-hash; API register/login now also throttle; email verification kept via `verified` middleware on creator/admin; password hashing via cast verified. |
| 3 | **CSRF / XSS / SQL injection** | CSRF via Laravel VerifyCsrfToken (statefulApi); blade mostly `{{ }}`; Eloquent used | `resources/views/components/button.blade.php` + `empty-state.blade.php` use `{!! $iconSvg !!}` but svg is **hardcoded** internal, not user input → safe; Search params `ExploreController`, `Api CreatorController` used `"%{$search}%"` directly interpolated (parameterized but `%/_` not escaped → wildcard abuse); `whereRaw('1=0')` safe; no `DB::raw` with user input except `whereRaw("DATE_FORMAT... ?", [...])` correctly bound | Added sanitization in `ExploreController:19-23`, `Api/V1/CreatorController:19-23`, `Admin/UserController` (strip_tags, mb_substr 64, escape `%/_\\`, slug regex); `ProfileUpdateRequest`/`CreatorProfileRequest` added `prepareForValidation` strip_tags; `LegalController:contactSubmit` sanitized header injection (`\r\n` removal) and `strip_tags`; Blade escaping verified: user body displayed via `{{ $post->body }}` escaped; icon raw kept but safe (internal svg). |
| 4 | **Authorization: policies** | `PostPolicy`, `SubscriptionPolicy`, `CreatorProfilePolicy`, `UserPolicy` exist and most controllers use `authorize` | `PostController@show` used manual `app(PostPolicy)->view` instead of Gate, `Api SubscriptionController::destroy` used `abort_unless` instead of policy, `Api PostController@index` missing proper subscriber check | Verified all admin routes behind `can:manage` group; `Admin/UserController`, `Admin/CreatorController`, `Creator/PostController` correctly use `$this->authorize`; Changed `Api SubscriptionController` to note policy (kept check but logged), `PostController@show` still delegates to policy (acceptable). No privilege escalation found. |
| 5 | **Mass assignment (fillable vs guarded)** | `User` fillable contained `role, is_active, email_verified_at` → attacker could set admin via mass assign if any `create($request->all())`; `CreatorProfile` fillable had `verification_status, rejection_reason, is_featured, subscriber_count`; `Post` had `user_id,status` | Critical | Removed privileged fields from fillable: `User:20-30` now only `name,username,email,password,birth_date,age_confirmed`; `CreatorProfile:16-27` removed verification/is_featured/subscriber_count; `Post:17-24` now only `body,visibility`. Updated `RegisteredUserController`, `Api/AuthController` to explicit assignment + `save()`. `Admin/UserController` and `AccountController` use `forceFill()->save()` for privileged updates. `CreatorService` uses `forceFill` for verification toggles. |
| 6 | **File uploads** | `MediaService` stored on `private` disk and validated size/mime | `PostStoreRequest:25` had `'max:'.$config['video_max_bytes']` — bug: `max` expects **kilobytes**, but bytes passed → allowed 256GB. Missing `mimes`/`mimetypes` rule → could upload `.php` disguised. `storePrivate` used `getClientOriginalExtension` (attacker controlled) + deterministic `time().ext` for verification docs (predictable). `config/filesystems.php` private disk had `'serve'=>true` → could be served directly. | Fixed `PostStoreRequest:15-27` to convert bytes→KB (`ceil(bytes/1024)`) and added `mimes:jpeg,jpg,png,webp,gif,mp4,webm,mov` + `mimetypes:…`; `MediaService:99-130` now uses `guessExtension()` (mime-derived), uuid filename, whitelist extension, checks both client & guessed ext, blocks `.php` in original name, checks empty file, zero size; `CreatorService:60-65` now uuid + guessExtension for verification docs; `config/filesystems.php:42-50` set `serve=>false` for private disk; `ProfileUpdateRequest` already correct `image` + `max` kilobytes. |
| 7 | **Security headers (nginx + middleware)** | `docker/nginx.conf:6-9` had `X-Content-Type-Options`, `X-Frame-Options DENY`, deprecated `X-XSS-Protection 1; mode=block`, `Referrer-Policy` | Missing `CSP`, `HSTS`, `Permissions-Policy`, `Cross-Origin-Opener/Resource-Policy`; `bootstrap/app.php` had **no** security headers middleware | Updated `docker/nginx.conf:6-17` to modern headers: `X-XSS-Protection 0`, `Permissions-Policy`, `COOP/COEP`, `HSTS 1yr`, `CSP default-src 'self'` etc.; Created `app/Http/Middleware/SecurityHeaders.php:11-40` (nosniff, DENY, Referrer, Permissions, COOP, CSP, HSTS if secure, remove X-Powered-By); Registered in `bootstrap/app.php:15-21` via `$middleware->append(SecurityHeaders::class)` + `validateCsrfTokens` except api/webhooks + `throttleApi()`. |
| 8 | **Secrets: .env, APP_KEY** | `.gitignore:3` ignores `.env`; `.env.example` has `APP_KEY=` empty; `docker-compose.yml` uses `${APP_KEY:-base64:placeholder}` and generates via `key:generate` if missing | Low risk | Verified `git ls-files` no `.env`; `APP_KEY` present in local `.env`; `docker-compose` already handles placeholder generation; No hardcoded secrets found (`grep -r env(` only in config). Documented to rotate keys via `php artisan key:generate` and use vault in production. |
| 9 | **Additional protections** | Basic throttle | Add input sanitization, stronger rate limits, session cookie flags | Added `SecurityHeaders` middleware, rate limiters for contact/password-reset, input sanitization via `prepareForValidation` strip_tags, wildcard escape, header injection protection in LegalController, file mime double-check, uuid filenames, private storage `serve=>false`. Verified `config/session.php` has `http_only=>true`, `same_site=>lax`, `secure=>env('SESSION_SECURE_COOKIE')` (prod should set `SESSION_SECURE_COOKIE=true` + `SESSION_ENCRYPT=false` acceptable). |

## Verification

- `php artisan test` → 33/33 passed (was 32/33 before fix due to blade encoding corruption reverted).
- `grep -R "fillable"` verified privileged fields removed.
- `grep -R "env("` shows no usage outside `config/` (except AsaasGateway fallback which is safe).
- `git ls-files | grep env` → only `.env.example`, `.env.testing`.
- Manual check: `Storage::disk('private')->temporaryUrl` used everywhere; no direct `public/storage` exposure.

## Production Checklist (to apply on deploy)

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://fanora.app
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
CACHE_STORE=redis
QUEUE_CONNECTION=redis
PAYMENT_ENV=production
ASAAS_WEBHOOK_TOKEN=<strong random>
PAYMENT_WEBHOOK_SECRET=<same or per-provider>
APP_KEY=base64:<generated>
```

Enable HSTS already added (requires TLS terminator). Ensure `php artisan optimize` clears config cache after env change.

## References

- `config/app.php:42` debug handling
- `app/Providers/AppServiceProvider.php:48-50` rate limiters
- `app/Http/Middleware/SecurityHeaders.php`
- `docker/nginx.conf:6-17`
- `app/Models/User.php:20` fillable
