<?php

namespace App\Imports;

use App\Models\Lead;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class LeadsImport implements ToCollection, WithHeadingRow, SkipsEmptyRows, WithBatchInserts, WithChunkReading
{
    public int $importedCount = 0;
    public int $skippedCount  = 0;

    /**
     * Pipedrive column → model field mapping
     */
    private array $map = [
        'lead_title'         => 'title',
        'lead_next_activity' => 'next_activity_at',
        'lead_labels'        => 'label',
        'lead_source_origin' => 'source_origin',
        'lead_lead_created'  => 'lead_created_at',
        'lead_owner'         => 'owner',
    ];

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $data = $this->mapRow($row->toArray());

            if (empty($data['title'])) {
                $this->skippedCount++;
                continue;
            }

            Lead::updateOrCreate(
                ['title' => $data['title']],
                $data
            );

            $this->importedCount++;
        }
    }

    private function mapRow(array $row): array
    {
        $mapped = [];

        foreach ($this->map as $csvKey => $modelKey) {
            // HeadingRow normalises headers (spaces→underscores, lowercase)
            $mapped[$modelKey] = $row[$csvKey] ?? null;
        }

        // Normalise label
        if (!empty($mapped['label'])) {
            $mapped['label'] = ucfirst(strtolower(trim($mapped['label'])));
            if (!in_array($mapped['label'], Lead::LABELS)) {
                $mapped['label'] = null;
            }
        }

        // Parse dates
        foreach (['next_activity_at', 'lead_created_at'] as $dateField) {
            if (!empty($mapped[$dateField])) {
                try {
                    $mapped[$dateField] = \Carbon\Carbon::parse($mapped[$dateField]);
                } catch (\Exception) {
                    $mapped[$dateField] = null;
                }
            }
        }

        return array_filter($mapped, fn($v) => $v !== null && $v !== '');
    }

    public function batchSize(): int  { return 500; }
    public function chunkSize(): int  { return 500; }
}
