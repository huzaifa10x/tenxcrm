<?php

namespace App\Models\Xero;

use Illuminate\Database\Eloquent\Model;

class XeroSyncLog extends Model
{
    protected $table = 'xero_sync_logs';

    protected $fillable = [
        'entity', 'sync_type', 'status',
        'started_at', 'completed_at', 'last_modified_since',
        'records_fetched', 'records_created', 'records_updated',
        'records_failed', 'error_message',
    ];

    protected $casts = [
        'started_at'          => 'datetime',
        'completed_at'        => 'datetime',
        'last_modified_since' => 'datetime',
    ];
}