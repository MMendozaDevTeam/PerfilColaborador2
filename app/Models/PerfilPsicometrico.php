<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerfilPsicometrico extends Model
{
    protected $fillable = [
        'user_id',
        'nombre',
        'edad',
        'sexo',
        'nivel_educativo',
        'antiguedad_anios',
        'area',
        'estado_civil',
        'respuesta_mentalidad',
        'respuesta_comunicacion',
    ];
}
