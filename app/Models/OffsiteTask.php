<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OffsiteTask extends Model
{
    use HasFactory;

    protected $fillable = ['task_id', 'sub_type', 'kw_url'];

    protected $casts = [
        'kw_url' => 'array', // Multi KW + URL JSON format
    ];

    public function task() {
        return $this->belongsTo(Task::class);
    }
}
