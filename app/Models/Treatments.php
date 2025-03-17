<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Treatments extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'diagnosis',
        'treatment',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointments::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescriptions::class);
    }
}
