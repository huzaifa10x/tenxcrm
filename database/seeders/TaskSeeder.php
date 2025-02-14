<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        foreach ($users as $user) {
            Task::create([
                'project_id' => 1,
                'title' => 'System Testing Task',
                'description' => 'This is an automatically assigned task for system testing.',
                'department_id' => 1,
                'created_by' => 1,
                'assigned_to' => $user->id,
                'priority' => "Medium",
                'type' => 'ADHOC',
                'status' => 'Completed',
                'due_date' => now(),
                'start_date' => now(),
                'completed_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}


