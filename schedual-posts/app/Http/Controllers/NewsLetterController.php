<?php

namespace App\Http\Controllers;

use App\Models\NewsLetter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NewsLetterController extends Controller
{
    public function index()
    {
        $newsLetter = NewsLetter::all();

        return view('main.newsletter.index',compact('newsLetter'));
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

        $validator =  $request->validate($validationRules);

        NewsLetter::create([
            'creator_id' => Auth::user()->id, // admin
            'title' => $request->title,
            'content' => $request->content,
            'image' => $storageImage
        ]);

        return back()->with('success','The post created successfully');
    }

    public function update(Request $request,$id)
    {
        // dd($request);
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

        $validator =  $request->validate($validationRules);

        $newsLetter->update([
            'creator_id' => Auth::user()->id, // admin
            'title' => $request->title,
            'content' => $request->content,
            'image' => $storageImage
        ]);

        return back()->with('success','The post updated successfully');
    }

    public function destroy($id)
    {
        $newsLetter = NewsLetter::find($id);

        if($newsLetter != null)
        {
            $newsLetter->delete();
            return back()->with('success','The post deleted successfully');
        }

    }
}
