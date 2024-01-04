<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\youtube_category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class YoutubeCategoryController extends Controller
{
    public function index()
    {
        $categories = youtube_category::all();

        return response()->json([
            'data' => $categories,
            'status' => true
        ],200);
    }

    public function store(Request $request)
    {
        try{
            $validator = Validator::make($request->all(),[
                'category_id' => 'required|integer',
                'category_name' => 'required|string',
            ]);
    
            if($validator->fails()){
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                    'status' => false
                ],422);
            }
    
            $category = youtube_category::create([
                'category_id' => $request->category_id,
                'category_name' => $request->category_name
            ]);
    
            return response()->json([
                'message' => 'Category added successfully',
                'data' => $category,
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

    public function show(string $id)
    {
        $category = youtube_category::find($id);

        if($category == null){
            return response()->json([
                'message' => 'Category not found',
                'status' => false
            ],404);
        }

        return response()->json([
            'message' => 'Category found',
            'data' => $category,
            'status' => true
        ],200);
    }

    public function update(Request $request, string $id)
    {
        $category = youtube_category::find($id);

        if($category == null){
            return response()->json([
                'message' => 'Category not found',
                'status' => false
            ],404);
        }

        $validator = Validator::make($request->all(),[
            'category_id' => 'required|integer',
            'category_name' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
                'status' => false
            ],422);
        }

        $category->update([
            'category_id' => $request->category_id,
            'category_name' => $request->category_name
        ]);

        return response()->json([
            'message' => 'Category updated successfully',
            'data' => $category,
            'status' => true
        ],200);
    }

    public function destroy(string $id)
    {
        $category = youtube_category::find($id);

        if($category == null){
            return response()->json([
                'message' => 'Category not found',
                'status' => false
            ],404);
        }

        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully',
            'status' => true
        ],200);
    }
}
