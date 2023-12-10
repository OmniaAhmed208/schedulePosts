<?php

namespace App\Http\Controllers;

use App\Models\UploadFiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UploadFilesController extends Controller
{
    public function store(Request $request)
    {
        $filenames = []; // Array to store filenames

        $validator = $request->validate([
            'images' => 'array',
            'images.*' => 'file|image|mimes:jpeg,jpg,png',
            'video' => 'mimetypes:video/quicktime,video/mp4,video/mpeg,video/mpg,video/mov,video/avi,video/webm',
        ]);

        if ($request->hasFile('images')) {
            $images = $request->file('images');
            foreach ($images as $image) {
                $user = 'user'.Auth::user()->id;
                $filename = time() . '_' . $image->getClientOriginalName();
                $dir = $user.'/'.'postImages';
                $image->storeAs('public/'. $dir . '/', $filename);
                $filenames[] = $filename;
                UploadFiles::create([
                    'file' => $filename,
                    'type' => 'image', 
                ]);
            }
            return ['images'=> $filenames ];
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
            return ['video'=> $filename ];
        }

        return '';
    }

    public function destroy(Request $request)
    {
        $tmp_file = UploadFiles::where('file', $request->filname)->first();

        if($tmp_file){
            $user = 'user'.Auth::user()->id;
            $dir = $user . '/' . ($tmp_file->type === 'image' ? 'postImages' : 'postVideo');
            Storage::delete('public/'. $dir . '/' .$tmp_file->file);
            $tmp_file->delete();
            return 'deleted';
        }
    }
}
