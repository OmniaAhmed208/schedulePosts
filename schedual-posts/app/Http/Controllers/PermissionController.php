<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:roles.permissions.add')->only(['store']);
        $this->middleware('permission:roles.permissions.edit')->only(['update']);
    }

    public function store(Request $request)
    {
        $validator = $request->validate(['permission'=>'required']);

        $permission = $request->permission;

        if (Permission::where('name', $permission)->exists()) {
            return redirect()->back()->with('error', 'The permission is already exists.');
        }

        Permission::create(['name' => $permission]);

        return redirect()->back()->with('success', 'The permission created successfully');
    }

    public function update(Request $request,$id)
    {
        $validator = $request->validate(['permission'=>'required']);

        $permission = Permission::find($id);
        
        $permission->update([
            'name' => $request->permission
        ]);

        return redirect()->back()->with('success', 'The permission updated successfully');
    }
}
