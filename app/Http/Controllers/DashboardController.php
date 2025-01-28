<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $roles = $user->roles;
        $permissions = [];
        foreach ($roles as $role) {
            foreach ($role->permissions as $permission) {
                $permissions[] = $permission->name;
            }
        }
        $permissions = array_unique($permissions);

        $demoData = [
            'ADMIN' => [
                'title' => 'Admin Dashboard',
                'description' => 'Admin has access to everything.',
                'cards' => [
                    ['title' => 'Manage Users', 'content' => 'Create, update, and delete users.'],
                    ['title' => 'Manage Projects', 'content' => 'Create and manage projects.'],
                    ['title' => 'Manage Tasks', 'content' => 'Create and manage tasks.'],
                    ['title' => 'View SEO Tasks', 'content' => 'Monitor SEO-related tasks.'],
                ]
            ],
            'SEO ADMIN' => [
                'title' => 'SEO ADMIN Dashboard',
                'description' => 'SEO ADMIN has access Seo things.',
                'cards' => [
                    ['title' => 'Manage Users', 'content' => 'Create, update, and delete users.'],
                    ['title' => 'Manage Projects', 'content' => 'Create and manage projects.'],
                    ['title' => 'Manage Tasks', 'content' => 'Create and manage tasks.'],
                    ['title' => 'View SEO Tasks', 'content' => 'Monitor SEO-related tasks.'],
                ]
            ],
            'DEVELOPER' => [
                'title' => 'Developer Dashboard',
                'description' => 'Developer can view and work on assigned tasks and projects.',
                'cards' => [
                    ['title' => 'View Assigned Projects', 'content' => 'See your assigned projects.'],
                    ['title' => 'View Assigned Tasks', 'content' => 'See tasks assigned to you.'],
                ]
            ],
            'SEO' => [
                'title' => 'SEO Dashboard',
                'description' => 'SEO Admin can manage and track SEO tasks.',
                'cards' => [
                    ['title' => 'Manage SEO Tasks', 'content' => 'Track SEO-related tasks and projects.'],
                    ['title' => 'View SEO Reports', 'content' => 'Analyze SEO reports and progress.'],
                ]
            ],
            // More roles can be added as necessary...
        ];

        // Get the role of the logged-in user
        $role = $user->roles->first()->name;

        // Fetch the demo data based on the user role
        $roleData = $demoData[$role] ?? [];

        return view('dashboard', [
            'roleData' => $roleData,
            'permissions' => $permissions,
        ]);
    }
}
