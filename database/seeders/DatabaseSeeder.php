<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use SeoTaskSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            CompaniesTableSeeder::class,
            IndustrySeeder::class,
            CompnayDetailTableSeeder::class,
            PaymentMethodsSeeder::class,
            ProjectSeeder::class,
            TaskSeeder::class,
        ]);
    }
}
