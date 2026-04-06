<?php

namespace App\Imports;

use App\Models\Deal;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class DealsImport implements ToCollection, WithHeadingRow, SkipsEmptyRows, WithBatchInserts, WithChunkReading
{
    public int $importedCount = 0;
    public int $skippedCount  = 0;

    /**
     * Pipedrive CSV column (heading-row normalised) → model field
     * Heading normalisation: lowercase, spaces→underscores, hyphens→underscores
     */
    private array $map = [
        'deal_title'                         => 'title',
        'deal_value'                         => 'value',
        'deal_organization'                  => 'organization',
        'deal_contact_person'                => 'contact_person',
        'deal_expected_close_date'           => 'expected_close_date',
        'deal_next_activity_date'            => 'next_activity_date',
        'deal_owner'                         => 'owner',
        'deal_currency_of_value'             => 'currency',
        'deal_pipeline'                      => 'pipeline',
        'deal_probability'                   => 'probability',
        'deal_status'                        => 'status',
        'deal_label'                         => 'label',
        'deal_stage'                         => 'stage',
        'deal_weighted_value'                => 'weighted_value',
        'deal_deal_created'                  => 'deal_created_at',
        'deal_update_time'                   => 'deal_updated_at',
        'deal_last_stage_change'             => 'last_stage_change',
        'deal_won_time'                      => 'won_time',
        'deal_last_activity_date'            => 'last_activity_date',
        'deal_lost_time'                     => 'lost_time',
        'deal_deal_closed_on'                => 'deal_closed_on',
        'deal_lost_reason'                   => 'lost_reason',
        'deal_visible_to'                    => 'visible_to',
        'deal_total_activities'              => 'total_activities',
        'deal_done_activities'               => 'done_activities',
        'deal_activities_to_do'              => 'activities_to_do',
        'deal_product_quantity'              => 'product_quantity',
        'deal_product_amount'                => 'product_amount',
        'deal_product_name'                  => 'product_name',
        'deal_source_origin'                 => 'source_origin',
        'deal_source_channel'                => 'source_channel',
        'deal_archive_status'                => 'archive_status',
        'deal_creator'                       => 'creator',
        'deal_id'                            => 'pipedrive_id',
    ];

    private array $dateFields = [
        'expected_close_date', 'next_activity_date', 'last_activity_date',
        'last_stage_change', 'won_time', 'lost_time', 'deal_closed_on',
        'deal_created_at', 'deal_updated_at',
    ];

    private array $numericFields = [
        'value', 'weighted_value', 'product_amount', 'probability',
        'total_activities', 'done_activities', 'activities_to_do', 'product_quantity',
    ];

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $data = $this->mapRow($row->toArray());

            if (empty($data['title'])) {
                $this->skippedCount++;
                continue;
            }

            $uniqueKey = !empty($data['pipedrive_id'])
                ? ['pipedrive_id' => $data['pipedrive_id']]
                : ['title' => $data['title']];

            Deal::updateOrCreate($uniqueKey, $data);
            $this->importedCount++;
        }
    }

    private function mapRow(array $row): array
    {
        $mapped = [];

        foreach ($this->map as $csvKey => $modelKey) {
            $mapped[$modelKey] = isset($row[$csvKey]) && $row[$csvKey] !== '' ? $row[$csvKey] : null;
        }

        // Parse dates
        foreach ($this->dateFields as $field) {
            if (!empty($mapped[$field])) {
                try {
                    $mapped[$field] = \Carbon\Carbon::parse($mapped[$field]);
                } catch (\Exception) {
                    $mapped[$field] = null;
                }
            }
        }

        // Cast numeric fields
        foreach ($this->numericFields as $field) {
            if (isset($mapped[$field]) && $mapped[$field] !== null) {
                $mapped[$field] = is_numeric($mapped[$field]) ? $mapped[$field] + 0 : null;
            }
        }

        // Normalise status
        if (!empty($mapped['status'])) {
            $mapped['status'] = ucfirst(strtolower(trim($mapped['status'])));
            if (!in_array($mapped['status'], Deal::STATUSES)) {
                $mapped['status'] = 'Open';
            }
        } else {
            $mapped['status'] = 'Open';
        }

        // Normalise stage
        if (!empty($mapped['stage'])) {
            $stageMap = array_combine(
                array_map('strtolower', Deal::STAGES),
                Deal::STAGES
            );
            $mapped['stage'] = $stageMap[strtolower(trim($mapped['stage']))] ?? $mapped['stage'];
        }

        // pipedrive_id must be integer or null
        if (isset($mapped['pipedrive_id'])) {
            $mapped['pipedrive_id'] = is_numeric($mapped['pipedrive_id'])
                ? (int) $mapped['pipedrive_id']
                : null;
        }

        return array_filter($mapped, fn($v) => $v !== null && $v !== '');
    }

    public function batchSize(): int { return 500; }
    public function chunkSize(): int { return 500; }
}
