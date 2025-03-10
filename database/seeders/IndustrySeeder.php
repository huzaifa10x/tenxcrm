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
            'E Commerce',
            'Automotive',
            'HealthCare',
            'B2B Marketing',
            'Education',
            'Real Estate',
            'Travel',
            'Finance',
        ];

        // Seed the industries
        foreach ($industries as $industry) {
            Industry::create([
                'industry' => $industry
            ]);
        }
    }
}
