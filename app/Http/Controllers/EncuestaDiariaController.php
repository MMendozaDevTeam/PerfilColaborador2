<?php
// app/Http/Controllers/EncuestaDiariaController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PerfilPsicometrico;
use Illuminate\Support\Facades\Auth;

class EncuestaDiariaController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->query('user_id');

        $perfil = PerfilPsicometrico::where('user_id', $userId)->first();
    
        // Si no hay perfil válido o faltan datos requeridos
        if (
            !$perfil ||
            !$perfil->sexo ||
            !$perfil->edad ||
            !$perfil->nivel_educativo ||
            !$perfil->area ||
            !$perfil->estado_civil
        ) {
            return view('encuesta-diaria.datos-personales', compact('perfil'));
        }

        if (!$perfil || !$perfil->respuesta_comunicacion || !$perfil->respuesta_mentalidad) {
        return redirect()->route('encuestas.psicometricas', ['user_id' => $userId])
                         ->with('warning', 'Antes de responder la encuesta diaria, completa tu perfil psicométrico.');
        }

        // Simulación de preguntas personalizadas según perfil
        $preguntas = [];

        if ($perfil->respuesta_comunicacion === 'Delfín') {
            $preguntas[] = "¿Te has sentido en armonía con tu equipo hoy?";
        }

        if ($perfil->respuesta_mentalidad === 'Creativa') {
            $preguntas[] = "¿Has tenido espacio para proponer ideas nuevas hoy?";
        } else {
            $preguntas[] = "¿Te sentiste cómodo con las tareas que ya dominas?";
        }

        $preguntas[] = "¿Cómo describirías tu estado emocional hoy?";
        $preguntas[] = "¿Te sientes con energía para rendir al máximo este día?";

        return view('encuesta-diaria.index', compact('preguntas', 'perfil'));
    }
}
