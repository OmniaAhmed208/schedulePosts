<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Api;
use App\Models\settingsApi;
use Illuminate\Support\Facades\Auth;
use Abraham\TwitterOAuth\TwitterOAuth;
use Abraham\TwitterOAuth\TwitterOAuthException;

class TwitterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function twitterRedirect()
    {
        $callback = route('twitter.callback');

        // consumer key and secret
        $twitterSettings = settingsApi::where('appType', 'twitter')->first(); 
        $apiKey = $twitterSettings['appID'];
        $apiSecret = $twitterSettings['appSecret'];
        
        $twitter_connect = new TwitterOAuth($apiKey, $apiSecret);
    
        $access_token = $twitter_connect->oauth('oauth/request_token',['oauth_callback'=>$callback]);

        $route = $twitter_connect->url('oauth/authorize',['oauth_token'=>$access_token['oauth_token']]);

        return redirect($route);
    }
    

    public function twitterCallback(Request $request)
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
}
