<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Doctors extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule',
        'patients_attended',
        'patients_pending',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function appointments()
    {
        return $this->hasMany(Appointments::class);
    }

    public function reports()
    {
        return $this->hasMany(Reports::class);
    }
}