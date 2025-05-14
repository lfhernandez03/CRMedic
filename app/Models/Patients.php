<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Patients extends Model
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'birthdate',
        'address',
        'medical_history',
    ];

    /**
     * Relación con las citas (appointments)
     */
    public function appointments()
    {
        return $this->hasMany(Appointments::class);
    }
}
