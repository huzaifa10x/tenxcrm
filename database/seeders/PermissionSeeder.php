<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Define demo permissions
        $permissions = [
            'can manage users',
            'can view users',
            'can manage tasks',
            'can view tasks',
            'can manage projects',
            'can view projects',
            'can manage SEO tasks',
            'can view SEO tasks',
        ];

        // Create permissions
        foreach ($permissions as $permissionName) {
            Permission::updateOrCreate(['name' => $permissionName]);
        }

        // Assign permissions to roles
        $rolePermissions = [
            'ADMIN' => ['can manage users', 'can view users', 'can manage tasks', 'can view tasks', 'can manage projects', 'can view projects'],
            'CONTENT' => ['can view tasks', 'can view projects'],
            'ONSITE' => ['can view tasks'],
            'OFF SITE' => ['can view tasks'],
            'DESIGN' => ['can view tasks'],
            'DEVELOPMENT' => ['can manage tasks', 'can view tasks'],
            'SEO ADMIN' => ['can manage SEO tasks', 'can view SEO tasks'],
            'AM' => ['can view projects', 'can view tasks'],
            'SM' => ['can view projects'],
            'SALES' => ['can view projects'],
        ];

        foreach ($rolePermissions as $roleName => $permissions) {
            $role = Role::where('name', $roleName)->first();

            if ($role) {
                $role->permissions()->sync(
                    Permission::whereIn('name', $permissions)->pluck('id')
                );
            }
        }
    }
}
