<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        $admin = Role::where('name', 'admin')->first();
        $seoManager = Role::where('name', 'seo_manager')->first();
        $developer = Role::where('name', 'developer')->first();
        
        // Assign permissions to the admin role
        $admin->permissions()->attach(Permission::whereIn('name', ['view_projects', 'add_projects', 'view_seo_tasks', 'edit_seo_tasks'])->get());
        
        // Assign permissions to the seo_manager role
        $seoManager->permissions()->attach(Permission::whereIn('name', ['view_seo_tasks', 'edit_seo_tasks'])->get());
        
        // Assign permissions to the developer role
        $developer->permissions()->attach(Permission::whereIn('name', ['view_projects'])->get());
    }
}
