<?php

namespace App\Jobs\Xero;

use App\Services\Xero\XeroSyncService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class XeroIncrementalSyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 600;
    public int $tries   = 3;
    public int $backoff = 60;

    public function __construct()
    {
        $this->onQueue('xero');
    }

    public function handle(XeroSyncService $syncService): void
    {
        Log::info('Xero: Incremental sync job started.');
        $results = $syncService->incrementalSync();
        Log::info('Xero: Incremental sync job completed.', $results);
    }
}