<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesPermissionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:roles.assign_roles_to_user')->only(['assignUserToRoles']);
        $this->middleware('permission:roles.assign_role_to_permissions')->only(['assignRoleToPermissions']);
    }

    public function index(Request $request)
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $role_has_permissions = DB::table('role_has_permissions')->get();

        $pages = $permissions->map(function ($permission) {
            return explode('.', $permission->name)[0];
        })->unique();

        return view('main.roles.index', compact('roles','permissions','role_has_permissions','pages'));
    }

    // public function assignRoleToPermissions(Request $request)
    // {
    //     // dd($request);
    //     $permissions = $request->permission;
    //     $permission_id = '';
    //     $role_id = '';
    //     dd($permissions);
    //     if($permissions){
    //         foreach ($permissions as $item) {
    //             $values = explode(',', $item);
    //             $permission_id = $values[0];
    //             $role_id = $values[1];
    
    //             // Check if the entry already exists in the table
    //             $existingEntry = DB::table('role_has_permissions')
    //                 ->where('role_id', $role_id)
    //                 ->where('permission_id', $permission_id)
    //                 ->first();
    
    //             if (!$existingEntry) {
    //                 // If the entry doesn't exist, insert it
    //                 DB::table('role_has_permissions')->insert([
    //                     'role_id' => $role_id,
    //                     'permission_id' => $permission_id,
    //                 ]);
    //             }         
    //         }

    //         $role_has_permissions = DB::table('role_has_permissions')->get();
    //         foreach ($role_has_permissions as $row) {
    //             if($row->role_id == $role_id){
    //                 $recordKey = $row->permission_id . ',' . $row->role_id;
    //                 // If the record is in db and not in the permissions array
    //                 if (!in_array($recordKey, $permissions)) {
    //                     DB::table('role_has_permissions')
    //                     ->where('role_id', $row->role_id)
    //                     ->where('permission_id', $row->permission_id)
    //                     ->delete(); 
    //                 }
    //             }
    //         }
    //     }
        

        

    //     return redirect()->back()->with('success', 'The role assigned to permissions successfully');
    // }

    public function assignRoleToPermissions(Request $request,$role_id)
    {
        // dd($request,$role_id);
        $permissions = $request->permission;
        $permission_id = '';
        // $role_id = '';
        // dd($permissions);
        if($permissions){
            foreach ($permissions as $item) {
                $values = explode(',', $item);
                $permission_id = $values[0];
                // $role_id = $values[1];
    
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
        

        

        return redirect()->back()->with('success', 'The role assigned to permissions successfully');
    }


    public function assignUserToRoles(Request $request, $userId)
    {
        $newRoles = $request->roles;

        // Fetch existing roles assigned to the user from db
        $existingRoles = DB::table('user_has_roles')
            ->where('user_id', $userId)
            ->pluck('role_id') // get column of role_id only
            ->toArray();

        $rolesToInsert = array_diff($newRoles, $existingRoles); // Find roles to insert (in the request but not in the database)
        
        $rolesToDelete = array_diff($existingRoles, $newRoles); // Find roles to delete (in the database but not in the request)

        foreach ($rolesToInsert as $role) { // Insert new roles
            DB::table('user_has_roles')
                ->insert([
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

        return redirect()->back()->with('rolePermission', 'Roles assigned to the user successfully.');
    }
}
