<?php

namespace App\Http\Controllers;

use App\Models\youtube_category;
use Illuminate\Http\Request;

class YoutubeCategoryController extends Controller
{
    public function index() {}

    public function store(Request $request) {

        $category = youtube_category::where('category_id',$request->categoryID)
        ->where('category_name', $request->categoryName)->first();

        if(!$category){
            youtube_category::create([
                'category_id' => $request->categoryID,
                'category_name' => $request->categoryName
            ]);
        }    

        return redirect()->route('services')->with('success', 'Youtube category created successfully');
    }

    public function update($id) {}

    public function show($id) {}

    public function delete() {}
}
