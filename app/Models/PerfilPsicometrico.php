<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerfilPsicometrico extends Model
{
    protected $table = 'perfiles_psicometricos';

    protected $fillable = [
        'nombre',
        'user_id',
        'edad',
        'sexo',
        'nivel_educativo',
        'antiguedad_anios',
        'es_nuevo',
        'area',
        'estado_civil',
        'respuesta_mentalidad',
        'respuesta_comunicacion',
    ];
}
