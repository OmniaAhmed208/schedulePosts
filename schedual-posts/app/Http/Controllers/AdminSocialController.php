<?php

namespace App\Http\Controllers;

use App\Models\settingsApi;
use Illuminate\Http\Request;

class AdminSocialController extends Controller
{
    public function test(){
        return view('AdminSocialMedia.test');
    }

    public function index(){
        return view('AdminSocialMedia.index');
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
            'appType' => $appType,
            'appID' => $request->appId,
            'appSecret' => $request->appSecret
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


