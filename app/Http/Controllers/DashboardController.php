<?php

namespace App\Http\Controllers;

use DateTime;
use DateTimeZone;
use Carbon\Carbon;
use App\Models\Api;
use App\Models\User;
use GuzzleHttp\Client;
use App\Models\publishPost;
use App\Models\settingsApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Location\Facades\Location;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function test(Request $request)
    {
        $ip = request()->ip();
        // $ip = $request->ip();
        $publicIp = $request->server('REMOTE_ADDR');
        // dd($publicIp);

        $client = new Client();
        $apiKey = '9a2a9fbda77723'; // Replace with your IPinfo API key

        // Make a request to the IPinfo API
        $response = $client->get("http://ipinfo.io/{$ip}?token={$apiKey}");
        $data = json_decode($response->getBody(), true);

        // dd($data);

        // ________________________________________________________

        $ip = '156.194.63.61'; //For static IP address get
        $ip = request()->ip(); //Dynamic IP address get
        $data = Location::get($ip);
        // dd($data);

        // ________________________________________________________

        $time = Carbon::now('Africa/Cairo');

        $userTimezoneString = 'Africa/Cairo';
        $userTz = new DateTimeZone($userTimezoneString);
        $userNow = new DateTime('now', $userTz);
        echo $userNow->format('Y-m-d H:i:s'); // 2023-05-09 12:22:46
        // dd($time, $userTz, $userNow);
        return view('main.test');
    }

    public function index()
    {
        $postsCount = publishPost::where('creator_id', Auth::user()->id)->count();
        $allPosts = publishPost::where('creator_id', Auth::user()->id)->with(['postImages', 'postVideos'])->get();

        $appCount = Api::distinct()->where('creator_id', Auth::user()->id)->count('account_type');
        $servicesCount = settingsApi::count();
        $accounts = api::where('creator_id', Auth::user()->id)->get();
        $allApps = settingsApi::all();
        $startDate = now()->subDays(7); // last 7 days
        $lastPosts = publishPost::where('scheduledTime', '>=', $startDate)->where('status', 'published')
        ->where('creator_id', Auth::user()->id)->count();
        $userId = Auth::user()->id;
        // Fetch posts from the last 10 days
        $publishPost = publishPost::where('status', 'published')->where('creator_id', Auth::user()->id)
        ->where('scheduledTime', '>=', $startDate)->get();

        return view('main.dashboard.index',compact('postsCount','allPosts','appCount','servicesCount','accounts',
        'allApps','startDate','lastPosts','userId','publishPost'));
    }

    public function show($userId) //dashboard for each user
    {
        $allPosts = PublishPost::where('creator_id', $userId)->count();
        $appCount = Api::distinct()->where('creator_id', $userId)->count('account_type');
        $servicesCount = settingsApi::count();

        $user = User::where('id', $userId)->get();
        // $userApps = App\Models\Api::where('creator_id', $userId)->distinct()->pluck('account_type'); // App of user regesterd in
        $allApps = settingsApi::all(); // all App on website

        $startDate = now()->subDays(7);
        $lastPosts = PublishPost::where('scheduledTime', '>=', $startDate)->where('status', 'published')
        ->where('creator_id', $userId)->count();

        $Publish_Post = PublishPost::where('status', 'published')->where('creator_id', $userId)
        ->where('scheduledTime', '>=', $startDate)->get();

        $monthlyCounts = PublishPost::select(DB::raw('DATE_FORMAT(scheduledTime, "%Y-%m") as month'), DB::raw('count(*) as post_count'))
        ->where('creator_id', $userId)
        ->where('scheduledTime', '>=', now()->subMonths(12))
        ->groupBy('month')
        ->get();


        return view('main.dashboard.show',compact('userId','allPosts','appCount','servicesCount','user',
        'allApps','startDate','lastPosts','Publish_Post'));
    }

    public function policy(){
        return view('main.policy');
    }


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

}


