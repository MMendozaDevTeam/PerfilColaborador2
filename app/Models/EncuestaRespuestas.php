<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EncuestaRespuestas extends Model
{
    protected $fillable = [
    'user_id',
    'pregunta',
    'respuesta',
    ];
}
