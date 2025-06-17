<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PerfilPsicometrico;


class EncuestaPsicometricaController extends Controller
{
    public function guardarMentalidad(Request $request)
    {
        $userId = $request->input('user_id');
        $respuestas = $request->input('respuestas'); // array con 6 valores escala Likert (1-5)

        $perfil = PerfilPsicometrico::where('user_id', $userId)->firstOrFail();

        $sumaIzquierda = array_sum(array_slice($respuestas, 0, 6)); // Preguntas pro-creatividad
        $sumaDerecha = array_sum(array_slice($respuestas, 6, 6));   // Preguntas zona de confort

        if ($sumaIzquierda - $sumaDerecha > 5) {
            $mentalidad = 'Creativa';
        } elseif ($sumaDerecha - $sumaIzquierda > 5) {
            $mentalidad = 'Zona de confort';
        } else {
            $mentalidad = 'Intermedia';
        }

        $perfil->update(['respuesta_mentalidad' => $mentalidad]);

        return redirect()->route('encuestas.psicometricas', ['user_id' => $userId])
                         ->with('success', 'Mentalidad empresarial registrada: ' . $mentalidad);
    }

    public function guardarComunicacion(Request $request)
    {
        $userId = $request->input('user_id');
        $respuestas = $request->input('respuestas'); // array de 12 respuestas (1-5)
    
        $perfil = PerfilPsicometrico::where('user_id', $userId)->firstOrFail();
    
        // 3 preguntas por animal
        $puntajes = [
            'León' => array_sum(array_slice($respuestas, 0, 3)),
            'Pavo Real' => array_sum(array_slice($respuestas, 3, 3)),
            'Delfín' => array_sum(array_slice($respuestas, 6, 3)),
            'Búho' => array_sum(array_slice($respuestas, 9, 3)),
        ];
    
        // Ordenar descendente
        arsort($puntajes);
        $animales = array_keys($puntajes);
        $valores = array_values($puntajes);
    
        // Determinar si el segundo es suficientemente alto
        if (isset($valores[1]) && ($valores[0] - $valores[1]) <= 2) {
            // Guardar dos perfiles (ej: "León y Pavo Real")
            $perfilComunicacion = $animales[0] . ' y ' . $animales[1];
        } else {
            // Guardar solo el de mayor puntaje
            $perfilComunicacion = $animales[0];
        }
    
        $perfil->update(['respuesta_comunicacion' => $perfilComunicacion]);
    
        // Si ya completó ambas encuestas, redirigir a encuesta diaria
        if ($perfil->respuesta_mentalidad) {
            return redirect()->route('encuesta.diaria', ['user_id' => $userId]);
        }
    
        return redirect()->route('encuestas.psicometricas', ['user_id' => $userId])
                         ->with('success', 'Perfil de comunicación registrado: ' . $perfilComunicacion);
    }

}
