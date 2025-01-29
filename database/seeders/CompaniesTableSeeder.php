<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('companies')->insert([
            [
                'name' => '10X Digital',
                'website' => 'https://www.10xdigital.ae',
                'city' => 'Sharjah',
                'country' => 'Dubai',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Al Emad',
                'website' => 'https://www.al-emad.ae',
                'city' => 'Sharjah',
                'country' => 'Dubai',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
