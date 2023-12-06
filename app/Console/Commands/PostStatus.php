<?php

namespace App\Console\Commands;

use Exception;
use Carbon\Carbon;
use Google_Client;
use App\Models\Api;
use Facebook\Facebook;
use App\Models\time_think;
use App\Models\PublishPost;
use App\Models\settingsApi;
use Google_Service_YouTube;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\PostService;
use Thujohn\Twitter\tmhOAuth;
use Illuminate\Console\Command;
use Google_Service_YouTube_Video;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Abraham\TwitterOAuth\TwitterOAuth;
use Google_Service_YouTube_VideoStatus;
use Google_Service_YouTube_VideoSnippet;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Exceptions\FacebookResponseException;

class PostStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'post:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'publish pending posts';

    /**
     * Execute the console command.
     */

    protected $postStore;

    public function __construct(PostService $post)
    {
        parent::__construct();
        $this->postStore = $post;
    }

    public function handle()
    {
        $time = $this->postStore->userTime();
        $userTimeNow = $time['userTimeNow'];

        $postStatus = DB::table("publish_posts")->where('status','pending')->get();
        
        $funRes = '';
        $results = [];

        foreach($postStatus as $post){
            $dateNow = Carbon::parse($userTimeNow);
            $datePost = Carbon::parse($post->scheduledTime);

            if($datePost->lte($dateNow)){
                echo 'post < now' . '<br> <br>';

                switch($post->account_type) {
                    case('facebook'):
                        $funRes = $this->facePublish($post->pageName, $post->tokenApp, $post->postData, $post->link, $post->image);
                        break;
         
                    case('instagram'):
                        $funRes = $this->instaPublish($post->tokenApp, $post->postData, $post->image);
                        break;
                    
                    case('twitter'):
                        // $funRes = $this->twitterPublish($post->tokenApp, $post->token_secret, $post->postData);
                        $funRes = $this->twitterPublish($post);
                        break;
         
                    case('youtube'):
                        $funRes = $this->youtubePublish($post);
                        break;

                    default:
                        $msg = 'Something went wrong.';
                }

                $results[] = ['funRes' => $funRes, 'postData' => $post];
            }
            else{
                echo 'post > now' . '<br> <br>';
            }
        }   

        $returnData = $this->returnedRes($results);

        $this->info('Success');
    }

    public function returnedRes($funRes)
    {
        // dd($funRes);
        $messages = [];

        foreach($funRes as $res)
        {
            if($res['funRes'] === "postCreated")
            {
                $res['postData']->update([
                    'status' => 'published'
                ]);
                // echo $res['funRes'] . '<br>';
                $msg = '- '.$res['postData']['account_type'].' : The post created successfully.';
            }
            elseif($res['funRes'] === "postFailed")
            {
                $msg = '- '.$res['postData']['account_type'].' : There exist an error.';
            }
            else{
                $msg = '- '.$res['postData']['account_type'].' : '.$res['funRes'];
            }
            $messages[] = $msg;
        }

        if(empty($funRes)){
            $messages[] = "All the posts don't have pending status for past date";
        }

        return $messages;
    }

    public function facePublish($pageName, $token, $postData, $link, $image)
    {
        $pageId = null;
        $pageToken = null;

        $response = Http::get("https://graph.facebook.com/v12.0/me/accounts?access_token={$token}");
        $pages = $response->json()['data'];

        $desiredPage = null;

        foreach ($pages as $page) {
            if ($page['name'] === $pageName) 
            {
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
        
        $fb->setDefaultAccessToken($token);
        
        $permissions = ['pages_manage_posts','pages_manage_ads','pages_manage_cta','pages_manage_metadata'];

        try {

            $url = "https://graph.facebook.com/v12.0/{$pageId}/feed";

            if ($image != null) 
            {
                $filename = Str::replace('postImages\\', '', $image);

                $response = Http::attach(
                    'source',
                    file_get_contents($image),
                    $filename
                )->post("https://graph.facebook.com/v12.0/{$pageId}/photos", [
                    'caption' => $postData,
                    'access_token' => $pageToken,
                ]);

                $imageData = $response->json();
            }
            else {
                $response = Http::post($url, [
                    'message' => $postData,
                    'link' => $link,
                    'access_token' => $pageToken,
                ]);
            }
            
            $responseData = $response->json();

            return true;

        } catch(FacebookResponseException $e) {
            return 'Graph returned an error: ' . $e->getMessage();
        } catch(FacebookSDKException $e) {
            return 'Facebook SDK returned an error: ' . $e->getMessage();
        }

    }

    public function instaPublish($token, $postData, $image)
    {
        // 17841458134934475 -> id evolve
        // 17841453423356345/media?image_url=https://i.ibb.co/j5jStSm/photo2.png
        // 17841453423356345/media_publish?creation_id=17981660207374630
        $accessToken = 'EAAS9OZAZBDis4BO75weG1EZBlq2t0D8NaZAtjSFU8BZBXvZCUV5AeOIjegpZA3SHIyAAyRlNdcCxNbCaVAJ2KPOGsxm9VIeArZCxe30AFvwocBAM5PsvkVBtV5iVPZBH6uf31GCq0qMZBjmrolZByr6wyBKEfXTFasNtkiGQHrmkAMaUYsBWo18KZACXaWZCJBZBqyNyyk6E3J5UVUZAPRXSqlZCVwZDZD'; // Replace with your actual access token
        $pageId = '17841453423356345';
        $imageUrl = 'https://i.ibb.co/j5jStSm/photo2.png';
        $caption = $postData;

        try {

            if ($image != null) {
                $filename = Str::replace('postImages\\', '', $image);
                $mediaResponse = Http::post("https://graph.facebook.com/v17.0/{$pageId}/media", [
                    'image_url' => $imageUrl,
                    'caption' => $caption,
                    'access_token' => $accessToken,
                ]);
                
                if ($mediaResponse->successful()) {
                    $mediaData = $mediaResponse->json();
                    $mediaId = $mediaData['id'];
                   
                    $publishResponse = Http::post("https://graph.facebook.com/v17.0/{$pageId}/media_publish", [
                        'creation_id' => $mediaId,
                        'access_token' => $accessToken,
                    ]);

                    if ($publishResponse->successful()) {
                        return true;
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
 
    public function twitterPublish($post)
    {   
        $account_id = $post->account_id;

        $twitterSettings = settingsApi::where('appType', 'twitter')->first(); 
        $consumer_key = $twitterSettings['appID'];
        $consumer_secret = $twitterSettings['appSecret'];
        $mediaIds = []; $imgPaths = ''; $videoPath ='';
    
        $text = $post->content;

        $accounts = Api::where('account_type', 'twitter')
            ->where('account_id', $account_id)
            ->where('creator_id', $post->creator_id)
            ->get();

        $postData = DB::table("publish_posts")->where('id', $post->id)->where('account_id', $account_id)->with(['postImages', 'postVideos'])->get();
        foreach($postData as $post){
            $imgPaths = $post->postImages;
            $videoPath = $post->postVideos;
        }
        
        foreach ($accounts as $account) {
            $twitterToken = $account->token;
            $twitterTokenSecret = $account->token_secret;
        }
            
        $connection = new TwitterOAuth($consumer_key, $consumer_secret, $twitterToken, $twitterTokenSecret);
        $connected = $connection->get("account/verify_credentials");

        if ($imgPaths->isEmpty() && $videoPath->isEmpty()) {
            $result = $connection->post('tweets', ['text' => $text], true);
        }
        if ($imgPaths->isNotEmpty())
        {
            foreach ($imgPaths as $img) {
                $imgPath = storage_path($img->image);
                $imgPath = Str::replace('storage\/storage','storage\app/public',$imgPath);
                if (file_exists($imgPath)) {
                    $connection->setApiVersion(1.1);
                    $connection->setTimeouts(60, 10);
                    $media = $connection->upload('media/upload', ['media' => $imgPath]);
                    $mediaIds[] = $media->media_id_string;
                } else {
                    return 'File does not exist';
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
        if ($videoPath->isNotEmpty()) {
            try{
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
                    dd('Error initializing video upload: ' . $connection->response['response']);
                }
            }
            catch(Exception $e){
                dd($e->getMessage());
            }
        }

        if ($connection->getLastHttpCode() == 403) {
            if (isset($result->detail) && $result->detail === "You are not allowed to create a Tweet with duplicate content.") {
                // Handle duplicate content error, e.g., display an error message to the user.
                return 'Duplicate tweet: You have already posted this content.';
            } else {
                // Handle other 403 Forbidden errors
                return 'Forbidden: ' . $result->detail;
            }
        } elseif ($connection->getLastHttpCode() == 201) {
            return 'postCreated'; // Success
        } else {
            return 'postFailed'; // Handle other cases as needed
        }

        
    }
}
