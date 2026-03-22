<?php

namespace Tests\Feature;

use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Store\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Tests targeting specific breaking changes introduced in the
 * Laravel 9 → 10 → 11 upgrade path per the official upgrade guides.
 */
class Laravel11UpgradeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    }

    protected function createAdminUser(array $overrides = []): User
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin'], [
            'display_name' => 'Admin', 'description' => 'Admin role',
        ]);
        $managerRole = Role::firstOrCreate(['name' => 'manager'], [
            'display_name' => 'Manager', 'description' => 'Manager role',
        ]);

        $user = User::factory()->create(array_merge([
            'store' => 1,
            'password' => Hash::make('password'),
        ], $overrides));

        $user->roles()->attach([$adminRole->id, $managerRole->id]);

        return $user->fresh();
    }

    // ========================================================================
    // Laravel 10: Model $casts method (replaces removed $dates property)
    // ========================================================================

    public function test_user_model_uses_casts_method()
    {
        $user = $this->createAdminUser();

        // Laravel 11: casts() method should return the cast map
        $casts = $user->getCasts();

        $this->assertArrayHasKey('deleted_at', $casts);
        $this->assertEquals('datetime', $casts['deleted_at']);
    }

    public function test_datetime_cast_returns_carbon_instance()
    {
        $user = $this->createAdminUser();
        $user->delete();

        $trashed = User::withTrashed()->find($user->id);
        $this->assertInstanceOf(Carbon::class, $trashed->deleted_at);
    }

    // ========================================================================
    // Laravel 11: hashed cast for password (automatic hashing)
    // ========================================================================

    public function test_password_hashed_cast_auto_hashes_on_set()
    {
        $user = User::factory()->create([
            'store' => 1,
            'password' => 'plain-text-secret',
        ]);

        $this->assertNotEquals('plain-text-secret', $user->password);
        $this->assertTrue(Hash::check('plain-text-secret', $user->password));
    }

    // ========================================================================
    // Laravel 10: $routeMiddleware renamed to $middlewareAliases
    // ========================================================================

    public function test_middleware_aliases_still_resolve()
    {
        // Custom 'role' and 'manager' aliases must still work
        $kernel = app(\Illuminate\Contracts\Http\Kernel::class);
        $router = app('router');

        $this->assertArrayHasKey('role', $router->getMiddleware());
        $this->assertArrayHasKey('manager', $router->getMiddleware());
        $this->assertArrayHasKey('auth', $router->getMiddleware());
    }

    public function test_role_middleware_blocks_unauthorized_users()
    {
        // User without manager role should be blocked from admin routes
        $user = User::factory()->create(['store' => 1, 'password' => Hash::make('x')]);
        $adminRole = Role::firstOrCreate(['name' => 'admin'], [
            'display_name' => 'Admin', 'description' => 'Admin',
        ]);
        $user->roles()->attach($adminRole->id);

        $response = $this->actingAs($user->fresh())->get('/admin/users');
        $this->assertContains($response->status(), [302, 403]);
    }

    // ========================================================================
    // Laravel 11: Per-second rate limiting (decayMinutes → decaySeconds)
    // ========================================================================

    public function test_rate_limiter_for_api_is_configured()
    {
        $limiter = RateLimiter::limiter('api');
        $this->assertIsCallable($limiter);

        $request = Request::create('/api/test', 'GET');
        $limit = $limiter($request);

        $this->assertInstanceOf(\Illuminate\Cache\RateLimiting\Limit::class, $limit);
        $this->assertEquals(60, $limit->maxAttempts);
    }

    public function test_rate_limiter_attempt_returns_closure_value()
    {
        RateLimiter::clear('test-key');

        // Laravel 10: attempt() now returns the closure's return value
        $result = RateLimiter::attempt('test-key', 5, fn () => 'executed', 60);

        $this->assertEquals('executed', $result);
    }

    // ========================================================================
    // Laravel 11: Native schema methods (Doctrine DBAL removed)
    // ========================================================================

    public function test_schema_native_methods_work_without_doctrine()
    {
        $tables = Schema::getTables();
        $this->assertNotEmpty($tables);

        $tableNames = array_column($tables, 'name');
        $this->assertContains('users', $tableNames);
        $this->assertContains('customers', $tableNames);
    }

    public function test_schema_get_columns_returns_structure()
    {
        $columns = Schema::getColumns('users');
        $this->assertNotEmpty($columns);

        $columnNames = array_column($columns, 'name');
        $this->assertContains('id', $columnNames);
        $this->assertContains('email', $columnNames);
        $this->assertContains('password', $columnNames);
    }

    // ========================================================================
    // Laravel 10: ServiceProvider::defaultProviders() helper
    // ========================================================================

    public function test_default_providers_are_registered()
    {
        $providers = config('app.providers');

        // Framework defaults should be present via defaultProviders()
        $this->assertContains(\Illuminate\Auth\AuthServiceProvider::class, $providers);
        $this->assertContains(\Illuminate\Database\DatabaseServiceProvider::class, $providers);

        // Custom app providers merged in
        $this->assertContains(\App\Providers\RepositoryServiceProvider::class, $providers);
        $this->assertContains(\App\Providers\RouteServiceProvider::class, $providers);
    }

    // ========================================================================
    // Laravel 11: Authenticatable contract has getAuthPasswordName()
    // ========================================================================

    public function test_user_implements_get_auth_password_name()
    {
        $user = $this->createAdminUser();
        $this->assertEquals('password', $user->getAuthPasswordName());
    }

    // ========================================================================
    // Laravel 11: Carbon 3 compatibility (if installed)
    // ========================================================================

    public function test_carbon_date_operations_work()
    {
        $now = Carbon::now();
        $future = $now->copy()->addDays(5);

        // Carbon 3: diffInDays may return float, ensure abs integer comparison
        $diff = abs((int) $now->diffInDays($future));
        $this->assertEquals(5, $diff);
    }

    // ========================================================================
    // Laravel 10: Bus::dispatchSync (dispatchNow removed)
    // ========================================================================

    public function test_dispatch_sync_helper_exists()
    {
        $this->assertTrue(function_exists('dispatch_sync'));
        $this->assertFalse(function_exists('dispatch_now'));
    }

    // ========================================================================
    // Laravel 11: Inertia integration ready
    // ========================================================================

    public function test_inertia_service_provider_is_loaded()
    {
        $this->assertTrue(
            app()->providerIsLoaded(\Inertia\ServiceProvider::class)
        );
    }

    public function test_inertia_middleware_class_exists()
    {
        $this->assertTrue(class_exists(\Inertia\Middleware::class));
    }

    // ========================================================================
    // Removed package: laravelcollective/html (abandoned, no L11 support)
    // ========================================================================

    public function test_laravelcollective_html_is_removed()
    {
        $this->assertFalse(class_exists(\Collective\Html\HtmlServiceProvider::class));
        $this->assertFalse(class_exists(\Collective\Html\FormFacade::class));
    }

    // ========================================================================
    // PHP version requirement
    // ========================================================================

    public function test_php_version_meets_laravel_11_requirement()
    {
        $this->assertTrue(
            version_compare(PHP_VERSION, '8.2.0', '>='),
            'Laravel 11 requires PHP 8.2+'
        );
    }
}
