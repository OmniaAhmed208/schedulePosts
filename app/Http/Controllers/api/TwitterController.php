<?php

namespace App\Http\Controllers\api;

use App\Models\Api;
use App\Models\settingsApi;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\Configurations;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;
use Abraham\TwitterOAuth\TwitterOAuthException;

class TwitterController extends Controller
{
    protected $configApp;

    public function __construct(Configurations $app)
    {
        $this->configApp = $app;
    }
    
    public function twitterRedirect()
    {
        $this->configApp->service('twitter');
        return Socialite::driver('twitter')->redirect();
    }

    public function twitterCallback(Request $request)
    {
        $this->configApp->service('twitter');

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
                    $filePath = storage_path('app/public/'. $path);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
                
                $existingApp->update($userData);

            } else {
                Api::create($userData);
            }

            // $this->getTweets($user);
            return response()->json([
                'message' => 'Account saved',
                'user' => $userData,
                'status' => true
            ],200);

            // return $this->postMessageToTwitter($oauth_token, $oauth_token_secret);
        } catch (TwitterOAuthException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => false
            ],500);
        }
        
    }
    
    public function twitterRedirect2()
    {
        $callback = route('twitter.callback');
        $twitterSettings = settingsApi::where('appType', 'twitter')->first();
        $apiKey = $twitterSettings['appID'];
        $apiSecret = $twitterSettings['appSecret'];
        $twitter_connect = new TwitterOAuth($apiKey, $apiSecret);
        $access_token = $twitter_connect->oauth('oauth/request_token',['oauth_callback'=>$callback]);
        $route = $twitter_connect->url('oauth/authorize',['oauth_token'=>$access_token['oauth_token']]);
        return redirect($route);
    }

    public function twitterCallback2(Request $request)
    {
        $response = $request->all();

        $twitterSettings = settingsApi::where('appType', 'twitter')->first();
        $apiKey = $twitterSettings['appID'];
        $apiSecret = $twitterSettings['appSecret'];

        $oauth_token = $response['oauth_token'];
        $oauth_verifier = $response['oauth_verifier'];

        $twitter_connect = new TwitterOAuth($apiKey, $apiSecret, $oauth_token, $oauth_verifier);

        try {
           // Verify user token and get access token and secret
            $token = $twitter_connect->oauth('oauth/access_token',['oauth_verifier'=>$oauth_verifier]);

            $oauth_token = $token['oauth_token']; // access token
            $oauth_token_secret = $token['oauth_token_secret']; // token secret

            $userName = $token['screen_name'];
            $userId = $token['user_id'];
            $profileLink = "https://twitter.com/intent/user?user_id={$userId}";

            $twitter = new TwitterOAuth($apiKey, $apiSecret, $oauth_token, $oauth_token_secret);

            $userData = [
                'creator_id'=> Auth::user()->id,
                'account_type' => 'twitter',
                'account_id' => $userId,
                'account_name' => $userName,
                'email' => $userName,
                // 'account_pic' => $profileImage,
                'account_link' => $profileLink,
                'token' => $oauth_token,
                'token_secret' => $oauth_token_secret ? $oauth_token_secret : ''
            ];


            $existingApp = Api::where('account_id', $userId)->where('creator_id', Auth::user()->id)->first();

            if ($existingApp) {
                $existingApp->update($userData);
            } else {
                Api::create($userData);
            }

            return response()->json([
                'message' => 'Account saved',
                'data' => $userData,
                'status' => true
            ],200);

        } catch (TwitterOAuthException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => true
            ],500);
        }

    }

    public function show(string $twitterId)
    {
        $twitterSettings = settingsApi::where('appType', 'twitter')->first();
        $consumer_key = $twitterSettings['appID'];
        $consumer_secret = $twitterSettings['appSecret'];

        $userData = Api::where('creator_id', Auth::user()->id)->where('account_id', $twitterId)->first();

        $screenName = $userData['account_name'];
        $oauth_token = $userData['token'];
        $oauth_token_secret = $userData['token_secret'];

        $connection = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);

        //to get bearer token
        $credentials = base64_encode($consumer_key . ':' . $consumer_secret);
        $auth_url = 'https://api.twitter.com/oauth2/token';
        $headers = array(
            'Authorization: Basic ' . $credentials,
            'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
        );
        $postfields = 'grant_type=client_credentials';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $auth_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($result, true);
        $bearerToken = $response['access_token'];

        $url = 'https://api.twitter.com/2/tweets?ids=1212092628029698048';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer '. $bearerToken
        ));
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            return response()->json(['error' => $error], 500);
        }
        curl_close($ch);
        $data = json_decode($response, true);

        return response()->json([
            'data' => $twitterId,
            'status' => true
        ],200);
    }
}
