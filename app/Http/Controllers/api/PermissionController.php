<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();

        return response()->json([
            'data' => $permissions,
            'status' => true
        ],200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'permission' => 'required|unique:permissions,name'
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
                'status' => false
            ],422);
        }

        $permission = Permission::create([
            'name' => $request->permission
        ]);

        return response()->json([
            'message' => 'The permission created successfully',
            'data' => $permission,
            'status' => true
        ],200);
    }

    public function update(Request $request, string $id)
    {
        $permission = permission::find($id);

        if($permission == null){
            return response()->json([
                'message' => 'The permission not found',
                'status' => false
            ],400);
        }

        $validator = Validator::make($request->all(),[
            'permission' =>  'required|unique:permissions,name,' . $permission->id,
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
                'status' => false
            ],422);
        }

        $permission->update([
            'name' => $request->permission,
        ]);

        return response()->json([
            'message' => 'The permission updated successfully',
            'data' => $permission,
            'status' => true
        ],200);
    }

    public function destroy(string $id)
    {
        $permission = permission::find($id);

        if($permission == null){
            return response()->json([
                'message' => 'This permission not found',
                'status' => false
            ],400);
        }

        $permission->delete();

        return response()->json([
            'message' => 'The permission has been deleted successfully',
            'status' => true
        ],200);
    }
}
