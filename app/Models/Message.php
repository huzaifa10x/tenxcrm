<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Message extends Model
{

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $casts = [
        'deleted_by_sender' => 'boolean',
        'deleted_by_receiver' => 'boolean',
    ];

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'is_read',
        'deleted_by_sender',
        'deleted_by_receiver',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
