<?php

namespace App\Http\Controllers\api;

use App\Models\publishPost;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::user()->id;

        $startDate = now()->subDays(7); // last 7 days
       
        // Fetch posts from the last 7 days to chart js
        $publish_post_from_startDate = publishPost::where('status', 'published')->where('creator_id', Auth::user()->id)
        ->where('scheduledTime', '>=', $startDate)->get();

        return response()->json([
            'data' => [
                'user_authenticated_id' => $userId,
                'startDate'=> $startDate,
                'publish_post_from_startDate' => $publish_post_from_startDate //data
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
    public function destroy(string $id)
    {
        //
    }
}
