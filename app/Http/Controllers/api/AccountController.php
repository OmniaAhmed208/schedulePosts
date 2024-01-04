<?php

namespace App\Http\Controllers\api;

use App\Models\Api;
use App\Models\User;
use App\Models\settingsApi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = User::with(['apis'])->find(request()->user()->id);
        
        $data = [
            'name' => $accounts->name,
            'email' => $accounts->email,
            'image' => $accounts->image,
            'accounts' => $accounts->apis->map(function ($api) {
                return [
                    'account_type' => $api->account_type,
                    'account_id' => $api->account_id,
                    'account_name' => $api->account_name,
                    'account_pic' => $api->account_pic,
                    'account_link' => $api->account_link,
                    'email' => $api->email,
                ];
            }),
        ];

        return response()->json([
            'account' => $data,
            'status' => true
        ],200);
    }

    public function destroy(string $accountId)
    {
        try{
            $account = Api::where('account_id',$accountId)->where('creator_id', request()->user()->id)->first();

            if($account == null){
                return response()->json([
                    'message' => 'Account not found',
                    'status' => false
                ],404);
            }

            $account->delete();

            return response()->json([
                'message' => 'Account deleted successfully',
                'status' => true
            ],200);
        }
        catch(\Throwable $th){
            return response()->json([
                'message' => $th->getMessage(),
                'status' => false,
            ],500);
        }
    }
}
