<?php 

namespace App\Services;

use DateTime;
use Exception;
use DateTimeZone;
use Carbon\Carbon;
use Google_Client;
use App\Models\Api;
use Facebook\Facebook;
use App\Models\settingsApi;
use App\Models\UploadFiles;
use Google_Service_YouTube;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Google_Service_Exception;
use Thujohn\Twitter\tmhOAuth;
use Google_Service_YouTube_Video;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Abraham\TwitterOAuth\TwitterOAuth;
use Google_Service_YouTube_VideoStatus;
use Illuminate\Support\Facades\Storage;
use Google_Service_YouTube_VideoSnippet;
use Stevebauman\Location\Facades\Location;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Exceptions\FacebookResponseException;

class PostService 
{
    public function userTime()
    {
        // $currentUrl = request()->url(); 
        //if (parse_url($currentUrl, PHP_URL_SCHEME) === 'https') {}

        $ip = '';
        if (request()->isSecure()) {
            $ip = request()->ip(); // Dynamic IP address get
        } else {
            $ip = '41.43.246.5'; // For static IP address get
        }

        $data = Location::get($ip);

        if ($data && isset($data->timezone)) {
            $userTimezoneString = $data->timezone;
            $userTz = new DateTimeZone($userTimezoneString);
            $userNow = new DateTime('now', $userTz);
            $userTimeNow = $userNow->format('Y-m-d H:i:s'); // ex: "2023-11-26 17:01:38"
            $currentTime = Carbon::now($userTimezoneString)->format('Y-m-d H:i:s');

            return [
                'userTimeNow' => $userTimeNow,
                'currentTime' => $currentTime,
            ];
        } else {
            return [
                'userTimeNow' => null,
                'currentTime' => null,
            ];
        }
    }
    
    public function saveImages($images)
    {
        $imgUpload = [];
        foreach ($images as $image) {
            $user = 'user'.Auth::user()->id;

            $tmp_file = UploadFiles::where('file',$image)->first();
            if($tmp_file){
                $dir = $user.'/'.'postImages';
                $storageImage = url('storage/' . $dir . '/'. $tmp_file->file);
                $imgUpload[] = $storageImage;
                $tmp_file->delete();
            }
        }
        return $imgUpload;
    }

    public function removeImagesUploaded($images) 
    {
        foreach ($images as $image) {
            $user = 'user'.Auth::user()->id;
            $tmp_file = UploadFiles::where('file',$image)->first();
            
            if($tmp_file){
                $dir = $user.'/'.'postImages';
                $filePath = storage_path('app/public/'. $dir .'/'.$tmp_file->file);
                if (file_exists($filePath)) {
                    Storage::delete('public/'. $dir .'/'.$tmp_file->file);
                }

                $tmp_file->delete();
            }
        }  
        return true;
    }

    public function saveVideo($video)
    {
        $user = 'user'.Auth::user()->id;

        $tmp_file = UploadFiles::where('file',$video)->first();
        $youtubeVideoPath = '';$twitterVideoPath = ''; $storageVideo='';
        if($tmp_file){
            $dir = $user.'/'.'postVideo';
            $OriginalVideo = 'public/'.$dir. '/' . $tmp_file->file;
            $youtubeVideoPath = $OriginalVideo; // for youtube
            $twitterVideoPath = storage_path('app/'. $OriginalVideo); 
            $storageVideo = url('storage/' . $dir . '/'. $tmp_file->file);
            $tmp_file->delete();
        }
        return [
            'youtubeVideoPath' => $youtubeVideoPath,
            'twitterVideoPath' => $twitterVideoPath,
            'storageVideo' => $storageVideo,
        ];
    }

    public function removeVideoUploaded($vidoe) 
    {
        $user = 'user'.Auth::user()->id;
        $tmp_file = UploadFiles::where('file',$vidoe)->first();
        
        if($tmp_file){
            $dir = $user.'/'.'postVideo';
            $filePath = storage_path('app/public/'. $dir .'/'.$tmp_file->file);
            if (file_exists($filePath)) {
                Storage::delete('public/'. $dir .'/'.$tmp_file->file);
            }

            $tmp_file->delete();
        }
        return true;
    }

    public function publishPost($requestData, $images, $youtubeVideoPath, $twitterVideoPath)
    {
        $account_type='';

        $services = settingsApi::get(['appType'])->map(function($query){
            return $query->appType;
        })
        ->toArray();

        $accountsID = $requestData->accounts_id;
        $selectedApps=[];

        if($accountsID != null){
            foreach($accountsID as $id){
                $accounts = Api::where('account_id',$id)->where('creator_id', Auth::user()->id)->get();
                foreach($accounts as $account){
                    $account_type = $account->account_type;
                }
                $selectedApps[] = $account_type;
            }
        }
        // Create an array of app types based on the selected apps
        // $selectedApps = array_intersect($selectedApps, $services);
        $selectedApps = array_unique(array_intersect($selectedApps, $services));

        $data = []; 
        // Loop through the selected app types and build the $data array
        foreach ($selectedApps as $appType) {
            $appRes = '';
            switch ($appType) {
                case 'facebook':
                    $facePublish = $this->facePublish($requestData,$images);
                    $appRes = $facePublish;
                    break;
                case 'instagram':
                    $insta = $this->instaPublish($requestData);
                    $appRes = $insta;
                    break;
                case 'twitter':
                    $twitter = $this->twitterPublish($requestData, $images,$twitterVideoPath);
                    $appRes = $twitter;
                    break;
                case 'youtube':
                    $yotutbe = $this->youtubePublish($requestData,$youtubeVideoPath);
                    $appRes = $yotutbe;
                    break;
            }
            // $data[] = $appRes;
            $data[] = [$appType, $appRes];
        }
 
        $appResults = [];
        foreach ($data as [$appType, $appRes]) {
            $appResults[$appType] = $appRes;
        }

        return $appResults;
    }
 
    public function facePublish($requestData,$imgPaths)
    {
        // dd($requestData);
        $accountsID = $requestData->accounts_id;
        $facebookSettings = settingsApi::where('appType', 'facebook')->first(); 
        $client_id = $facebookSettings['appID'];
        $client_secret = $facebookSettings['appSecret'];

        foreach ($accountsID as $id) {
            $accounts = Api::where('account_type', 'facebook')
                ->where('account_id', $id)
                ->where('creator_id', Auth::user()->id)
                ->get();
    
            foreach ($accounts as $account) {
                $facebookPageToken = $account->token_secret;

                $text = $requestData['content'] ?? '';

                $fb = new Facebook([
                    'app_id' => $client_id,
                    'app_secret' => $client_secret,
                    'default_graph_version' => 'v12.0', // Use the appropriate version
                ]);
                
                $fb->setDefaultAccessToken($facebookPageToken);
                $permissions = 
                [
                    'pages_show_list',
                    'pages_read_engagement',
                    'pages_manage_posts',
                    'pages_manage_ads',
                    'pages_manage_cta',
                    'pages_manage_metadata'
                ];

                try 
                {
                    $url = "https://graph.facebook.com/v12.0/{$account->account_id}/feed";
                    if (!empty($imgPaths)) 
                    {
                        foreach ($imgPaths as $imgPath) {
                            // http://192.168.1.15:8000/storage/uploadImages/img.png
                            $rm_urlPath = parse_url($imgPath, PHP_URL_PATH);
                            $filename = Str::replace('/storage/uploadImages/','',$rm_urlPath);
                            $response = Http::attach(
                                'source',
                                file_get_contents($imgPath),
                                // $imgPath,
                                $filename
                            )->post("https://graph.facebook.com/v12.0/{$account->account_id}/photos", [
                                'message' => $text,
                                'privacy' => '{"value": "EVERYONE"}',
                                'access_token' => $facebookPageToken,
                            ]);

                            $rm_urlPath = parse_url($imgPath, PHP_URL_PATH);
                            $storagePath = Str::replace('public\/storage/','storage\app/public/',public_path($rm_urlPath));
                            // if (file_exists($storagePath)) {
                            //     // dd('exist');
                            // } else {
                            //     dd('File does not exist: ' . $storagePath);
                            // }
                        }
                    }
                    else {
                        $response = Http::post($url, [
                            'message' => $text,
                            'link' => $requestData->link,
                            // 'privacy' => '{"value": "EVERYONE"}',
                            'access_token' => $facebookPageToken,
                        ]);
                        // return 'postCreated'; //: Only images or videos can be posted, not both.
                    }
                    $response = $response->json();    
        
                } catch(FacebookResponseException $e) {
                    return 'Graph returned an error: ' . $e->getMessage();
                } catch(FacebookSDKException $e) {
                    return 'Facebook SDK returned an error: ' . $e->getMessage();
                }
            }
        }
    }

    public function twitterPublish($requestData, $imgPaths,$videoPath)
    {   
        $accountsID = $requestData->accounts_id;
        $twitterSettings = settingsApi::where('appType', 'twitter')->first(); 
        $consumer_key = $twitterSettings['appID'];
        $consumer_secret = $twitterSettings['appSecret'];
        $mediaIds = [];
    
        foreach ($accountsID as $id) {
            $accounts = Api::where('account_type', 'twitter')
                ->where('account_id', $id)
                ->where('creator_id', Auth::user()->id)
                ->get();
    
            foreach ($accounts as $account) {
                $twitterToken = $account->token;
                $twitterTokenSecret = $account->token_secret;
    
                // Modify the text to include a unique identifier
                $text = $requestData['content'] ?? '';
    
                $connection = new TwitterOAuth($consumer_key, $consumer_secret, $twitterToken, $twitterTokenSecret);
                $connected = $connection->get("account/verify_credentials");
    
                if (!empty($imgPaths) && !empty($videoPath)) {
                    return 'postFailed'; //: Only images or videos can be posted, not both.
                }
                elseif (!empty($imgPaths))
                {
                    foreach ($imgPaths as $imgPath) 
                    {
                        $rm_urlPath = parse_url($imgPath, PHP_URL_PATH);
                        $storagePath = Str::replace('public\/storage/','storage\app/public/',public_path($rm_urlPath));

                        if (file_exists($storagePath)) {
                            $connection->setApiVersion(1.1);
                            $connection->setTimeouts(60, 10);
                            $media = $connection->upload('media/upload', ['media' => $storagePath]);
                            $mediaIds[] = $media->media_id_string;
                        } else {
                            return 'postFailed'; // dd('File does not exist: ' . $storagePath);
                        }
                    }

                    $connection->setApiVersion(2);
                    $parameters = [
                        'text' => $text,
                        'media' => ['media_ids' => $mediaIds],
                    ];
                    $result = $connection->post('tweets', $parameters, true);
                    // dd($result);
                    $mediaIds = [];      
                }
                elseif (!empty($videoPath)) { //http://192.168.1.15:8000/storage/uploadVideos/sec.mp4
                    try{
                        $rm_urlPath = parse_url($videoPath, PHP_URL_PATH);
                        $storagePath = Str::replace('public\/storage/','storage\app/public/',public_path($rm_urlPath));

                        $connection = new tmhOAuth([
                            'consumer_key' => $consumer_key,
                            'consumer_secret' => $consumer_secret,
                            'token' => $twitterToken,
                            'secret' => $twitterTokenSecret,
                        ]);
                        $code = $connection->request(
                            'POST',
                            $connection->url('1.1/media/upload.json'),
                            [
                                'command' => 'INIT',
                                'media_type' => 'video/mp4',
                                'total_bytes' => filesize($videoPath),
                            ]
                        );
                        // dd($connection->response);
                        $results = json_decode($connection->response['response']);
                        
                        if ($connection->response['code'] === 200) {
                            $media_id = $results->media_id_string;
                    
                            // Step 2: Upload video chunks (POST media/upload - APPEND)
                            $chunkSize = 1024 * 1024; // 1 MB chunk size
                            $file = fopen($videoPath, 'rb');
                            $segmentIndex = 0;
                    
                            while (!feof($file)) {
                                $chunk = fread($file, $chunkSize);
                                $connection->request(
                                    'POST',
                                    $connection->url('1.1/media/upload.json'),
                                    [
                                        'command' => 'APPEND',
                                        'media_id' => $media_id,
                                        'segment_index' => $segmentIndex,
                                    ],
                                    true, // Use raw post data
                                    $chunk
                                );
                                $segmentIndex++;
                            }
                    
                            fclose($file);
                    
                            // Step 3: Finalize the video upload (POST media/upload - FINALIZE)
                            $connection->request(
                                'POST',
                                $connection->url('1.1/media/upload.json'),
                                [
                                    'command' => 'FINALIZE',
                                    'media_id' => $media_id,
                                ]
                            );
                    
                            if ($connection->response['code'] === 200) {
                                dd($media_id); // Video upload successful
                            } else {
                                dd('Error finalizing video upload: ' . $connection->response['response']);
                            }
                        } else {
                            dd('Error initializing video upload: ' . json_encode($connection->response));
                            // dd('Error initializing video upload: ' . $connection->response['response']);
                        }
                    }
                    catch(Exception $e){
                        dd($e->getMessage());
                    }
                }
                else {
                    $result = $connection->post('tweets', ['text' => $text]);
                }
            }
        }
    
        if ($connection->getLastHttpCode() === 201) {
            return 'postCreated';
        } else {
            return 'postFailed';
        }
    }

    public function youtubePublish($requestData, $videoPath)
    {
        $accountsID = $requestData->accounts_id;
        $refreshToken='';
        
        $youtubeSettings = settingsApi::where('appType', 'youtube')->first();
        $client_id = $youtubeSettings['appID'];
        $client_secret = $youtubeSettings['appSecret'];

        foreach($accountsID as $id){
            $accounts = Api::where('account_type', 'youtube')
            ->where('account_id',$id)
            ->where('creator_id', Auth::user()->id)
            ->get();

            foreach($accounts as $account)
            {
                $refreshToken = $account->token_secret;

                $client = new Google_Client();
                $client->setClientId($client_id);
                $client->setClientSecret($client_secret);
                $client->setRedirectUri(route('youtube.callback'));
                $client->setScopes(['https://www.googleapis.com/auth/youtube.upload']);
                $allUploadsSuccessful = true;

                $validationToken = $client->fetchAccessTokenWithRefreshToken($refreshToken);
                $newAccessToken = $client->getAccessToken();
                $videoPathStorage = storage_path('app/' . $videoPath);
                // $fullPathToVideo = Str::replace('\\', '/', $videoPathStorage);
                $fullPathToVideo = realpath($videoPathStorage);
                // dd($fullPathToVideo);

                $youTubeService = new Google_Service_YouTube($client);

                $tags = !empty($requestData->youtubeTags) ? explode(',', $requestData->youtubeTags) : ['tag1', 'tag2'];
                $category_id = !empty($requestData->youtubeCategory) ? $requestData->youtubeCategory : '22';
                $category_id ='22';

                // dd($validationToken);
                if($client->getAccessToken()) {
                    $snippet = new Google_Service_YouTube_VideoSnippet();
                    $snippet->setTitle($requestData->videoTitle);
                    $snippet->setDescription($requestData->content);
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
                        $obj = $youTubeService->videos->insert(
                            "status,snippet", 
                            $video,
                            array(
                                "data"=>file_get_contents($fullPathToVideo), 
                                "mimeType" => "video/*"
                            )
                        );
                        // $videoId = $obj->id;
                        // $videoLink = "https://www.youtube.com/watch?v=" . $videoId;
                        // dd($videoLink);
                    } catch(Google_Service_Exception $e) {
                        $allUploadsSuccessful = false;
                        dd ("Caught Google service Exception ".$e->getCode(). " message is ".$e->getMessage(). " <br>");
                        // dd ("Stack trace is ".$e->getTraceAsString());
                    }
                }
                else{
                    $allUploadsSuccessful = false;
                }

                if ($allUploadsSuccessful) {
                    return 'postCreated';
                } else {
                    return 'postFailed'; 
                }
            }  
            
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
        $caption = $requestData->content;

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

    public function checkVideoStatus($videoId)
    {
        $youtubeSettings = settingsApi::where('appType', 'youtube')->first();          
        $client = new Google_Client();
        $client->setClientId($youtubeSettings['appID']);
        $client->setClientSecret($youtubeSettings['appSecret']);
        $client->setScopes('https://www.googleapis.com/auth/youtube.upload');
        $client->setRedirectUri(route('youtube.callback'));
        $state = bin2hex(random_bytes(16));
        $client->setState($state);
        $youtube = new Google_Service_YouTube($client);

        $part = 'status';

        $videoResponse = $youtube->videos->listVideos($part, array('id' => $videoId));

        // Check if there is a valid response
        if ($videoResponse->items && count($videoResponse->items) > 0) {
            $video = $videoResponse->items[0];
            $status = $video->status;

            // You can now check the video status
            $uploadStatus = $status->uploadStatus;
            $privacyStatus = $status->privacyStatus;

            if ($uploadStatus === 'processed' && $privacyStatus === 'public') {
                return "Video is published and public.";
            } else {
                return "Video is not yet processed or not public.";
            }
        } else {
            return "Video not found.";
        }
    }

}