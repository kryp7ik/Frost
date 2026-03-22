<?php

namespace Tests\Feature;

use App\Models\Auth\Role;
use App\Models\Auth\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Fortify\Features;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Tests\TestCase;

class TwoFactorAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\App\Http\Middleware\PreventRequestForgery::class);
    }

    protected function createAdminUser(): User
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin'], [
            'display_name' => 'Admin', 'description' => 'Admin role',
        ]);

        $user = User::factory()->create(['store' => 1]);
        $user->roles()->attach($adminRole->id);

        return $user->fresh();
    }

    // ========================================================================
    // Configuration & setup
    // ========================================================================

    public function test_fortify_two_factor_feature_is_enabled()
    {
        $this->assertTrue(Features::enabled(Features::twoFactorAuthentication()));
    }

    public function test_user_model_uses_two_factor_authenticatable_trait()
    {
        $this->assertContains(
            TwoFactorAuthenticatable::class,
            class_uses_recursive(User::class)
        );
    }

    public function test_two_factor_columns_exist_on_users_table()
    {
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('users');

        $this->assertContains('two_factor_secret', $columns);
        $this->assertContains('two_factor_recovery_codes', $columns);
        $this->assertContains('two_factor_confirmed_at', $columns);
    }

    public function test_two_factor_secret_is_hidden_from_serialization()
    {
        $user = $this->createAdminUser();
        $user->forceFill(['two_factor_secret' => encrypt('DUMMYSECRET')])->save();

        $array = $user->fresh()->toArray();

        $this->assertArrayNotHasKey('two_factor_secret', $array);
        $this->assertArrayNotHasKey('two_factor_recovery_codes', $array);
    }

    // ========================================================================
    // Page & routing
    // ========================================================================

    public function test_two_factor_page_requires_authentication()
    {
        $this->get('/account/two-factor')->assertRedirect('/users/login');
    }

    public function test_two_factor_page_renders_for_authenticated_user()
    {
        $user = $this->createAdminUser();

        $this->actingAs($user)
            ->get('/account/two-factor')
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('Profile/TwoFactor'));
    }

    public function test_inertia_shares_two_factor_enabled_status()
    {
        $user = $this->createAdminUser();

        $this->actingAs($user)
            ->get('/account/two-factor')
            ->assertInertia(fn ($page) => $page
                ->where('auth.user.two_factor_enabled', false)
            );
    }

    // ========================================================================
    // Enable / confirm / disable flow (Fortify endpoints)
    // ========================================================================

    public function test_user_can_enable_two_factor_authentication()
    {
        $user = $this->createAdminUser();

        $this->actingAs($user)
            ->post('/user/two-factor-authentication')
            ->assertRedirect();

        $user->refresh();
        $this->assertNotNull($user->two_factor_secret);
        $this->assertNotNull($user->two_factor_recovery_codes);
        // Not yet confirmed
        $this->assertNull($user->two_factor_confirmed_at);
    }

    public function test_user_can_retrieve_qr_code_after_enabling()
    {
        $user = $this->createAdminUser();

        $this->actingAs($user)->post('/user/two-factor-authentication');

        $this->actingAs($user)
            ->getJson('/user/two-factor-qr-code')
            ->assertOk()
            ->assertJsonStructure(['svg']);
    }

    public function test_user_can_retrieve_secret_key_after_enabling()
    {
        $user = $this->createAdminUser();

        $this->actingAs($user)->post('/user/two-factor-authentication');

        $this->actingAs($user)
            ->getJson('/user/two-factor-secret-key')
            ->assertOk()
            ->assertJsonStructure(['secretKey']);
    }

    public function test_user_can_retrieve_recovery_codes_after_enabling()
    {
        $user = $this->createAdminUser();

        $this->actingAs($user)->post('/user/two-factor-authentication');

        $response = $this->actingAs($user)
            ->getJson('/user/two-factor-recovery-codes')
            ->assertOk();

        $this->assertCount(8, $response->json());
    }

    public function test_confirmation_fails_with_invalid_code()
    {
        $user = $this->createAdminUser();

        $this->actingAs($user)->post('/user/two-factor-authentication');

        $this->actingAs($user)
            ->postJson('/user/confirmed-two-factor-authentication', [
                'code' => '000000',
            ])
            ->assertUnprocessable();

        $this->assertNull($user->fresh()->two_factor_confirmed_at);
    }

    public function test_user_can_confirm_two_factor_with_valid_code()
    {
        $user = $this->createAdminUser();

        $this->actingAs($user)->post('/user/two-factor-authentication');
        $user->refresh();

        $validCode = app(\PragmaRX\Google2FA\Google2FA::class)
            ->getCurrentOtp(decrypt($user->two_factor_secret));

        $this->actingAs($user)
            ->postJson('/user/confirmed-two-factor-authentication', [
                'code' => $validCode,
            ])
            ->assertOk();

        $this->assertNotNull($user->fresh()->two_factor_confirmed_at);
    }

    public function test_user_can_regenerate_recovery_codes()
    {
        $user = $this->createAdminUser();

        $this->actingAs($user)->post('/user/two-factor-authentication');
        $original = $user->fresh()->two_factor_recovery_codes;

        $this->actingAs($user)
            ->post('/user/two-factor-recovery-codes')
            ->assertRedirect();

        $this->assertNotEquals($original, $user->fresh()->two_factor_recovery_codes);
    }

    public function test_user_can_disable_two_factor_authentication()
    {
        $user = $this->createAdminUser();

        $this->actingAs($user)->post('/user/two-factor-authentication');
        $this->assertNotNull($user->fresh()->two_factor_secret);

        $this->actingAs($user)
            ->delete('/user/two-factor-authentication')
            ->assertRedirect();

        $user->refresh();
        $this->assertNull($user->two_factor_secret);
        $this->assertNull($user->two_factor_recovery_codes);
        $this->assertNull($user->two_factor_confirmed_at);
    }

    public function test_inertia_reflects_enabled_status_after_confirmation()
    {
        $user = $this->createAdminUser();
        $user->forceFill([
            'two_factor_secret' => encrypt('DUMMYSECRET'),
            'two_factor_recovery_codes' => encrypt(json_encode(['code1', 'code2'])),
            'two_factor_confirmed_at' => now(),
        ])->save();

        $this->actingAs($user)
            ->get('/account/two-factor')
            ->assertInertia(fn ($page) => $page
                ->where('auth.user.two_factor_enabled', true)
            );
    }
}
