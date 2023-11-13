<?php

namespace App\Http\Controllers;

use App\Models\Api;
use App\Models\User;
use App\Models\publishPost;
use App\Models\settingsApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function test(){
        return view('AdminSocialMedia.test');
    }

    public function index()
    {
        $allPosts = publishPost::where('creator_id', Auth::user()->id)->count();
        $appCount = Api::distinct()->where('creator_id', Auth::user()->id)->count('account_type');
        $servicesCount = settingsApi::count();
        $allApps = settingsApi::all();
        $startDate = now()->subDays(7); // last 7 days
        $lastPosts = publishPost::where('scheduledTime', '>=', $startDate)->where('status', 'published')
        ->where('creator_id', Auth::user()->id)->count();
        $userId = Auth::user()->id;
        // Fetch posts from the last 10 days
        $publishPost = publishPost::where('status', 'published')->where('creator_id', Auth::user()->id)
        ->where('scheduledTime', '>=', $startDate)->get();

        return view('main.dashboard.index',compact('allPosts','appCount','servicesCount',
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

        return view('main.dashboard.show',compact('userId','allPosts','appCount','servicesCount','user',
        'allApps','startDate','lastPosts','Publish_Post'));
    }

    public function policy(){
        return view('main.policy');
    }

}


