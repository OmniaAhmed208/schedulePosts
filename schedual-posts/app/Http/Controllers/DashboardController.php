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

        return view('main.dashboard',compact('allPosts','appCount','servicesCount',
        'allApps','startDate','lastPosts','userId','publishPost'));
    }

    public function privacy_policy(){
        return view('main.privacy');
    }

    public function terms_policy(){
        return view('main.terms');
    }

}


