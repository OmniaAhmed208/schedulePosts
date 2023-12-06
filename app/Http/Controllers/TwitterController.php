<?php

namespace App\Http\Controllers;

use App\Models\Api;
use App\Models\settingsApi;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;
use Abraham\TwitterOAuth\TwitterOAuthException;

class TwitterController extends Controller
{
    public function twitterRedirect()
    {
        return Socialite::driver('twitter')->redirect();
    }

    public function twitterCallback(Request $request)
    {
       try{
            $user = Socialite::driver('twitter')->user();

            $profileImage = $user->avatar;
            $ext = pathinfo($profileImage, PATHINFO_EXTENSION);
            $filename = time() . '.' . $ext;
            $userFolder = 'user'.Auth::user()->id;
            Storage::put('public/'.$userFolder.'/'.'profile_images/'. $filename, file_get_contents($profileImage));
            $storageImage = url('storage/'.$userFolder.'/'.'profile_images/'. $filename);

            $userData = [
                'creator_id'=> Auth::user()->id,
                'account_type' => 'twitter',
                'account_id' => $user->id,
                'account_name' => $user->name,
                'email' => $user->email,
                'account_pic' => $storageImage,
                'account_link' => 'https://twitter.com/' . $user->nickname,
                'token' => $user->token,
                'token_secret' => $user->tokenSecret 
            ];


            $existingApp = Api::where('account_id', $user->id)->where('creator_id', Auth::user()->id)->first();

            if ($existingApp) 
            {
                if($existingApp->account_pic != null){
                    $rm_urlPath = parse_url($existingApp->account_pic, PHP_URL_PATH);
                    $path = Str::replace('/storage/', '', $rm_urlPath);
                    unlink(storage_path('app/public/'. $path));
                }
                
                $existingApp->update($userData);

            } else {
                Api::create($userData);
            }

            // $this->getTweets($user);

            return redirect()->route('users.show', ['user' => Auth::user()->id]);
            // return $this->postMessageToTwitter($oauth_token, $oauth_token_secret);
        } catch (TwitterOAuthException $e) {
            return redirect()->route('users.show', ['user' => Auth::user()->id]);
        }
        
    }

    public function show($twitterId)
    {
        $userData = Api::where('creator_id', Auth::user()->id)->where('account_id', $twitterId)->first();
    
        $oauthToken = 'NXZ4eUhfc256TWl3bi1meXE4UEY6MTpjaQ';
        $oauthTokenSecret = '9pOyPxmxHCz_WSRrgjuLj5e5zj4Ix5sDsHOyLr16m_cCNX9Cp7';

        $apiEndpoint = 'https://api.twitter.com/2/tweets/user_timeline';
    
        $queryParameters = [
            'max_results' => 10, // You can adjust the number of tweets to retrieve
        ];
    
        // Make the authenticated API request to retrieve the user's tweets
        $response = Http::withToken($oauthToken, $oauthTokenSecret)
            ->get($apiEndpoint, $queryParameters);
    
            dd($response);
        // Check if the request was successful
        if ($response->successful()) {
            $tweets = $response->json();

            return view('tweets', compact('tweets'));
        } else {
            // Handle the case where the request to Twitter's API failed
            return redirect()->route('socialAccounts.index');
        }
    }

    public function twitterCallback2(Request $request)
    {
        $response = $request->all();
        
        // Load Twitter API settings from your database
        $twitterSettings = settingsApi::where('appType', 'twitter')->first(); 
        $apiKey = $twitterSettings['appID'];
        $apiSecret = $twitterSettings['appSecret'];
        
        $oauth_token = $response['oauth_token'];
        $oauth_verifier = $response['oauth_verifier'];
        
        $twitter_connect = new TwitterOAuth($apiKey, $apiSecret, $oauth_token, $oauth_verifier);
        
        // Verify user token and get access token and secret
        $token = $twitter_connect->oauth('oauth/access_token', ['oauth_verifier' => $oauth_verifier]);
        
        $oauth_token = $token['oauth_token']; // access token
        $oauth_token_secret = $token['oauth_token_secret']; // token secret
        
        // Use the obtained access token and token secret to fetch user data
        $twitter = new TwitterOAuth($apiKey, $apiSecret, $oauth_token, $oauth_token_secret);
        $userData = $twitter->get('account/verify_credentials', ['include_entities' => 'false', 'skip_status' => 'true']);
        
        // Now $userData contains user information, including name, profile picture, and ID
        $userName = $userData->name;
        $userProfilePicture = $userData->profile_image_url_https; // Use a secure URL
        $userId = $userData->id;
        
        // You can store or use this user data as needed
        
        // Redirect to the desired route or view
        return redirect()->route('socialAccounts');
        // Alternatively, you can return the user data if needed
        // return view('profile', compact('userName', 'userProfilePicture', 'userId'));
    }

    public function postMessageToTwitter($oauth_token, $oauth_token_secret)
    {
        $twitterSettings = settingsApi::where('appType', 'twitter')->first(); 
        $consumer_key = $twitterSettings['appID'];;
        $consumer_secret = $twitterSettings['appSecret'];
    
        $connection = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);
        $connection->setApiVersion('2');
    
        $tweet = 'kkkk';
    
        $response = $connection->post('tweets', ['text' => $tweet]);

        return redirect()->route('socialAccounts');
    }
}