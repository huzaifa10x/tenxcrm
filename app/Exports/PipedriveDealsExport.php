<?php

namespace App\Exports;

use App\Models\Crm\Deal;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PipedriveDealsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function __construct(protected User $actor, protected array $filters = []) {}

    public function query()
    {
        $query = Deal::query()
            ->visibleTo($this->actor)
            ->with(['person', 'organization', 'owner', 'stage']);

        $f = $this->filters;
        if (! empty($f['search'])) {
            $s = $f['search'];
            $query->where(fn ($q) => $q->where('title', 'like', "%{$s}%")
                ->orWhereHas('person', fn ($p) => $p->where('name', 'like', "%{$s}%"))
                ->orWhereHas('organization', fn ($o) => $o->where('name', 'like', "%{$s}%")));
        }
        if (! empty($f['status']))   $query->where('status', $f['status']);
        if (! empty($f['stage_id'])) $query->where('stage_id', $f['stage_id']);
        if (! empty($f['owner_id'])) $query->where('owner_id', $f['owner_id']);

        return $query->latest();
    }

    public function headings(): array
    {
        return [
            'Deal ID', 'Title', 'Value', 'Currency', 'Stage', 'Status',
            'Person', 'Phone', 'Email', 'Organization', 'Owner',
            'Label', 'Expected Close', 'Source Channel', 'Created',
        ];
    }

    public function map($deal): array
    {
        return [
            $deal->id,
            $deal->title,
            number_format((float) $deal->value, 2, '.', ''),
            $deal->currency,
            $deal->stage?->name,
            ucfirst($deal->status),
            $deal->person?->name,
            $deal->person?->phone,
            $deal->person?->email,
            $deal->organization?->name,
            $deal->owner?->name,
            $deal->label,
            optional($deal->expected_close_date)->format('Y-m-d'),
            $deal->source_channel,
            $deal->created_at?->format('Y-m-d H:i'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
