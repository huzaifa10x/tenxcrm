<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * One row per scheduled email actually sent, so reminders never go out twice.
 */
class EmailReminderLog extends Model
{
    public $timestamps = false;

    protected $fillable = ['kind', 'subject_type', 'subject_id', 'reference', 'recipient_id', 'sent_at'];

    protected $casts = ['sent_at' => 'datetime'];

    /**
     * Atomically reserve a send. Returns false if it was already sent (or another
     * scheduler run claimed it first), true if this caller should send it.
     */
    public static function claim(string $kind, string $subjectType, int $subjectId, string $reference, ?int $recipientId = null): bool
    {
        return static::query()->insertOrIgnore([
            'kind' => $kind,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'reference' => $reference,
            'recipient_id' => $recipientId,
            'sent_at' => now(),
        ]) === 1;
    }

    public static function wasSent(string $kind, string $subjectType, int $subjectId, string $reference): bool
    {
        return static::query()->where([
            'kind' => $kind,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'reference' => $reference,
        ])->exists();
    }

    /** Undo a claim when sending failed, so the next run retries. */
    public static function release(string $kind, string $subjectType, int $subjectId, string $reference): void
    {
        static::query()->where([
            'kind' => $kind,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'reference' => $reference,
        ])->delete();
    }
}
