<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CompnayDetailTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('company_details')->insert([
            [
                'name' => 'Roma',
                'email' => 'demo@10xdigital.ae',
                'phone' => '03123454545',
                'city' => 'Sharjah',
                'company_id' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], 
        ]);
    }
}
