<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use Google_Client;
use App\Models\Api;
use App\Models\User;
use Facebook\Facebook;
use GuzzleHttp\Client;
use App\Models\Instagram;
use App\Models\PostImages;
use App\Models\PostVideos;
use App\Models\time_think;
use App\Models\publishPost;
use App\Models\settingsApi;
use Google_Service_YouTube;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Madcoda\Youtube\Youtube;
use Thujohn\Twitter\Twitter;
use Google_Service_Exception;
use Thujohn\Twitter\tmhOAuth;
use App\Models\youtube_category;
use Google_Service_YouTube_Video;
use Google\Service\Blogger\PostList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Intervention\Image\Facades\Image;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Support\Facades\Schema;
use Google_Service_YouTube_VideoStatus;
use Illuminate\Support\Facades\Storage;
use Google_Service_YouTube_VideoSnippet;
use Facebook\Exceptions\FacebookSDKException;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Facebook\Exceptions\FacebookResponseException;
use App\Services\PostService;

class PostController extends Controller
{
    protected $postStore;

    public function __construct(PostService $post)
    {
        $this->postStore = $post;
    }

    public function index()
    {
        $allPosts = PublishPost::where('creator_id', Auth::user()->id)->with(['postImages', 'postVideos'])->get();
        $allApps = settingsApi::all();
        // $groupedPosts = $allPosts->groupBy('scheduledTime');

        return view('AdminSocialMedia.posts',compact('allPosts','allApps'));
    }

    public function create()
    {
        $userApps = Api::where('creator_id', Auth::user()->id)->distinct()->pluck('account_type'); // App of user regesterd in
        $userAccounts = Api::all()->where('creator_id', Auth::user()->id);
        $channels = Api::all()->where('account_type', 'youtube')->where('creator_id', Auth::user()->id);
        $timeThink = time_think::where('creator_id', Auth::user()->id)->first();
        $youtubeCategories = youtube_category::all();
        return view('main.posts.create',compact('userApps','userAccounts','channels','timeThink','youtubeCategories'));
    }

    // public function store(Request $request)
    // {
    //     $messages = $this->postStore->store($request);
    //     return redirect()->back()->with('postStatusForPublishing', $messages);
    // }

    public function store(Request $request) 
    { 
        $validator = $request->validate([
            'postData' => 'max:5000',
            // 'video' => 'mimetypes:video/mov,video/mp4,video/mpg,video/mpeg,video/avi,video/webm',
            // 'images' => 'mimes:jpeg,jpg,png',

            'video' => 'mimetypes:video/quicktime,video/mp4,video/mpeg,video/avi,video/webm',
            'images' => 'required|array',
            'images.*' => 'required|file|image|mimes:jpeg,jpg,png',
            
            'accounts_id' => 'required'
        ]);

        $validationRules = [
            'postData' => 'required',
        ];
        if ($request->has('images') || $request->has('video')) {
            unset($validationRules['postData']); // If there's an image or video, text is not required
        }

        $accountsID = $request->accounts_id;
        $accountsType = [];
        $accounts = '';
        
        $accountsData = [];

        foreach($accountsID as $id){
            $accounts = Api::where('account_id',$id)->where('creator_id', Auth::user()->id)->get();
            foreach($accounts as $account){
                $account_type = $account->account_type;
                if($account_type == 'youtube'){
                    $validationRules['videoTitle'] = 'required';
                    $validationRules['video'] = 'required';
                }

                if($account_type == 'instagram'){
                    // $validationRules['file'] = 'required|mimetypes:video/mp4, image/png';
                    $validationRules['images'] = 'required';
                    $validationRules['video'] = 'required';
                    if($request->has('images')){
                        unset($validationRules['video']);
                    }
                    if($request->has('video')){
                        unset($validationRules['images']);
                    }
                }

                $accountData = [
                    'creator_id'=> Auth::user()->id,
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

        $request->validate($validationRules);

        $imgUpload = []; $imgLocation = []; $filename = '';
        $publishPosts = [];

        if ($request->hasFile('images')) 
        {
            $images = $request->file('images');        
            foreach ($images as $image) {
                $filename = time() . '_' . $image->getClientOriginalName(); // Generate a unique filename
                $image->storeAs('public/uploadImages', $filename); // Store the file with the unique filename
                $localFilePath = storage_path('uploadImages/' . $filename); // Get the local file path (fullPath)
                $storageImage = Storage::url('uploadImages/'. $filename); //storage/uploadImages/img_name
                $imgUpload[] = $localFilePath;
                $imgLocation[] = $storageImage;
            }
        }

        $youtubeVideoPath='';$twitterVideoPath='';$storageVideo='';
        if ($request->hasfile('video')) 
        {
            $video = $request->file('video');
            $filename = $video->getClientOriginalName();
            $storagePath = 'uploadVideos';
            if (!Storage::exists($storagePath)) {
                Storage::makeDirectory($storagePath);
            }

            $video->storeAs($storagePath, $filename);
            $OriginalVideo = $storagePath . '/' . $filename;

            $newVideo = FFMpeg::fromDisk('local')->open($OriginalVideo)->addFilter(function ($filters) {
                $filters->resize(new \FFMpeg\Coordinate\Dimension(2000, 2000));
            });

            $commpressedVideo = $storagePath . '/' . 'compressed_' . $filename;
            $youtubeVideoPath = $commpressedVideo; // for youtube
            $twitterVideoPath = storage_path('app/'. $commpressedVideo); // Get the local file path // twitter
            $storageVideo = Storage::url('app/'. $commpressedVideo);

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
            // $publishPosts[] = $this->publishPost($request, $imgUpload, $youtubeVideoPath, $twitterVideoPath);
            $publishPosts[] = $this->postStore->publishPost($request, $imgUpload, $youtubeVideoPath, $twitterVideoPath);
        }

        $successfulApps = []; // apps that return 'postCreated' and not error
        $messages = [];
        
        if(!empty($publishPosts)){
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
        }

        $data = [];
        $allAccountsData = [];
        foreach($accountsData as $account){
            $account['status'] = $status;
            $account['content'] = $request->postData ?? '';
            $account['link'] = $request->link;
            $account['scheduledTime'] = $postTime;

            $allAccountsData[] = $account;
        }

        $allServices = settingsApi::all();
        $services = [];
        foreach($allServices as $service){
            $services[] = $service['appType'];
        }
        $selectedApps = array_intersect($accountsType, $services);
        

        foreach ($selectedApps as $appType) {
            if (in_array($appType, $successfulApps) || $status == 'pending') // if appType in successefullApp means that post created and not failed
            {
                foreach($allAccountsData as $account){
                    switch ($appType) {
                        case 'youtube':
                            // $account['thumbnail'] = $storageVideo;
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

        // dd($data);

        if (!empty($data)) {
            foreach ($data as $attributes) {
                
                $post = new publishPost(); // Create a new instance of publishPost model
                $post->fill($attributes); // Set the attributes for the model
                $post->save(); // Save the model to the database
        
                if (is_array($imgLocation) && !empty($imgLocation)) {
                    foreach ($imgLocation as $img) {
                        PostImages::create([
                            'post_id' => $post->id,
                            'creator_id' => Auth::user()->id,
                            'image' => $img
                        ]);
                    }
                }

                if ($storageVideo) {
                    PostVideos::create([
                        'post_id' => $post->id,
                        'creator_id' => Auth::user()->id,
                        'video' => $storageVideo
                    ]);
                }
            }
        }

        return redirect()->back()->with('postStatusForPublishing', $messages);
    }


    public function edit(Request $request,$id)
    {
        $post = publishPost::find($id);
        $images = publishPost::find($id)->postImages()->get();
        $videos = publishPost::find($id)->postVideos()->get();
        $channels = Api::all()->where('account_type', 'youtube')->where('creator_id', Auth::user()->id);
        $youtubeCategories = youtube_category::all();

        if($post){
            return view('main.posts.edit',compact('post','images','videos','youtubeCategories'));
        }
    }

    public function update(Request $request,$id)
    {
        $post = publishPost::find($id);

        $validator = $request->validate([
            'postData' => 'max:5000',
            'video' => 'mimetypes:video/mov,video/mp4,video/mpg,video/mpeg,video/avi,video/webm',
            'images' => 'mimetypes: image/png,image/jpg,image/jpeg',
        ]);
        $validationRules = [];
        // dd($request);

        if($request->postData == '')
        {
            if(!($request->has('images')) && !($request->has('video')) && !($request->oldImages)){
                $validationRules['postData'] = 'required';
            }
            else{
                unset($validationRules['postData']);
            }
        }
        $request->validate($validationRules);


        $diff_time = time_think::where('creator_id', Auth::user()->id)->first()->time;
        $now = Carbon::now()->addHours($diff_time)->format('Y-m-d H:i');
        $oldTime = $post->scheduledTime;

        if($request->scheduledTime){
            $scheduledTime = Carbon::parse($request->scheduledTime)->format('Y-m-d H:i');
            if ($scheduledTime > $now){
                $post->status = 'pending';
                $post->scheduledTime =  Carbon::parse($request->scheduledTime)->format('Y-m-d H:i');
            }
            else{
                return dd('The time must be after to now '. $now );
            }
        }

        $post->update([
            'content' => $request->postData,
            'link' => $request->link,
        ]);

        $post['scheduledTime'] = $post->scheduledTime;

        if($request->accoutType == 'youtube'){
            $post['post_title'] = $request->videoTitle;
            $post['youtube_privacy'] = $request->youtubePrivacy;
            $post['youtube_tags'] = $request->youtubeTags;
            $post['youtube_category'] = $request->youtubeCategory;
        }

        $imagesID = [];$videosID = [];

        if ($request->oldImages) {
            $allPostImages = PostImages::where('post_id', $post->id)->get();

            if (!empty($allPostImages)) {
                foreach ($allPostImages as $postImage) {
                    $imagesID[] = $postImage->id;
                }
            }

            $rowsId = array_intersect($request->oldImages, $imagesID);

            $imagesToDelete = PostImages::where('post_id', $post->id)
                ->whereNotIn('id', $rowsId)
                ->get();
            $imagesToDelete->each->delete();
        }

        if ($request->oldVideos) {
            $allPostVideos = PostVideos::where('post_id', $post->id)->get();

            if (!empty($allPostVideos)) {
                foreach ($allPostVideos as $postVideo) {
                    $videosID[] = $postVideo->id;
                }
            }

            $rowsId = array_intersect($request->oldVideos, $videosID);

            $videosToDelete = PostVideos::where('post_id', $post->id)
                ->whereNotIn('id', $rowsId)
                ->get();
            $videosToDelete->each->delete();
        }

        if($request->file('images') || $request->file('video')){

            $imgUpload = []; $imgLocation = []; $filename = '';
            if($request->file('images')){
                $images = $request->file('images');
                foreach ($images as $image) {
                    $filename = time() . '_' . $image->getClientOriginalName();
                    $image->storeAs('public/uploadImages', $filename); // Store the file with the unique filename
                    $localFilePath = storage_path('uploadImages/' . $filename); // Get the local file path (fullPath)
                    $storageImage = Storage::url('uploadImages/'. $filename); //storage/uploadImages/img_name
                    $imgUpload[] = $localFilePath;
                    $imgLocation[] = $storageImage;
                }

                if (is_array($imgLocation) && !empty($imgLocation)) {
                    foreach ($imgLocation as $img) {
                        $postImages = PostImages::where('post_id',$post->id)->get();
                        PostImages::create([
                            'post_id' => $post->id,
                            'creator_id' => Auth::user()->id,
                            'image' => $img
                        ]);
                    }
                }
            }

            if ($request->hasfile('video'))
            {
                $video = $request->file('video');
                $filename = $video->getClientOriginalName();
                $storagePath = 'uploadVideos';
                if (!Storage::exists($storagePath)) {
                    Storage::makeDirectory($storagePath);
                }

                $video->storeAs($storagePath, $filename);
                $OriginalVideo = $storagePath . '/' . $filename;

                $newVideo = FFMpeg::fromDisk('local')->open($OriginalVideo)->addFilter(function ($filters) {
                    $filters->resize(new \FFMpeg\Coordinate\Dimension(2000, 2000));
                });

                $commpressedVideo = $storagePath . '/' . 'compressed_' . $filename;
                $storageVideo = Storage::url('app/'. $commpressedVideo);

                $newVideo->export()
                ->toDisk('local')
                ->inFormat(new \FFMpeg\Format\Video\X264())
                ->save($storagePath . '/' . 'compressed_' . $filename);

                if ($storageVideo) {
                    PostVideos::create([
                        'post_id' => $post->id,
                        'creator_id' => Auth::user()->id,
                        'video' => $storageVideo
                    ]);
                }
            }
        }

        return back()->with('success', 'Post updated successfully');
    }

    public function destroy($postId)
    {
        $post = publishPost::find($postId);

        if ($post && $post->status == 'pending') {
            $post->deletePostWithVideos();
            $post->deletePostWithImages();

            $post->delete();
            return back()->with('success', 'Post deleted successfully');
        }
        else{
            return back()->with('error', "Post can't remove, it's already published");
        }
    }

    // public function removeAccount($userId)
    // {
    //     Api::where('account_id',$userId)->where('creator_id', Auth::user()->id)->delete(); // account_id => unique

    //     return redirect()->route('socialAccounts')->with('success','Account deleted successfully');
    // }


    public function chartJS(Request $request,$userId)
    {
        $startDate = now()->subDays(9);

        if($request)
        {
            $startDate = $request->input('selectedDate');
        }

        $Publish_Post = publishPost::where('status', 'published')->where('creator_id', $userId)
            ->where('scheduledTime', '>=', $startDate)->get();

        return $Publish_Post;
    }

    public function updateInterval(Request $request)
    {
        $data = Api::where('creator_id', Auth::user()->id)->get();

        if ($data->isNotEmpty()) {
            foreach ($data as $record) {
                $record->update([
                    'update_interval' => $request->update_interval,
                ]);
            }

            return redirect()->back()->with('timeUpdated', 'Time saved successfully');
        } else {
            return redirect()->back()->with('timeUpdated', 'No records found for the authenticated user.');
        }
    }

    public function updatePostsTime()
    {
        return view('AdminSocialMedia.updatePostsTime');
    }

    public function schedulePosts()
    {
        return view('AdminSocialMedia.schedulePosts');
    }

    public function updatePostsNow()
    {
        return view('AdminSocialMedia.updatePostsNow');
    }

    public function historyPosts()
    {
        $columns =  Schema::getColumnListing('publish_posts');
        $posts = PublishPost::all()->where('creator_id', Auth::user()->id);
        $postsCount = PublishPost::where('creator_id', Auth::user()->id)->count();

        return view('AdminSocialMedia.historyPosts',compact('columns','posts','postsCount'));
    }

    public function removeSocialPost($id)
    {
        $post = publishPost::findOrFail($id);
        $post->delete();
        return Redirect()->back()->with('postDeleted','post deleted successfully');
    }

    public function repostEdit($id) {
        $post = publishPost::findOrFail($id);
        return view('AdminSocialMedia.repost',compact('post'));
    }

    public function accountPages()
    {
        $faceToken = '';
        $pages = '';

        if(Api::count() != 0){
            if($appSetting = Api::where('account_type', 'facebook')->where('creator_id', Auth::user()->id)->first()){
                $faceToken = $appSetting['token'];

                $response = Http::get("https://graph.facebook.com/v12.0/me/accounts?access_token={$faceToken}");
                $pages = $response->json()['data'];
            }
        }

        return view('AdminSocialMedia.publishPost',compact('pages'));
    }


}
