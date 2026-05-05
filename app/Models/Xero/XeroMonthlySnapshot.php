<?php

namespace App\Models\Xero;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class XeroMonthlySnapshot extends Model
{
    protected $table = 'xero_monthly_snapshots';

    protected $fillable = [
        'year_month', 'department_id', 'normalized_service',
        'xero_client_id', 'snapshot_type',
        'revenue', 'invoice_count', 'calculated_at',
    ];

    protected $casts = [
        'calculated_at' => 'datetime',
        'revenue'       => 'decimal:2',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(XeroDepartment::class, 'department_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(XeroClient::class, 'xero_client_id');
    }

    // ── Quick read helpers ─────────────────────────────────────────

    public static function totalForMonth(string $ym): float
    {
        return (float) static::where('snapshot_type', 'total')
            ->where('year_month', $ym)
            ->value('revenue');
    }

    public static function departmentTrend(int $months = 12): Collection
    {
        $from = Carbon::now()->subMonths($months - 1)->format('Y-m');
        return static::where('snapshot_type', 'department')
            ->where('year_month', '>=', $from)
            ->with('department:id,name,color')
            ->orderBy('year_month')
            ->get();
    }

    public static function topClientsForMonth(string $ym, int $limit = 10): Collection
    {
        return static::where('snapshot_type', 'client')
            ->where('year_month', $ym)
            ->with('client:id,name,email')
            ->orderByDesc('revenue')
            ->limit($limit)
            ->get();
    }
}