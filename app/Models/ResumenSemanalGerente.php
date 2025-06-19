<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ResumenSemanalGerente extends Model
{
    use HasFactory;

    protected $table = 'resumen_semanal_gerentes';
    
    protected $fillable = [
        'user_id',
        'semana_inicio',
        'semana_fin',
        'contenido',
    ];

    protected $casts = [
        'contenido' => 'array',
        'semana_inicio' => 'date',
        'semana_fin' => 'date',
    ];

}
