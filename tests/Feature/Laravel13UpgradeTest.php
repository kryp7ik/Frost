<?php

namespace Tests\Feature;

use App\Models\Auth\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Tests targeting breaking changes in the Laravel 12 → 13 upgrade per
 * https://laravel.com/docs/13.x/upgrade plus community-reported gotchas.
 */
class Laravel13UpgradeTest extends TestCase
{
    use RefreshDatabase;

    // ========================================================================
    // L13 High: VerifyCsrfToken → PreventRequestForgery rename
    // ========================================================================

    public function test_prevent_request_forgery_middleware_class_exists()
    {
        $this->assertTrue(
            class_exists(\Illuminate\Foundation\Http\Middleware\PreventRequestForgery::class)
        );
    }

    public function test_app_middleware_extends_prevent_request_forgery()
    {
        $this->assertTrue(
            is_subclass_of(
                \App\Http\Middleware\PreventRequestForgery::class,
                \Illuminate\Foundation\Http\Middleware\PreventRequestForgery::class
            )
        );
    }

    public function test_prevent_request_forgery_registered_in_web_group()
    {
        $kernel = app(\Illuminate\Contracts\Http\Kernel::class);
        $groups = $kernel->getMiddlewareGroups();

        $this->assertContains(
            \App\Http\Middleware\PreventRequestForgery::class,
            $groups['web']
        );
    }

    public function test_deprecated_verify_csrf_token_alias_still_available()
    {
        // L13 keeps VerifyCsrfToken as a deprecated alias for backward compat
        $this->assertTrue(
            class_exists(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class)
        );
    }

    // ========================================================================
    // L13 Medium: Cache serializable_classes security hardening
    // ========================================================================

    public function test_cache_serializable_classes_configured()
    {
        // L13 default: false prevents arbitrary object deserialization
        $this->assertFalse(config('cache.serializable_classes'));
    }

    public function test_cache_scalar_values_still_work()
    {
        Cache::put('l13-scalar', 'string value', 60);
        $this->assertEquals('string value', Cache::get('l13-scalar'));

        Cache::put('l13-array', ['a' => 1, 'b' => 2], 60);
        $this->assertEquals(['a' => 1, 'b' => 2], Cache::get('l13-array'));
    }

    // ========================================================================
    // L13 Low: Container::call() respects nullable defaults
    // (L12 changed Container::make(), L13 extends to Container::call())
    // ========================================================================

    public function test_container_call_respects_nullable_defaults()
    {
        $result = app()->call(function (?User $user = null) {
            return $user;
        });

        // L12 would auto-resolve and inject a User; L13 respects the null default
        $this->assertNull($result);
    }

    // ========================================================================
    // L13 Low: Cache Store contract requires touch() method
    // ========================================================================

    public function test_cache_store_contract_declares_touch()
    {
        $this->assertTrue(
            method_exists(\Illuminate\Contracts\Cache\Store::class, 'touch')
        );
    }

    public function test_framework_cache_stores_implement_touch()
    {
        $store = Cache::store('array')->getStore();
        $this->assertTrue(method_exists($store, 'touch'));
    }

    // ========================================================================
    // L13 Low: Str factories reset between tests
    // ========================================================================

    public function test_str_factories_reset_between_tests_part_one()
    {
        Str::createUuidsUsing(fn () => 'fixed-uuid-value-xxxx-xxxxxxxxxxxx');
        $this->assertEquals('fixed-uuid-value-xxxx-xxxxxxxxxxxx', (string) Str::uuid());
    }

    public function test_str_factories_reset_between_tests_part_two()
    {
        // L13: the custom factory from the previous test should NOT leak here
        $uuid = (string) Str::uuid();
        $this->assertNotEquals('fixed-uuid-value-xxxx-xxxxxxxxxxxx', $uuid);
        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/',
            $uuid
        );
    }

    // ========================================================================
    // L13 Low: Js::from() uses JSON_UNESCAPED_UNICODE by default
    // ========================================================================

    public function test_js_from_uses_unescaped_unicode()
    {
        $output = (string) \Illuminate\Support\Js::from(['name' => 'café']);

        $this->assertStringContainsString('café', $output);
        $this->assertStringNotContainsString('\u00e9', $output);
    }

    // ========================================================================
    // L13 Low: Cache/session prefix immune (we hardcode values)
    // ========================================================================

    public function test_cache_prefix_hardcoded_immune_to_slug_change()
    {
        $this->assertEquals('laravel', config('cache.prefix'));
    }

    public function test_session_cookie_hardcoded_immune_to_slug_change()
    {
        $this->assertEquals('laravel_session', config('session.cookie'));
    }

    // ========================================================================
    // L13 Contract: MustVerifyEmail gains markEmailAsUnverified()
    // ========================================================================

    public function test_must_verify_email_contract_declares_mark_unverified()
    {
        $this->assertTrue(
            method_exists(
                \Illuminate\Contracts\Auth\MustVerifyEmail::class,
                'markEmailAsUnverified'
            )
        );
    }

    // ========================================================================
    // L13 Contract: Dispatcher gains dispatchAfterResponse()
    // ========================================================================

    public function test_dispatcher_contract_declares_dispatch_after_response()
    {
        $this->assertTrue(
            method_exists(
                \Illuminate\Contracts\Bus\Dispatcher::class,
                'dispatchAfterResponse'
            )
        );
    }

    // ========================================================================
    // L13: PHPUnit 12 compatibility
    // ========================================================================

    public function test_phpunit_12_is_installed()
    {
        $this->assertTrue(
            version_compare(\PHPUnit\Runner\Version::id(), '12.0.0', '>=')
        );
    }

    // ========================================================================
    // Framework version assertion
    // ========================================================================

    public function test_laravel_version_is_13x()
    {
        $this->assertStringStartsWith('13.', app()->version());
    }
}
