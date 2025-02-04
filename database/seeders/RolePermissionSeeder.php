<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Define Permissions
        $permissions = [
            'can view companies',
            'can manage companies',
            'can view users',
            'can manage users',
            'can view permissions',
            'can view customers',
            'can manage customers',
            'can view roles',
            'can manage roles',
            'can view industries',
            'can manage industries',
            'can view payment methods',
            'can manage payment methods',
            'can view all projects',
            'can view own projects',
            'can edit projects',
            'can add projects',
            'can add project files',
            'can remove project files',
            'can update project status',
        ];

        // Define Roles
        $roles = [
            'ADMIN',
            'CONTENT',
            'ONSITE',
            'OFF SITE',
            'DESIGN',
            'DEVELOPMENT',
            'SEO ADMIN',
            'SALES',
            'TECH MANAGER',
            'ACCOUNT MANAGER',
            'SALES MANAGER',
            'SOCIAL MEDIA MANAGER',
            'FINANCE',
        ];

        // Insert Permissions
        foreach ($permissions as $permission) {
            DB::table('permissions')->insert([
                'name' => $permission,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Insert Roles
        foreach ($roles as $role) {
            DB::table('roles')->insert([
                'name' => $role,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Assign all permissions to the ADMIN role
        $adminRole = DB::table('roles')->where('name', 'ADMIN')->first();
        $allPermissions = DB::table('permissions')->get();

        foreach ($allPermissions as $permission) {
            DB::table('permission_role')->insert([
                'role_id' => $adminRole->id,
                'permission_id' => $permission->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
