<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminTask extends Model
{
    use HasFactory;

    protected $fillable = ['task_id', 'sub_type'];

    public function task() {
        return $this->belongsTo(Task::class);
    }
}
