<?php

namespace App\Http\Controllers\api;

use Google_Client;
use App\Models\Api;
use App\Models\settingsApi;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class YoutubeController extends Controller
{
    public function redirectToYoutube()
    {
        $youtubeSettings = settingsApi::where('appType', 'youtube')->first(); 
        $client_id = $youtubeSettings['appID'];
        $client_secret = $youtubeSettings['appSecret'];
        
        $client = new Google_Client();
        $client->setClientId($client_id);
        $client->setClientSecret($client_secret);
        // $client->setAuthConfig(Config::get('services.youtube'));
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');
        $client->setScopes([
            'https://www.googleapis.com/auth/youtube.force-ssl',
        ]);
    
        // Set the redirect URI
        $client->setRedirectUri(route('youtube.callback'));
    
        $authUrl = $client->createAuthUrl();
        return redirect($authUrl);
    }

    public function YoutubeCallback(Request $request)
    {       
        $youtubeSettings = settingsApi::where('appType', 'youtube')->first(); 
        $client_id = $youtubeSettings['appID'];
        $client_secret = $youtubeSettings['appSecret'];

        try 
        {
            $client = new Google_Client();
            $client->setApplicationName('schedual-posts');
            $client->setScopes([
                'https://www.googleapis.com/auth/youtube.readonly',
                'https://www.googleapis.com/auth/youtube.force-ssl',
            ]);

            $client->setClientId($client_id);
            $client->setClientSecret($client_secret);
            $client->setAccessType('offline');

            $client->setRedirectUri(route('youtube.callback')); // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            
            if ($request->has('code')) {
                // Exchange verification code for an access token.
                $authCode = $request->input('code');
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                if (isset($accessToken['access_token'])) 
                {
                    $access_token = $accessToken['access_token'];
                    // $refresh_token = $accessToken['refresh_token'];
                    $refresh_token = isset($accessToken['refresh_token']) ? $accessToken['refresh_token'] : null;
                    
                    $url = "https://www.googleapis.com/youtube/v3/channels?access_token=$access_token&part=snippet&mine=true";
                    
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec($ch);
                    // dd($response);
                    if (curl_errno($ch)) {
                        $error = curl_error($ch);
                        curl_close($ch);

                        return response()->json(['error' => $error], 500);
                    }
                    curl_close($ch);

                    $data = json_decode($response, true);
                    if (isset($data['items']) && !empty($data['items'])) {
                    // if (isset($data['items'][0])) {
                        $channelId = $data['items'][0]['id'];
                        $channelName = $data['items'][0]['snippet']['title'];
                        $channelImageUrl = $data['items'][0]['snippet']['thumbnails']['default']['url'];
                        $channelLink = "https://www.youtube.com/channel/{$channelId}";

                        $userFolder = 'user'.Auth::user()->id;
                        $profileImage = $channelImageUrl;
                        $ext = pathinfo($profileImage, PATHINFO_EXTENSION);
                        $filename = time() . '.' . $ext;
                        Storage::put('public/'.$userFolder.'/'.'profile_images/'. $filename, file_get_contents($profileImage));
                        $storageImage = url('storage/'.$userFolder.'/'.'profile_images/'. $filename);

                        $userData = [
                            'creator_id'=> Auth::user()->id,
                            'account_type' => 'youtube',
                            'account_id' => $channelId,
                            'account_name' => $channelName,
                            'email' => $channelName,
                            'account_pic' => $storageImage,
                            'account_link' => $channelLink,
                            'token' => $access_token,
                            'token_secret' => $refresh_token
                        ];
            
                        $existingApp = Api::where('account_id',$channelId)->where('creator_id', Auth::user()->id)->first();
            
                        if ($existingApp) {
                            if($existingApp->account_pic != null){
                                $rm_urlPath = parse_url($existingApp->account_pic, PHP_URL_PATH);
                                $path = Str::replace('/storage/', '', $rm_urlPath);
                                $filePath = storage_path('app/public/'. $path);
                                if (file_exists($filePath)) {
                                    unlink($filePath);
                                }
                            }
                            
                            $existingApp->update($userData);
                        } else {
                            Api::create($userData);
                        }

                        return response()->json([
                            'message' => 'Account saved',
                            'data' => $userData,
                            'status' => true
                        ],200);

                    } else {
                        return response()->json([
                            'message' => 'No YouTube channel found for this account.',
                            'status' => false
                        ],404);
                    }
                    
                } else {
                   return response()->json([
                        'error' => 'error to access token',
                        'status' => false
                    ],400);
                }
            }
            else
            {
                return response()->json([
                    'message' => 'Error to get code',
                    'status' => false
                ],500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => false
            ],500);
        }  
    } 

    public function show(string $channelId)
    {
        $api_id = Api::where('account_id',$channelId)->where('creator_id', Auth::user()->id)->first();
        $youtubePosts = Api::find($api_id['id'])->social_posts()->get();
        
        return response()->json([
            'message' => 'Channel found',
            'data' => $youtubePosts,
            'status' => true
        ],200);
    }
}
