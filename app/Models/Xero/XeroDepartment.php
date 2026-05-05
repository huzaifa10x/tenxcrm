<?php

namespace App\Models\Xero;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class XeroDepartment extends Model
{
    protected $table = 'xero_departments';

    protected $fillable = ['name', 'slug', 'color'];

    public function serviceMappings(): HasMany
    {
        return $this->hasMany(XeroServiceMapping::class, 'department_id');
    }

    public function invoiceItems(): HasMany
    {
        return $this->hasMany(XeroInvoiceItem::class, 'department_id');
    }
}