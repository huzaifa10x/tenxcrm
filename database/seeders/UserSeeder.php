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
                'name' => 'Muhammad Ali',
                'email' => 'muhammad.ali@10xdigital.ae',
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
                'role' => 'OFFSITE',
            ],
            [
                'name' => 'BAQAR',
                'email' => 'baqar@10xdigital.ae',
                'password' => Hash::make('password'),
                'phone' => '1234567890',
                'role' => 'OFFSITE',
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
                'role' => 'SALES MANAGER',
            ],
            [
                'name' => 'RAKHSHAAN',
                'email' => 'rakhshaan@10xdigital.ae',
                'password' => Hash::make('password'),
                'phone' => '1234567890',
                'role' => 'SOCIAL MEDIA MANAGER',
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
                'name' => 'ANEES',
                'email' => 'anees@10xdigital.ae',
                'password' => Hash::make('password'),
                'phone' => '1234567890',
                'role' => 'SALES MANAGER',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $userData['password'],
                    'phone' => $faker->phoneNumber,
                ]
            );
            if ($userData['role'] == 'ADMIN') {
                $user->roles()->sync(Role::where('name', 'ADMIN')->first());
            } elseif ($userData['role'] == 'CONTENT') {
                $user->roles()->sync(Role::where('name', 'CONTENT')->first());
            } elseif ($userData['role'] == 'ONSITE') {
                $user->roles()->sync(Role::where('name', 'ONSITE')->first());
            } elseif ($userData['role'] == 'OFF SITE') {
                $user->roles()->sync(Role::where('name', 'OFFSITE')->first());
            } elseif ($userData['role'] == 'DESIGN') {
                $user->roles()->sync(Role::where('name', 'DESIGN')->first());
            } elseif ($userData['role'] == 'DEVELOPMENT') {
                $user->roles()->sync(Role::where('name', 'DEVELOPMENT')->first());
            } elseif ($userData['role'] == 'SEO ADMIN') {
                $user->roles()->sync(Role::where('name', 'SEO ADMIN')->first());
            } elseif ($userData['role'] == 'ACCOUNT MANAGER') {
                $user->roles()->sync(Role::where('name', 'AM')->first());
            } elseif ($userData['role'] == 'SALES MANAGER') {
                $user->roles()->sync(Role::where('name', 'SOCIAL MEDIA MANAGER')->first());
            } elseif ($userData['role'] == 'SALES MANAGER') {
                $user->roles()->sync(Role::where('name', 'SALES MANAGER')->first());
            } elseif ($userData['role'] == 'SALES') {
                $user->roles()->sync(Role::where('name', 'SALES')->first());
            }
        }
    }
}
