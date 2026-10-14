<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Writes to the notifications table directly (same as the rest of the app),
 * so the bell updates without needing the queue worker.
 */
class InAppNotification
{
    public static function send(User $user, string $title, string $message, string $url): void
    {
        DB::table('notifications')->insert([
            'id' => (string) Str::uuid(),
            'type' => \App\Notifications\CustomNotification::class,
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
            'data' => json_encode(compact('title', 'message', 'url')),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
