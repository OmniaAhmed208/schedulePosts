<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Api;
use App\Models\PostImages;
use App\Models\PostVideos;
use App\Models\time_think;
use App\Models\publishPost;
use App\Models\settingsApi;
use Illuminate\Http\Request;
use App\Services\PostService;
use App\Models\youtube_category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

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

    public function show($id)
    {
        $post = PublishPost::where('id', $id)->where('creator_id', Auth::user()->id)->with(['postImages', 'postVideos'])->first();
        $youtubeCategories = youtube_category::all();

        if($post == null){
            return view('AdminSocialMedia.posts')->with('error','post not found');
        }

        return view('AdminSocialMedia.posts',compact('post','youtubeCategories'))->with('success','post found');
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

    public function store(Request $request)
    {
        $validator = $request->validate([
            'postData' => 'max:5000',
            'video' => 'mimetypes:video/quicktime,video/mp4,video/mpeg,video/mpg,video/mov,video/avi,video/webm',
            'images' => 'array',
            'images.*' => 'file|image|mimes:jpeg,jpg,png',
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

        $imgUpload = []; 
        $publishPosts = [];

        if ($request->hasFile('images'))
        {
            $images = $request->file('images');
            $imgUpload = $this->postStore->saveImages($images);
        }

        $youtubeVideoPath='';$twitterVideoPath='';$storageVideo='';
        if ($request->hasfile('video'))
        {
            $video = $request->file('video');
            $videoUpload = $this->postStore->saveVideo($video);

            $youtubeVideoPath = $videoUpload['youtubeVideoPath'];
            $twitterVideoPath = $videoUpload['twitterVideoPath'];
            $storageVideo = $videoUpload['storageVideo'];
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

                if (is_array($imgUpload) && !empty($imgUpload)) {
                    foreach ($imgUpload as $img) {
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

        if($request->scheduledTime != null){
            $scheduledTime = Carbon::parse($request->scheduledTime)->format('Y-m-d H:i');
            if ($scheduledTime > $now){
                $post->status = 'pending';
                $post->scheduledTime =  Carbon::parse($request->scheduledTime)->format('Y-m-d H:i');
            }
            else{
                return redirect()->back()->with('error','The time must be after to now '. $now );
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

            $imgUpload = []; 
            if($request->file('images')){
                $images = $request->file('images');
                $imgUpload = $this->postStore->saveImages($images);

                if (is_array($imgUpload) && !empty($imgUpload)) {
                    foreach ($imgUpload as $img) {
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
                $videoUpload = $this->postStore->saveVideo($video);
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
