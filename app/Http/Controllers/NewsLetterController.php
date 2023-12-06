<?php

namespace App\Http\Controllers;

use App\Models\NewsLetter;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NewsLetterController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:newsletter.create')->only(['store']);
        $this->middleware('permission:newsletter.edit')->only(['update']);
        $this->middleware('permission:newsletter.delete')->only('destroy');
    }

    public function index()
    {
        $newsLetter = NewsLetter::all();

        return view('main.newsletter.index',compact('newsLetter'));
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

            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $user = 'user'.Auth::user()->id;
            $image->storeAs('public/'.$user.'/'.'newsLetter', $filename);
            $storageImage = url('storage/'.$user.'/'.'newsLetter/'. $filename);
        }

        $validator =  $request->validate($validationRules);

        NewsLetter::create([
            'creator_id' => Auth::user()->id, // admin
            'title' => $request->title,
            'content' => $request->content,
            'image' => $storageImage,
            // 'color' => $request->color
        ]);

        return back()->with('success','The post created successfully');
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
            $userFolder = 'user'.Auth::user()->id;

            if($newsLetter->image != null){
                $rm_urlPath = parse_url($newsLetter->image, PHP_URL_PATH);
                $path = Str::replace('/storage/', '', $rm_urlPath);
                unlink(storage_path('app/public/'.$path));
            }
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/'.$userFolder.'/'.'newsLetter', $filename);
            $storageImage = url('storage/'.$userFolder.'/'.'newsLetter/'. $filename);
        }

        $validator =  $request->validate($validationRules);

        $newsLetter->update([
            'creator_id' => Auth::user()->id, // admin
            'title' => $request->title,
            'content' => $request->content,
            'image' => $storageImage,
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
