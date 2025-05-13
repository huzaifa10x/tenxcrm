<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'comment',
        'user_id',
    ];

    // Relationship with Lead
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    // Relationship with User (creator)
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
