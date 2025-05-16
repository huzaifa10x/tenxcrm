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
    public function payments()
    {
        return $this->hasMany(InvoicePayment::class);
    }

    public function getPaidAmountAttribute()
    {
        return $this->payments()->sum('amount');
    }

    public function updatePaymentStatus()
    {
        $paidAmount = $this->payments()->sum('amount');
        $this->is_paid = $paidAmount >= $this->grand_total;
        $this->save();
    }

    public function getIsFullyPaidAttribute()
    {
        return $this->paid_amount >= $this->grand_total;
    }
}
