<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WeeklyTaskSummaryMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user, $todoCount, $inProgressCount, $reviewCount;

    public function __construct($user, $todoCount, $inProgressCount, $reviewCount)
    {
        $this->user = $user;
        $this->todoCount = $todoCount;
        $this->inProgressCount = $inProgressCount;
        $this->reviewCount = $reviewCount;
    }

    public function build()
    {
        return $this->subject('Weekly Task Summary – ' . now()->format('d M Y'))
            ->markdown('emails.tasks.weekly-summary');
    }
}
