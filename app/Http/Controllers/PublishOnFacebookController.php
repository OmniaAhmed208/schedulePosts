<?php

namespace App\Http\Controllers;
session_start();
use Facebook\Facebook;
use Illuminate\Http\Request;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Exceptions\FacebookResponseException;

class PublishOnFacebookController extends Controller
{

    public function store(Request $request){
        // dd($request);
        // $token = 'IGQVJXVko5UjVCUDg0YVlyZAlpiMExydVRsY1l0dzNBWEhBVk9nMFFTNWRzaXJ1bklUNW5zeTJneTJBU0pCR2tabWxlc2szejZAoaS1KNXdnSTNocWFOSnp2Q1U1YkdhTUw4UjhHSTh2TG5UbVdDZAFNfRwZDZDv';
        $token = 'EAAS9OZAZBDis4BO8jsAG0li87P3UX7B9IScZCKqgFZA7WdoACZBAK4E87ZBz5loydI8TRmhRkCbdURM9E7kGN75P9YGZAPmSNcOTkUZBXMS5AN9Xzry5hHLTroPyjBsqmzUCeVAZBoZB8kio8Q2K0BRZB3QJBsOQ7KZAvpzemNfui8rNYZAWlkltYoSYZCVber706c7glahCUSYrQ42y2ZBMRQh3XWQBzkZD';
        
        $fb = new Facebook([
            'app_id' => config('services.facebook.client_id'),
            'app_secret' => config('services.facebook.client_secret'),
            'default_graph_version' => 'v12.0', // Use the appropriate version
        ]);
        
        $fb->setDefaultAccessToken($token);
        
        $pageId = '113695178285974';
        // $pageId = '961436048312260';

        $message = 'Hello, this is a test post!'; //$request->storePost
        
        try {
            $response = $fb->post(
                "$pageId/feed",
                ['message' => $message],
                $token
            );
            // $response = Http::get("https://graph.facebook.com/$pageId/feed?$message&access_token={$token}");

            return $response->getGraphNode();
  
        } catch(FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
    }

    public function face_login()
    {
        $fb = new Facebook([
            'app_id' => config('services.facebook.client_id'),
            'app_secret' => config('services.facebook.client_secret'),
            'default_graph_version' => 'v17.0', 
        ]);
        
        $helper = $fb->getRedirectLoginHelper();
        $permissions = ['pages_manage_posts','pages_manage_ads','pages_manage_cta','pages_manage_metadata'];
        $redirectURL = 'http://localhost/e-commerce/Social/schedule-posts/face_callback';
        $login_url = $helper->getLoginUrl($redirectURL,$permissions);
        $login_url .= '&state=' . bin2hex(random_bytes(16));
        return redirect()->away($login_url);
    }

    public function face_callback()
    {
        // dd('callback');
        
        $fb = new Facebook([
            'app_id' => config('services.facebook.client_id'),
            'app_secret' => config('services.facebook.client_secret'),
            'default_graph_version' => 'v17.0', 
        ]);

        $helper = $fb->getRedirectLoginHelper();

        if (isset($_GET['state'])) {
            $helper->getPersistentDataHandler()->set('state', $_GET['state']);
        }

        try
        {
            $accessToken = $helper->getAccessToken();

        } catch(FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        if(!isset($accessToken)){
            if($helper->getError()){
                echo('helper error');
            }
            else{
                echo('no access token');
            }
        }

        $fb->setDefaultAccessToken($accessToken);
        $response = $fb->post("113695178285974/feed", ['message' => 'test post']);

        $postId = $response->getDecodedBody()['id'];

        return $postId;

    }

}
