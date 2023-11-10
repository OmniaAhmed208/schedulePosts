<?php

namespace App\Http\Controllers\api;

use App\Models\NewsLetter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
        // dd($request);
        $validationRules = [
            'title' => 'required',
            'content' => 'required',
        ];

        $storageImage = '';
        if ($request->hasFile('image')) 
        {
            unset($validationRules['content']);

            $image = $request->file('image');  
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/newsLetter', $filename);
            $storageImage = Storage::url('newsLetter/'. $filename);   
        }

        $validator = Validator::make($request->all(), $validationRules);

        if($validator->fails()){
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
                'status' => false
            ],401);
        }

        NewsLetter::create([
            'creator_id' => Auth::user()->id, // admin
            'title' => $request->title,
            'content' => $request->content,
            'image' => $storageImage
        ]);

        return response()->json([
            'message' => 'The post created successfully',
            'status' => true,
        ],200);
    }

    public function update(Request $request,$id)
    {
        $newsLetter = NewsLetter::find($id);

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

            $image = $request->file('image');  
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/newsLetter', $filename);
            $storageImage = Storage::url('newsLetter/'. $filename);   
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

        $newsLetter->delete();

        return response()->json([
            'message' => 'The post deleted successfully',
            'status' => true
        ],200);
    }
}
