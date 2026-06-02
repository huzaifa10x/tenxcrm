<?php

namespace Database\Seeders;

use App\Models\Crm\Pipeline;
use App\Models\Crm\Stage;
use Illuminate\Database\Seeder;

class CrmSeeder extends Seeder
{
    public function run(): void
    {
        // Default pipeline mirroring the current Pipedrive setup
        $pipeline = Pipeline::firstOrCreate(
            ['name' => 'Sales Pipeline'],
            ['position' => 0, 'is_default' => true]
        );

        $stages = [
            ['name' => 'New Leads',      'color' => '#fa5252', 'is_won' => false, 'is_lost' => false, 'rotting_days' => 14],
            ['name' => 'Not Answering',  'color' => '#e8590c', 'is_won' => false, 'is_lost' => false, 'rotting_days' => 7],
            ['name' => 'Engaged',        'color' => '#1971c2', 'is_won' => false, 'is_lost' => false, 'rotting_days' => 10],
            ['name' => 'Meeting Booked', 'color' => '#7048e8', 'is_won' => false, 'is_lost' => false, 'rotting_days' => 10],
            ['name' => 'Negotiation',    'color' => '#f08c00', 'is_won' => false, 'is_lost' => false, 'rotting_days' => 14],
            ['name' => 'Closed WON',     'color' => '#2f9e44', 'is_won' => true,  'is_lost' => false, 'rotting_days' => null],
            ['name' => 'Reactivated',    'color' => '#0ca678', 'is_won' => false, 'is_lost' => false, 'rotting_days' => 30],
            ['name' => 'Lost',           'color' => '#868e96', 'is_won' => false, 'is_lost' => true,  'rotting_days' => null],
            ['name' => 'SPAM',           'color' => '#212529', 'is_won' => false, 'is_lost' => true,  'rotting_days' => null],
        ];

        foreach ($stages as $i => $stage) {
            Stage::firstOrCreate(
                ['pipeline_id' => $pipeline->id, 'name' => $stage['name']],
                array_merge($stage, ['position' => $i])
            );
        }
    }
}
