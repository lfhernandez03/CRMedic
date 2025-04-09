<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointments extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'date',
        'time',
        'status',
        'reason',
        'notificated',
    ];

    protected static function booted(): void
    {
        static::created(function ($appointment) {
            if ($appointment->status === 'scheduled') {
                $appointment->doctor?->increment('pacientes_pendientes');
            } elseif ($appointment->status === 'completed') {
                $appointment->doctor?->increment('pacientes_atendidos');
            }
        });

        static::updated(function ($appointment) {
            if ($appointment->isDirty('status')) {
                $original = $appointment->getOriginal('status');
                $nuevo = $appointment->status;

                if ($original === 'scheduled' && $nuevo === 'completed') {
                    $appointment->doctor?->decrement('pacientes_pendientes');
                    $appointment->doctor?->increment('pacientes_atendidos');
                }

                if ($original === 'scheduled' && $nuevo === 'canceled') {
                    $appointment->doctor?->decrement('pacientes_pendientes');
                }

                if ($original === 'completed' && $nuevo === 'scheduled') {
                    $appointment->doctor?->decrement('pacientes_atendidos');
                    $appointment->doctor?->increment('pacientes_pendientes');
                }

                if ($original === 'completed' && $nuevo === 'canceled') {
                    $appointment->doctor?->decrement('pacientes_atendidos');
                }

                if ($original === 'canceled' && $nuevo === 'scheduled') {
                    $appointment->doctor?->increment('pacientes_pendientes');
                }

                if ($original === 'canceled' && $nuevo === 'completed') {
                    $appointment->doctor?->increment('pacientes_atendidos');
                }
            }
        });

        static::deleted(function ($appointment) {
            if ($appointment->status === 'scheduled') {
                $appointment->doctor?->decrement('pacientes_pendientes');
            } elseif ($appointment->status === 'completed') {
                $appointment->doctor?->decrement('pacientes_atendidos');
            }
        });
    }

    public function patient()
    {
        return $this->belongsTo(Patients::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function treatments()
    {
        return $this->hasMany(Treatments::class);
    }
}
