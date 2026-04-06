<?php

namespace App\Exports;

use App\Models\Deal;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DealsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function __construct(private array $filters = []) {}

    public function query()
    {
        return Deal::query()->filter($this->filters)->orderByDesc('deal_created_at');
    }

    public function headings(): array
    {
        return [
            'ID', 'Pipedrive ID', 'Title', 'Value', 'Currency', 'Organization',
            'Contact Person', 'Owner', 'Pipeline', 'Stage', 'Status', 'Label',
            'Probability', 'Weighted Value', 'Source Origin', 'Source Channel',
            'Lost Reason', 'Total Activities', 'Done Activities', 'Activities To Do',
            'Product Name', 'Product Amount', 'Product Qty', 'Archive Status',
            'Creator', 'Expected Close', 'Last Activity', 'Won Time', 'Lost Time',
            'Deal Created', 'Notes', 'Updated At',
        ];
    }

    public function map($deal): array
    {
        return [
            $deal->id,
            $deal->pipedrive_id,
            $deal->title,
            $deal->value,
            $deal->currency,
            $deal->organization,
            $deal->contact_person,
            $deal->owner,
            $deal->pipeline,
            $deal->stage,
            $deal->status,
            $deal->label,
            $deal->probability,
            $deal->weighted_value,
            $deal->source_origin,
            $deal->source_channel,
            $deal->lost_reason,
            $deal->total_activities,
            $deal->done_activities,
            $deal->activities_to_do,
            $deal->product_name,
            $deal->product_amount,
            $deal->product_quantity,
            $deal->archive_status,
            $deal->creator,
            $deal->expected_close_date?->format('Y-m-d'),
            $deal->last_activity_date?->format('Y-m-d'),
            $deal->won_time?->format('Y-m-d H:i'),
            $deal->lost_time?->format('Y-m-d H:i'),
            $deal->deal_created_at?->format('Y-m-d H:i'),
            $deal->notes,
            $deal->updated_at->format('Y-m-d H:i'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '198754'],
            ]],
        ];
    }
}
