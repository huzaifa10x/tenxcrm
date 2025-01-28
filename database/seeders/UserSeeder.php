<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $users = [
            [
                'name' => 'IQRA',
                'email' => 'iqra@example.com',
                'password' => Hash::make('password'),
                'role' => 'CONTENT',
            ],
            [
                'name' => 'ABDULLAH',
                'email' => 'abdullah@example.com',
                'password' => Hash::make('password'),
                'role' => 'ONSITE',
            ],
            [
                'name' => 'RUBAB',
                'email' => 'rubab@example.com',
                'password' => Hash::make('password'),
                'role' => 'OFF SITE',
            ],
            [
                'name' => 'BAQAR',
                'email' => 'baqar@example.com',
                'password' => Hash::make('password'),
                'role' => 'OFF SITE',
            ],
            [
                'name' => 'FASIH',
                'email' => 'fasih@example.com',
                'password' => Hash::make('password'),
                'role' => 'DESIGN',
            ],
            [
                'name' => 'FAIZAN',
                'email' => 'faizan@example.com',
                'password' => Hash::make('password'),
                'role' => 'DEVELOPMENT',
            ],
            [
                'name' => 'TAYYAB',
                'email' => 'tayyab@example.com',
                'password' => Hash::make('password'),
                'role' => 'DEVELOPMENT',
            ],
            [
                'name' => 'HUZAIFA',
                'email' => 'huzaifa@example.com',
                'password' => Hash::make('password'),
                'role' => 'DEVELOPMENT',
            ],
            [
                'name' => 'WAQAR',
                'email' => 'waqar@example.com',
                'password' => Hash::make('password'),
                'role' => 'SEO ADMIN',
            ],
            [
                'name' => 'ADNAN',
                'email' => 'adnan@example.com',
                'password' => Hash::make('password'),
                'role' => 'AM',
            ],
            [
                'name' => 'FILZA',
                'email' => 'filza@example.com',
                'password' => Hash::make('password'),
                'role' => 'AM',
            ],
            [
                'name' => 'RAHUL',
                'email' => 'rahul@example.com',
                'password' => Hash::make('password'),
                'role' => 'AM',
            ],
            [
                'name' => 'SAMMAR',
                'email' => 'sammar@example.com',
                'password' => Hash::make('password'),
                'role' => 'AM',
            ],
            [
                'name' => 'RAKHSHAAN',
                'email' => 'rakhshaan@example.com',
                'password' => Hash::make('password'),
                'role' => 'SM',
            ],
            [
                'name' => 'BILAL',
                'email' => 'bilal@example.com',
                'password' => Hash::make('password'),
                'role' => 'ADMIN',
            ],
            [
                'name' => 'KHURRAM',
                'email' => 'khurram@example.com',
                'password' => Hash::make('password'),
                'role' => 'ADMIN',
            ],
            [
                'name' => 'ALI',
                'email' => 'ali@example.com',
                'password' => Hash::make('password'),
                'role' => 'ADMIN',
            ],
            [
                'name' => 'ANEES',
                'email' => 'anees@example.com',
                'password' => Hash::make('password'),
                'role' => 'SALES',
            ],
        ];

        foreach ($users as $userData) {
            $role = Role::where('name', $userData['role'])->first();

            if ($role) {
                $user = User::updateOrCreate(
                    ['email' => $userData['email']],
                    [
                        'name' => $userData['name'],
                        'password' => $userData['password'],
                    ]
                );
                $user->roles()->sync([$role->id]);
            }
        }
    }
}
