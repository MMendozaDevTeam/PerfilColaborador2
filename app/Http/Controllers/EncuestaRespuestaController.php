<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EncuestaRespuestas;
use Illuminate\Support\Facades\Validator;


class EncuestaRespuestaController extends Controller
{
public function store(Request $request)
    {
    $userId = $request->input('user_id');

    // Validar existencia de user_id y formato de respuestas
    $validator = Validator::make($request->all(), [
        'user_id' => 'required|integer|exists:perfiles_psicometricos,user_id',
        'preguntas' => 'required|array|min:1',
        'respuestas' => 'required|array|min:1',
        'respuestas.*' => 'required|integer|min:1|max:5',
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    $preguntas = $request->input('preguntas');
    $respuestas = $request->input('respuestas');


    foreach ($preguntas as $index => $preguntaTexto) {
        EncuestaRespuestas::create([
            'user_id' => $userId,
            'pregunta' => $preguntaTexto,
            'respuesta' => $respuestas[$index],
        ]);
    }

    return redirect()->route('opciones.colaborador' , ['user_id' => $userId])->with('success', 'Respuestas guardadas correctamente, vuelve mañana para contestar una nueva encuesta');
    }
}
