<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        /*
         * Docs: https://laravel.com/docs/filesystem#temporary-urls
         * Pre-Reference: https://laravel.com/docs/urls#signed-urls
         * Temporary routes for `public` storage disk
         * Needs `download.temp` route defined
         */
        Storage::disk('public')->buildTemporaryUrlsUsing(function ($path, $expiration, $options) {
            return URL::temporarySignedRoute(
                'download.temp',
                $expiration,
                array_merge($options, ['path' => $path])
            );
        });
    }
}
