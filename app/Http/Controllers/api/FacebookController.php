<?php

namespace App\Http\Controllers\api;

use App\Models\Api;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;

class FacebookController extends Controller
{
    public function callback(){
        try{
            $user = Socialite::driver('facebook')->user();

            // $data = User::where('email',$user->email)->first();
            $data = User::where('email', $user->email)
            ->with(['apis' => function($q) use($user){
                $q->where('account_id', $user->id);
            }])
            ->first();
                        
            $response = Http::get("https://graph.facebook.com/v12.0/me/accounts?access_token={$user->token}");
            $pages = $response->json()['data'];

            $profileImage = $user->avatar;
            $ext = pathinfo($profileImage, PATHINFO_EXTENSION);
            $filename = time() . '.' . $ext;
            $userFolder = 'user'.Auth::user()->id;
            Storage::put('public/'.$userFolder.'/'.'profile_images/'. $filename, file_get_contents($profileImage));
            $storageImage = url('storage/'.$userFolder.'/'.'profile_images/'. $filename);

            // Get Facebook user profile
            $facebookApiUrl = 'https://graph.facebook.com/v12.0/' . $user->id;
            $facebookApiResponse = json_decode(file_get_contents($facebookApiUrl), true);
            $facebookLink = isset($facebookApiResponse['link']) ? $facebookApiResponse['link'] : '';

            $userData = [
                'creator_id' => Auth::user()->id,
                'account_type' => 'facebook',
                'account_id' => $user->id,
                'account_name' => $user->name,
                'email' => $user->email,
                'account_pic' => $storageImage,
                'account_link' => $facebookLink,
                'token' => $user->token,
                'token_secret' => $user->tokenSecret 
            ];

            // $existingApp = Api::where('account_id', $user->id)->where('creator_id', Auth::user()->id)->first();
            $existingApp = $data->apis;

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
            
            return response()->json([
                'message' => 'user created successfully',
                'user' => $userData,
                'status' => true
            ],200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => false
            ],401);
        }
    }

    public function getPages(Request $request)
    {
        try {
            $responseData = [];
            $requestData = json_decode($request->getContent(), true);

            $userID = $requestData['userID'];
            $access_token = $requestData['access_token'];

            // Fetch user data
            $urlUser = "https://graph.facebook.com/v12.0/me?fields=id,name,email,picture&access_token={$access_token}";
            $userData = $this->curlFun($urlUser);

            // Fetch pages data
            $urlPages = "https://graph.facebook.com/{$userID}/accounts?fields=name,access_token,picture&access_token={$access_token}";
            $response = Http::get($urlPages);

            $pagesData = $response->json();

            $responseData['userData'] = $userData;
            $responseData['pagesData'] = $pagesData;

            Log::info('Facebook API Response: ' . $response->body());

            return response()->json([
                'message' => 'user created successfully',
                'data' => $responseData,
                'status' => true
            ],200);

        } catch (\Exception $e) {
            Log::error('Facebook API Error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Facebook API Error: ' . $e->getMessage(),
                'error' => 'Failed to fetch Facebook pages data',
                'status' => false
            ], 500);
        }
    }

    public function pagesFacebook()
    {
        $permissions = [ // instagram
            'instagram_basic',
            'instagram_content_publish',
            'instagram_manage_comments',
            'instagram_manage_insights',
            'manage_pages', 
            'pages_show_list',
            'Instagram Public Content Access'
        ];
        
        try {
            $responseData = [];
            // $requestData = json_decode($request->getContent(), true);

            $userID = '2049786572026608';
            $access_token = 'EAAJztuEvLeQBOxd9bNLx9ULYGxtlljtSIEEwZBLimmSMKVbqwlnj3KZCEAkQADKJLwyxAIgyQOT6uIE6E41CaCYEhnZCsOB42LSw4rgN54quCs9Nw8k2fSiQjqyckpgexwHU6ZCpfXHpSkUaFL962DVVWAvYJaioSgAu9ZCIe4uqu7ggHpKl8jJ4sWPRqZAouRqgnZBOhpi0L6iZC0mKMX3wNmugzXwZD';

            $url = "https://graph.facebook.com/v12.0/113695178285974?fields=instagram_business_account,username&access_token={$access_token}"; // page id
            $response1 = Http::get($url);
            $insta1 = $response1->json();
            $url2 = "https://graph.facebook.com/v12.0/2049786572026608?fields=instagram_business_account,username&access_token={$access_token}"; // page id
            $response2 = Http::get($url2);
            $insta2 = $response2->json();

            // Fetch user data
            $urlUser = "https://graph.facebook.com/v12.0/me?fields=id,name,email,picture&access_token={$access_token}";
            $userData = $this->curlFun($urlUser);

            // Fetch pages data
            $urlPages = "https://graph.facebook.com/{$userID}/accounts?fields=name,access_token,picture&access_token={$access_token}";
            $response = Http::get($urlPages);
            $pagesData = $response->json();

            // dd($insta1, $insta2, $userData, $pagesData);

            $responseData['userData'] = $userData;
            $responseData['pagesData'] = $pagesData;

            Log::info('Facebook API Response: ' . $response->body());

            return response()->json([
                'data' => $responseData,
                'status' => true
            ],200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Facebook API Error: ' . $e->getMessage(),
                'message' => 'Failed to fetch Facebook pages data',
                'status' => false
            ], 500);
        }
    }

    public function store(Request $request) 
    {
        try{
            $profileImage = $request->pageImage;
            $ext = pathinfo($profileImage, PATHINFO_EXTENSION);
            $filename = time() . '.' . $ext;
            $userFolder = 'user'.Auth::user()->id;
            Storage::put('public/'.$userFolder.'/'.'profile_images/'. $filename, file_get_contents($profileImage));
            $storageImage = url('storage/'.$userFolder.'/'.'profile_images/'. $filename);

            $userData = [
                'creator_id'=> Auth::user()->id,
                'account_type' => 'facebook',
                'account_id' => $request->pageId,
                'account_name' => $request->pageName,
                'email' => $request->email,
                'account_pic' => $storageImage,
                'account_link' => 'https://www.facebook.com/' . $request->pageId,
                'token' => $request->account_token,
                'token_secret' => $request->page_access_token 
            ];

            $existingApp = Api::where('account_id', $request->pageId)->where('creator_id', Auth::user()->id)->first();

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

            return response()->json([
                'user' => $userData,
                'status' => true
            ],200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to save Facebook page',
                'error' => $e->getMessage(),
                'status' => false
            ],500);

        }
    }

    public function curlFun($url)
    {
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

        return $data;
    }
    
}
