<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\settingsApi;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        return view('AdminSocialMedia.services');
    }

    public function store(Request $request)
    {
        // dd($request);
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

        // $settingsApi = settingsApi::updateOrCreate($settingsData);

        return back()->with('success', 'Settings saved successfully');   
    }
}
