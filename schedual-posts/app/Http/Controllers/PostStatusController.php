<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Facebook\Facebook;
use App\Models\time_think;
use App\Models\settingsApi;
use Illuminate\Support\Str;
use App\Models\Publish_Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Abraham\TwitterOAuth\TwitterOAuth;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Exceptions\FacebookResponseException;

class PostStatusController extends Controller
{
    public function timeThink(Request $request){
        return view('AdminSocialMedia.timeThink');
    }

    public function timeThinkStore(Request $request)
    {
        $time_table = time_think::where('creator_id', Auth::user()->id)->first();
        if(!$time_table){
            time_think::create([
                'creator_id' => Auth::user()->id,
                'time' => $request->time
            ]);
        }
        else{
            $time_table->update([
                'time' => $request->time
            ]);
        }
    
        return redirect()->back()->with('timeUpdated', 'Time saved successfully'); //AdminSocialMedia.timeThink
    }
    
    
    public function checkPostStatus(){
        $postStatus = Publish_Post::get()->where('status','pending');
        $time_table = time_think::where('creator_id', Auth::user()->id)->first();

        $now = Carbon::now(); 
        $diff_time = $time_table->time;
        $newDateTime = $now->copy()->addHours($diff_time);

        $dateNow = $newDateTime->format('Y-m-d H:i');

        echo $dateNow . '<br> <br>';

        $funRes = '';
        $results = [];

        foreach($postStatus as $post){
            $dateNow = Carbon::parse($dateNow);
            $datePost = Carbon::parse($post->scheduledTime);

            if($datePost->lte($dateNow)){
                echo 'post < now' . '<br> <br>';

                switch($post->type) {
                    case('facebook'):
                        $funRes = $this->facePublish($post->pageName, $post->tokenApp, $post->postData, $post->link, $post->image);
                        break;
         
                    case('instagram'):
                        $funRes = $this->instaPublish($post->tokenApp, $post->postData, $post->image);
                        break;
                    
                    case('twitter'):
                        $funRes = $this->twitterPublish($post->tokenApp, $post->token_secret, $post->postData);
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
        return redirect()->back()->with('postStatusForPublishing', $returnData);

    }

    
    public function returnedRes($funRes)
    {
        // dd($funRes);
        $messages = [];

        foreach($funRes as $res)
        {
            if($res['funRes'] === true)
            {
                $res['postData']->update([
                    'status' => 'published'
                ]);
                // echo $res['funRes'] . '<br>';
                $msg = '- '.$res['postData']['type'].' : The post created successfully.';
            }
            else
            {
                $msg = '- '.$res['postData']['type'].' : There exist an error.';
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
 
    public function twitterPublish($tokenApp, $token_secret, $postData)
    {
        $twitterSettings = settingsApi::where('appType', 'twitter')->first(); 
        $consumer_key = $twitterSettings['appID'];;
        $consumer_secret = $twitterSettings['appSecret'];
    
        $connection = new TwitterOAuth($consumer_key, $consumer_secret, $tokenApp, $token_secret);
        $connection->setApiVersion('2');
    
        $tweet = $postData;
    
        $response = $connection->post('tweets', ['text' => $tweet]);

        if ($connection->getLastHttpCode() === 201) {
            // HTTP status code 201 indicates a successful tweet creation
            return 'true';
        } else {
            return 'false';
        }
    }
}








// switch($post->type) {
//     case('facebook'):
//         $faceFun = $this->facePublish($post->pageName, $post->tokenApp, $post->postData, $post->link, $post->image);
//         if($faceFun === true){
//             $post->update([
//                 'status' => 'published'
//             ]);
//             echo  $faceFun . '<br>';
//         }
//         break;

//     case('instagram'):
//         $instaFun = $this->instaPublish($post->tokenApp, $post->postData, $post->image);
//         if($instaFun === true){
//             $post->update([
//                 'status' => 'published'
//             ]);
//             echo $instaFun . '<br>';
//         }
//         break;
    
//     case('twitter'):
//         $twitterFun = $this->twitterPublish($post->tokenApp, $post->token_secret, $post->postData);
//         // dd($twitterFun);
//         if($twitterFun === true){
//             $post->update([
//                 'status' => 'published'
//             ]);
//             echo $twitterFun . '<br>';
//         }
//         break;

//     default:
//         $msg = 'Something went wrong.';
// }