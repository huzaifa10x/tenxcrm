<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->paginate(15);
        $permissions = Permission::get();
        return view('roles.index', compact('roles', 'permissions'));
    }
    public function create()
    {
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
        ]);

        $role = Role::create([
            'name' => $request->name,
        ]);

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Role created successfully!');
    }
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        return view('roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->update(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Role updated successfully!');
    }

    public function destroy($id)
    {
        Role::findOrFail($id)->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully!');
    }

    public function updatePermissions(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'nullable|array'
        ]);

        $role = Role::findOrFail($request->role_id);
        $role->permissions()->sync($request->permissions);

        return response()->json(['success' => true, 'message' => 'Permissions updated successfully!']);
    }
}
