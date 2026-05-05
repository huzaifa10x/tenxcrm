<?php

namespace App\Models\Xero;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class XeroInvoiceItem extends Model
{
    protected $table = 'xero_invoice_items';

    protected $fillable = [
        'invoice_id', 'xero_item_id', 'item_code', 'description',
        'account_code', 'quantity', 'unit_price', 'discount_rate',
        'line_amount', 'tax_amount', 'tax_type', 'line_item_id',
        'department_id', 'normalized_service',
    ];

    protected $casts = [
        'quantity'      => 'decimal:4',
        'unit_price'    => 'decimal:2',
        'discount_rate' => 'decimal:2',
        'line_amount'   => 'decimal:2',
        'tax_amount'    => 'decimal:2',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(XeroInvoice::class, 'invoice_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(XeroDepartment::class, 'department_id');
    }
}