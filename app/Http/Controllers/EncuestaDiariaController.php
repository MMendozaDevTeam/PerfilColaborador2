<?php
// app/Http/Controllers/EncuestaDiariaController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PerfilPsicometrico;
use App\Models\EncuestaRespuestas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use OpenAI;


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
    
        $yaRespondida = EncuestaRespuestas::where('user_id', $userId)
                        ->whereDate('created_at', Carbon::today())
                        ->exists();
    
        if ($yaRespondida) {
            return redirect()->route('bienvenida')
                             ->with('success', 'Ya has respondido la encuesta del día de hoy. ¡Gracias!');
        }

        $contexto = "Nombre: {$perfil->nombre}, Edad: {$perfil->edad}, Sexo: {$perfil->sexo}, Nivel educativo: {$perfil->nivel_educativo}, Antigüedad: {$perfil->antiguedad_anios}, Área: {$perfil->area}, Mentalidad: {$perfil->respuesta_mentalidad}, Comunicación: {$perfil->respuesta_comunicacion}";

        $preguntas = $this->generarConOpenAI($contexto);
    
        return view('encuesta-diaria.index', compact('preguntas', 'perfil'));
    }

    private function generarConOpenAI($contexto)
    {
        $client = OpenAI::client(env('OPENAI_API_KEY'));

        $prompt = <<<PROMPT
        Eres un experto en psicología organizacional. Necesito que generes 4 preguntas tipo escala Likert (1-5) para una encuesta diaria muy personalizada.
        
        El objetivo es monitorear el rendimiento, emociones, motivación y actitud diaria de un colaborador con el siguiente perfil histórico:
        
        $contexto
        
        Características de las preguntas:
        - Enfocadas en estado emocional, motivación, nivel de energía, actitud ante el trabajo.
        - Cada pregunta debe ser clara, concreta y adecuada para responder con valores del 1 (muy en desacuerdo) al 5 (muy de acuerdo).
        - No repitas conceptos ni temas entre preguntas.
        - No uses lenguaje ambiguo ni rebuscado.
        - Devuélvelas como un array PHP plano, por ejemplo:
        ["Pregunta 1", "Pregunta 2", "Pregunta 3", "Pregunta 4"]
        PROMPT;
        
        $response = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'Eres un experto en psicología organizacional.'],
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);
        
        $output = $response['choices'][0]['message']['content'];
        
        // Intentar convertir la respuesta en array
        try {
            eval("\$preguntas = $output;");
        } catch (\Throwable $e) {
            $preguntas = [
                "¿Cómo te sientes emocionalmente hoy?",
                "¿Te has sentido motivado para alcanzar tus objetivos hoy?",
                "¿Tu nivel de energía fue suficiente para cumplir con tus tareas?",
                "¿Cómo fue tu actitud hacia los retos laborales hoy?"
            ];
        }
        
        return $preguntas;
            }
        
}
