<?php

namespace App\Http\Controllers\api;

use App\Models\NewsLetter;
use App\Models\Subscriber;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\SubscriberEmail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class NewsLetterController extends Controller
{
    public function index()
    {
        $newsLetter = NewsLetter::all();

        return response()->json([
            'data' => $newsLetter,
            'status' => true
        ],200);
    }

    public function store(Request $request)
    {
        $validationRules = [
            'title' => 'required',
            'content' => 'required',
        ];

        $storageImage = '';
        if ($request->hasFile('image'))
        {
            unset($validationRules['content']);

            $userFolder = 'user'.Auth::user()->id;
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/'.$userFolder.'/'.'newsLetter', $filename);
            $storageImage = url('storage/'.$userFolder.'/'.'newsLetter/'. $filename);
        }

        $validator = Validator::make($request->all(), $validationRules);

        if($validator->fails()){
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
                'status' => false
            ],422);
        }

        NewsLetter::create([
            'creator_id' => Auth::user()->id, // admin
            'title' => $request->title,
            'content' => $request->content,
            'image' => $storageImage
        ]);

        $subscribers = Subscriber::all();
        foreach($subscribers as $subscriber){
            $newsletter_message = 'New Newsletter at Social Media App';
            $cc = $subscriber;
            $bcc = $subscriber;
            Mail::to($subscriber)
            ->cc($cc)
            ->bcc($bcc)
            ->send(new SubscriberEmail($newsletter_message));
        }
        
        return response()->json([
            'message' => 'The post created successfully',
            'status' => true,
        ],200);
    }
    
    public function show($id)
    {
        $newsletter = NewsLetter::find($id);
        if (!$newsletter) {
            return response()->json([
                'message' => 'Newsletter not found',
                'status' => false
            ], 404);
        }

        return response()->json([
            'newsletter' => $newsletter,
            'status' => true
        ],200);
    }

    public function update(Request $request,$id)
    {
        $newsLetter = NewsLetter::find($id);
        if (!$newsLetter) {
            return response()->json([
                'message' => 'Newsletter not found',
                'status' => false
            ], 404);
        }

        $validationRules = [
            'title' => 'required',
            'content' => 'required',
        ];

        $storageImage = '';

        $oldImage = $newsLetter->oldImage;
        
        if($oldImage != null){
            $storageImage = $oldImage;
            unset($validationRules['content']);
        }

        if ($request->hasFile('image'))
        {
            unset($validationRules['content']);
            $userFolder = 'user'.Auth::user()->id;

            if($newsLetter->image != null){
                $rm_urlPath = parse_url($newsLetter->image, PHP_URL_PATH);
                $path = Str::replace('/storage/', '', $rm_urlPath);
                $filePath = storage_path('app/public/'. $path);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/'.$userFolder.'/'.'newsLetter', $filename);
            $storageImage = url('storage/'.$userFolder.'/'.'newsLetter/'. $filename);
        }

        $validator = Validator::make($request->all(), $validationRules);

        $newsLetter->update([
            'creator_id' => Auth::user()->id,
            'title' => $request->title,
            'content' => $request->content,
            'image' => $storageImage
        ]);

        return response()->json([
            'message' => 'The post updated successfully',
            'status' => true
        ],200);
    }
    
    public function destroy($id)
    {
        $newsLetter = NewsLetter::find($id);

        if($newsLetter == null){
            return response()->json([
                'message' => 'Post not found',
                'status' => false
            ],401);
        }

        if($newsLetter->image != null){
            $rm_urlPath = parse_url($newsLetter->image, PHP_URL_PATH);
            $path = Str::replace('/storage/', '', $rm_urlPath);
            $filePath = storage_path('app/public/'. $path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $newsLetter->delete();

        return response()->json([
            'message' => 'The post deleted successfully',
            'status' => true
        ],200);
    }
}
