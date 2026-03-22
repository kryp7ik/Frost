<?php

namespace Tests\Feature;

use App\Http\Middleware\HandleInertiaRequests;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Verifies the Inertia + Vue 3 integration layer works correctly
 * without breaking legacy Blade-rendered routes.
 */
class InertiaIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    }

    protected function createAdminUser(): User
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin'], [
            'display_name' => 'Admin', 'description' => 'Admin role',
        ]);
        $managerRole = Role::firstOrCreate(['name' => 'manager'], [
            'display_name' => 'Manager', 'description' => 'Manager role',
        ]);

        $user = User::factory()->create([
            'store' => 1,
            'password' => Hash::make('password'),
        ]);

        $user->roles()->attach([$adminRole->id, $managerRole->id]);

        return $user->fresh();
    }

    public function test_inertia_middleware_is_registered_in_web_group()
    {
        $kernel = app(\App\Http\Kernel::class);
        $groups = $kernel->getMiddlewareGroups();

        $this->assertContains(
            HandleInertiaRequests::class,
            $groups['web']
        );
    }

    public function test_inertia_shared_props_include_auth_user_when_logged_in()
    {
        $user = $this->createAdminUser();

        $request = Request::create('/', 'GET');
        $request->setLaravelSession(app('session.store'));
        $request->setUserResolver(fn () => $user);

        $middleware = new HandleInertiaRequests();
        $shared = $middleware->share($request);

        $this->assertArrayHasKey('auth', $shared);
        $this->assertEquals($user->id, $shared['auth']['user']['id']);
        $this->assertEquals($user->name, $shared['auth']['user']['name']);
        $this->assertEquals($user->email, $shared['auth']['user']['email']);
    }

    public function test_inertia_shared_props_auth_is_null_when_guest()
    {
        $request = Request::create('/', 'GET');
        $request->setLaravelSession(app('session.store'));
        $request->setUserResolver(fn () => null);

        $middleware = new HandleInertiaRequests();
        $shared = $middleware->share($request);

        $this->assertArrayHasKey('auth', $shared);
        $this->assertNull($shared['auth']['user']);
    }

    public function test_inertia_root_view_exists()
    {
        $this->assertTrue(view()->exists('app'));
    }

    public function test_legacy_blade_routes_still_render_with_inertia_middleware()
    {
        // Inertia middleware should pass through non-Inertia responses unchanged
        $response = $this->get('/users/login');
        $response->assertStatus(200);
        $response->assertDontSee('X-Inertia');
    }

    public function test_legacy_authenticated_routes_still_work()
    {
        $user = $this->createAdminUser();

        $response = $this->actingAs($user)->get('/customers');
        $response->assertStatus(200);
    }

    public function test_inertia_version_returns_string_or_null()
    {
        $request = Request::create('/', 'GET');
        $request->setLaravelSession(app('session.store'));

        $middleware = new HandleInertiaRequests();
        $version = $middleware->version($request);

        $this->assertTrue(is_string($version) || is_null($version));
    }
}
