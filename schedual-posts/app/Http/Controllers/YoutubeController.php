<?php

namespace App\Http\Controllers;

use Google_Client;
use App\Models\Api;
use App\Models\settingsApi;
use App\Models\social_posts;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

class YoutubeController extends Controller
{
    public function show($channelId)
    {
        // $youtubePosts = social_posts::where('page_id',$channelId)->get();
        $api_id = Api::where('account_id',$channelId)->where('creator_id', Auth::user()->id)->first();
        $youtubePosts = Api::find($api_id['id'])->social_posts()->get();
        
        return view('social.youtube',compact('channelId'));
    }

    public function youtubeData($channelId)
    {
        $youtubeSettings = settingsApi::where('appType', 'youtube')->first();
        $key = $youtubeSettings['apiKey'];
        // $key = Config::get('services.youtube.api_key');
        $base_url = 'https://www.googleapis.com/youtube/v3/';
        $maxResults = 10;

        $url = $base_url."search?part=snippet&channelId=".$channelId."&maxResult=".$maxResults."&key=".$key;
        $url = $base_url."search?order=date&part=snippet&channelId=".$channelId."&maxResults=".$maxResults."&key=".$key;

        try{
            $videos = json_decode(file_get_contents($url));
        }
        catch(Exception $e){
            // $url = https://www.googleapis.com/youtube/v3/search?order=date&part=snippet&maxResults=3&channelId=UCRi9XQdahkIxtdlx4CawKhQ&key=AIzaSyCZhW13YQV1En4FEtVET312rRwIbAj3Rp4
            return redirect()->route('socialAccounts')->with('error', 'The limit of access of youtube channel has been finished');
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
            // $existingChannel = Api::find($existingAccount['id'])->social_posts()->get();

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
                // $postsToDelete = social_posts::where('type','youtube')->whereNotIn('post_id', $postIdsInDataArr)->get(); // Step 2: Find the records in the database that need to be deleted
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
            
            // echo "<pre>";
            // print_r(json_encode($data));
            return response()->json(['message' => 'Data fetched successfully']);

        } else {
            echo "No videos found.";
        }
        // return view('social.youtube',['data'=>json_encode($data),'videos'=>json_encode($videos)]);
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

                        return redirect()->route('socialAccounts');
                    } else {
                        return redirect()->route('socialAccounts')->with('error', 'No YouTube channel found for this account.');
                    }
                    
                } else {
                   return back();
                }
            }

            else{
                dd('error to get code');
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
            // return redirect()->route('socialAccounts')->with('error', $e->getMessage());
        } 
        
    }
    

    public function videosList() {
        $to_show_videoLink = 'https://www.youtube.com/watch?v=videoId';

        $youtubeSettings = settingsApi::where('appType', 'youtube')->first();
        $apiKey = $youtubeSettings['apiKey'];

        $part = 'snippet';
        $country = 'BD';
        // $apiKey = 'AIzaSyCZhW13YQV1En4FEtVET312rRwIbAj3Rp4';
        $maxResults = 10;
        $youtube_endPoint = 'https://www.googleapis.com/youtube/v3/search/';
        // $type = 'video,playlist,channel';
        $type = 'video';
        $keyword = 'laravel chat'; // if user want to search specific videos or playlist..
        
        $url = "$youtube_endPoint?part=$part&maxResults=$maxResults&regionCode=$country&type=$type&key=$apiKey&q=$keyword";
        $response = Http::get($url);
        $result = json_decode($response);
        // dd($result);
        // File::put(storage_path() . '/results.json', $response->body());
    }
    public function singleVideo($id) 
    {
        $apiKey = 'AIzaSyCZhW13YQV1En4FEtVET312rRwIbAj3Rp4';   
        $part = 'snippet';
        $url = "https://www.googleapis.com/youtube/v3/videos?part=$part&id=$id&key=$apiKey";
        $response = Http::get($url);
        $result = json_decode($response);
    }
    
}
          



// OR
// $parameter = [
//  // 'order' => 'date',
//     'part'=> 'snippet',
//     'maxResults'=> '3',
//     'channelId' => 'UCRi9XQdahkIxtdlx4CawKhQ',
//     'key'=> $key
// ];
// $url = 'https://www.googleapis.com/youtube/v3/search?';
// $channel_URL = $url . http_build_query($parameter); // object

// echo "<pre>";
// print_r($videos->items);


