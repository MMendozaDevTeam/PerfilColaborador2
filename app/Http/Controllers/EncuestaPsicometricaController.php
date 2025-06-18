<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PerfilPsicometrico;


class EncuestaPsicometricaController extends Controller
{
    public function guardarMentalidad(Request $request)
        {
            $userId = $request->input('user_id');
            $respuestas = $request->input('respuestas'); // array de 6 valores (1-5)
        
            $perfil = PerfilPsicometrico::where('user_id', $userId)->firstOrFail();
        
            $puntosCreativa = 0;
            $puntosZonaConfort = 0;
        
            foreach ($respuestas as $valor) {
                // Entre más cercano a 1 → Creativa, más cercano a 5 → Zona de confort
                $puntosCreativa += (6 - $valor);      // 5 → 1 punto, 1 → 5 puntos
                $puntosZonaConfort += ($valor);       // 1 → 1 punto, 5 → 5 puntos
            }
        
            // Decide el tipo de mentalidad
            if (abs($puntosCreativa - $puntosZonaConfort) <= 3) {
                $mentalidad = 'Intermedia';
            } elseif ($puntosCreativa > $puntosZonaConfort) {
                $mentalidad = 'Creativa';
            } else {
                $mentalidad = 'Zona de confort';
            }
        
            $perfil->update(['respuesta_mentalidad' => $mentalidad]);
        
            return redirect()->route('encuestas.psicometricas', ['user_id' => $userId])
                             ->with('success', 'Mentalidad empresarial registrada: ' . $mentalidad);
        }

        public function guardarComunicacion(Request $request)
        {
            $userId = $request->input('user_id');
            $respuestas = $request->input('respuestas'); // array con índices 0 a 13, valores del 1 al 4
        
            $perfil = PerfilPsicometrico::where('user_id', $userId)->firstOrFail();
        
            // Inicializar puntajes por animal
            $puntajes = [
                'León' => 0,
                'Pavo Real' => 0,
                'Delfín' => 0,
                'Búho' => 0,
            ];
        
            // Asignar un punto a cada animal según la opción marcada
            foreach ($respuestas as $respuesta) {
                switch ($respuesta) {
                    case 1:
                        $puntajes['León']++;
                        break;
                    case 2:
                        $puntajes['Pavo Real']++;
                        break;
                    case 3:
                        $puntajes['Delfín']++;
                        break;
                    case 4:
                        $puntajes['Búho']++;
                        break;
                }
            }
        
            // Ordenar de mayor a menor
            arsort($puntajes);
            $animales = array_keys($puntajes);
            $valores = array_values($puntajes);
        
            // Evaluar si hay dos animales dominantes
            if (isset($valores[1]) && ($valores[0] - $valores[1]) <= 2) {
                $perfilComunicacion = $animales[0] . ' y ' . $animales[1];
            } else {
                $perfilComunicacion = $animales[0];
            }
        
            $perfil->update(['respuesta_comunicacion' => $perfilComunicacion]);
        
            return redirect()->route('encuestas.psicometricas', ['user_id' => $userId])
                             ->with('success', 'Perfil de comunicación registrado: ' . $perfilComunicacion);
        }

}
