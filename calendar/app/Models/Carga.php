<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carga extends Model
{
    use HasFactory;
    protected $table = 'carga';

    protected $fillable = [
        'inicio',
        'fin',
        'usuario_id',
        'materia_id',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function materia()
    {
        return $this->belongsTo(Materia::class);
    }

}
