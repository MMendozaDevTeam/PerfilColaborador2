<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PerfilPsicometrico;
use App\Models\EncuestaRespuestas;
use App\Models\ResumenMensual;
use App\Models\ResumenSemanal;
use Illuminate\Support\Carbon;
use OpenAI;


class PerfilPsicometricoController extends Controller
{
     public function store(Request $request)
    {
        // Validación básica
        $validated = $request->validate([
            'edad' => 'required|integer|min:15|max:99',
            'sexo' => 'required|in:Masculino,Femenino,No binario,Otro',
            'nivel_educativo' => 'required|string|max:100',
            'antiguedad_anios' => 'required_unless:es_nuevo,1|nullable|integer|min:0|max:50',
            'es_nuevo' => 'nullable|boolean',
            'area' => 'required|string|max:100',
            'estado_civil' => 'required|string|max:50',
        ]);

        // Si tienes autenticación de usuario, puedes incluir el user_id
           $userId = $request->input('user_id');

        // Crear el perfil
          $perfil = PerfilPsicometrico::where('user_id', $userId)->first();

           if ($perfil) {
               $perfil->update($validated);
               return redirect()->back()->with('success', 'Datos personales actualizados correctamente.');
           } else {
               return redirect()->back()->with('error', 'No se encontró el perfil para actualizar.');
           }
    }

    public function crearPerfilBasico(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'user_id' => 'required|integer|unique:perfiles_psicometricos,user_id',
        ]);
    
        PerfilPsicometrico::create([
            'nombre' => $validated['nombre'],
            'user_id' => $validated['user_id'],
        ]);
    
        return redirect()->back()->with('success', 'Perfil creado correctamente. Ya puedes ingresar tu NIP.');
    }

    public function mostrarPerfilColaborador(Request $request)
    {
        $userId = $request->query('user_id');
        $perfil = PerfilPsicometrico::where('user_id', $userId)->firstOrFail();

        $respuestasTotales = EncuestaRespuestas::where('user_id', $userId)->count();

        if ($respuestasTotales < 20) {
            return redirect()->route('opciones.colaborador', ['user_id' => $userId])
                             ->with('error', 'Debes responder al menos 20 preguntas antes de ver tu perfil generado por IA.');
        }
    
        $inicioSemana = now()->startOfWeek(); // lunes
        $finSemana = now()->endOfWeek();     // domingo
    
        $resumenActual = ResumenSemanal::where('user_id', $userId)
            ->whereDate('semana_inicio', $inicioSemana)
            ->first();
    
        if (!$resumenActual) {
            $resumenGenerado = $this->generarResumenSemanalIA($perfil, $inicioSemana, $finSemana);
    
            $resumenActual = ResumenSemanal::create([
                'user_id' => $userId,
                'semana_inicio' => $inicioSemana,
                'semana_fin' => $finSemana,
                'contenido' => $resumenGenerado,
            ]);
        }

        $jsonContenido = json_decode($resumenActual->contenido, true);
    
        return view('perfil-colaborador', [
            'perfil' => $perfil,
            'fechaEvaluacion' => now()->format('d \d\e F \d\e Y'),
            'informeGeneral' => $jsonContenido ?? [
                'resumen' => [
                    'emociones' => 'No disponible.',
                    'motivacion' => 'No disponible.',
                    'actitud' => 'No disponible.'
                ],
                'recomendaciones' => [],
                'consejos_practicos' => [],
            ],
        ]);

    }

    private function generarResumenSemanalIA($perfil, $inicioSemana, $finSemana): string
    {
    // Respuestas de la semana
    $respuestas = EncuestaRespuestas::where('user_id', $perfil->user_id)
        ->whereBetween('created_at', [$inicioSemana, $finSemana])
        ->orderBy('created_at', 'asc')
        ->get(['pregunta', 'respuesta', 'created_at']);

    $bloqueRespuestas = $respuestas->map(function ($r) {
        return "- {$r->created_at->format('d/m')}: {$r->pregunta} → {$r->respuesta}/5";
    })->implode("\n");

    // Resúmenes anteriores
    $resumenesAnteriores = ResumenMensual::where('user_id', $perfil->user_id)
        ->orderBy('created_at', 'asc')
        ->get();

    $bloqueResumenes = $resumenesAnteriores->map(function ($resumen) {
        $mes = Carbon::parse($resumen->created_at)->format('F Y');
        return "📅 [$mes]\n" . trim($resumen->contenido);
    })->implode("\n\n");

    // Contexto
    $contexto = <<<TXT
    Perfil del colaborador:
    - Nombre: {$perfil->nombre}
    - Edad: {$perfil->edad}
    - Sexo: {$perfil->sexo}
    - Nivel educativo: {$perfil->nivel_educativo}
    - Área: {$perfil->area}
    - Antigüedad: {$perfil->antiguedad_anios} años
    - Mentalidad empresarial: {$perfil->respuesta_mentalidad}
    - Estilo de comunicación: {$perfil->respuesta_comunicacion}
    - Es nuevo en la empresa: {$perfil->es_nuevo}

    Resúmenes mensuales anteriores:
    {$bloqueResumenes}

    Respuestas de esta semana:
    $bloqueRespuestas
    TXT;

    // Prompt
    $prompt = <<<PROMPT
    Eres un experto en psicología organizacional y coaching personal. A partir del siguiente contexto:
    
    $contexto
    
    Escribe un resumen claro, motivacional y práctico para el colaborador que contenga:
    1. Cómo se ha sentido esta semana (emociones, motivación, actitud), extiende esta parte.
    2. 5 Recomendaciones para mejorar su bienestar personal y laboral.
    3. 5 Consejos prácticos para aplicar esta semana.

    Usa un tono empático y motivador. **Dirígete directamente al colaborador usando "tú" en todo momento**.
    
    Devuelve el resultado en este formato JSON plano:
    {
      "resumen": {
        "emociones": "...",
        "motivacion": "...",
        "actitud": "..."
      },
      "recomendaciones": ["...", "..."],
      "consejos_practicos": ["...", "..."]
    }

    PROMPT;
    
        // Llamar a la API
        $client = OpenAI::client(env('OPENAI_API_KEY'));
        $response = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'Eres un experto en psicología organizacional.'],
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);
    
        return $response['choices'][0]['message']['content'] ?? 'No se pudo generar el informe.';
    }


}
