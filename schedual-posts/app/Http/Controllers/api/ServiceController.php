<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use App\Models\settingsApi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = settingsApi::all();

        return response()->json([
            'message' => count($services).' services exist',
            'data' => $services,
            'status' => true
        ],200);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $appType = $request->appType; 

            $validator = Validator::make($request->all(),[
                'appType' => 'required|unique:settings_apis,appType',
                'appId' => 'required',
                'appSecret' => 'required',
            ]);

            if($appType == 'youtube'){
                $validator = Validator::make($request->all(),[
                    'appType' => 'required|unique:settings_apis,appType',
                    'appId' => 'required',
                    'appSecret' => 'required',
                    'apiKey' => 'required',
                ]);
            }

            if($validator->fails()){
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                    'status' => false
                ],422);
            }

            $settingsData = settingsApi::create([
                'creator_id'=> User::where('user_type','admin')->first()->id,
                'appType' => $appType,
                'appID' => $request->appId,
                'appSecret' => $request->appSecret,
                'apiKey' => $request->apiKey
            ]);

            return response()->json([
                'message' => 'Service added successfully',
                'data' => $settingsData,
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

    /**
     * Display the specified resource.
     */
    public function show(string $appType)
    {
        try{
            $service = settingsApi::where('appType',$appType)->first();

            if($service == null){
                return response()->json([
                    'message' => 'This app not found',
                    'status' => false
                ],401);
            }

            return response()->json([
                'message' => 'This app found',
                'data' => $service,
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $appType)
    {
        try{
            $service = settingsApi::where('appType',$appType)->first();

            if($service == null){
                return response()->json([
                    'message' => 'This app not found',
                    'status' => false
                ],401);
            }

            $validator = Validator::make($request->all(),[
                'appType' => 'unique:settings_apis,appType,' . $service->id,
                'appId' => 'required',
                'appSecret' => 'required',
            ]);

            if($appType === 'youtube' || $request->appType === 'youtube'){
                $validator = Validator::make($request->all(),[
                    'appType' => 'unique:settings_apis,appType,' . $service->id,
                    'appId' => 'required',
                    'appSecret' => 'required',
                    'apiKey' => 'required',
                ]);
            }            

            if($validator->fails()){
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                    'status' => false
                ],422);
            }

            $service->update([
                'creator_id'=> User::where('user_type','admin')->first()->id,
                'appType' => $request->appType,
                'appID' => $request->appId,
                'appSecret' => $request->appSecret,
                'apiKey' => $request->apiKey
            ]);

            return response()->json([
                'message' => 'Service updated successfully',
                'data' => $service,
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $appType)
    {
        try{
            $service = settingsApi::where('appType',$appType)->first();

            if($service == null){
                return response()->json([
                    'message' => 'This app not found',
                    'status' => false
                ],401);
            }

            $service->delete();

            return response()->json([
                'message' => 'This app deleted successfully',
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
