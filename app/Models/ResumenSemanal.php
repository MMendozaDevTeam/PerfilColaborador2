<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResumenSemanal extends Model
{
    use HasFactory;

    protected $table = "resumenes_semanales";

    protected $fillable = [
        'user_id',
        'contenido',
        'semana_inicio',
        'semana_fin',
    ];
}
