<?php 

namespace App\Services;

use App\Models\settingsApi;

class Configurations 
{
    public function service($serviceName)
    {
        $services = settingsApi::all();

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
            // INSTAGRAM_CLIENT_ID= 961436048312260
            // INSTAGRAM_CLIENT_SECRET= a43ca3ea9793b904157e2092a8b34677

            $TwitterConfig = [
                'client_id' => null, // TWITTER_CONSUMER_KEY
                'client_secret' => null, // TWITTER_CONSUMER_SECRET 
                // 'redirect' => 'http://192.168.1.15:8000/auth/twitter/callback',
                'redirect' => 'https://social.evolvetechsys.com/auth/twitter/callback',
            ];

            $YoutubeConfig = [
                'client_id' => null, // GOOGLE_CLIENT_ID
                'client_secret' => null, // GOOGLE_CLIENT_SECRET
                'api_key' => null, // YOUTUBE_API_KEY
                // 'redirect' => 'http://localhost/e-commerce/Social/schedual-posts/auth/youtube/callback',
                'redirect' => 'https://social.evolvetechsys.com/auth/youtube/callback',
            ];

            foreach ($services as $service) {
                switch ($service->appType) {
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

        return config('services.' . $serviceName);
    }
}