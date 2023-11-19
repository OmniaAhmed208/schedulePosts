<?php

namespace App\Http\Controllers;

use Facebook\Facebook;
use App\Models\Instagram;
use Illuminate\Http\Request;

class InstagramController extends Controller
{
    public function index(){
        return view('social.instagram');
    }

    public function socialStatusInsta() 
    {
        if(Instagram::count() > 0)
        {
            Instagram::truncate();
        }
        return response()->json(['status' => 'success']);
    }

    public function getData($pageId,$accessToken,$url){

        $fb = new Facebook([
            'app_id' => '1333955093826254', //config('services.facebook.client_id')
            'app_secret' => 'dd3aacba0ae1fcc0c4e1c11b80be3291', //config('services.facebook.client_secret')
            'default_graph_version' => 'v12.0',
        ]);

        $fb->setDefaultAccessToken($accessToken);

        // $url = "https://graph.facebook.com/{$pageId}?fields=id,name,picture,posts{full_picture,created_time}&access_token={$accessToken}"; // page
        // $url = "https://graph.facebook.com/{$pageId}/posts?fields=permalink_url,full_picture,created_time&access_token={$accessToken}&limit=6"; //posts of page

        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the cURL request
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);

            return response()->json(['error' => $error], 500);
        }

        // Close cURL session
        curl_close($ch);

        // Process and return the response
        $data = json_decode($response, true);
        // return response()->json(['data' => $data]);

        return $data;
    }

    public function instagram()
    {
        $apiCount = Instagram::count();
        $fullPictures = []; // Initialize the variables
        $fullLinks = [];
        $httpCode = '';

        if($apiCount != 0) // if exist data in the table
        {
            $data = Instagram::all()->last();
            $insta_api = $data['insta_token'];

            $url = 'https://graph.instagram.com/me/media?fields=id,caption,media_type,media_url,permalink&access_token=' . $insta_api;

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            curl_close($curl);

            if ($httpCode === 200)
            {
                $data = json_decode($response, true);

                foreach ($data['data'] as $post)
                {
                    $fullPictures[] = $post['media_url'];
                    $fullLinks[] = $post['permalink'];
                }

                return view('social.instagram',compact('insta_api','fullLinks','fullPictures','httpCode'));

            } else {
                return view('social.instagram',compact('insta_api','fullLinks','fullPictures','httpCode'));
            }

        }
        else
        {
            return view('social.instagram', compact('fullPictures','fullLinks','httpCode'));
        }
    }

    public function insta_create(){
        // return view('social.instaCreate');
        return view('AdminSocialMedia.instaCreate');
    }

    public function store_instaApi(Request $request)
    {
        $request->validate([
            'insta_name' => 'required',
            'insta_token' => 'required'
        ]);

        Instagram::create([
            'insta_name' => $request->insta_name,
            'insta_token' => $request->insta_token,
        ]);

        return $this->instagram();
    }



    public function redirectToInstagramProvider(Request $request)
    {
        $appId = config('services.instagram.client_id');
        $redirectUri = urlencode(config('services.instagram.redirect'));
        $state = bin2hex(random_bytes(16));
        session(['instagram_state' => $state]);
        $url = "https://api.instagram.com/oauth/authorize?client_id={$appId}&redirect_uri={$redirectUri}&scope=user_profile,user_media&response_type=code&state={$state}";
        $urlData = redirect()->to($url);
        // dd($urlData);

        return redirect()->to("https://api.instagram.com/oauth/authorize?client_id={$appId}&redirect_uri={$redirectUri}&scope=user_profile,user_media&response_type=code&state={$state}");
        // return redirect()->to("https://api.instagram.com/oauth/authorize?app_id={$appId}&redirect_uri={$redirectUri}&scope=user_profile,user_media&response_type=code");
    }

    public function instagramProviderCallback(Request $request)
    {
        $code = $request->input('code');
        // dd($code);

        if (empty($code)) return redirect()->route('home')->with('error', 'Failed to login with Instagram.');

        $appId = config('services.instagram.client_id');
        $secret = config('services.instagram.client_secret');
        $redirectUri = config('services.instagram.redirect');

        $client = new \GuzzleHttp\Client();

        // Get access token
        $response = $client->request('POST', 'https://api.instagram.com/oauth/access_token', [
            'form_params' => [
                'client_id' => $appId,
                'client_secret' => $secret,
                'grant_type' => 'authorization_code',
                'redirect_uri' => $redirectUri,
                'code' => $code,
            ]
        ]);

        if ($response->getStatusCode() != 200) {
            return redirect()->route('home')->with('error', 'Unauthorized login to Instagram.');
        }

        $content = $response->getBody()->getContents();
        $content = json_decode($content);

        $accessToken = $content->access_token;
        $userId = $content->user_id;

        // Store the access token in a session
        session(['instagram_access_token' => $accessToken]);

        return redirect()->route('instagram')->with('success', 'Logged in with Instagram!');
    }
}
