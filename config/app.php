<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [

    'name' => env('APP_NAME', 'Frost'),

    'env' => env('APP_ENV', 'production'),

    'debug' => (bool) env('APP_DEBUG', false),

    'url' => env('APP_URL', 'http://frost.com'),

    'asset_url' => env('ASSET_URL'),

    'timezone' => 'America/New_York',

    'locale' => 'en',

    'fallback_locale' => 'en',

    'faker_locale' => 'en_US',

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    'maintenance' => [
        'driver' => 'file',
    ],

    'providers' => ServiceProvider::defaultProviders()->merge([

        /*
         * Package Service Providers...
         */
        Laracasts\Flash\FlashServiceProvider::class,
        Barryvdh\Snappy\ServiceProvider::class,
        Yajra\DataTables\DataTablesServiceProvider::class,

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\FortifyServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        App\Providers\RepositoryServiceProvider::class,

    ])->toArray(),

    'aliases' => Facade::defaultAliases()->merge([
        'PDF' => Barryvdh\Snappy\Facades\SnappyPdf::class,
        'SnappyImage' => Barryvdh\Snappy\Facades\SnappyImage::class,
        'DateHelper' => App\Helpers\DateHelper::class,
        'DataTables' => Yajra\DataTables\Facades\DataTables::class,
    ])->toArray(),

];
