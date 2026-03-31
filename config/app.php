<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    */
    'name' => env('APP_NAME', 'BASMAN'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    */
    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    */
    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    */
    'url' => env('APP_URL', 'http://localhost'),

    'asset_url' => env('ASSET_URL'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    */
    'timezone' => 'Asia/Jakarta',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    */
    'locale' => 'id',

    'fallback_locale' => 'en',

    'faker_locale' => 'id_ID',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    */
    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode
    |--------------------------------------------------------------------------
    */
    'maintenance' => [
        'driver' => 'file',
    ],

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers (Laravel 12)
    |--------------------------------------------------------------------------
    |
    | Laravel 11+ TIDAK lagi memakai:
    | - EventServiceProvider
    | - BroadcastServiceProvider
    | - RouteServiceProvider
    |
    */
    'providers' => ServiceProvider::defaultProviders()->merge([

        /*
         * Application Service Providers
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,

        /*
         * Package Service Providers (aktifkan jika sudah install)
         */
        // Barryvdh\DomPDF\ServiceProvider::class,
        // Maatwebsite\Excel\ExcelServiceProvider::class,

    ])->toArray(),

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    */
    'aliases' => Facade::defaultAliases()->merge([
        // 'PDF' => Barryvdh\DomPDF\Facade\Pdf::class,
        // 'Excel' => Maatwebsite\Excel\Facades\Excel::class,
    ])->toArray(),

];
