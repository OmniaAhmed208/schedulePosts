<?php

namespace App\Http\Controllers\api;

use App\Models\UploadFiles;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UploadFilesController extends Controller
{
    public function store(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        $filenames = []; // Array to store filenames
        if (!Auth::check()) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
        
        $validator = Validator::make($request->all(), [
            'images' => 'array',
            'images.*' => 'file|image|mimes:jpeg,jpg,png',
            'video' => 'file|mimes:video/quicktime,video/mp4,video/mpeg,video/mpg,video/mov,video/avi,video/webm',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
                'status' => false
            ], 422);
        }

        if ($request->hasFile('images')) {
            $images = $request->file('images');
            foreach ($images as $image) {
                $user = 'user' . Auth::user()->id;
                $filename = time() . '_' . $image->getClientOriginalName();
                $dir = $user . '/' . 'postImages';
                $image->storeAs('public/' . $dir . '/', $filename);
                $filenames[] = $filename;
                UploadFiles::create([
                    'file' => $filename,
                    'type' => 'image',
                ]);
            }
            return response()->json(['filenames' => $filenames]);
        }

        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $user = 'user' . Auth::user()->id;
            $filename = time() . '_' . $video->getClientOriginalName();
            $dir = $user . '/' . 'postVideo';
            $video->storeAs('public/' . $dir . '/', $filename);
            UploadFiles::create([
                'file' => $filename,
                'type' => 'video',
            ]);
            return response()->json(['filename' => $filename]);
        }

        return response()->json(['message' => 'No files uploaded']);
    }


    public function destroy()
    {
        $tmp_file = UploadFiles::where('file', request()->getContent())->first();

        if($tmp_file){
            $user = 'user'.Auth::user()->id;
            $dir = $user . '/' . ($tmp_file->type === 'image' ? 'postImages' : 'postVideo');
            Storage::delete('public/'. $dir . '/' .$tmp_file->file);
            $tmp_file->delete();
            return response('');
        }
    }
}
