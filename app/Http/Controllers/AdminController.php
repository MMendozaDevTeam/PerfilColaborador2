<?php

namespace App\Http\Controllers;

use App\Models\PerfilPsicometrico;
use App\Models\EncuestaRespuestas;
use App\Models\ResumenSemanalGerente;
use Illuminate\Support\Carbon;
use OpenAI;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $nip = $request->query('nip');
    
        if (!$nip || $nip !== '1234') {
            return redirect()->route('bienvenida')->with('error', 'Autenticación fallida. NIP inválido o no proporcionado.');
        }
    
        $colaboradores = PerfilPsicometrico::all();
        return view('admin.colaboradores', compact('colaboradores'));
    }

    public function mostrarResumen(Request $request)
    {
    
    $userId = $request->query('user_id');

    $perfil = PerfilPsicometrico::where('user_id', $userId)->firstOrFail();

    $respuestasTotales = EncuestaRespuestas::where('user_id', $userId)->count();

    if ($respuestasTotales < 20) {
        return redirect()->route('admin.colaboradores')
                         ->with('error', 'Este colaborador aún no ha respondido suficientes preguntas para generar un resumen.');
    }

    $inicioSemana = now()->startOfWeek();
    $finSemana = now()->endOfWeek();

    $inicioSemanaFormatted = $inicioSemana->locale('es')->translatedFormat('d \d\e F');
    $finSemanaFormatted = $finSemana->locale('es')->translatedFormat('d \d\e F');

    $resumenActual = ResumenSemanalGerente::where('user_id', $userId)
    ->whereDate('semana_inicio', $inicioSemana)
    ->first();

    if (!$resumenActual) {
    // Obtener respuestas de la semana actual
    $respuestas = EncuestaRespuestas::where('user_id', $userId)
        ->whereBetween('created_at', [$inicioSemana, $finSemana])
        ->orderBy('created_at', 'asc')
        ->get(['pregunta', 'respuesta', 'created_at']);

    $bloqueRespuestas = $respuestas->map(function ($r) {
        return "- {$r->created_at->format('d/m')}: {$r->pregunta} → {$r->respuesta}/5";
    })->implode("\n");

    // Crear contexto para el gerente
    $contexto = <<<CTX
    Perfil del colaborador:
    - Nombre: {$perfil->nombre}
    - Edad: {$perfil->edad}
    - Nivel educativo: {$perfil->nivel_educativo}
    - Área: {$perfil->area}
    - Antigüedad: {$perfil->antiguedad_anios} años
    - Mentalidad empresarial: {$perfil->respuesta_mentalidad}
    - Estilo de comunicación: {$perfil->respuesta_comunicacion}

    Respuestas de esta semana:
    $bloqueRespuestas
    CTX;

    // Prompt para IA enfocado al gerente
    $prompt = <<<PROMPT
    Eres un experto en gestión de talento y liderazgo organizacional. A continuación, se describe el contexto y respuestas de un colaborador:

    $contexto

    Genera un informe para el gerente que incluya:
    1. Un análisis del estado emocional, motivación y actitud del colaborador esta semana.
    2. Señales de alerta o situaciones a considerar desde la perspectiva de liderazgo.
    3. Recomendaciones claras y accionables para el gerente sobre cómo apoyar al colaborador esta semana.

    Devuelve únicamente un JSON con la siguiente estructura:
    {
      "informe": "Texto con el análisis principal para el gerente",
      "recomendaciones": ["Recomendación 1", "Recomendación 2", ...]
    }
    PROMPT;

    $client = OpenAI::client(env('OPENAI_API_KEY'));

    $response = $client->chat()->create([
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'system', 'content' => 'Eres un experto en liderazgo y desarrollo organizacional.'],
            ['role' => 'user', 'content' => $prompt],
        ],
    ]);


     $json = json_decode($response['choices'][0]['message']['content'], true);

        $resumenActual = ResumenSemanalGerente::create([
            'user_id' => $userId,
            'semana_inicio' => $inicioSemana,
            'semana_fin' => $finSemana,
            'contenido' => $json,
        ]);
    }

    return view('admin.colaborador', [
        'perfil' => $perfil,
        'informeGerente' => $resumenActual->contenido,
        'fechaInicioSemana' => $inicioSemanaFormatted,
        'fechaFinSemana' => $finSemanaFormatted,
    ]);
    }
}
