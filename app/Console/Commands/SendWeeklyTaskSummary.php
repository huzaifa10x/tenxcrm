<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Task;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Mail\WeeklyTaskSummaryMail;

class SendWeeklyTaskSummary extends Command
{
    protected $signature = 'tasks:weekly-summary';
    protected $description = 'Send weekly task summary to assigned users';

    public function handle()
    {
        Log::info('Weekly task summary command started.');

        $users = User::whereHas('tasks')->get();

        foreach ($users as $user) {
            $todoCount = Task::where('assigned_to', $user->id)->where('status', 'Todo')->count();
            $inProgressCount = Task::where('assigned_to', $user->id)->where('status', 'In Progress')->count();
            $reviewCount = Task::where('assigned_to', $user->id)->where('status', 'Review')->count();

            if ($todoCount + $inProgressCount + $reviewCount > 0) {
                Mail::to($user->email)
                    ->cc('muhammad.ali@10xdigital.ae')
                    ->send(new WeeklyTaskSummaryMail(
                        $user,
                        $todoCount,
                        $inProgressCount,
                        $reviewCount
                    ));

                Log::info("Weekly summary sent to {$user->email}");
            }
        }

        Log::info('Weekly task summary emails sent successfully.');
    }
}
