<?php

namespace App\Models\Xero;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class XeroPayment extends Model
{
    protected $table = 'xero_payments';

    protected $fillable = [
        'xero_payment_id', 'invoice_id', 'xero_invoice_id',
        'payment_date', 'amount', 'currency_code', 'currency_rate',
        'reference', 'payment_type', 'status', 'xero_updated_at',
    ];

    protected $casts = [
        'payment_date'    => 'date',
        'xero_updated_at' => 'datetime',
        'amount'          => 'decimal:2',
        'currency_rate'   => 'decimal:8',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(XeroInvoice::class, 'invoice_id');
    }
}