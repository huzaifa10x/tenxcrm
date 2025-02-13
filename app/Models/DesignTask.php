<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesignTask extends Model
{
    use HasFactory;

    protected $fillable = ['task_id', 'sub_type', 'input_number', 'closing_input'];

    public function task() {
        return $this->belongsTo(Task::class);
    }
}
