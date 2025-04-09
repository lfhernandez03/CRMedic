<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Specialities extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
    ];

    public function doctors()
    {
        return $this->hasMany(User::class, 'id_especialidad')->where('rol', 'medico');
    }


    public function reports()
    {
        return $this->hasMany(Reports::class);
    }
}
