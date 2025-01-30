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
            'can view companies',
            'can manage companies',
            'can view users',
            'can manage users',
            'can view permissions',
            'can manage permissions',
            'can view customers',
            'can manage customers',
            'can view roles',
            'can manage roles',
            'can view industries',
            'can manage industries',
            'can view seo-campaign',
            'can manage seo-campaign',
            'can view google-ads-projects',
            'can manage google-ads-projects',
        ];

        // Create permissions
        foreach ($permissions as $permissionName) {
            Permission::updateOrCreate(['name' => $permissionName]);
        }
    }
}
