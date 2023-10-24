<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesPermissionsController extends Controller
{
    public function index(Request $request){
        return view('AdminSocialMedia.rolePermission');
    }

    public function RoleStore(Request $request)
    {
        $roleName = $request->newRole;
        $roleColor = $request->roleColor ? $request->roleColor : 'dark';

        if (Role::where('name', $roleName)->exists()) {
            return redirect()->back()->with('rolePermission', 'The role is already exists.');
        }

        $role = Role::create([
            'name' => $roleName, 
            'role_color' => $roleColor
        ]);

        return redirect()->back()->with('rolePermission', 'The role created successfully');
    }
    
    public function RoleUpdate(Request $request)
    {
        $roleID = $request->roleId; 
        $roleName = $request->roleName;
        $roleColor = $request->roleColor ? $request->roleColor : 'dark';
        
        Role::where('id', $roleID)->update([
            'name' => $roleName, 
            'role_color' => $roleColor
        ]);

        return redirect()->back()->with('rolePermission', 'The role updated successfully');
    }

    public function PermissionStore(Request $request)
    {
        $rolePermission = $request->newPermission;

        if (Permission::where('name', $rolePermission)->exists()) {
            return redirect()->back()->with('rolePermission', 'The permission is already exists.');
        }
        $permission = Permission::create(['name' => $rolePermission]);
        return redirect()->back()->with('rolePermission', 'The permission created successfully');
    }

    public function RolePermissionStore(Request $request)
    {
        // dd($request);
        $permissions = $request->permission;
        $permission_id = '';
        $role_id = '';
        
        foreach ($permissions as $item) {
            $values = explode(',', $item);
            $permission_id = $values[0];
            $role_id = $values[1];

            // Check if the entry already exists in the table
            $existingEntry = DB::table('role_has_permissions')
                ->where('role_id', $role_id)
                ->where('permission_id', $permission_id)
                ->first();

            if ($existingEntry) {
                $roleName = Role::where('id',$role_id)->first()->name;
                $permissionName = Permission::where('id',$permission_id)->first()->name;
                // return redirect()->back()->with('rolePermission', "This role '$roleName' assigned to this permission '$permissionName' before");
            }
           else{
                // If the entry doesn't exist, insert it
                DB::table('role_has_permissions')->insert([
                    'role_id' => $role_id,
                    'permission_id' => $permission_id,
                ]);
           }

           
        }

        $role_has_permissions = DB::table('role_has_permissions')->get();
        foreach ($role_has_permissions as $row) {
            $recordKey = $row->permission_id . ',' . $row->role_id;
            
            // If the record is in db and not in the permissions array
            if (!in_array($recordKey, $permissions)) {
                DB::table('role_has_permissions')
                ->where('role_id', $row->role_id)
                ->where('permission_id', $row->permission_id)
                ->delete(); 
            }
        }

        return redirect()->back()->with('rolePermission', 'The role assigned to permissions successfully');
    }

    public function rolePermissionUser(Request $request, $userId)
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
