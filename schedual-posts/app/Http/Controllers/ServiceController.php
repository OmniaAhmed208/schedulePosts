<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\settingsApi;
use App\Models\youtube_category;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $allApps = ['facebook', 'instagram','twitter','youtube'];
        $settingsApiType = settingsApi::all();
        $youtubeCategories = youtube_category::all();

        return view('main.services.index',compact('allApps','settingsApiType','youtubeCategories'));
    }

    public function store(Request $request)
    {
        $appType = $request->appType; 

        $settingsData = [
            'creator_id'=> User::where('user_type','admin')->first()->id,
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

        return back()->with('success', 'Settings saved successfully');   
    }
}
