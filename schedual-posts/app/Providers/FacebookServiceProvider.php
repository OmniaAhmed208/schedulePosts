<?php

namespace App\Providers;

use App\Models\settingsApi;
use Illuminate\Support\ServiceProvider;

class FacebookServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->booted(function () {
            // This code will execute after the application has fully booted.
            $facebookConfig = [
                'client_id' => null,
                'client_secret' => null,
                'redirect' => 'https://social.evolvetechsys.com/auth/callback',
            ];

            if (app()->runningInConsole() === false) {
                // Check if the app is not running in console (to avoid issues during migrations)
                $facebookConfig['client_id'] = settingsApi::where('appType', 'facebook')->value('appID');
                $facebookConfig['client_secret'] = settingsApi::where('appType', 'facebook')->value('appSecret');
            }

            config(['services.facebook' => $facebookConfig]);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
