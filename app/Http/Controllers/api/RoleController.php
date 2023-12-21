<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:roles.add')->only(['store']);
        $this->middleware('permission:roles.edit')->only(['update']);
    }

    public function index()
    {
        $roles = Role::all();
        return response()->json([
            'roles' => $roles,
            'status' => true
        ],200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'role' => 'required|unique:roles,name'
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
                'status' => false
            ],422);
        }

        $role = $request->role;
        $color = $request->color ?? 'dark';

        if (Role::where('name', $role)->exists()) {
            return response()->json([
                'message' => 'The role is already exists.',
                'status' => false
            ],500);
        }

        $role = Role::create([
            'name' => $role,
            'color' => $color
        ]);

        return response()->json([
            'message' => 'The role created successfully',
            'data' => $role,
            'status' => true
        ],200);
    }

    public function update(Request $request, string $id)
    {
        $role = Role::find($id);

        if ($role == null) {
            return response()->json([
                'message' => 'The role not found',
                'status' => false
            ], 404); // Change the status code to 404 (Not Found)
        }

        $validator = Validator::make($request->all(), [
            'role' => 'required|unique:roles,name,' . $role->id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
                'status' => false
            ], 422);
        }

        $role->update([
            'name' => $request->role,
            'color' => $request->color ?? 'dark',
        ]);

        return response()->json([
            'message' => 'The role updated successfully',
            'data' => $role,
            'status' => true
        ], 200);
    }

    public function destroy(string $id)
    {
        $role = Role::find($id);

        if($role == null){
            return response()->json([
                'message' => 'The role not found',
                'status' => false
            ],400);
        }

        $role->delete();

        return response()->json([
            'message' => 'The role deleted successfully',
            'status' => true
        ],200);
    }
}
