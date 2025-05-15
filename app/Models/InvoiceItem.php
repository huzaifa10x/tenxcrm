<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'serial_no',
        'description',
        'qty',
        'rate',
        'amount',
        'tax'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
