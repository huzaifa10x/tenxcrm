<?php

/*
|--------------------------------------------------------------------------
| Scheduled task & renewal emails
|--------------------------------------------------------------------------
| Every value has a built-in default in App\Support\ReminderSettings, so the
| emails keep working even if this file is missing or the config is cached.
*/

return [
    // tasks.due_date is a DATE column, so this is the time of day a task is "due".
    'due_time' => env('TASK_DUE_TIME', '18:00'),

    // Due-date reminders: hours before the due moment.
    'reminder_hours' => [48, 24],

    // Overdue notices are only sent for tasks that went overdue within this many days
    // (stops a flood of emails for old, stale tasks the first time this runs).
    'overdue_lookback_days' => (int) env('TASK_OVERDUE_LOOKBACK_DAYS', 14),

    // Escalate to the assignee's lead once a task is this many days overdue and still open.
    'escalation_threshold_days' => (int) env('TASK_ESCALATION_DAYS', 2),

    // Used when the assignee has no lead in account_manager_leads. Leave empty to skip.
    'escalation_fallback_email' => env('TASK_ESCALATION_FALLBACK_EMAIL'),

    // Daily digest: how far ahead "upcoming" looks, and how many rows per section.
    'digest_upcoming_days' => 7,
    'digest_max_items' => 10,

    // Renewal reminders: [stage => [earliest day, latest day]] before the renewal date.
    // The window is a few days wide so a missed scheduler run still sends the email.
    'renewal_windows' => [
        60 => [54, 60],
        30 => [24, 30],
    ],

    // Monthly value at or above which an account is flagged high-value (AED).
    'high_value_mrr' => 5000,
];
