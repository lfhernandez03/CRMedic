<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
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
        'specialities',
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
        'specialities' => 'array',
    ];

    // MÉTODO REQUERIDO PARA FILAMENT
    public function canAccessPanel(Panel $panel): bool
    {
        return true; 
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

    // Mutador para guardar las especialidades como texto plano
    public function setSpecialitiesAttribute($value)
    {
        $this->attributes['specialities'] = is_array($value) ? implode(',', $value) : $value;
    }

    // Accesor para leer las especialidades como un array
    public function getSpecialitiesAttribute($value)
    {
        return $value ? explode(',', $value) : [];
    }
}