<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notifications extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'message',
        'status',
        'type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}