<?php

namespace App\Models\Xero;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class XeroServiceMapping extends Model
{
    protected $table = 'xero_service_mappings';

    protected $fillable = [
        'xero_item_id', 'item_code', 'item_name',
        'normalized_name', 'department_id',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(XeroDepartment::class, 'department_id');
    }

    // ── Department resolution ─────────────────────────────────────

    public static array $DEPARTMENT_MAP = [
        'seo'               => 'marketing',
        'search engine'     => 'marketing',
        'digital marketing' => 'marketing',
        'digital markeitng' => 'marketing',
        'google ads'        => 'marketing',
        'google ad'         => 'marketing',
        'ppc'               => 'marketing',
        'social media'      => 'marketing',
        'smm'               => 'marketing',
        'content'           => 'marketing',
        'blog'              => 'marketing',
        'copywriting'       => 'marketing',
        'web development'   => 'engineering',
        'web dev'           => 'engineering',
        'development'       => 'engineering',
        'wordpress'         => 'engineering',
        'web maintenance'   => 'engineering',
        'maintenance'       => 'engineering',
        'hosting'           => 'engineering',
        'design'            => 'design',
        'graphic'           => 'design',
        'photo shoot'       => 'design',
        'video shoot'       => 'design',
        'video'             => 'design',
        'branding'          => 'design',
    ];

    public static function resolveForItem(
        ?string $itemCode,
        ?string $itemName,
        ?string $description
    ): array {
        $depts = Cache::remember('xero_departments_map', 3600, function () {
            return XeroDepartment::all()->keyBy('slug');
        });

        $uncategorized = $depts->get('uncategorized');

        if ($itemCode) {
            $mapping = static::where('item_code', $itemCode)->with('department')->first();
            if ($mapping) {
                return [
                    'department_id'      => $mapping->department_id,
                    'normalized_service' => $mapping->normalized_name,
                ];
            }
        }

        $haystack = strtolower(($itemName ?? '') . ' ' . ($description ?? ''));

        foreach (static::$DEPARTMENT_MAP as $keyword => $slug) {
            if (str_contains($haystack, $keyword)) {
                $dept       = $depts->get($slug) ?? $uncategorized;
                $normalized = str_replace(' ', '_', $keyword);
                return [
                    'department_id'      => $dept?->id,
                    'normalized_service' => $normalized,
                ];
            }
        }

        return [
            'department_id'      => $uncategorized?->id,
            'normalized_service' => 'uncategorized',
        ];
    }

    public static function seedFromXeroItems(array $items): void
    {
        foreach ($items as $item) {
            $itemName = $item['Name'] ?? '';
            $resolved = static::resolveForItem(
                $item['Code'] ?? null,
                $itemName,
                $item['Description'] ?? null
            );

            static::updateOrCreate(
                ['item_code' => $item['Code'] ?? null, 'item_name' => $itemName],
                [
                    'xero_item_id'    => $item['ItemID'] ?? null,
                    'normalized_name' => $resolved['normalized_service'],
                    'department_id'   => $resolved['department_id'],
                ]
            );
        }

        Cache::forget('xero_departments_map');
    }
}