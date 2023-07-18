<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    use HasFactory;

    protected $table = 'carrera';

    protected $fillable = ['nameCarr'];

    public function materias()
    {
        return $this->hasMany(Materia::class);
    }

}
