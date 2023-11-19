<?php

namespace App\Http\Controllers\api;

use App\Models\Api;
use App\Models\User;
use App\Models\publishPost;
use App\Models\settingsApi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $servicesCount = settingsApi::count();
        $allServices = settingsApi::all();

        $user = user::where('id', Auth::user()->id)->first();

        $registeredAppCount = Api::distinct()->where('creator_id', Auth::user()->id)->count('account_type');
        $count_all_posts = publishPost::where('creator_id', Auth::user()->id)->count(); // publish & pending

        $startDate = now()->subDays(7); // last 7 days ==> chart js
        $publishPostCount_for_lastWeek = publishPost::where('status', 'published')->where('creator_id', Auth::user()->id)
        ->where('scheduledTime', '>=', $startDate)->count();

        $allPosts = PublishPost::where('creator_id', Auth::user()->id)->with(['postImages', 'postVideos'])->get();
        $posts = [];

        foreach ($allPosts as $post) {
            $postImages = $post->postImages;
            $postVideos = $post->postVideos;

            $posts[] = [
                'post' => $post,
            ];
        }

        return response()->json([
            'data' => [
                'user' => $user,
                'registeredAppCount' => $registeredAppCount,
                'servicesCount' => $servicesCount,
                'publish_post_count_for_lastWeek' => $publishPostCount_for_lastWeek, //count,
                'count_all_posts' => $count_all_posts,
                'allPosts' => $posts
            ],
            'status' => true
        ],200);
    }

    public function show(string $id) // show dashboard for each user to admin
    {
        $user = User::find($id);

        $count_all_posts = PublishPost::where('creator_id', $id)->count();
        $appCount = Api::distinct()->where('creator_id', $id)->count('account_type');
        $servicesCount = settingsApi::count();

        // $userApps = App\Models\Api::where('creator_id', $userId)->distinct()->pluck('account_type'); // App of user regesterd in
        $allApps = settingsApi::all(); // all App on website

        $startDate = now()->subDays(7);
        $publishPostCount_for_lastWeek = PublishPost::where('scheduledTime', '>=', $startDate)->where('status', 'published')
        ->where('creator_id', $id)->count();

        $allPosts = PublishPost::all()->where('creator_id', $id);

        if($user == null){
            return response()->json([
                'message' => 'User not found',
                'status' => false
            ],401);
        }

        return response()->json([
            'message' => 'User found',
            'data' => [
                'user'=> $user,
                'registeredAppCount' => $appCount,
                'servicesCount' => $servicesCount,
                'publish_post_count_for_lastWeek' => $publishPostCount_for_lastWeek, //count,
                'count_all_posts' => $count_all_posts,
                'allPosts' => $allPosts,
                'allApps' => $allApps
            ],
            'status' => true
        ],200);
    }
}
