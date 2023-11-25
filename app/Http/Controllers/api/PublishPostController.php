<?php

namespace App\Http\Controllers\api;

use Carbon\Carbon;
use App\Models\Api;
use App\Models\time_think;
use App\Models\publishPost;
use App\Models\settingsApi;
use Illuminate\Http\Request;
use App\Models\youtube_category;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Google_Client;
use Facebook\Facebook;
use Google_Service_YouTube;
use Illuminate\Support\Str;
use Google_Service_Exception;
use Google_Service_YouTube_Video;
use Illuminate\Support\Facades\Http;
use Abraham\TwitterOAuth\TwitterOAuth;
use Google_Service_YouTube_VideoStatus;
use Google_Service_YouTube_VideoSnippet;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Exceptions\FacebookResponseException;

class PublishPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userAccounts = Api::all()->where('creator_id', Auth::user()->id);
        $timeThink = time_think::where('creator_id', Auth::user()->id)->first();
        $youtubeCategories = youtube_category::all();

        return response()->json([
            'data' => [
                'userAccounts' => $userAccounts, // if empty .. not send form before register in account
                // 'timeThink' => $timeThink,
                'youtubeCategories' => $youtubeCategories,
            ],
            'status' => true
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $validatedData =  Validator::make($request->all(), [
                'accounts_id' => 'array|required',
                'postData' => 'string|required|max:5000',
                'image' => 'image|mimes:jpg,jpeg,png,gif',
                'video' => 'file|mimetypes:video/*',
                'link' => 'string',
            ]);
    
            if($validatedData->fails()){
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validatedData->errors(),
                    'status' => false
                ],200);
            }
    
            $accountsID = $request->accounts_id;
            $accountsType = [];
            $accountsData = [];
    
            foreach ($accountsID as $id) {
                $accounts = Api::where('account_id', $id)->where('creator_id', Auth::user()->id)->get();
                foreach ($accounts as $account) {
                    $validator = $this->getValidatorForAccountType($account['account_type'], $request);
    
                    if($validator->fails()){
                        return response()->json([
                            'message' => 'Validation error',
                            'errors' => $validator->errors(),
                            'status' => false
                        ],422);
                    }
    
                    $account_type = $account->account_type;
                    $accountData = [
                        'creator_id' => Auth::user()->id,
                        'account_type' => $account->account_type,
                        'account_id' => $account->account_id,
                        'account_name' => $account->account_name,
                        'tokenApp' => $account->token,
                        'token_secret' => $account->token_secret
                    ];
                }
    
                $accountsType[] = $account_type;
                $accountsData[] = $accountData;
            }

            $allApps = Api::all()->where('creator_id', Auth::user()->id);
            $appID = $allApps->pluck('account_id')->toArray();

            $selectedApps = $request->input('accounts_id');
            $selectedApps = array_intersect($selectedApps, $appID);
    
            $img = null;
            $publishPosts = [];

            if ($request->hasFile('image')) 
            {
                $file = $request->file('image');
                $ext = $file->getClientOriginalExtension();
                $filename = time().'.'.$ext;
                // $img = $file->move('postImages/',$filename); 
                $img = Image::make($file->getRealPath());
                $img->fit(100); // fit(100,100) -> 100x100
                // $img = $img->save('postImages/'.$filename); 
                $storagePath = 'uploadImaged';
                if (!Storage::exists($storagePath)) {
                    Storage::makeDirectory($storagePath);
                }
                $img = $img->storeAs($storagePath, $filename);
            }

            $commpressedVideoPath= '';
            if ($request->hasFile('video')) 
            {
                $video = $request->file('video');
                $filename = $video->getClientOriginalName();
                $storagePath = 'uploadVideos';
                if (!Storage::exists($storagePath)) {
                    Storage::makeDirectory($storagePath);
                }

                $video->storeAs($storagePath, $filename);
                $videoPath = $storagePath . '/' . $filename;

                $newVideo = FFMpeg::fromDisk('local')->open($videoPath)->addFilter(function ($filters) {
                    $filters->resize(new \FFMpeg\Coordinate\Dimension(2000, 2000));
                });

                $commpressedVideoPath = $storagePath . '/' . 'compressed_' . $filename;

                $newVideo->export()
                ->toDisk('local')
                ->inFormat(new \FFMpeg\Format\Video\X264())
                ->save($storagePath . '/' . 'compressed_' . $filename);
            }

            if($request->scheduledTime){
                $postTime =  Carbon::parse($request->scheduledTime)->format('Y-m-d H:i');
                $status = 'pending';
            }
            else{
                $now = Carbon::now(); 
                $diff_time = time_think::where('creator_id', Auth::user()->id)->first()->time;
                $postTime = $now->copy()->addHours($diff_time)->format('Y-m-d H:i');
                $status = 'published';
                $publishPosts[] = $this->publishPost($request, $img,$commpressedVideoPath);
            }

            $successfulApps = []; // apps that return 'postCreated' and not error
            $messages = [];
            // $messages = $this->formatPublishMessages($publishPosts);
            foreach ($publishPosts[0] as $appName => $appResults) {
                switch ($appResults) {
                    case 'postCreated':
                        $successfulApps[] = $appName;
                        $msg = '- '.$appName.' : The post created successfully.';
                        break;
                    default:
                        $msg = '- '.$appName.' : There exist an error.';
                        break;
                }
                $messages[] = $msg;
            }

            $data = [];
            // $data = $this->preparePostData($request, $img, $videoPath, $status, $accountsData, $selectedApps);
            $allAccountsData = [];
            foreach($accountsData as $account){
                $account['status'] = $status;
                $account['content'] = $request->postData;
                $account['link'] = $request->link;
                $account['scheduledTime'] = $postTime;

                $allAccountsData[] = $account;
            }

            $selectedApps = array_intersect($accountsType, ['facebook', 'instagram', 'twitter','youtube']);

            foreach ($selectedApps as $appType) {
                if (in_array($appType, $successfulApps) || $status == 'pending') // if appType in successefullApp means that post created and not failed
                {
                    foreach($allAccountsData as $account){
                        switch ($appType) {
                            case 'facebook':
                                $account['thumbnail'] = $img;
                                break;
                            case 'instagram':
                                $account['thumbnail'] = $img;
                                break;
                            case 'twitter':
                                $account['thumbnail'] = $img;
                                break;
                            case 'youtube':
                                $account['thumbnail'] = $videoPath;
                                $account['post_title'] = $request->videoTitle;
                                $account['youtube_privacy'] = $request->youtubePrivacy;
                                $account['youtube_tags'] = $request->youtubeTags;
                                $account['youtube_category'] = $request->youtubeCategory;
                                break;
                        }
                        $data[] = $account;
                    }
                }
            }


            if (!empty($data)) {
                foreach($data as $post){
                    publishPost::create($post);
                } 
            }
    
            return response()->json([
                'message' => $messages,
                'data' => $data,
                'status' => true
            ],200);

        }
        catch(\Throwable $th){
            return response()->json([
                'message' => $th->getMessage(),
                'status' => false,
            ],500);
        }
    }


    private function getValidatorForAccountType($accountType, $request)
    {
        if ($accountType == 'youtube') {
            return Validator::make($request->all(), [
                'videoTitle' => 'required|string',
                'video' => 'required|file|mimetypes:video/*',
                'postData' => 'max:5000|string'
            ]);
        } elseif ($accountType == 'instagram') {
            return Validator::make($request->all(), [
                'image' => 'required|image|mimes:jpg,jpeg,png,gif',
                'postData' => 'max:5000|string'
            ]);
        }
        // ["1708817086653386752","UCFfozYKZZoCfh_Rs9gMujhQ"],
        return Validator::make($request->all(), ['postData' => 'required|string|max:5000']); 
    }

    public function publishPost($requestData, $img,$videoPath) 
    {
        $allApps = settingsApi::all();
        $appsType = [];
        foreach($allApps as $app){
            $app_type = $app->appType;
            $appsType[] = $app_type;
        }

        $accountsID = $requestData->accounts_id;
        $selectedApps=[];
        foreach($accountsID as $id){
            $accounts = Api::where('account_id',$id)->where('creator_id', Auth::user()->id)->get();
            foreach($accounts as $account){
                $account_type = $account->account_type;
            }
            $selectedApps[] = $account_type;
        }

        // Create an array of app types based on the selected apps
        $selectedApps = array_intersect($selectedApps, $appsType);
        $data = []; 
        // Loop through the selected app types and build the $data array
        foreach ($selectedApps as $appType) {
            $appRes = '';
            switch ($appType) {
                case 'facebook':
                    $facePublish = $this->facePublish($requestData,$img);
                    $appRes = $facePublish;
                    break;
                case 'instagram':
                    $insta = $this->instaPublish($requestData);
                    $appRes = $insta;
                    break;
                case 'twitter':
                    $twitter = $this->twitterPublish($requestData, $img);
                    $appRes = $twitter;
                    break;
                case 'youtube':
                    $yotutbe = $this->youtubePublish($requestData,$videoPath);
                    $appRes = $yotutbe;
                    break;
            }
            // $data[] = $appRes;
            $data[] = [$appType, $appRes];
        }
 
        $appResults = [];
        foreach ($data as [$appType, $appRes]) {
            $appResults[$appType] = $appRes; // associative array
        }

        return $appResults;
    }

    public function facePublish($requestData,$img)
    {
        // dd($requestData);
        $faceToken = '';
        $pageName = $requestData->page;
        $pageToken = null; 
        $pageId = null;
        $urlImage = '';

        $response = Http::get("https://graph.facebook.com/v12.0/me/accounts?access_token={$faceToken}");
        $pages = $response->json()['data'];

        $desiredPage = null;

        foreach ($pages as $page) {
            if ($page['name'] === $pageName) {
                $desiredPage = $page;
                $pageToken = $desiredPage['access_token'];
                $pageId = $desiredPage['id'];
                break;
            }
        }

        $fb = new Facebook([
            'app_id' => config('services.facebook.client_id'),
            'app_secret' => config('services.facebook.client_secret'),
            'default_graph_version' => 'v12.0', // Use the appropriate version
        ]);
        
        $fb->setDefaultAccessToken($pageToken);
        
        $permissions = 
        [
            'pages_show_list',
            'pages_read_engagement',
            'pages_manage_posts',
            'pages_manage_ads',
            'pages_manage_cta',
            'pages_manage_metadata'
        ];
        
        try {

            $url = "https://graph.facebook.com/v12.0/{$pageId}/feed";

            if ($img != null) {
                $filename = Str::replace('postImages\\', '', $img);
                $response = Http::attach(
                    'source',
                    file_get_contents($img),
                    $filename
                )->post("https://graph.facebook.com/v12.0/{$pageId}/photos", [
                    'message' => $requestData->postData,
                    'privacy' => '{"value": "EVERYONE"}',
                    'access_token' => $pageToken,
                ]);
            }
            else {
                $response = Http::post($url, [
                    'message' => $requestData->postData,
                    'link' => $requestData->link,
                    // 'privacy' => '{"value": "EVERYONE"}',
                    'access_token' => $pageToken,
                ]);
            }
            
            $response = $response->json();

            return 'postCreated';

        } catch(FacebookResponseException $e) {
            return 'Graph returned an error: ' . $e->getMessage();
        } catch(FacebookSDKException $e) {
            return 'Facebook SDK returned an error: ' . $e->getMessage();
        }
    }


    public function instaPublish($requestData)
    {
        $instaToken = '';
        // 17841458134934475 -> id evolve
        // 17841453423356345/media?image_url=https://i.ibb.co/j5jStSm/photo2.png
        // 17841453423356345/media_publish?creation_id=17989019528213233
        $accessToken = 'EAAS9OZAZBDis4BO9EwYkPxfZAr7ZCq0qiI0XMibHk4eMYN6jHDTZC0B43lned3EL9ZCEPROCWgdLKe81lELqjIiZBgZAHBjCb5Ys6bZClGzcsZAxGsApn1DA2rcjFrCCC8xltvo3ioZCkqb2tai3jXuyJbuFbru4s3Nojjf8a4QXxfusOekfwjatgUeYgrgB0EYtSUXBpzl8vBeuZCnUbMmTkAZDZD'; // Replace with your actual access token
        $pageId = '17841453423356345';
        $imageUrl = 'https://i.ibb.co/j5jStSm/photo2.png';
        $caption = $requestData->postData;

        try {

            if ($requestData->hasfile('image')) {
                $file = $requestData->file('image');
                $ext = $file->getClientOriginalExtension();
                $filename = time().'.'.$ext;
                // $img = $file->move('postImages/',$filename); 

                $mediaResponse = Http::post("https://graph.facebook.com/v17.0/{$pageId}/media", [
                    'image_url' => $imageUrl,
                    'caption' => $caption,
                    'access_token' => $accessToken,
                ]);

                
                if ($mediaResponse->successful()) {

                    $mediaData = $mediaResponse->json();
                    $mediaId = $mediaData['id'];
                    
                    if ($requestData->scheduledTime) {
                        // If you want to schedule the post
                        $scheduledTime = Carbon::parse($requestData->scheduledTime)->timestamp;
                        // Step 2: Publish media
                        $publishResponse = Http::post("https://graph.facebook.com/v17.0/{$pageId}/media_publish", [
                            'published' => false,
                            'scheduled_publish_time' => $scheduledTime,
                            'creation_id' => $mediaId,
                            'access_token' => $accessToken,
                        ]);
                    }
                    else{
                        $publishResponse = Http::post("https://graph.facebook.com/v17.0/{$pageId}/media_publish", [
                            'creation_id' => $mediaId,
                            'access_token' => $accessToken,
                        ]);
                    }    
    
                    if ($publishResponse->successful()) {
                        return 'postCreated';
                    } else {
                        return $publishResponse->status();
                    }
                } else {
                    return $mediaResponse->status();
                }
            }
            else {
                return'should choose image for instagram';
            }

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    public function twitterPublish($requestData, $img)
    {   
        $accountsID = $requestData->accounts_id;
        $twitterToken='';$twitterTokenSecret='';

        foreach($accountsID as $id){
            $accounts = Api::where('account_type', 'twitter')->where('account_id',$id)->where('creator_id', Auth::user()->id)->get();
            foreach($accounts as $account)
            {
                $twitterToken = $account->token;
                $twitterTokenSecret = $account->token_secret;
            }       
        }

        $twitterSettings = settingsApi::where('appType', 'twitter')->first(); 
        $consumer_key = $twitterSettings['appID'];
        $consumer_secret = $twitterSettings['appSecret'];
    
        $connection = new TwitterOAuth($consumer_key, $consumer_secret, $twitterToken, $twitterTokenSecret);
        $connection->setApiVersion('2');
        $connected = $connection->get("account/verify_credentials");

        $tweet = $requestData['postData'];

        $response = $connection->post('tweets', ['text' => $tweet]);
        
        if ($connection->getLastHttpCode() === 201) { // HTTP status code 201 indicates a successful tweet creation
            return 'postCreated';
        } else {
            return 'postFailed';
        }

        // $realPath = Str::replace('\\', '/', $realPath);
        // dd($realPath);
    }


    public function youtubePublish($requestData, $videoPath)
    {
        $accountsID = $requestData->accounts_id;
        $refreshToken='';

        foreach($accountsID as $id){
            $accounts = Api::where('account_type', 'youtube')->where('account_id',$id)->where('creator_id', Auth::user()->id)->get();
            foreach($accounts as $account)
            {
                $refreshToken = $account->token_secret;
            }       
        }

        $youtubeSettings = settingsApi::where('appType', 'youtube')->first();

        $client = new Google_Client();
        $client->setClientId($youtubeSettings['appID']);
        $client->setClientSecret($youtubeSettings['appSecret']);
        $client->setRedirectUri(route('youtube.callback'));
        $client->setScopes(['https://www.googleapis.com/auth/youtube.upload']);

        $client->fetchAccessTokenWithRefreshToken($refreshToken);
        $newAccessToken = $client->getAccessToken();
        $videoPath = storage_path('app/' . $videoPath);
        $fullPathToVideo = Str::replace('\\', '/', $videoPath);

        $youTubeService = new Google_Service_YouTube($client);

        $tags = !empty($requestData->youtubeTags) ? explode(',', $requestData->youtubeTags) : ['tag1', 'tag2'];
        $category_id = !empty($requestData->youtubeCategory) ? $requestData->youtubeCategory : '22';

        if($client->getAccessToken()) {
            $snippet = new Google_Service_YouTube_VideoSnippet();
            $snippet->setTitle($requestData->videoTitle);
            $snippet->setDescription($requestData->postData);
            // $snippet->setTags(array("tag1","tag2"));
            $snippet->setTags($tags);
            $snippet->setCategoryId($category_id);
            $snippet->setChannelId($requestData->channel);
        
            $status = new Google_Service_YouTube_VideoStatus();
            $status->privacyStatus = $requestData->youtubePrivacy ? $requestData->youtubePrivacy : 'public';
        
            $video = new Google_Service_YouTube_Video();
            $video->setSnippet($snippet);
            $video->setStatus($status);

            try {
                $obj = $youTubeService->videos->insert("status,snippet", $video,
                                                array("data"=>file_get_contents($fullPathToVideo), 
                                                "mimeType" => "video/*"));
                return 'postCreated';
            } catch(Google_Service_Exception $e) {
                // dd ("Caught Google service Exception ".$e->getCode(). " message is ".$e->getMessage(). " <br>");
                // dd ("Stack trace is ".$e->getTraceAsString());
                return 'postFailed';
            }
        }
    }    


    
    public function show(string $id){}
    public function update(Request $request, string $id){}
    public function destroy(string $id){}
}
