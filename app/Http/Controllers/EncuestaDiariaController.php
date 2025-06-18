<?php
// app/Http/Controllers/EncuestaDiariaController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PerfilPsicometrico;
use App\Models\EncuestaRespuestas;
use App\Models\ResumenMensual;
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
    
        //$yaRespondida = EncuestaRespuestas::where('user_id', $userId)
        //                 ->whereDate('created_at', Carbon::today())
        //                ->exists();
        //
        //if ($yaRespondida) {
        //    return redirect()->route('bienvenida')
        //                     ->with('success', 'Ya has respondido la encuesta del día de hoy. ¡Gracias!');
        //}

            $inicioMes = Carbon::now()->startOfMonth();
            $finMes = Carbon::now()->endOfMonth();
        
            $respuestasMes = EncuestaRespuestas::where('user_id', $userId)
                                ->whereBetween('created_at', [$inicioMes, $finMes])
                                ->count();
        
            $hoy = Carbon::today();
           // $esUltimoDia = $hoy->isSameDay($finMes);
        
            // Si es fin de mes y hay suficientes respuestas, genera y guarda resumen
            if ($respuestasMes >= 20) {
                $resumenYaExiste = ResumenMensual::where('user_id', $userId)
                                    ->whereYear('created_at', $hoy->year)
                                    ->whereMonth('created_at', $hoy->month)
                                    ->exists();
        
                if (!$resumenYaExiste) {
                    $contexto = $this->obtenerContextoHistorico($userId);
                    $resumen = $this->generarResumenMensualOpenAI($contexto);
        
                    ResumenMensual::create([
                        'user_id' => $userId,
                        'contenido' => $resumen,
                        'mes' => ucfirst(Carbon::now()->translatedFormat('F Y')), // Ej: "Junio 2025"
                    ]);
                }
            }


        $contexto = $this->obtenerContextoHistorico($perfil->user_id);

        $preguntas = $this->generarConOpenAI($contexto);
    
        return view('encuesta-diaria.index', compact('preguntas', 'perfil'));
    }















    private function obtenerContextoHistorico(int $userId): string
    {
    $perfil = PerfilPsicometrico::where('user_id', $userId)->first();

    $hoy = now();
    $inicioMesActual = $hoy->copy()->startOfMonth();

    // Traer resumen mensual anterior si existe
    $resumenAnterior = ResumenMensual::where('user_id', $userId)
        ->where('created_at', '<', $inicioMesActual)
        ->orderByDesc('created_at')
        ->value('contenido');

    // Traer respuestas del mes actual
    $respuestasActuales = EncuestaRespuestas::where('user_id', $userId)
        ->where('created_at', '>=', $inicioMesActual)
        ->orderBy('created_at', 'asc')
        ->get(['pregunta', 'respuesta', 'created_at']);

    $bloqueRespuestas = $respuestasActuales->map(function ($r) {
        return "- {$r->created_at->format('d/m')}: {$r->pregunta} → {$r->respuesta}/5";
    })->implode("\n");

    $contexto = <<<TXT
    Perfil psicométrico:
    Nombre: {$perfil->nombre}
    Edad: {$perfil->edad}
    Sexo: {$perfil->sexo}
    Nivel educativo: {$perfil->nivel_educativo}
    Antigüedad: {$perfil->antiguedad_anios} años
    es_nuevo: {$perfil->es_nuevo}
    Área: {$perfil->area}
    Mentalidad empresarial: {$perfil->respuesta_mentalidad}
    Estilo de comunicación: {$perfil->respuesta_comunicacion}
    
    TXT;
    
        if ($resumenAnterior) {
            $contexto .= <<<TXT
    Resumen mensual anterior:
    $resumenAnterior
    
    TXT;
        }
    
        $contexto .= <<<TXT
    Historial de respuestas del mes actual:
    $bloqueRespuestas
    TXT;
    
        return $contexto;
    }
















    private function generarConOpenAI($contexto)
    {
        $client = OpenAI::client(env('OPENAI_API_KEY'));

        $prompt = <<<PROMPT
        Eres un experto en psicología organizacional. Necesito que generes 4 preguntas tipo escala Likert (1-5) para una encuesta diaria muy personalizada.
        
        El objetivo es monitorear el rendimiento, emociones, motivación y actitud diaria de un colaborador con el siguiente perfil histórico:
        
        $contexto
        
        Características de las preguntas:
        - Debe variar con las preguntas ya hechas en dias pasados que se te dieron en contexto
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
            $preguntas = [];
        }
        
        return $preguntas;
    }

    private function generarResumenMensualOpenAI(string $contexto): string
    {
        $prompt = <<<PROMPT
    Eres un experto en psicología organizacional. A partir del siguiente perfil y registro de respuestas, genera un resumen mensual sobre el estado emocional, actitud, motivación y nivel de energía del colaborador.
    
    Este resumen será utilizado por el gerente para comprender el comportamiento del colaborador durante el mes. Incluye observaciones clave y recomendaciones prácticas para el siguiente mes.
    
    Contenido histórico:
    $contexto
    
    Entrega únicamente el resumen en texto claro y profesional.
    PROMPT;
    
        try {
            $client = OpenAI::client(env('OPENAI_API_KEY'));
    
            $response = $client->chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'Eres un psicólogo organizacional con experiencia en análisis de comportamiento.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);
    
            return $response['choices'][0]['message']['content'] ?? 'No se pudo generar resumen.';
        } catch (\Throwable $e) {
            \Log::error("Error generando resumen mensual con OpenAI: " . $e->getMessage());
            return 'Error al generar el resumen.';
        }
    }

        
}
