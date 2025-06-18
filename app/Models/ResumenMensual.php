<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResumenMensual extends Model
{
    protected $table = 'resumen_mensual';

    protected $fillable = [
        'user_id',
        'contenido',
        'mes',
    ];

}
