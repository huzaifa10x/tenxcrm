<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            'CONTENT',
            'ONSITE',
            'OFF SITE',
            'DESIGN',
            'DEVELOPMENT',
            'SEO ADMIN',
            'AM',
            'SM',
            'ADMIN',
            'SALES',
            'CUSTOMER',
        ];

        foreach ($roles as $roleName) {
            Role::updateOrCreate(['name' => $roleName]);
        }
    }
}
