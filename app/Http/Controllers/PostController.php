<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Api;
use App\Models\PostImages;
use App\Models\PostVideos;
use App\Models\publishPost;
use App\Models\settingsApi;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\PostService;
use App\Models\youtube_category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    protected $postStore;

    public function __construct(PostService $post)
    {
        $this->postStore = $post;
    }

    public function create()
    {
        $userApps = Api::where('creator_id', Auth::user()->id)->distinct()->pluck('account_type'); // App of user regesterd in
        $userAccounts = Api::all()->where('creator_id', Auth::user()->id);
        $channels = Api::all()->where('account_type', 'youtube')->where('creator_id', Auth::user()->id);
        $youtubeCategories = youtube_category::all();
        return view('main.posts.create',compact('userApps','userAccounts','channels','youtubeCategories'));
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'content' => 'max:5000',
            // 'video' => 'mimetypes:video/quicktime,video/mp4,video/mpeg,video/mpg,video/mov,video/avi,video/webm',
            'images.*' => function ($attribute, $value, $fail) {
                $allowedExtensions = ['jpeg', 'jpg', 'png'];
                $extension = pathinfo($value, PATHINFO_EXTENSION);

                if (!in_array($extension, $allowedExtensions)) {
                    $fail("The $attribute field must have a valid image extension (jpeg, jpg, png).");
                }
            },
            'accounts_id' => 'required'
        ]);

        $accountsID = $request->accounts_id;
        $accountsType = [];
        $accounts = '';
        $validationRules = [];
        $accountsData = [];

        $validationRules['content'] = 'required';

        foreach($accountsID as $id){
            $accounts = Api::where('account_id',$id)->where('creator_id', Auth::user()->id)->get();
            foreach($accounts as $account){
                $account_type = $account->account_type;
                if($account_type == 'youtube'){
                    unset($validationRules['content']);
                    $validationRules['videoTitle'] = 'required';
                    $validationRules['video'] = 'required';
                }

                if($account_type == 'instagram'){
                    // $validationRules['file'] = 'required|mimetypes:video/mp4, image/png';
                    $validationRules['images'] = 'required';
                    $validationRules['video'] = 'required';
                    if($request->images){
                        unset($validationRules['content']);
                        unset($validationRules['video']);
                    }
                    if($request->video){
                        unset($validationRules['content']);
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

        if($request->images || $request->video){
            unset($validationRules['content']);
        }
        // $request->validate($validationRules);
        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            if ($request->images)
            {$removeImagesUploaded = $this->postStore->removeImagesUploaded($request->images);}
            if ($request->video)
            {$removeVideoUploaded = $this->postStore->removeVideoUploaded($request->video);}
            
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $imgUpload = []; $publishPosts = [];

        if ($request->images)
        {
            // $images = $request->file('images');
            $imgUpload = $this->postStore->saveImages($request->images);
        }

        $youtubeVideoPath='';$twitterVideoPath='';$storageVideo='';
        if ($request->video)
        {
            $video = $request->video;
            $videoUpload = $this->postStore->saveVideo($video);

            $youtubeVideoPath = $videoUpload['youtubeVideoPath'];
            $twitterVideoPath = $videoUpload['twitterVideoPath'];
            $storageVideo = $videoUpload['storageVideo'];
        }
        
        $time = $this->postStore->userTime();
        $userTimeNow = $time['userTimeNow'];

        if($request->scheduledTime){
            $postTime =  Carbon::parse($request->scheduledTime)->format('Y-m-d H:i');
            $status = 'pending';
        }
        else{
            $postTime = $userTimeNow;
            $status = 'published';
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
            $account['content'] = $request->content ?? '';
            $account['link'] = $request->link;
            $account['scheduledTime'] = $postTime;
            if($request->images || $request->video){
                $account['thumbnail'] = 'has file';
            }
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

                $post = new publishPost();
                $post->fill($attributes);
                $post->save(); 

                if (is_array($imgUpload) && !empty($imgUpload)) {
                    foreach ($imgUpload as $img) {
                        DB::table("post_images")->insert([
                            'post_id' => $post->id,
                            'creator_id' => $request->user()->id,
                            'image' => $img,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }

                if ($storageVideo) {
                    DB::table("post_videos")->insert([
                        'post_id' => $post->id,
                        'creator_id' => $request->user()->id,
                        'video' => $storageVideo,
                        'created_at' => now(),
                        'updated_at' => now()
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
        // dd($request->oldImages);
        
        $post = publishPost::find($id);
        $validator = $request->validate([
            'content' => 'max:5000',
            'images.*' => function ($attribute, $value, $fail) {
                $allowedExtensions = ['jpeg', 'jpg', 'png'];
                $extension = pathinfo($value, PATHINFO_EXTENSION);

                if (!in_array($extension, $allowedExtensions)) {
                    $fail("The $attribute field must have a valid image extension (jpeg, jpg, png).");
                }
            },
            'videoTitle' => $post->account_type === 'youtube' ? 'required|string' : '',
        ]);

        $validationRules =  [];

        if($request->content == '' || $request->content == null)
        {
            if($post->account_type != 'youtube'){
                if(!($request->images) && !($request->video) && (!($request->oldImages) || !($request->oldVideos))){
                    $validationRules['content'] = 'required';
                }
                else{
                    unset($validationRules['content']);
                }
            }
        }
        
        if($post->account_type == 'youtube'){
            if(!($request->oldVideos)){
                $validationRules['video'] = 'required';
            }
        }

        $validator =  Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            if ($request->images)
            {$removeImagesUploaded = $this->postStore->removeImagesUploaded($request->images);}
            if ($request->video)
            {$removeVideoUploaded = $this->postStore->removeVideoUploaded($request->video);}
            
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $time = $this->postStore->userTime();
        $userTimeNow = $time['userTimeNow'];
        $oldTime = $post->scheduledTime;

        if($request->scheduledTime != null){
            $scheduledTime = Carbon::parse($request->scheduledTime)->format('Y-m-d H:i');
            if ($scheduledTime > $userTimeNow){
                $post->status = 'pending';
                $post->scheduledTime =  Carbon::parse($request->scheduledTime)->format('Y-m-d H:i');
            }
            else{
                return redirect()->back()->with('error','The time must be after to now '. $userTimeNow );
            }
        }

        $post->update([
            'content' => $request->content,
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
            $oldImagesArray = is_array($request->oldImages) ? $request->oldImages : json_decode($request->oldImages, true);
            // dd(json_decode($request->oldImages));
            $allPostImages = PostImages::where('post_id', $post->id)->get();
            if (!empty($allPostImages)) {
                foreach ($allPostImages as $postImage) {
                    $imagesID[] = $postImage->id;
                }
            }
            
            $rowsId = array_intersect($oldImagesArray, $imagesID);
            $imagesToDelete = PostImages::where('post_id', $post->id)
            ->whereNotIn('id', $rowsId)
            ->get();
            $imagesWantToDelete = $imagesToDelete;
            $imagesToDelete->each->delete();
            $this->removeImageFromStorage($imagesWantToDelete);
        }
        else{
            $imagesToDelete = PostImages::where('post_id', $post->id)->get();
            if($imagesToDelete){
                $imagesWantToDelete = $imagesToDelete;
                $imagesToDelete->each->delete();
                $this->removeImageFromStorage($imagesWantToDelete);
            }
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
            $videoWantToDelete = $videosToDelete;
            $videosToDelete->each->delete();
            $this->removeVideoFromStorage($videoWantToDelete);
        }
        else{
            $videosToDelete = PostVideos::where('post_id', $post->id)->get();
            if($videosToDelete){
                $videoWantToDelete = $videosToDelete;
                $videosToDelete->each->delete();
                $this->removeVideoFromStorage($videoWantToDelete);
            }
        }

        if($request->images || $request->video){
            $imgUpload = []; 
            if($request->images)
            {
                $imgUpload = $this->postStore->saveImages($request->images);
                if (is_array($imgUpload) && !empty($imgUpload)) {
                    foreach ($imgUpload as $img) {
                        PostImages::create([
                            'post_id' => $post->id,
                            'creator_id' => Auth::user()->id,
                            'image' => $img
                        ]);
                    }
                }
            }

            if ($request->video)
            {
                $videoUpload = $this->postStore->saveVideo($request->video);
                $storageVideo = $videoUpload['storageVideo'];

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
        
        if ($post && $post->status == 'pending') 
        {
            $images = publishPost::find($postId)->postImages()->get();
            $videos = publishPost::find($postId)->postVideos()->get();
            
            $post->deletePostWithVideos();
            $post->deletePostWithImages();
            $post->delete();
            
            if($images){
                $this->removeImageFromStorage($images);
            }
            if($videos){
                $this->removeVideoFromStorage($videos);
            }
            return back()->with('success', 'Post deleted successfully');
        }
        else{
            return back()->with('error', "Post can't remove, it's already published");
        }
    }

    public function removeImageFromStorage($imagesWantToDelete){
        // remove image from storage folder if not app use it again
        $allImages=[];
        $allImagesTable = PostImages::all();
        if (!empty($allImagesTable)) {
            foreach ($allImagesTable as $img) {
                $allImages[] = $img->image;
            }

            foreach ($imagesWantToDelete as $image) {
                $imgUrl = $image->image;
                $imgExist = in_array($imgUrl, $allImages);
                if(!$imgExist){
                    $rm_urlPath = parse_url($imgUrl, PHP_URL_PATH);
                    $path = Str::replace('/storage/', '', $rm_urlPath);
                    $filePath = storage_path('app/public/'. $path);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }
        }
    }

    public function removeVideoFromStorage($videoWantToDelete){
        $allVideos=[];
        $allVideosTable = PostVideos::all();
        if (!empty($allVideosTable)) {
            foreach ($allVideosTable as $video) {
                $allVideos[] = $video->video;
            }

            foreach ($videoWantToDelete as $video) {
                $videoUrl = $video->video;
                $imgExist = in_array($videoUrl, $allVideos);
                if(!$imgExist){
                    $rm_urlPath = parse_url($videoUrl, PHP_URL_PATH);
                    $path = Str::replace('/storage/', '', $rm_urlPath);
                    $filePath = storage_path('app/public/'. $path);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }
        }
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
