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
        $services = settingsApi::get(['appType', 'appID', 'appSecret', 'apiKey']);

        if (!empty($services)) 
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
    
            $TwitterConfig = [
                'client_id' => null, // TWITTER_CONSUMER_KEY
                'client_secret' => null, // TWITTER_CONSUMER_SECRET
                'redirect' => 'https://social.evolvetechsys.com/auth/twitter/callback',
            ];
    
            $YoutubeConfig = [
                'client_id' => null, // GOOGLE_CLIENT_ID
                'client_secret' => null, // GOOGLE_CLIENT_SECRET
                'api_key' => null, // YOUTUBE_API_KEY
                'redirect' => 'https://social.evolvetechsys.com/auth/youtube/callback',
            ];

            foreach ($services as $service) {
                switch ($service['appType']) {
                    case 'facebook':
                        $FacebookConfig['client_id'] = $service->appID;
                        $FacebookConfig['client_secret'] = $service->appSecret;
                        config(['services.facebook' => $FacebookConfig]);
                        break;

                    case 'twitter':
                        $TwitterConfig['client_id'] = $service->appID;
                        $TwitterConfig['client_secret'] = $service->appSecret;
                        config(['services.twitter' => $TwitterConfig]);
                        break;

                    case 'instagram':
                        $InstagramConfig['client_id'] = $service->appID;
                        $InstagramConfig['client_secret'] = $service->appSecret;
                        config(['services.instagram' => $InstagramConfig]);
                        break;

                    case 'youtube':
                        $YoutubeConfig['client_id'] = $service->appID;
                        $YoutubeConfig['client_secret'] = $service->appSecret;
                        $YoutubeConfig['api_key'] = $service->apiKey;
                        config(['services.youtube' => $YoutubeConfig]);
                        break;
                }
            }
        }
    }
}
