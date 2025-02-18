<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RepeatingTask;
use App\Models\Task;

class ProcessRepeatingTasks extends Command
{
    protected $signature = 'tasks:process-repeating';
    protected $description = 'Process tasks that need to repeat based on their interval.';

    public function handle()
    {
        $repeatingTasks = RepeatingTask::where('next_run_at', '<=', now())->get();

        foreach ($repeatingTasks as $repeatingTask) {
            // Create new task based on the repetition
            $task = $repeatingTask->task->replicate();  // Replicate the existing task
            $task->save();

            // Update the 'next_run_at' for the next repetition
            $repeatingTask->next_run_at = now()->addHours($repeatingTask->repeat_every);
            $repeatingTask->save();
        }

        $this->info('Repeating tasks processed.');
    }
}
