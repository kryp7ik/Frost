<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MessengerServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(
            'messenger.messenger',
            'App\Http\ViewComposers\MessengerComposer'
        );
    }

    /**
     * Register the application repositories.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('raven', function () {
            return $this->app->make('App\Services\MessengerService');
        });
    }
}
