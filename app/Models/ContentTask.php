<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentTask extends Model
{
    use HasFactory;

    protected $fillable = ['task_id', 'sub_type', 'number_of_pages', 'no_of_words', 'notes', 'closing_input'];

    public function task() {
        return $this->belongsTo(Task::class);
    }
}
