# Laravel 5.2 → 9.x Upgrade Notes

Environment: PHP 8.1.2, Composer 2.9.5
Final version: **Laravel 9.52.21**

> Because only PHP 8.1 was available (Laravel 5.x–7.x do not run on PHP 8),
> code changes from each upgrade guide were applied incrementally and
> verified with `composer install` + `php artisan test` on Laravel 9.

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

## Acceptance Criteria — PASSED ✓

```
$ php artisan test
Tests:  28 passed
Time:   2.09s
```

- ✅ 100% pass rate, no failures
- ✅ Auth: login, logout, invalid-credential rejection, protected-route redirect
- ✅ CRUD: customer + announcement create/read/update/delete, validation errors
- ✅ Eloquent: `findOrFail`, `exists()`, `firstOrCreate`, `hasMany`, `belongsToMany`, soft deletes
- ✅ Storage (Flysystem 3): put/get/delete/assert
- ✅ Mail (Symfony Mailer): send without exception
- ✅ Queue: `Job::dispatch()` + `Bus::assertDispatched`
- ✅ Custom facades/helpers resolve
- ✅ No deprecation warnings (`php -d error_reporting=E_ALL artisan route:list` clean)
