<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PerfilPsicometrico;

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
}
