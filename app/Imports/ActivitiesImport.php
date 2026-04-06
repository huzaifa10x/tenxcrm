<?php

namespace App\Imports;

use App\Models\Activity;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ActivitiesImport implements ToCollection, WithHeadingRow, SkipsEmptyRows, WithBatchInserts, WithChunkReading
{
    public int $importedCount = 0;
    public int $skippedCount  = 0;

    /**
     * Pipedrive heading (normalised) → model field
     */
    private array $map = [
        'activity_done'              => 'done',
        'activity_subject'           => 'subject',
        'activity_deal'              => '_deal_blob',   // special: needs parsing
        'activity_contact_person'    => 'contact_person',
        'person_email_work'          => 'email_work',
        'person_email_home'          => 'email_home',
        'person_email_other'         => 'email_other',
        'person_phone_work'          => 'phone_work',
        'person_phone_home'          => 'phone_home',
        'person_phone_mobile'        => 'phone_mobile',
        'person_phone_other'         => 'phone_other',
        'activity_organization'      => 'organization',
        'activity_due_date'          => 'due_date',
        'activity_duration'          => 'duration',
        'activity_assigned_to_user'  => 'assigned_to',
    ];

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $data = $this->mapRow($row->toArray());

            if (empty($data['subject'])) {
                $this->skippedCount++;
                continue;
            }

            Activity::create($data);
            $this->importedCount++;
        }
    }

    private function mapRow(array $row): array
    {
        $mapped = [];

        foreach ($this->map as $csvKey => $modelKey) {
            $val = isset($row[$csvKey]) && $row[$csvKey] !== '' ? $row[$csvKey] : null;

            if ($modelKey === '_deal_blob') {
                // Parse Pipedrive's blob: "Name: Foo Phone Number: +971... Email: ..."
                $mapped['deal_name'] = $this->parseDealName($val);
            } else {
                $mapped[$modelKey] = $val;
            }
        }

        // Normalise done
        if (!empty($mapped['done'])) {
            $mapped['done'] = in_array($mapped['done'], ['To do', 'Done'])
                ? $mapped['done']
                : 'To do';
        } else {
            $mapped['done'] = 'To do';
        }

        // Infer type from subject keywords
        $mapped['type'] = $this->inferType($mapped['subject'] ?? '');

        // Parse date
        if (!empty($mapped['due_date'])) {
            try {
                $mapped['due_date'] = \Carbon\Carbon::parse($mapped['due_date'])->toDateString();
            } catch (\Exception) {
                $mapped['due_date'] = null;
            }
        }

        // Parse duration → keep as string "HH:MM"
        if (!empty($mapped['duration'])) {
            $mapped['duration'] = trim($mapped['duration']);
        }

        return array_filter($mapped, fn($v) => $v !== null && $v !== '');
    }

    /**
     * Pipedrive exports deal info as a single blob string.
     * "Name: Foo Phone Number:  +971... Email: foo@bar.com  Job Title: Date:..."
     * We extract the Name portion as the deal name.
     */
    private function parseDealName(?string $blob): ?string
    {
        if (!$blob) return null;

        // Try "Name: <value> Phone" pattern
        if (preg_match('/Name:\s*(.+?)(?:\s+Phone Number:|$)/i', $blob, $m)) {
            $name = trim($m[1]);
            return $name !== '' ? $name : null;
        }

        // Fallback: return first 100 chars
        return mb_substr($blob, 0, 100);
    }

    /**
     * Best-effort type inference from subject text.
     */
    private function inferType(string $subject): string
    {
        $s = strtolower($subject);

        if (str_contains($s, 'meeting') || str_contains($s, 'book meeting') || str_contains($s, 'arrange meeting')) {
            return 'Meeting';
        }
        if (str_contains($s, 'email')) return 'Email';
        if (str_contains($s, 'lunch') || str_contains($s, 'dinner')) return 'Lunch';
        if (str_contains($s, 'deadline')) return 'Deadline';
        if (str_contains($s, 'task') || str_contains($s, 'follow up') || str_contains($s, 'fix')) return 'Task';

        return 'Call'; // default — matches CSV data where all are calls
    }

    public function batchSize(): int { return 500; }
    public function chunkSize(): int { return 500; }
}
