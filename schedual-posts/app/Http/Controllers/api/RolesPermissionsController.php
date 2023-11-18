<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesPermissionsController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $role_has_permissions = DB::table('role_has_permissions')->get();

        $pages = $permissions->map(function ($permission) {
            return explode('.', $permission->name)[0];
        })->unique();

        return response()->json([
            'data' => [
                'roles' => $roles,
                'permissions' => $permissions,
                'role_has_permissions' => $role_has_permissions,
                'pages' => $pages
            ],
            'status' => true
        ],200);
    }

    public function assignRoleToPermissions(Request $request,$role_id)
    {
        $permissions = $request->permission;
        $permission_id = '';

        if($permissions){
            foreach ($permissions as $item) {
                $values = explode(',', $item);
                $permission_id = $values[0];

                // Check if the entry already exists in the table
                $existingEntry = DB::table('role_has_permissions')
                    ->where('role_id', $role_id)
                    ->where('permission_id', $permission_id)
                    ->first();

                if (!$existingEntry) {
                    // If the entry doesn't exist, insert it
                    DB::table('role_has_permissions')->insert([
                        'role_id' => $role_id,
                        'permission_id' => $permission_id,
                    ]);
                }
            }

            $role_has_permissions = DB::table('role_has_permissions')->get();
            foreach ($role_has_permissions as $row) {
                if($row->role_id == $role_id){
                    $recordKey = $row->permission_id . ',' . $row->role_id;
                    // If the record is in db and not in the permissions array
                    if (!in_array($recordKey, $permissions)) {
                        DB::table('role_has_permissions')
                        ->where('role_id', $row->role_id)
                        ->where('permission_id', $row->permission_id)
                        ->delete();
                    }
                }
            }
        }
        else{
            DB::table('role_has_permissions')->where('role_id', $role_id)->delete();
        }

        return response()->json([
            'message' => "The role assigned to permissions successfully",
            'status' => true
        ],200);
    }

    public function assignUserToRoles(Request $request, $userId)
    {
        $newRoles = $request->roles;

        // Fetch existing roles assigned to the user from the database
        $existingRoles = DB::table('user_has_roles')
            ->where('user_id', $userId)
            ->pluck('role_id') // get column of role_id only
            ->toArray();

        if ($newRoles === null || empty($newRoles)) {
            $role = Role::where('name', 'user')->first();

            // Remove existing roles for the user
            DB::table('user_has_roles')->where('user_id', $userId)->delete();

            // Insert the 'user' role
            DB::table('user_has_roles')->insert([
                'role_id' => $role->id,
                'user_id' => $userId,
            ]);

        } else {
            $rolesToInsert = array_diff($newRoles, $existingRoles);

            $rolesToDelete = array_diff($existingRoles, $newRoles);

            foreach ($rolesToInsert as $role) {
                DB::table('user_has_roles')->insert([
                    'role_id' => $role,
                    'user_id' => $userId,
                ]);
            }

            // Delete roles that are not in the request
            foreach ($rolesToDelete as $role) {
                DB::table('user_has_roles')
                    ->where('user_id', $userId)
                    ->where('role_id', $role)
                    ->delete();
            }
        }

        return response()->json([
            'message' => "Roles assigned to the user successfully.",
            'status' => true
        ],200);
    }
}
