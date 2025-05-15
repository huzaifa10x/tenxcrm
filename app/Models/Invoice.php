<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'client_name',
        'client_email',
        'issue_date',
        'due_date',
        'is_paid',
        'notes',
        'total_amount',
        'tax_amount',
        'grand_total'
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    protected static function booted()
    {
        static::creating(function ($invoice) {
            $invoice->uuid = (string) Str::uuid();
        });
    }
}
