<?php

namespace App\Http\Controllers;

use App\Models\Api;
use App\Models\User;
use Facebook\Facebook;
use App\Models\settingsApi;
use Illuminate\Support\Str;
use App\Models\social_posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;

class FacebookController extends Controller
{
    public function index(){
        return view('social.facebook');
    }

    // public function socialStatusFacebook(){
    //     if(Api::count() > 0){
    //         Api::truncate();
    //     }
    //     return response()->json(['status' => 'success']);
    // }

    public function callback(){
        try{
            $user = Socialite::driver('facebook')->user();

            $data = User::where('email',$user->email)->first();
            
            $response = Http::get("https://graph.facebook.com/v12.0/me/accounts?access_token={$user->token}");
            $pages = $response->json()['data'];

            dd($pages);

            $profileImage = $user->avatar;
            $ext = pathinfo($profileImage, PATHINFO_EXTENSION);
            $filename = time() . '.' . $ext;
            Storage::put('public/profile_images/' . $filename, file_get_contents($profileImage));
            $storageImage = url('storage/profile_images/' . $filename);

            // Get Facebook user profile
            $facebookApiUrl = 'https://graph.facebook.com/v12.0/' . $user->id;
            $facebookApiResponse = json_decode(file_get_contents($facebookApiUrl), true);
            $facebookLink = isset($facebookApiResponse['link']) ? $facebookApiResponse['link'] : '';

            $userData = [
                'creator_id' => Auth::user()->id,
                'account_type' => 'facebook', // Corrected account type to 'facebook'
                'account_id' => $user->id,
                'account_name' => $user->name,
                'email' => $user->email,
                'account_pic' => $storageImage,
                'account_link' => $facebookLink,
                'token' => $user->token,
                'token_secret' => $user->tokenSecret 
            ];

            $existingApp = Api::where('account_id', $user->id)->where('creator_id', Auth::user()->id)->first();

            if ($existingApp) 
            {
                if($existingApp->account_pic != null){
                    $rm_urlPath = parse_url($existingApp->account_pic, PHP_URL_PATH);
                    $path = Str::replace('/storage/', '', $rm_urlPath);
                    unlink(storage_path('app/public/'. $path));
                }
                
                $existingApp->update($userData);

            } else {
                Api::create($userData);
            }
            
            return redirect()->back();

        } catch (\Exception $e) {
            dd($e->getMessage());
        }
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

    public function create(){
        $showLink = true;
        // return view('social.faceCreate',compact('showLink'));
        return view('AdminSocialMedia.faceCreate',compact('showLink'));
    }

    public function store_facebookApi(Request $request)
    {
        $user = Api::where('creator_id', Auth::user()->id)->where('social_type', 'facebook')->last();

        if($request->page != null){
            $user->update([
                'page_name' => $request->page,
            ]);
        }
        return $this->facebookApi();
    }

    public function facebookApi()
    {
        $apiCount = Api::where('creator_id', Auth::user()->id)->count();
        $allData = Api::where('creator_id', Auth::user()->id)->where('social_type', 'facebook')->last();
        $social_posts_count = social_posts::count();
        $all_social_posts = social_posts::all();

        $accessToken = null;
        $pageName = null;

        foreach ($allData as $item) {
            if (!is_null($item->face_name)) {
                $pageName = $item->face_name;
                $accessToken = $item->token;
            }
        }

        if($apiCount == 0 ||  $pageName == null){
            return view('social.facebook',compact('pageName'));
        }
        
        // Retrieve pages
        $response = Http::get("https://graph.facebook.com/v12.0/me/accounts?access_token={$accessToken}");
        $pages = $response->json()['data'];

        $desiredPage = null;

        foreach ($pages as $page) {
            if ($page['name'] === $pageName) {
                $desiredPage = $page; // it will take array of this page only
                break;
            }
        }

        $dataRes = null;
        $pageData = null;

        // Check if the desired page was found
        if ($desiredPage) {
            $pageAccessToken = $desiredPage['access_token'];
            $pageId = $desiredPage['id'];

            // to get posts of page
            $urlPosts = "https://graph.facebook.com/{$pageId}/posts?fields=permalink_url,full_picture,attachments,created_time&access_token={$pageAccessToken}";
            // $urlPostsLimit = "https://graph.facebook.com/{$pageId}/posts?fields=permalink_url,full_picture,attachments,created_time&access_token={$pageAccessToken}&limit=3";
            
            $data = $this->getData($pageId,$pageAccessToken,$urlPosts);

            $dataRes = $data['data'];
            // dd($dataRes);
            // to get information of page it self
            $urlPage = "https://graph.facebook.com/{$pageId}?fields=id,name,link,picture,posts{full_picture,created_time}&access_token={$pageAccessToken}";
            $pageData = $this->getData($pageId,$pageAccessToken,$urlPage);
            
            if( $social_posts_count == 0)
            {
                foreach ($dataRes as $index => $post){
                    if (isset($post['full_picture']) || isset($post['attachments']['data'][0]['description'])){
                        $post_id = $post['id'];
                        $post_link = $post['permalink_url'];
                        $post_date = $post['created_time'];
                        $post_img = isset($post['full_picture']) ? $post['full_picture'] : null;
                        $post_caption = isset($post['attachments']['data'][0]['description']) ? $post['attachments']['data'][0]['description'] : null;

                        $social_posts = social_posts::create([
                            'type' => 'facebook',
                            'page_id' => $pageData['id'],
                            'page_name' => $pageData['name'],
                            'page_link' => $pageData['link'],
                            'page_img' => $pageData['picture']['data']['url'],
                            'post_id' => $post_id,
                            'post_img' => $post_img,
                            'post_link' => $post_link,
                            'post_caption' => $post_caption,
                            'post_date' => $post_date,
                        ]);
                    }
                }
            }
            else{
                $existingPage = social_posts::where('type', 'facebook')
                ->where('page_id', $pageData['id'])
                ->get();
                
                if($existingPage->isEmpty()){ // no data for this new page on database --> remove another page
                    social_posts::where('type', 'facebook')->delete();
                    
                    foreach ($dataRes as $index => $post){
                        if (isset($post['full_picture']) || isset($post['attachments']['data'][0]['description'])){
                            $post_id = $post['id'];
                            $post_link = $post['permalink_url'];
                            $post_date = $post['created_time'];
                            $post_img = isset($post['full_picture']) ? $post['full_picture'] : null;
                            $post_caption = isset($post['attachments']['data'][0]['description']) ? $post['attachments']['data'][0]['description'] : null;

                            $existingPost = social_posts::where('post_id', $post_id)->first();

                            if (!$existingPost) {
                                $social_posts = social_posts::create([
                                    'type' => 'facebook',
                                    'page_id' => $pageData['id'],
                                    'page_name' => $pageData['name'],
                                    'page_link' => $pageData['link'],
                                    'page_img' => $pageData['picture']['data']['url'],
                                    'post_id' => $post_id,
                                    'post_img' => $post_img,
                                    'post_link' => $post_link,
                                    'post_caption' => $post_caption,
                                    'post_date' => $post_date,
                                ]);
                            }
                            else
                            {
                                $existingPost->update([
                                    'type' => 'facebook',
                                    'page_id' => $pageData['id'],
                                    'page_name' => $pageData['name'],
                                    'page_link' => $pageData['link'],
                                    'page_img' => $pageData['picture']['data']['url'],
                                    'post_id' => $post_id,
                                    'post_img' => $post_img,
                                    'post_link' => $post_link,
                                    'post_caption' => $post_caption,
                                    'post_date' => $post_date,
                                ]);
                            }
                        }
                    }
                }
                else{
                    // dd('not empty');

                    foreach ($dataRes as $index => $post){
                        if (isset($post['full_picture']) || isset($post['attachments']['data'][0]['description'])){
                            $post_id = $post['id'];
                            $post_link = $post['permalink_url'];
                            $post_date = $post['created_time'];
                            $post_img = isset($post['full_picture']) ? $post['full_picture'] : null;
                            $post_caption = isset($post['attachments']['data'][0]['description']) ? $post['attachments']['data'][0]['description'] : null;

                            $existingPost = social_posts::where('post_id', $post_id)->first();

                            if (!$existingPost) {
                                $social_posts = social_posts::create([
                                    'type' => 'facebook',
                                    'page_id' => $pageData['id'],
                                    'page_name' => $pageData['name'],
                                    'page_link' => $pageData['link'],
                                    'page_img' => $pageData['picture']['data']['url'],
                                    'post_id' => $post_id,
                                    'post_img' => $post_img,
                                    'post_link' => $post_link,
                                    'post_caption' => $post_caption,
                                    'post_date' => $post_date,
                                ]);
                            }
                            else
                            {
                                $existingPost->update([
                                    'type' => 'facebook',
                                    'page_id' => $pageData['id'],
                                    'page_name' => $pageData['name'],
                                    'page_link' => $pageData['link'],
                                    'page_img' => $pageData['picture']['data']['url'],
                                    'post_id' => $post_id,
                                    'post_img' => $post_img,
                                    'post_link' => $post_link,
                                    'post_caption' => $post_caption,
                                    'post_date' => $post_date,
                                ]);
                            }
                        }
                    }

                    // Step 1: Get all post_ids from the $dataRes array
                    $postIdsInDataRes = array_column($dataRes, 'id');
                    // Step 2: Find the records in the database that need to be deleted
                    $postsToDelete = social_posts::where('type','facebook')->whereNotIn('post_id', $postIdsInDataRes)->get();
                    // Step 3: Delete the found records from the database
                    $postsToDelete->each->delete();
                }
                
            }

            return redirect()->route('facebookPosts', ['data' => $all_social_posts]);

        } else {
            // The desired page was not found
            // return view('post.index', compact('dataRes','pageData','pageName'));
            // echo "Page not found";
            return redirect()->route('facebookPosts', ['data' => $all_social_posts]);
        }
    }

    
    public function facebookPosts()
    {
        $social_posts_count = social_posts::count();

        $data = social_posts::orderBy('post_date', 'desc')->get();
        // dd($data);
        if($social_posts_count == 0){
            return view('social.facebook');
        }

        return view('social.facebook',compact('data'));
    }

    //calling function of facebookapi every time which selected to get new posted
    public function fetchDataFromFacebook()
    {
        $this->facebookApi();
        
        return response()->json(['message' => 'Data fetched successfully']);
    }


    public function getPages(Request $request)
    {
        try {
            // Access data sent in the request
            $responseData = [];
            $requestData = json_decode($request->getContent(), true);

            $userID = $requestData['userID'];
            $access_token = $requestData['access_token'];
            $appType = $requestData['appType'];
            $status = $requestData['status'];

            $userData1 = [
                'userID' => $userID,
                'access_token' => $access_token,
                'appType' => $appType,
                'status' => $status
            ];

            $urlUser = "https://graph.facebook.com/v12.0/me?fields=id,name,email,picture&access_token={$access_token}";

            $userData2 = $this->curlFun($urlUser);

            // _______________________________________________

            $urlPages = "https://graph.facebook.com/{$userID}/accounts?fields=name,access_token&access_token={$access_token}";
            
            $response = Http::get("https://graph.facebook.com/{$userID}/accounts", [
                'fields' => 'name,access_token,picture,perms',
                'access_token' => $access_token,
            ]);
            
            if ($response->failed()) {
                // Handle the error here, log it, and return an appropriate response
                return response()->json(['error' => 'Failed to fetch Facebook pages data'], 500);
            }

            $pagesData = $response->json();

            if (isset($pagesData['error'])) {
                // Handle the error here, log it, and return an appropriate response
                return response()->json(['error' => $pagesData['error']], 500);
            }

            $pages = [];
            foreach ($pagesData['data'] as $page) {
                $pageInfo = [
                    'id' => $page['id'],
                    'access_token' => $page['access_token'],
                    'image' => $page['picture']['data']['url'],
                    'type' => $page['perms'][0],
                ];
                $pages[] = $pageInfo;
            }


            // $pagesData = $this->curlFun($urlPages);

            // // Check if there are errors in the Facebook Graph API response
            // if (isset($pageData['error'])) {
            //     // Handle the error here, log it, and return an appropriate response
            //     return response()->json(['error' => $pageData['error']], 500);
            // }

            $responseData['userData1'] = $userData1;
            $responseData['userData2'] = $userData2;
            $responseData['pagesData'] = $pagesData;

            // Return the combined data as JSON
            return response()->json($responseData);
            
        } catch (\Exception $e) {
            // Handle the exception, log it, and return an error response
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
        
    }

    public function getPages2(Request $request)
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
            $urlPages = "https://graph.facebook.com/{$userID}/accounts?fields=name,access_token,picture,perms&access_token={$access_token}";
            $response = Http::get($urlPages);

            if ($response->failed()) {
                return response()->json(['error' => 'Failed to fetch Facebook pages data'], 500);
            }

            $pagesData = $response->json();

            if (isset($pagesData['error'])) {
                return response()->json(['error' => $pagesData['error']], 500);
            }

            $pages = [];
            foreach ($pagesData['data'] as $page) {
                $pageInfo = [
                    'id' => $page['id'],
                    'access_token' => $page['access_token'],
                    'image' => $page['picture']['data']['url'],
                    'type' => $page['perms'][0],
                ];
                $pages[] = $pageInfo;
            }

            $responseData['userData'] = $userData;
            $responseData['pagesData'] = $pages;

            return response()->json($responseData);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    // function saveUserData(responseData) {
    //     console.log('getPages', responseData);
        
    //     // Assuming responseData is the JSON structure from the server
    //     var userData = responseData.userData;
    //     var pagesData = responseData.pagesData;
    
    //     // Handle user data
    //     console.log('User ID:', userData.id);
    //     console.log('User Name:', userData.name);
    //     console.log('User Email:', userData.email);
    
    //     // Assuming you have an image element for the user profile picture
    //     var userImageElement = document.getElementById('userProfilePicture');
    //     userImageElement.src = userData.picture.data.url;
    
    //     // Handle pages data
    //     for (var i = 0; i < pagesData.length; i++) {
    //         var page = pagesData[i];
    //         console.log('Page ID:', page.id);
    //         console.log('Page Access Token:', page.access_token);
    //         console.log('Page Image:', page.image);
    //         console.log('Page Type:', page.type);
    
    //         // Assuming you have a container element for displaying pages
    //         var pagesContainer = document.getElementById('pagesContainer');
    
    //         // Create a new element for each page and append it to the container
    //         var pageElement = document.createElement('div');
    //         pageElement.innerHTML = `
    //             <div class="d-flex justify-content-between align-items-center mb-2">
    //                 <div class="position-relative">
    //                     <img src="${page.image}" class="rounded-circle border-primary p-1" alt="Page Image">
    //                     <i class="fab fa-facebook position-absolute rounded-circle text-primary icon"></i>
    //                     ${page.name} <!-- Assuming there is a name property in your pagesData -->
    //                 </div>
    //                 <div><input type="checkbox"></div>
    //             </div>
    //         `;
    //         pagesContainer.appendChild(pageElement);
    //     }
    
    //     // Rest of your code...
    // }
    
    public function store(Request $request) 
    {
        dd($request);
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

    public function getDataApi(Request $request)
    {
        $fb = new Facebook([
            'app_id' => '6649375938488429',
            'app_secret' => '0d4efbf6b02dbf3c8a3e013636133a00',
            'default_graph_version' => 'v12.0',
        ]);
    }
    
}



// use Illuminate\Support\Facades\Http; // Import the Http facade

// // Replace this with your existing code to get user data and access token

// // Make an HTTP GET request to the Facebook Graph API to fetch the pages data
// $response = Http::get("https://graph.facebook.com/{$userID}/accounts", [
//     'fields' => 'name,access_token,picture,perms',
//     'access_token' => $access_token,
// ]);

// // Check if there was an error in the HTTP request
// if ($response->failed()) {
//     // Handle the error here, log it, and return an appropriate response
//     return response()->json(['error' => 'Failed to fetch Facebook pages data'], 500);
// }

// $pagesData = $response->json();

// // Check if there are errors in the Facebook Graph API response
// if (isset($pagesData['error'])) {
//     // Handle the error here, log it, and return an appropriate response
//     return response()->json(['error' => $pagesData['error']], 500);
// }

// // Process the pages data to extract the required information
// $pages = [];
// foreach ($pagesData['data'] as $page) {
//     $pageInfo = [
//         'id' => $page['id'],
//         'access_token' => $page['access_token'],
//         'image' => $page['picture']['data']['url'],
//         'type' => $page['perms'][0], // Assuming 'perms' contains the type information
//     ];
//     $pages[] = $pageInfo;
// }

// $responseData['userData1'] = $userData1;
// $responseData['userData2'] = $userData2;
// $responseData['pagesData'] = $pages;

// // Return the combined data as JSON
// return response()->json($responseData);
