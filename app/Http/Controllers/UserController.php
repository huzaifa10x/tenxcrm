<?php

namespace App\Http\Controllers;

use App\Models\AccountManagerLead;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->whereDoesntHave('roles', function ($query) {
            $query->where('name', 'COMPANY');
        })->paginate(15);
        $roles = Role::get();

        return view('users.index', compact('users', 'roles'));
    }

    public function gridView()
    {
        $users = User::with('roles')->whereDoesntHave('roles', function ($query) {
            $query->where('name', 'COMPANY');
        })->paginate(15);

        return view('users.users-grid-view', compact('users'));
    }

    public function updateRoles(Request $request, $id)
    {
        $request->validate([
            'roles' => 'nullable|array',
        ]);

        $user = User::findOrFail($id);
        $user->roles()->sync($request->roles);

        return response()->json(['success' => true, 'message' => 'Roles updated successfully!']);
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:25',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:8|confirmed',
            'roles' => 'nullable|array',
            'img' => 'nullable',
        ]);

        // Update user details
        $user->update($request->only('name', 'email', 'phone'));

        // Handle image upload
        if ($request->hasFile('img')) {
            // Delete old image if exists
            if ($user->img) {
                Storage::disk('public')->delete($user->img);
            }

            // Upload new image
            $imgPath = $request->file('img')->store('user-images', 'public');
            $user->update(['img' => $imgPath]);  // Save new image path in DB
        }

        // Update password if provided
        if ($request->filled('password')) {
            $user->update(['password' => bcrypt($request->password)]);
        }

        // Update roles if provided
        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }


    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:25',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'roles' => 'nullable|array',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        $imgPath = null;
        if ($request->hasFile('img')) {
            $imgPath = $request->file('img')->store('user-images', 'public');
        }

        // Create a new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'img' => $imgPath,
        ]);

        // Assign roles if provided
        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        }

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->img && file_exists(public_path($user->img))) {
            Storage::disk('public')->delete($user->img);
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }

    public function getUsersByDepartment($department)
    {
        $users = User::whereHas('roles', function ($query) use ($department) {
            $query->where('name', $department);
        })->get();

        return response()->json($users);
    }

    public function removeMember(Request $request)
    {
        $manager = User::findOrFail($request->manager_id);
        $manager->teams()->detach($request->am_id);

        return response()->json(['status' => 'success']);
    }

    public function showTeam()
    {
        $managers = User::whereHas('roles', function ($q) {
            $q->where('role_id', 12);
        })->get();

        return view('account-manager.index', compact('managers'));
    }

    public function fetchAMUsers(Request $request)
    {
        $managerId = $request->manager_id;

        // All users with 'AM' role
        $amRoleId = 11; // or dynamically fetch
        $allUsers = User::whereHas('roles', function ($q) use ($amRoleId) {
            $q->where('roles.id', $amRoleId);
        })->get();

        // Existing team users for the selected manager
        $manager = User::find($managerId);
        $teamUsers = $manager->teams ?? [];

        return response()->json([
            'all_users' => $allUsers,
            'team_users' => $teamUsers
        ]);
    }

    public function addTeamMember(Request $request)
    {
        $request->validate([
            'am_id'      => 'required|exists:users,id',
            'manager_id' => 'required|exists:users,id',
        ]);

        AccountManagerLead::firstOrCreate([
            'am_id'      => $request->am_id,
            'manager_id' => $request->manager_id,
        ]);

        return response()->json(['status' => 'success']);
    }
}
