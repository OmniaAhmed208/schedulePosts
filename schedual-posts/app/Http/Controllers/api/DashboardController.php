<?php

namespace App\Http\Controllers\api;

use App\Models\Api;
use App\Models\publishPost;
use App\Models\settingsApi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $servicesCount = settingsApi::count();
        $allServices = settingsApi::all();

        $registeredAppCount = Api::distinct()->where('creator_id', Auth::user()->id)->count('account_type');
        $count_all_posts = publishPost::where('creator_id', Auth::user()->id)->count(); // publish & pending

        $userId = Auth::user()->id;

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
                'registeredAppCount' => $registeredAppCount,
                'servicesCount' => $servicesCount,
                'publish_post_count_for_lastWeek' => $publishPostCount_for_lastWeek, //count,
                'count_all_posts' => $count_all_posts,
                'allPosts' => $posts
            ],
            'status' => true
        ],200);
    }

}
