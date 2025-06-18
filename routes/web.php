<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\EncuestaDiariaController;
use App\Http\Controllers\PerfilPsicometricoController;
use App\Http\Controllers\EncuestaPsicometricaController;
use App\Http\Controllers\EncuestaRespuestaController;
use App\Models\PerfilPsicometrico;
use OpenAI\Laravel\Facades\OpenAI;



Route::get('/', function () {
    return view('welcome');
});


Route::get('/encuesta-perfil-comunicacion', function (Request $request) {
    $userId = $request->query('user_id');

    // Opcional: verificar que el usuario exista
    $perfil = PerfilPsicometrico::where('user_id', $userId)->first();
    if (!$perfil) {
        return redirect('/bienvenida')->with('error', 'Usuario no encontrado.');
    }

    if ($perfil->respuesta_comunicacion) {
        return redirect()->route('encuestas.psicometricas', ['user_id' => $userId])
        ->with('success', 'Ya completaste esta encuesta.');
    }

    return view('encuesta-perfil-comunicacion', compact('userId'));
})->name('encuesta.comunicacion');


Route::get('/encuesta-mentalidad-empresarial', function (Request $request) {
    $userId = $request->query('user_id');

    // Opcional: verificar que el usuario exista
    $perfil = PerfilPsicometrico::where('user_id', $userId)->first();
    if (!$perfil) {
        return redirect('/bienvenida')->with('error', 'Usuario no encontrado.');
    }

    if ($perfil->respuesta_mentalidad) {
        return redirect()->route('encuestas.psicometricas', ['user_id' => $userId])
        ->with('success', 'Ya completaste esta encuesta.');
    }

    return view('encuesta-mentalidad-empresarial', compact('userId'));
})->name('encuesta.mentalidad');


Route::get('/perfil-colaborador', function () {
    return view('perfil-colaborador');
});

Route::get('/resumen-admin', function () {
    return view('resumen-admin');
});

Route::get('/bienvenida', function () {
    return view('bienvenida');
})->name('bienvenida');

Route::get('/encuestas-psicometricas', function (Request $request) {
    $userId = $request->query('user_id');
    return view('encuestas-psicometricas', ['user_id' => $userId]);
})->name('encuestas.psicometricas');

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
Route::post('/guardar-mentalidad', [EncuestaPsicometricaController::class, 'guardarMentalidad'])->name('encuesta.mentalidad.guardar');
Route::post('/guardar-comunicacion', [EncuestaPsicometricaController::class, 'guardarComunicacion'])->name('encuesta.comunicacion.guardar');
Route::post('/crear-perfil', [PerfilPsicometricoController::class, 'crearPerfilBasico'])->name('perfil.nuevo');
Route::post('/encuesta-diaria/guardar', [EncuestaRespuestaController::class, 'store'])->name('encuesta.diaria.store');
