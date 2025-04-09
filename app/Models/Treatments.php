<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Treatments extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'purpose',
        'instructions',
    ];

    public function prescriptions()
    {
        return $this->hasMany(Prescriptions::class);
    }
}
