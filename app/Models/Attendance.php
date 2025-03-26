<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'date', 'punch_in', 'punch_out', 'total_hours', 'location', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
