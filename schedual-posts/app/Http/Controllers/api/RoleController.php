<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        
        return response()->json([
            'data' => $roles,
            'status' => true
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'roleName' => 'required|unique:roles,name'
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
                'status' => false
            ],422);
        }

        $role = Role::create([
            'name' => $request->roleName, 
            'role_color' => $request->roleColor ? $request->roleColor : 'dark'
        ]);

        return response()->json([
            'message' => 'The role created successfully',
            'data' => $role,
            'status' => true
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

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
            'roleName' => 'required|unique:roles,name,' . $role->id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
                'status' => false
            ], 422);
        }

        $role->update([
            'name' => $request->roleName, 
            'role_color' => $request->roleColor ?? 'dark',
        ]);

        return response()->json([
            'message' => 'The role updated successfully',
            'data' => $role,
            'status' => true
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
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
