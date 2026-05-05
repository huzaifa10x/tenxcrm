<?php

namespace App\Jobs\Xero;

use App\Services\Xero\XeroSyncService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class XeroFullSyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 3600;
    public int $tries   = 1;

    public function __construct()
    {
        $this->onQueue('xero');
    }

    public function handle(XeroSyncService $syncService): void
    {
        Log::info('Xero: Full sync job started.');
        $results = $syncService->fullSync();
        Log::info('Xero: Full sync job completed.', $results);
    }
}