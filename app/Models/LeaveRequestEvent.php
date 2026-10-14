<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequestEvent extends Model
{
    public $timestamps = false;

    protected $fillable = ['leave_request_id', 'user_id', 'action', 'comment', 'meta', 'created_at'];

    protected $casts = ['meta' => 'array', 'created_at' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
