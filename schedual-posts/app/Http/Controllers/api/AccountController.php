<?php

namespace App\Http\Controllers\api;

use App\Models\Api;
use App\Models\settingsApi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $apiAccounts = Api::all()->where('creator_id', Auth::user()->id);
        $services = settingsApi::all(); // all services

        return response()->json([
            'message' => count($services).' services exist',
            'data' => [
                'apiAccounts' => $apiAccounts,
                'services' => $services
            ],
            'status' => true
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $accountId)
    {
        try{
            $account = Api::where('account_id',$accountId)->where('creator_id', Auth::user()->id)->first();

            if($account == null){
                return response()->json([
                    'message' => 'Account not found',
                    'status' => false
                ],401);
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
