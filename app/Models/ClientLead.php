<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientLead extends Model
{
    protected $fillable = ['website_name', 'website_domain', 'form_data'];

    protected $casts = [
        'form_data' => 'array',
    ];
}
