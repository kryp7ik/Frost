<?php

namespace Tests\Feature;

use App\Models\Auth\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Tests targeting breaking changes in the Laravel 11 → 12 upgrade per
 * https://laravel.com/docs/12.x/upgrade plus community-reported gotchas.
 */
class Laravel12UpgradeTest extends TestCase
{
    use RefreshDatabase;

    // ========================================================================
    // L12 High: Carbon 3 required (Carbon 2 support dropped)
    // ========================================================================

    public function test_carbon_3_is_installed()
    {
        $version = \Composer\InstalledVersions::getVersion('nesbot/carbon');
        $this->assertTrue(
            version_compare($version, '3.0.0', '>='),
            'Laravel 12 requires Carbon 3.x, got '.$version
        );
    }

    public function test_carbon_3_diff_returns_float_and_signed()
    {
        $past = Carbon::parse('2025-01-01');
        $future = Carbon::parse('2025-01-06');

        // Carbon 3: diffInDays returns float, can be negative
        $diff = $past->diffInDays($future);
        $this->assertIsFloat($diff);
        $this->assertEquals(5.0, $diff);

        // Reverse direction returns negative in Carbon 3
        $reverse = $future->diffInDays($past);
        $this->assertEquals(-5.0, $reverse);
    }

    // ========================================================================
    // L12 Low: Schema::getTables() multi-schema behaviour
    // ========================================================================

    public function test_schema_get_tables_accepts_schema_filter()
    {
        // L12: method now accepts schema parameter to filter results
        $tables = Schema::getTables(schema: 'main');
        $this->assertIsArray($tables);

        $names = array_column($tables, 'name');
        $this->assertContains('users', $names);
    }

    public function test_schema_get_table_listing_supports_qualification_flag()
    {
        // L12: new schemaQualified param
        $unqualified = Schema::getTableListing(schemaQualified: false);
        $this->assertContains('users', $unqualified);
        $this->assertContains('customers', $unqualified);
    }

    // ========================================================================
    // L12 Low: Local disk default root changed to storage/app/private
    // Our config/filesystems.php explicitly pins storage_path('app')
    // ========================================================================

    public function test_local_disk_root_remains_storage_app()
    {
        $config = config('filesystems.disks.local.root');
        $this->assertEquals(storage_path('app'), $config);
    }

    public function test_storage_fake_still_works_for_local_disk()
    {
        Storage::fake('local');
        Storage::disk('local')->put('l12.txt', 'content');
        Storage::disk('local')->assertExists('l12.txt');
    }

    // ========================================================================
    // L12 Low: Container respects nullable default parameter values
    // ========================================================================

    public function test_container_respects_nullable_defaults()
    {
        // L12: container no longer auto-resolves nullable params with defaults
        $instance = app()->make(NullableDefaultExample::class);
        $this->assertNull($instance->user);
    }

    // ========================================================================
    // L12 Medium: Str::uuid() / HasUuids now generate UUIDv7 (ordered)
    // ========================================================================

    public function test_has_uuids_trait_generates_v7()
    {
        // L12: HasUuids trait now uses newUniqueId() → Str::uuid7()
        $model = new class extends \Illuminate\Database\Eloquent\Model {
            use \Illuminate\Database\Eloquent\Concerns\HasUuids;
        };

        $uuid = $model->newUniqueId();
        // UUIDv7: version nibble (char at index 14) must be '7'
        $this->assertEquals('7', $uuid[14]);
    }

    public function test_has_version4_uuids_trait_available_for_backcompat()
    {
        $this->assertTrue(
            trait_exists(\Illuminate\Database\Eloquent\Concerns\HasVersion4Uuids::class)
        );
    }

    // ========================================================================
    // L12: Image validation excludes SVG by default
    // ========================================================================

    public function test_image_rule_rejects_svg_by_default()
    {
        $validator = validator(
            ['photo' => \Illuminate\Http\UploadedFile::fake()->create('x.svg', 1, 'image/svg+xml')],
            ['photo' => 'image']
        );
        $this->assertTrue($validator->fails());
    }

    public function test_image_rule_accepts_svg_with_allow_svg_flag()
    {
        $validator = validator(
            ['photo' => \Illuminate\Http\UploadedFile::fake()->create('x.svg', 1, 'image/svg+xml')],
            ['photo' => 'image:allow_svg']
        );
        $this->assertFalse($validator->fails());
    }

    // ========================================================================
    // L12: PHPUnit 11 compatibility
    // ========================================================================

    public function test_phpunit_11_is_installed()
    {
        $this->assertTrue(
            version_compare(\PHPUnit\Runner\Version::id(), '11.0.0', '>=')
        );
    }

    // ========================================================================
    // L12: CreatesApplication trait no longer required
    // ========================================================================

    public function test_base_testcase_handles_app_creation_natively()
    {
        // The trait was removed from our TestCase; app still boots
        $this->assertFalse(
            in_array(
                'Tests\CreatesApplication',
                class_uses_recursive(static::class)
            )
        );
        $this->assertInstanceOf(
            \Illuminate\Foundation\Application::class,
            $this->app
        );
    }

    // ========================================================================
    // Undocumented: Request::mergeIfMissing supports dot notation in L12
    // ========================================================================

    public function test_merge_if_missing_supports_dot_notation()
    {
        $request = \Illuminate\Http\Request::create('/', 'GET');
        $request->mergeIfMissing(['user.name' => 'Jane']);

        $this->assertEquals('Jane', $request->input('user.name'));
    }

    // ========================================================================
    // Framework version assertion
    // ========================================================================

    public function test_laravel_version_is_12x()
    {
        $this->assertStringStartsWith('12.', app()->version());
    }
}

/**
 * Helper class for container nullable-default resolution test.
 */
class NullableDefaultExample
{
    public function __construct(public ?User $user = null) {}
}
