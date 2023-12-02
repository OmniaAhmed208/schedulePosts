<?php

namespace App\Http\Controllers;

use App\Models\Api;
use App\Models\settingsApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index()
    {
        $apiAccounts = Api::all()->where('creator_id', Auth::user()->id);
        $userApps = settingsApi::all(); // all App on website

        return view('AdminSocialMedia.socialAccounts',compact('apiAccounts','userApps'));
    }

    public function destroy($userId) 
    {
        $account = Api::where('account_id',$userId)->where('creator_id', Auth::user()->id)->first();
        $account->deleteAccountWithSocialPosts();
        
        Api::where('account_id',$userId)->where('creator_id', Auth::user()->id)->delete(); // account_id => unique

        return redirect()->back()->with('success','Account deleted successfully');
    }
}
