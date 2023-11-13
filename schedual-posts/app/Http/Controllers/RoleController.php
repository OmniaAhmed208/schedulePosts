<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:roles.add')->only(['store']);
        $this->middleware('permission:roles.edit')->only(['update']);
    }

    public function store(Request $request)
    {
        $validator = $request->validate(['role'=>'required']);

        $role = $request->role;
        $color = $request->color ?? 'dark';

        if (Role::where('name', $role)->exists()) {
            return redirect()->back()->with('error', 'The role is already exists.');
        }

        $role = Role::create([
            'name' => $role, 
            'color' => $color
        ]);

        return redirect()->back()->with('success', 'The role created successfully');
    }

    public function update(Request $request,$id)
    {
        $validator = $request->validate(['role'=>'required']);

        $role = Role::find($id);
        
        $role->update([
            'name' => $request->role, 
            'color' => $request->color ?? 'dark'
        ]);

        return redirect()->back()->with('success', 'The role updated successfully');
    }

}
