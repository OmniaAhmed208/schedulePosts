<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use App\Models\settingsApi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\youtube_category;


class ServiceController extends Controller
{
    public function index()
    {
        $youtubeCategories = youtube_category::get()->map(function($query){
            return [
                "category_id" => $query->category_id,
                "category_name" => $query->category_name
            ];
        });

        $services = settingsApi::get()->map(function($query){
            return [
                "appType" => $query->appType,
                "appID" => $query->appID,
                "appSecret" => $query->appSecret,
                "apiKey" => $query->apiKey
            ];
        });

        return response()->json([
            'data' => [
                'services' => $services,
                'youtubeCategories' => $youtubeCategories
            ],
            'status' => true
        ],200);
    }

    public function store(Request $request)
    {
        try{
            Validator::make($request->all(), [
                'appType' => 'required|unique:settings_apis,appType',
                'appID' => 'required',
                'appSecret' => 'required',
            ]);
            
            $appType = $request->appType;

            $validationRules =  [];

            if($appType == 'youtube'){
                $validationRules['apiKey'] = 'required';
            }

            $validator =  Validator::make($request->all(), $validationRules);

            if($validator->fails()){
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                    'status' => false
                ],422);
            }
            
            $settingsData = [
                'creator_id'=> request()->user()->id,
                'appType' => $appType,
                'appID' => $request->appID,
                'appSecret' => $request->appSecret,
                'apiKey' => $request->apiKey
            ];

            $existingApp = settingsApi::where('appType', $appType)->first(); // if appType = face or insta... get it

            if ($existingApp) {
                $existingApp->update($settingsData);
            } else {
                settingsApi::create($settingsData);
            }

            return response()->json([
                'message' => 'Settings saved successfully',
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
