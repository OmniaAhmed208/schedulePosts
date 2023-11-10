<?php

namespace App\Http\Controllers;

use App\Models\Api;
use App\Models\User;
use App\Models\PublishPost;
use App\Models\settingsApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(){

        $allUsers = User::all();
        $roles = Role::all();
        $user_roles = DB::table('user_has_roles')->get();

        return view('AdminSocialMedia.allUsers',compact('allUsers','roles','user_roles'));
    }

    public function show($userId)
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

        return view('AdminSocialMedia.userDashboard',compact('userId','allPosts','appCount','servicesCount','user',
        'allApps','startDate','lastPosts','Publish_Post'));
    }
}
