<?php

namespace App\Http\Controllers;

use App\Models\Api;
use App\Models\User;
use App\Models\publishPost;
use App\Models\settingsApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminSocialController extends Controller
{
    public function test(){
        return view('AdminSocialMedia.test');
    }

    public function index()
    {
        $allPosts = publishPost::where('creator_id', Auth::user()->id)->count();
        $appCount = Api::distinct()->where('creator_id', Auth::user()->id)->count('account_type');
        $servicesCount = settingsApi::count();

        return view('AdminSocialMedia.index',compact('allPosts','appCount','servicesCount'));
    }
    
    public function privacy_policy(){
        return view('social.privacy');
    }

    public function terms_policy(){
        return view('social.terms');
    }

    public function services(){
        return view('AdminSocialMedia.services');
    }

    public function socialAccounts(){
        return view('AdminSocialMedia.socialAccounts');
    }
    
    public function allUsers(){
        return view('AdminSocialMedia.allUsers');
    }

    public function userDashboard($userId){
        return view('AdminSocialMedia.userDashboard',compact('userId'));
    }

    public function settingsApi(Request $request)
    {
        // dd($request);
        $appType = $request->appType; 

        $settingsData = [
            'creator_id'=> user::where('user_type','admin')->first()->id,
            'appType' => $appType,
            'appID' => $request->appId,
            'appSecret' => $request->appSecret,
            'apiKey' => $request->apiKey
        ];

        $existingApp = settingsApi::where('appType', $appType)->first(); // if appType = face or insta... get it
        
        if ($existingApp) {
            $existingApp->update($settingsData);
        } else {
            settingsApi::create($settingsData);
        }

        // $settingsApi = settingsApi::updateOrCreate($settingsData);

        return back()->with('settingsApi', 'Settings saved successfully');   
    }

}


