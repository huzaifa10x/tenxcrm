<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmNote extends Model
{
    protected $fillable = ['notable_id', 'notable_type', 'author', 'content'];

    public function notable()
    {
        return $this->morphTo();
    }
}
