<?php

namespace App\Exports;

use App\Models\Lead;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LeadsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function __construct(private array $filters = []) {}

    public function query()
    {
        return Lead::query()->filter($this->filters)->orderByDesc('lead_created_at');
    }

    public function headings(): array
    {
        return [
            'ID', 'Title', 'Label', 'Source Origin', 'Owner',
            'Next Activity', 'Lead Created', 'Notes', 'Created At',
        ];
    }

    public function map($lead): array
    {
        return [
            $lead->id,
            $lead->title,
            $lead->label,
            $lead->source_origin,
            $lead->owner,
            $lead->next_activity_at?->format('Y-m-d'),
            $lead->lead_created_at?->format('Y-m-d H:i'),
            $lead->notes,
            $lead->created_at->format('Y-m-d H:i'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0d6efd'],
            ]],
        ];
    }
}
