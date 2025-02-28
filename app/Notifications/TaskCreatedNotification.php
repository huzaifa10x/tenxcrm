<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;

class TaskCreatedNotification extends Notification
{
    use Queueable;

    protected $task;

    /**
     * Create a new notification instance.
     */
    public function __construct($task)
    {
        $this->task = $task;
    }

    /**
     * Specify the delivery channels.
     */
    public function via($notifiable)
    {
        return ['database']; // Store in the database
    }

    /**
     * Get the notification data to store in the database.
     */
    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Task Created',
            'message' => 'A new task "' . $this->task->title . '" has been created.',
            'task_id' => $this->task->id,
            'created_by' => auth()->user()->name ?? 'System',
        ];
    }
}
