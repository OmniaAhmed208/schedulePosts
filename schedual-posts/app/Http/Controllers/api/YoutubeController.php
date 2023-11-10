<?php

namespace App\Http\Controllers\api;

use App\Models\Api;
use App\Models\settingsApi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Google_Client;
use App\Models\social_posts;
use Exception;
use Illuminate\Support\Facades\Http;

class YoutubeController extends Controller
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

    public function youtubeData($channelId)
    {
        $youtubeSettings = settingsApi::where('appType', 'youtube')->first();
        $key = $youtubeSettings['apiKey'];
        $base_url = 'https://www.googleapis.com/youtube/v3/';
        $maxResults = 10;

        $url = $base_url."search?part=snippet&channelId=".$channelId."&maxResult=".$maxResults."&key=".$key;
        $url = $base_url."search?order=date&part=snippet&channelId=".$channelId."&maxResults=".$maxResults."&key=".$key;

        try{
            $videos = json_decode(file_get_contents($url));
        }
        catch(Exception $e){
            return response()->json([
                'message' => 'The limit of access of youtube channel has been finished',
                'status' => false
            ],500);
        }

        $data = [];
        $account_id = '';

        if (isset($videos->items)) 
        {
            foreach ($videos->items as $index => $item) 
            {
                $videoData = [];
                $lastIndex = count($videos->items) - 1; // because last record is channel info

                if ($index !== array_keys($videos->items)[$lastIndex])
                {
                    $account_id = $item->snippet->channelId;
                    $apiAccount = Api::where('account_id', $account_id)->where('creator_id', Auth::user()->id)->first();
                    $postDate = date('Y-m-d H:i:s', strtotime($item->snippet->publishTime));
                    $videoData = [
                        'api_account_id' => $apiAccount->id,
                        'post_id' => $item->id->videoId,
                        'post_video' => $item->snippet->thumbnails->high->url,
                        'post_link' => "https://www.youtube.com/watch?v=" . $item->id->videoId,
                        'post_title' => $item->snippet->title,
                        'content' => $item->snippet->description,
                        'post_date' => $postDate,
                    ];
                    $data[] = $videoData; 
                }
            }        

            $existingAccount = Api::where('account_id',$account_id)->where('creator_id', Auth::user()->id)->first(); // if channel exist?
            $existingChannel = social_posts::where('api_account_id', $existingAccount['id'])->get(); // this channel has posts

            if($existingChannel->isNotEmpty()) // channel exist
            {   
                foreach ($data as $index => $post)
                { 
                    $existingPost = social_posts::where('post_id', $post['post_id'])->first();
                    
                    if (!$existingPost) {
                        foreach($data as $post){
                            social_posts::create($post);
                        }  
                    }
                    else
                    {
                        $existingPost->update($post);
                    }
                }

                // if i remove video from channel manually .. i don't it here in db ... (when save data to db .. found videos not remaing exist in original channel)
                $postIdsInDataArr = array_column($data, 'post_id'); // Step 1: Get all post_ids from the $data array
                $postsToDelete = Api::find($existingAccount['id'])->social_posts()->whereNotIn('post_id', $postIdsInDataArr)->get();
                $postsToDelete->each->delete(); 
            }
            else
            {   //channel not exist
                if (!empty($data) ) {
                    foreach($data as $post){
                        social_posts::create($post);
                    }   
                }
            }
            
            return response()->json([
                'message' => 'Data fetched successfully',
                'data' => $data,
                'status' => true
            ],200);

        } else {
            return response()->json([
                'message' => 'No videos found.',
                'status' => true
            ],200);
        }
    }


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

            // Request authorization from the user.
            $client->setRedirectUri(route('youtube.callback'));
            $authUrl = $client->createAuthUrl();
            // printf("Open this link in your browser:\n%s\n", $authUrl);
            if ($request->has('code')) {
                // Exchange verification code for an access token.
                $authCode = $request->input('code');
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                if (isset($accessToken['access_token'])) 
                {
                    $access_token = $accessToken['access_token'];
                    $refresh_token = $accessToken['refresh_token'];
                    
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
                    if (isset($data['items'][0])) {
                        $channelId = $data['items'][0]['id'];
                        $channelName = $data['items'][0]['snippet']['title'];
                        $channelImageUrl = $data['items'][0]['snippet']['thumbnails']['default']['url'];
                        $channelLink = "https://www.youtube.com/channel/{$channelId}";

                        $userData = [
                            'creator_id'=> Auth::user()->id,
                            'account_type' => 'youtube',
                            'account_id' => $channelId,
                            'account_name' => $channelName,
                            'email' => $channelName,
                            'account_pic' => $channelImageUrl,
                            'account_link' => $channelLink,
                            'token' => $access_token,
                            'token_secret' => $refresh_token
                        ];
            
                        $existingApp = Api::where('account_id',$channelId)->where('creator_id', Auth::user()->id)->first();
            
                        if ($existingApp) {
                            $existingApp->update($userData);
                        } else {
                            Api::create($userData);
                        }
                        $this->youtubeData($channelId);

                        return response()->json([
                            'message' => 'Account saved',
                            'data' => $userData,
                            'status' => true
                        ],200);

                    } else 
                    {
                        return response()->json([
                            'message' => 'No YouTube channel found for this account.',
                            'status' => false
                        ],400);
                    }
                    
                } 
            }

            else{
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
}
