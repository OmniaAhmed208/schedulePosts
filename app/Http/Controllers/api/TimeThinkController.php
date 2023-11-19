<?php

namespace App\Http\Controllers\api;

use App\Models\time_think;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TimeThinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $timeThink = time_think::where('creator_id', Auth::user()->id)->first();
        
        if($timeThink != null){
            return response()->json([
                'data' => $timeThink,
                'status' => true
            ],200);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'time' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
                'status' => false
            ],422);
        }

        $time = time_think::create([
            'creator_id' => Auth::user()->id,
            'time' => $request->time
        ]);
    
        return response()->json([
            'message' => 'Time saved successfully',
            'data' => $time,
            'status' => true
        ],200);
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
        try{
            $time = time_think::find($id);

            if($time == null){
                return response()->json([
                    'message' => 'Time not regesterd',
                    'status' => false
                ],404);
            }

            $validator = Validator::make($request->all(), [
                'time' => 'required|integer'
            ]);

            if($validator->fails()){
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                    'status' => false
                ],422);
            }

            $time->update([
                'time' => $request->time,
            ]);

            return response()->json([
                'message' => 'Time updated successfully',
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
    public function destroy(string $id)
    {
        //
    }
}
