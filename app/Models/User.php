<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'birthdate',
        'rol',
        'status',
        'speciality_id',
        'horario',
        'pacientes_atendidos',
        'pacientes_pendientes'
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function speciality()
    {
        return $this->belongsTo(Specialities::class, 'speciality_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notifications::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointments::class, 'id_medico');
    }

    public function reports()
    {
        return $this->hasMany(Reports::class, 'id_medico');
    }

    public function isDoctor()
    {
        return $this->rol === 'medico';
    }

    public function getScheduleAttribute()
    {
        return $this->horario;
    }

    public function setScheduleAttribute($value)
    {
        $this->attributes['horario'] = $value;
    }

    public function getPatientsAttendedAttribute()
    {
        return $this->pacientes_atendidos;
    }

    public function setPatientsAttendedAttribute($value)
    {
        $this->attributes['pacientes_atendidos'] = $value;
    }

    public function getPatientsPendingAttribute()
    {
        return $this->pacientes_pendientes;
    }

    public function setPatientsPendingAttribute($value)
    {
        $this->attributes['pacientes_pendientes'] = $value;
    }
}
