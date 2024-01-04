<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use App\Models\settingsApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $servicesCount = settingsApi::count();
        $startDate = now()->subDays(7);
        
        $userData = Cache::remember('dashboard_' . Auth::user()->id, 60*60*24, function () use ($startDate) { // store cache for 1 day
            return User::with([
                'publishPosts',
            ])
            ->withCount([
                'apis as registeredAppCount' => function ($query) {
                    $query->select(DB::raw('COUNT(DISTINCT account_type)'));
                },
                'publishPosts as count_all_posts',
                'publishPosts as publishPostsCount' => function ($query) use ($startDate) {
                    $query->where('status', 'published')
                        ->where('scheduledTime', '>=', $startDate);
                },
            ])->find(Auth::user()->id);
        });

        $publishPosts = $userData->publishPosts->load(['postImages', 'postVideos']);
        $postsData = $publishPosts->map(function ($post) 
        {
            $postImage = $post->postImages->isNotEmpty() ? $post->postImages[0] : null;
            return [
                'id' => $post->id,
                'content' => $post->content,
                'account_type' => $post->account_type,
                'account_name' => $post->account_name,
                'status' => $post->status,
                'scheduledTime' => $post->scheduledTime,
                'postImage' => $postImage ? $postImage->image : null,
            ];
        });

        $data = [
            'name' => $userData->name,
            'email' => $userData->email,
            'email_verified_at' => $userData->email_verified_at,
            'user_type' => $userData->user_type,
            'image' => $userData->image,
            'registeredAppCount' => $userData->registeredAppCount,
            'count_all_posts' => $userData->count_all_posts,
            'servicesCount' => $servicesCount,
            'publish_post_count_for_lastWeek' => $userData->publishPostsCount,
            'posts' => $postsData,
        ];
        
        return response()->json([
            'data' => $data,
            'status' => true
        ],200);

    }

    public function show(string $id) // show dashboard for each user to admin
    {

        $startDate = now()->subDays(7);

        $userData = Cache::remember('dashboard_' . $id, now()->addMinutes(10), function () use ($startDate) {
            return User::withCount([
                'apis as registeredAppCount' => function ($query) {
                    $query->select(DB::raw('COUNT(DISTINCT account_type)'));
                },
                'publishPosts as allPosts',
                'publishPosts as lastPublishPosts' => function ($query) use ($startDate) {
                    $query->where('status', 'published')
                        ->where('scheduledTime', '>=', $startDate);
                },
            ]);
        })->find($id);

        if ($userData == null) {
            return response()->json([
                'message' => 'User not found',
                'status' => false
            ], 401);
        }

        $allApps = settingsApi::pluck('appType')->toArray();

        $data = [
            'name' => $userData->name,
            'registeredAppCount' => $userData->registeredAppCount,
            'count_all_posts' => $userData->allPosts,
            'servicesCount' => count($allApps),
            'publish_post_count_for_lastWeek' => $userData->lastPublishPosts,
            'allApps' => $allApps,
        ];

        return response()->json([
            'data' => $data,
            'status' => true
        ], 200);
     
    }
}
