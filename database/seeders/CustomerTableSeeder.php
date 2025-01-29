<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CustomerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('customers')->insert([
            [
                'name' => 'demo customer',
                'email' => 'demo@10xdigital.ae',
                'phone' => '03123454545',
                'city' => 'Sharjah',
                'website' => 'https://www.10xdigital.ae',
                'company_id' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], 
        ]);
    }
}
