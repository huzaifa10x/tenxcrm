<?php

namespace App\Services\Xero;

use App\Models\Xero\XeroClient;
use App\Models\Xero\XeroInvoice;
use App\Models\Xero\XeroServiceMapping;
use App\Models\Xero\XeroSyncLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class XeroSyncService
{
    public function __construct(
        private readonly XeroApiClient           $api,
        private readonly AnalyticsSnapshotService $snapshot,
    ) {}

    // ── Public entry points ────────────────────────────────────────

    public function fullSync(): array
    {
        Log::info('Xero: Starting FULL sync.');

        $results             = [];
        $results['contacts'] = $this->syncContacts(null, 'full');
        $results['items']    = $this->syncItems();
        $results['invoices'] = $this->syncInvoices(null, 'full');

        $this->snapshot->rebuildAll();

        Log::info('Xero: FULL sync complete.', $results);
        return $results;
    }

    public function incrementalSync(): array
    {
        Log::info('Xero: Starting INCREMENTAL sync.');

        $lastContactSync = $this->getLastSyncTimestamp('contacts');
        $lastInvoiceSync = $this->getLastSyncTimestamp('invoices');

        $results             = [];
        $results['contacts'] = $this->syncContacts($lastContactSync, 'incremental');
        $results['items']    = $this->syncItems();
        $results['invoices'] = $this->syncInvoices($lastInvoiceSync, 'incremental');

        if ($results['invoices']['records_fetched'] > 0) {
            $this->snapshot->rebuildRecentMonths(3);
        }

        Log::info('Xero: INCREMENTAL sync complete.', $results);
        return $results;
    }

    // ── Contacts ───────────────────────────────────────────────────

    private function syncContacts(?Carbon $since, string $syncType): array
    {
        $log = XeroSyncLog::create([
            'entity'     => 'contacts',
            'sync_type'  => $syncType,
            'status'     => 'running',
            'started_at' => now(),
        ]);

        $created = $updated = $failed = 0;
        $contacts = [];

        try {
            $contacts = $this->api->fetchAll(
                'Contacts',
                ['where' => 'IsCustomer=true'],
                'Contacts',
                $since ? $since->toRfc7231String() : null
            );

            foreach ($contacts as $contact) {
                try {
                    $existing = XeroClient::where('xero_contact_id', $contact['ContactID'])->first();
                    XeroClient::upsertFromXero($contact);
                    $existing ? $updated++ : $created++;
                } catch (\Exception $e) {
                    $failed++;
                    Log::error("Xero: Contact sync failed for {$contact['ContactID']}: " . $e->getMessage());
                }
            }

            $log->update([
                'status'              => 'completed',
                'completed_at'        => now(),
                'last_modified_since' => now(),
                'records_fetched'     => count($contacts),
                'records_created'     => $created,
                'records_updated'     => $updated,
                'records_failed'      => $failed,
            ]);
        } catch (\Exception $e) {
            $log->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
            throw $e;
        }

        return compact('created', 'updated', 'failed') + ['records_fetched' => count($contacts)];
    }

    // ── Items ──────────────────────────────────────────────────────

    private function syncItems(): array
    {
        try {
            $response = $this->api->get('Items');
            $items    = $response['Items'] ?? [];

            XeroServiceMapping::seedFromXeroItems($items);

            Log::info('Xero: Items synced — ' . count($items) . ' items.');
            return ['records_fetched' => count($items)];
        } catch (\Exception $e) {
            Log::error('Xero: Items sync failed: ' . $e->getMessage());
            return ['records_fetched' => 0, 'error' => $e->getMessage()];
        }
    }

    // ── Invoices ───────────────────────────────────────────────────

    private function syncInvoices(?Carbon $since, string $syncType): array
    {
        $log = XeroSyncLog::create([
            'entity'     => 'invoices',
            'sync_type'  => $syncType,
            'status'     => 'running',
            'started_at' => now(),
        ]);

        $created = $updated = $failed = 0;
        $invoices = [];

        try {
            $invoices = $this->api->fetchAll(
                'Invoices',
                ['Type' => 'ACCREC'],
                'Invoices',
                $since ? $since->toRfc7231String() : null
            );

            foreach ($invoices as $inv) {
                try {
                    DB::transaction(function () use ($inv, &$created, &$updated) {
                        $contactId = $inv['Contact']['ContactID'] ?? null;
                        if (!$contactId) return;

                        $client = XeroClient::firstOrCreate(
                            ['xero_contact_id' => $contactId],
                            [
                                'name'   => $inv['Contact']['Name'] ?? 'Unknown',
                                'status' => 'ACTIVE',
                            ]
                        );

                        $existing = XeroInvoice::where('xero_invoice_id', $inv['InvoiceID'])->exists();
                        XeroInvoice::upsertFromXero($inv, $client->id);
                        $existing ? $updated++ : $created++;
                    });
                } catch (\Exception $e) {
                    $failed++;
                    Log::error("Xero: Invoice sync failed for {$inv['InvoiceID']}: " . $e->getMessage());
                }
            }

            $log->update([
                'status'              => 'completed',
                'completed_at'        => now(),
                'last_modified_since' => now(),
                'records_fetched'     => count($invoices),
                'records_created'     => $created,
                'records_updated'     => $updated,
                'records_failed'      => $failed,
            ]);
        } catch (\Exception $e) {
            $log->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
            throw $e;
        }

        return compact('created', 'updated', 'failed') + ['records_fetched' => count($invoices)];
    }

    // ── Helpers ────────────────────────────────────────────────────

    private function getLastSyncTimestamp(string $entity): ?Carbon
    {
        $log = XeroSyncLog::where('entity', $entity)
            ->where('status', 'completed')
            ->whereNotNull('last_modified_since')
            ->latest('completed_at')
            ->first();

        return $log ? Carbon::parse($log->last_modified_since) : null;
    }
}
