<?php

namespace App\Exports;

use App\Models\Activity;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ActivitiesExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function __construct(private array $filters = []) {}

    public function query()
    {
        return Activity::query()->filter($this->filters)->orderBy('due_date');
    }

    public function headings(): array
    {
        return [
            'Done', 'Type', 'Subject', 'Deal / Lead', 'Contact Person',
            'Email (Work)', 'Phone (Work)', 'Phone (Mobile)',
            'Organization', 'Due Date', 'Duration',
            'Assigned To', 'Priority', 'Description', 'Note', 'Created At',
        ];
    }

    public function map($a): array
    {
        return [
            $a->done,
            $a->type,
            $a->subject,
            $a->deal_name ?? ($a->deal?->title ?? ($a->lead?->title ?? '')),
            $a->contact_person,
            $a->email_work,
            $a->phone_work,
            $a->phone_mobile,
            $a->organization,
            $a->due_date?->format('Y-m-d'),
            $a->duration,
            $a->assigned_to,
            $a->priority,
            $a->description,
            $a->note,
            $a->created_at->format('Y-m-d H:i'),
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
