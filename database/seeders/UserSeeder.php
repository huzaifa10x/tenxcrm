<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        $faker = Faker::create();

        $users = [
            [
                'name' => 'ADMIN',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('12345678'),
                'phone' => '1234567890',
                'role' => 'ADMIN',
            ],
            [
                'name' => 'IQRA',
                'email' => 'iqra@10xdigital.ae',
                'password' => Hash::make('password'),
                'phone' => '1234567890',
                'role' => 'CONTENT',
            ],
            [
                'name' => 'ABDULLAH',
                'email' => 'abdullah@10xdigital.ae',
                'password' => Hash::make('password'),
                'phone' => '1234567890',
                'role' => 'ONSITE',
            ],
            [
                'name' => 'RUBAB',
                'email' => 'rubab@10xdigital.ae',
                'password' => Hash::make('password'),
                'phone' => '1234567890',
                'role' => 'OFF SITE',
            ],
            [
                'name' => 'BAQAR',
                'email' => 'baqar@10xdigital.ae',
                'password' => Hash::make('password'),
                'phone' => '1234567890',
                'role' => 'OFF SITE',
            ],
            [
                'name' => 'FASIH',
                'email' => 'fasih@10xdigital.ae',
                'password' => Hash::make('password'),
                'phone' => '1234567890',
                'role' => 'DESIGN',
            ],
            [
                'name' => 'FAIZAN',
                'email' => 'faizan@10xdigital.ae',
                'password' => Hash::make('password'),
                'phone' => '1234567890',
                'role' => 'DEVELOPMENT',
            ],
            [
                'name' => 'TAYYAB',
                'email' => 'tayyab@10xdigital.ae',
                'password' => Hash::make('password'),
                'phone' => '1234567890',
                'role' => 'DEVELOPMENT',
            ],
            [
                'name' => 'HUZAIFA',
                'email' => 'huzaifa@10xdigital.ae',
                'password' => Hash::make('password'),
                'phone' => '1234567890',
                'role' => 'DEVELOPMENT',
            ],
            [
                'name' => 'WAQAR',
                'email' => 'waqar@10xdigital.ae',
                'password' => Hash::make('password'),
                'phone' => '1234567890',
                'role' => 'SEO ADMIN',
            ],
            [
                'name' => 'ADNAN',
                'email' => 'adnan@10xdigital.ae',
                'password' => Hash::make('password'),
                'phone' => '1234567890',
                'role' => 'AM',
            ],
            [
                'name' => 'FILZA',
                'email' => 'filza@10xdigital.ae',
                'password' => Hash::make('password'),
                'phone' => '1234567890',
                'role' => 'AM',
            ],
            [
                'name' => 'RAHUL',
                'email' => 'rahul@10xdigital.ae',
                'password' => Hash::make('password'),
                'phone' => '1234567890',
                'role' => 'AM',
            ],
            [
                'name' => 'SAMMAR',
                'email' => 'sammar@10xdigital.ae',
                'password' => Hash::make('password'),
                'phone' => '1234567890',
                'role' => 'AM',
            ],
            [
                'name' => 'RAKHSHAAN',
                'email' => 'rakhshaan@10xdigital.ae',
                'password' => Hash::make('password'),
                'phone' => '1234567890',
                'role' => 'SM',
            ],
            [
                'name' => 'BILAL',
                'email' => 'bilal@10xdigital.ae',
                'password' => Hash::make('password'),
                'phone' => '1234567890',
                'role' => 'ADMIN',
            ],
            [
                'name' => 'KHURRAM',
                'email' => 'khurram@10xdigital.ae',
                'password' => Hash::make('password'),
                'phone' => '1234567890',
                'role' => 'ADMIN',
            ],
            [
                'name' => 'ALI',
                'email' => 'ali@10xdigital.ae',
                'password' => Hash::make('password'),
                'phone' => '1234567890',
                'role' => 'ADMIN',
            ],
            [
                'name' => 'ANEES',
                'email' => 'anees@10xdigital.ae',
                'password' => Hash::make('password'),
                'phone' => '1234567890',
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
                        'phone' => $faker->phoneNumber,
                    ]
                );
                $user->roles()->sync([$role->id]);
            }
        }
    }
}
