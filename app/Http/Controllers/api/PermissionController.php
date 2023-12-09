<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    public function index()
    {
        // $permissions = Permission::all();
        $user_has_roles = DB::table('user_has_roles')->where('user_id', Auth::user()->id)->get();
        $userPermissions = [];
        $roles = [];

        foreach ($user_has_roles as $role) {
            $role_has_permissions = DB::table('role_has_permissions')
                ->where('role_id', $role->role_id)
                ->get();

            $roleModel = Role::find($role->role_id);
            if ($roleModel) {
                $roles[] = $roleModel->name;
            }

            foreach ($role_has_permissions as $permission) {
                $permissionModel = Permission::find($permission->permission_id);
                if ($permissionModel) {
                    $userPermissions[] = $permissionModel->name;
                }
            }
        }
        $uniquePermissions = array_unique($userPermissions); // if user has multi roles get this permissions without redundancy

        $userRole = '';
        $hasPagesLinkPermission = in_array('pages.link', $userPermissions);
        if ($hasPagesLinkPermission) {
            $userRole = 'not user';
        } else {
            $userRole = 'user';
        }

        return response()->json([
            'userRole' => $userRole,
            // 'userPermissions' => $uniquePermissions,
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
