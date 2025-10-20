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
        // Log::info('Weekly task summary command started.');

        $users = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['SEO ADMIN', 'AM']);
        })
            ->whereHas('tasks', function ($query) {
                $query->where('start_date', '<=', Carbon::now());
            })
            ->get();

        foreach ($users as $user) {
            $todoCount = Task::where('assigned_to', $user->id)->where('status', 'Todo')->visibleTasks()->count();
            $inProgressCount = Task::where('assigned_to', $user->id)->where('status', 'In Progress')->visibleTasks()->count();
            $reviewCount = Task::where('assigned_to', $user->id)->where('status', 'Review')->visibleTasks()->count();
            if ($todoCount + $inProgressCount + $reviewCount > 0) {
                // Log::info("Weekly summary sent to {$user->email} with Todo: {$todoCount}, In Progress: {$inProgressCount}, Review: {$reviewCount}");
                Mail::to($user->email)
                    ->cc('muhammad.ali@10xdigital.ae')
                    ->send(new WeeklyTaskSummaryMail(
                        $user,
                        $todoCount,
                        $inProgressCount,
                        $reviewCount
                    ));
                // Log::info("Weekly summary sent to {$user->email}");
            }
        }
        // Log::info('Weekly task summary emails sent successfully.');
    }
}
