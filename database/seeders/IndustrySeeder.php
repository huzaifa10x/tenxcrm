<?php

namespace Database\Seeders;

use App\Models\Industry;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IndustrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Define default industries
        $industries = [
            'Advertising',
            'Aerospace & Defense',
            'Agricultural Inputs',
            'Airlines',
            'Airports & Air Services',
            'Aluminum',
            'Apparel Manufacturing',
            'Apparel Retail',
            'IT',
            'Software House',
        ];

        // Seed the industries
        foreach ($industries as $industry) {
            Industry::create([
                'industry' => $industry
            ]);
        }
    }
}
