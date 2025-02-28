<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Jobs\CheckNewNotifications;

class CheckNewNotificationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for new unread notifications and dispatch jobs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();
        foreach ($users as $user) {
            dispatch(new CheckNewNotifications($user->id));
        }

        $this->info('Notification check jobs dispatched successfully.');
    }
}
