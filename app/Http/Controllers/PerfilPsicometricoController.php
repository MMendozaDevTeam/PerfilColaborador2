<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PerfilPsicometricoController extends Controller
{
     public function store(Request $request)
    {
        // Validación básica
        $validated = $request->validate([
            'edad' => 'required|integer|min:15|max:99',
            'sexo' => 'required|in:Masculino,Femenino,No binario,Otro',
            'nivel_educativo' => 'required|string|max:100',
            'antiguedad_anios' => 'required|integer|min:0|max:50',
            'area' => 'required|string|max:100',
            'estado_civil' => 'required|string|max:50',
            'respuesta_mentalidad' => 'required|in:Creativa,Zona de confort,Intermedia',
            'respuesta_comunicacion' => 'required|in:León,Pavo Real,Delfín,Búho',
        ]);

        // Si tienes autenticación de usuario, puedes incluir el user_id
        $validated['user_id'] = auth()->id();

        // Crear el perfil
        PerfilPsicometrico::create($validated);

        return redirect()->back()->with('success', 'Perfil psicométrico guardado correctamente.');
    }
}
