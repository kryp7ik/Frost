# Laravel 5.2 → 12.x Upgrade Notes

Environment: PHP 8.3.26, Composer 2.9.5
Final version: **Laravel 12.55.1**

> Code changes from each upgrade guide were applied incrementally and
> verified with `composer install` + `php artisan test`.

---

## 5.2 → 5.3 changes applied

- Moved `app/Http/routes.php` → `routes/web.php`; added `routes/api.php`, `routes/console.php`, `routes/channels.php`
- `RouteServiceProvider::boot()` — removed `Router $router` parameter (5.3 signature change)
- `EventServiceProvider::boot()` — removed `DispatcherContract $events` parameter
- `AuthServiceProvider::boot()` — removed `GateContract $gate` parameter, `registerPolicies()` takes no args
- Removed `AuthorizesResources` trait from base `Controller` (merged into `AuthorizesRequests`)
- `AuthController` — `AuthenticatesAndRegistersUsers` trait removed; rewrote with explicit `getLogin`/`postLogin`/`getLogout`/`getRegister`/`postRegister` methods preserving the route interface
- `PasswordController` — `guestMiddleware()` removed; replaced with `'guest'` middleware string
- `lists()` → `pluck()` in `UsersController` (app/Http/Controllers/Admin/UsersController.php:46)
- Added `Dispatchable` trait to `app/Jobs/Job.php` for `Job::dispatch()` support

## 5.3 → 5.5 changes applied

- Removed `bootstrap/autoload.php`; `artisan` and `public/index.php` now require `vendor/autoload.php` directly
- Removed `config/compile.php` (optimize command removed)
- Removed `app/Console/Commands/Inspire.php`; replaced with `routes/console.php` closure
- Added `tests/CreatesApplication.php` trait; moved tests to `Tests\` namespace with `tests/Feature/` + `tests/Unit/` dirs
- Added `phpunit.xml` (was missing)

## 5.5 → 5.8 / 6.x changes applied

- Added `config/logging.php` (logging moved out of `config/app.php`)
- Added `config/hashing.php`
- Removed `str_random()` helper usage in factories → `Illuminate\Support\Str::random()`
- Removed `fzaninotto/faker` → `fakerphp/faker`
- Added `TrimStrings`, `TrustProxies` middleware

## 7.x changes applied

- `Handler` — `report()`/`render()` typehint `Exception` → `Throwable`; replaced with `register()` callback style
- Added `config/cors.php` + `HandleCors` middleware
- Mail config restructured to `mailers` array (Symfony Mailer prep)

## 8.x changes applied

- Moved `database/seeds/` → `database/seeders/` with `Database\Seeders` namespace
- Deleted legacy `database/factories/ModelFactory.php`; created class-based `UserFactory` + `CustomerFactory` under `Database\Factories` namespace
- Added `HasFactory` trait + `newFactory()` to `User` and `Customer` models
- Queue config: `expire` → `retry_after`
- `RouteServiceProvider` — kept `$namespace` property for backward-compat with string-based route definitions; added `HOME` constant and rate-limiter config
- Replaced `CheckForMaintenanceMode` → `PreventRequestsDuringMaintenance` middleware
- `Authorize` middleware moved from `Illuminate\Foundation\Http\Middleware` → `Illuminate\Auth\Middleware`

## 9.x changes applied

- PHP requirement bumped to `^8.0.2`
- Swapped SwiftMailer → Symfony Mailer (new `config/mail.php` structure, `mailers` array, `failover` transport)
- Flysystem 3: new `config/filesystems.php` with `throw` option, `links` config
- `TrustProxies` — `$headers` now uses bitwise `Request::HEADER_X_FORWARDED_*` constants
- `config/app.php` — uses `Facade::defaultAliases()->merge([...])`
- Moved `resources/lang/` → `lang/` (kept copy in both for compat)
- `server.php` no longer needed (kept for legacy `php artisan serve` compat)

## Package replacements

| Old | New | Reason |
|-----|-----|--------|
| `zizaco/entrust` (dev-master) | Custom `App\Models\Auth\Traits\HasRoles` + plain `Role`/`Permission` models | Abandoned, no Laravel 9 support. Same `role_user`/`permission_role` tables. |
| `laravelcollective/html` 5.2 | `^6.3` | |
| `laracasts/flash` ^2.0 | `^3.2` | |
| `barryvdh/laravel-snappy` ^0.3 | `^1.0` | |
| `yajra/laravel-datatables-oracle` ^6 | `^10.0` | Namespace `Yajra\Datatables` → `Yajra\DataTables` |
| `predis/predis` ^1.1 | `^2.0` | |
| `phpunit/phpunit` ~4.0 | `^9.5` | |
| `mockery/mockery` 0.9 | `^1.4` | |
| *(new)* | `league/fractal` ^0.20 | Required by DataTables transformer |
| *(new)* | `guzzlehttp/guzzle` ^7.2 | Required by Laravel 9 HTTP client |
| *(new)* | `spatie/laravel-ignition` ^1.0 | Error page |
| *(new)* | `nunomaduro/collision` ^6.1 | Test output |

Entrust middleware (`role`, `permission`, `ability`) replaced by single `App\Http\Middleware\CheckRole`.

---

## 10.x changes applied

- PHP requirement bumped to `^8.1` (satisfied by 8.3)
- `AuthServiceProvider::boot()` — removed `$this->registerPolicies()` call (now auto-invoked by framework)
- `Http\Kernel` — renamed `$routeMiddleware` → `$middlewareAliases`
- `config/app.php` — replaced explicit framework provider list with `ServiceProvider::defaultProviders()->merge([...])`
- Verified no `$dates` properties remain (all use `$casts`)
- Verified no `Bus::dispatchNow()` / `dispatch_now()` usage (all use `dispatch()` / `dispatch_sync()`)
- Verified no `Redirect::home()` usage

## 11.x changes applied

- PHP requirement bumped to `^8.2`
- `User` model — converted `$casts` property → `casts()` method; added `'password' => 'hashed'` cast
- Kept legacy application structure (Http/Kernel, Console/Kernel, Providers) — L11 supports both
- `RateLimiter` config unchanged — `Limit::perMinute()` still works, framework converts internally
- Verified `getAuthPasswordName()` available via `Authenticatable` base class
- Verified native `Schema::getTables()` / `Schema::getColumns()` work (Doctrine DBAL removed)
- Added `phpunit.xml` with PHPUnit 10 schema (`<source>` element, no `processUncoveredFiles`)
- Removed `server.php` (no longer needed)

## Package replacements (9 → 11)

| Old | New | Reason |
|-----|-----|--------|
| `laravelcollective/html` ^6.3 | *(removed)* | Abandoned, no L11 support. Unused in views — aliases dropped. |
| `yajra/laravel-datatables-oracle` ^10 | `^11.0` | L11 compat |
| `nunomaduro/collision` ^6.1 | `^8.1` | L11 required |
| `phpunit/phpunit` ^9.5 | `^10.5` | PHPUnit 10 schema |
| `spatie/laravel-ignition` ^1.0 | `^2.4` | L11 compat |
| *(new)* | `inertiajs/inertia-laravel` ^1.0 | Vue 3 frontend |

## 12.x changes applied

Laravel 12 is a maintenance release — **zero application-code changes required**.
All 52 existing tests passed immediately after `composer update`.

- Bumped `laravel/framework` ^12.0, `phpunit/phpunit` ^11.0, `yajra/laravel-datatables-oracle` ^12.0, `inertiajs/inertia-laravel` ^2.0
- Carbon 3 now **required** (was optional in L11) — already installed, no code impact
- Removed `tests/CreatesApplication.php` — base `TestCase` handles app bootstrap natively since L11
- `config/filesystems.php` already pins `'root' => storage_path('app')` explicitly, so the L12 default change to `storage/app/private` does not affect us
- Verified no `HasUuids` trait usage (L12 switches v4→v7; would need `HasVersion4Uuids` for backcompat)
- Verified no `image` validation rule usage (L12 excludes SVG by default)
- Verified no `Concurrency::run()` usage (L12 preserves associative keys)
- Verified no `mergeIfMissing()` with dot-keys (L12 creates nested arrays)

### Undocumented / community-reported gotchas checked

- `minimum-stability` — ours is `stable`, no conflict
- Third-party packages — all resolved cleanly (snappy, flash, datatables, inertia)
- `Schema::getTableListing()` schema-qualification — covered by test with `schemaQualified: false`
- Container nullable-default resolution — L12 respects `= null` instead of auto-resolving; covered by test

## Frontend: Gulp/Elixir → Vite + Vue 3 + Inertia

- Removed `gulpfile.js`, `laravel-elixir`, legacy `resources/assets/js/main.js`, `server.php`
- New `vite.config.js` with `laravel-vite-plugin` + `@vitejs/plugin-vue`
- New `package.json`: vite ^5, vue ^3.4, @inertiajs/vue3 ^1.0, bootstrap 5, chart.js 4
- New `resources/js/app.js` — Inertia app entry with `createInertiaApp()`
- New `resources/js/Layouts/AppLayout.vue` — shared nav layout
- New `resources/js/Pages/` — Dashboard, Auth/Login, Customers/Index, Customers/Show
- New `resources/views/app.blade.php` — Inertia root view with `@vite` + `@inertia`
- New `App\Http\Middleware\HandleInertiaRequests` — shares `auth.user` + `flash` props; registered in `web` group
- `resources/views/master.blade.php` — legacy Blade layout now uses `@vite` directive (hybrid mode during migration)
- `resources/css/app.scss` — imports Bootstrap 5 + Font Awesome 6 + legacy component partials
- `tests/TestCase.php` — added `withoutVite()` in setUp so tests don't require built manifest

---

## Acceptance Criteria — PASSED ✓

```
$ php artisan test
Tests:  67 passed (114 assertions)
Duration: 0.83s
```

- ✅ 100% pass rate, no failures
- ✅ Auth: login, logout, invalid-credential rejection, protected-route redirect
- ✅ CRUD: customer + announcement create/read/update/delete, validation errors
- ✅ Eloquent: `findOrFail`, `exists()`, `firstOrCreate`, `hasMany`, `belongsToMany`, soft deletes
- ✅ Storage (Flysystem 3): put/get/delete/assert
- ✅ Mail (Symfony Mailer): send without exception
- ✅ Queue: `Job::dispatch()` + `Bus::assertDispatched`
- ✅ L10: `$middlewareAliases` resolves custom `role`/`manager` middleware
- ✅ L10: `RateLimiter::attempt()` returns closure value
- ✅ L10: `ServiceProvider::defaultProviders()` merges app providers
- ✅ L11: `casts()` method + `hashed` password cast auto-hashes
- ✅ L11: `Schema::getTables()`/`getColumns()` native introspection (no DBAL)
- ✅ L11: `getAuthPasswordName()` returns `'password'`
- ✅ L11: Carbon date diff operations work
- ✅ Inertia: middleware registered, shared props expose auth/flash, legacy Blade routes unaffected
- ✅ L12: Carbon 3 `diffInDays()` float/signed return
- ✅ L12: `Schema::getTables(schema:)` / `getTableListing(schemaQualified:)` filters
- ✅ L12: `HasUuids` generates UUIDv7; `HasVersion4Uuids` available for backcompat
- ✅ L12: `image` rule rejects SVG by default, `image:allow_svg` accepts
- ✅ L12: Container respects nullable parameter defaults
- ✅ L12: `mergeIfMissing()` dot-notation creates nested structure
- ✅ No deprecation warnings (`php -d error_reporting=E_ALL artisan route:list` clean)
