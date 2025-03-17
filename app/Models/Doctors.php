<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Doctors extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'speciality_id',
        'schedule',
        'patients_attended',
        'patients_pending',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function speciality()
    {
        return $this->belongsTo(Specialities::class);
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