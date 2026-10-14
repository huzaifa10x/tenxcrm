<?php

namespace App\Support;

/**
 * Settings for scheduled task / renewal emails, with defaults that apply
 * even when config/task_emails.php is missing or the config cache is stale.
 */
class ReminderSettings
{
    public const TIMEZONE = 'Asia/Dubai';
    public const TIMEZONE_LABEL = 'GST';

    /** Task statuses that count as "still open". */
    public const OPEN_STATUSES = ['Todo', 'In Progress', 'Review'];

    /** Projects in these statuses get no task reminders and no renewal emails. */
    public const INACTIVE_PROJECT_STATUSES = ['Paused', 'Expired', 'Cancelled', 'Completed'];

    public const SYSTEM_EMAIL = 'system@10xdigital.ae';

    private const DEFAULTS = [
        'due_time' => '18:00',
        'reminder_hours' => [48, 24],
        'overdue_lookback_days' => 14,
        'escalation_threshold_days' => 2,
        'escalation_fallback_email' => null,
        'digest_upcoming_days' => 7,
        'digest_max_items' => 10,
        'renewal_windows' => [60 => [54, 60], 30 => [24, 30]],
        'high_value_mrr' => 5000,
    ];

    public static function get(string $key)
    {
        $value = config('task_emails.' . $key);

        return ($value === null || $value === '') ? self::DEFAULTS[$key] : $value;
    }
}
