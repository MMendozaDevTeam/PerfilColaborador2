<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\EncuestaDiariaController;
use App\Http\Controllers\PerfilPsicometricoController;
use App\Models\PerfilPsicometrico;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/encuesta-perfil-comunicacion', function () {
    return view('encuesta-perfil-comunicacion');
});

Route::get('/encuesta-mentalidad-empresarial', function () {
    return view('encuesta-mentalidad-empresarial');
});

Route::get('/perfil-colaborador', function () {
    return view('perfil-colaborador');
});

Route::get('/resumen-admin', function () {
    return view('resumen-admin');
});

Route::get('/bienvenida', function () {
    return view('bienvenida');
});

Route::get('/encuesta-diaria', [EncuestaDiariaController::class, 'index'])->name('encuesta.diaria');


Route::post('/perfil-comunicacion', function (\Illuminate\Http\Request $request) {
    // Aquí podrías guardar en base de datos, por ahora solo lo mostramos
    return back()->with('success', 'Respuestas guardadas correctamente.');
})->name('perfil-comunicacion.guardar');


Route::post('/test-mentalidad', function (\Illuminate\Http\Request $request) {
    // Aquí podrías guardar en base o hacer análisis
    return back()->with('success', 'Test enviado correctamente.');
})->name('test-mentalidad.guardar');

Route::get('/acceso', function (Request $request) {
    $userId = $request->query('pin'); // ahora 'pin' es el user_id

    $perfil = PerfilPsicometrico::where('user_id', $userId)->first();

    if (!$perfil) {
        return redirect('/bienvenida')->with('error', 'NIP no válido o usuario no encontrado.');
    }

    // Verificamos si ya respondió ambos tests psicométricos
    if ($perfil->respuesta_comunicacion && $perfil->respuesta_mentalidad) {
        // Redirigir a encuesta diaria
        return redirect()->route('encuesta.diaria', ['user_id' => $perfil->user_id]);
    }

    // Redirigir a encuestas psicométricas (puedes personalizar si mentalidad o comunicación falta)
    return redirect()->route('encuestas.psicometricas', ['user_id' => $perfil->user_id]);
});

Route::post('/perfil-psicometrico', [PerfilPsicometricoController::class, 'store'])->name('perfil.psicometrico.store');
