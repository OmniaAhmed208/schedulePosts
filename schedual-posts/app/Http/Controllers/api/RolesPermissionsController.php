<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesPermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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

            if ($existingEntry) 
            {
                $roleName = Role::where('id',$role_id)->first()->name;
                $permissionName = Permission::where('id',$permission_id)->first()->name;
                return response()->json([
                    'message' => "This role $roleName assigned to this permission $permissionName before",
                    'status' => true
                ],200);
            }

            DB::table('role_has_permissions')->create([
                'role_id' => $role_id,
                'permission_id' => $permission_id,
            ]);

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

        return response()->json([
            'message' => "This role assigned to this permission successfully",
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
