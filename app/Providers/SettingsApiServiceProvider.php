<?php

namespace App\Providers;

use App\Models\settingsApi;
use Illuminate\Support\ServiceProvider;

class SettingsApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {        
        $FacebookConfig = [
            'client_id' => null,
            'client_secret' => null,
            'redirect' => 'https://social.evolvetechsys.com/auth/callback',
        ];

        $InstagramConfig = [
            'client_id' => null,
            'client_secret' => null,
            'redirect' => 'https://www.google.com.eg/instagram/public/login/instagram/callback',
        ];

        // INSTAGRAM_CLIENT_ID= 961436048312260
        // INSTAGRAM_CLIENT_SECRET= a43ca3ea9793b904157e2092a8b34677

        $TwitterConfig = [
            'client_id' => null, // TWITTER_CONSUMER_KEY
            'client_secret' => null, // TWITTER_CONSUMER_SECRET
            'redirect' => 'https://social.evolvetechsys.com/auth/twitter/callback',
        ];

        $YoutubeConfig = [
            'client_id' => null, // GOOGLE_CLIENT_ID
            'client_secret' => null, // GOOGLE_CLIENT_SECRET
            'api_key' => null, // YOUTUBE_API_KEY
            'redirect' => 'http://localhost/e-commerce/Social/schedual-posts/auth/youtube/callback',
        ];

        $services = settingsApi::all();

        if (!empty($services)) {
            // Loop through each service
            foreach ($services as $service) {
                if (isset($service['appType']) && $service['appType'] === 'twitter') 
                {
                    $TwitterConfig['client_id'] = settingsApi::where('appType', 'twitter')->value('appID');
                    $TwitterConfig['client_secret'] = settingsApi::where('appType', 'twitter')->value('appSecret');
            
                    config(['services.twitter' => $TwitterConfig]);
                }

                if (isset($service['appType']) && $service['appType'] === 'facebook') 
                {
                    $FacebookConfig['client_id'] = settingsApi::where('appType', 'facebook')->value('appID');
                    $FacebookConfig['client_secret'] = settingsApi::where('appType', 'facebook')->value('appSecret');
            
                    config(['services.facebook' => $FacebookConfig]);
                }

                if (isset($service['appType']) && $service['appType'] === 'instagram') 
                {
                    $InstagramConfig['client_id'] = settingsApi::where('appType', 'instagram')->value('appID');
                    $InstagramConfig['client_secret'] = settingsApi::where('appType', 'instagram')->value('appSecret');
            
                    config(['services.instagram' => $InstagramConfig]);
                }

                if (isset($service['appType']) && $service['appType'] === 'youtube') 
                {
                    $YoutubeConfig['client_id'] = settingsApi::where('appType', 'youtube')->value('appID');
                    $YoutubeConfig['client_secret'] = settingsApi::where('appType', 'youtube')->value('appSecret');
                    $YoutubeConfig['api_key'] = settingsApi::where('appType', 'youtube')->value('apiKey');
            
                    config(['services.youtube' => $YoutubeConfig]);
                }
            }
        }
    }
}
