<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/encuesta-perfil-comunicacion', function () {
    return view('encuesta-perfil-comunicacion');
});

Route::get('/encuesta-mentalidad-empresarial', function () {
    return view('encuesta-mentalidad-empresarial');
});

Route::post('/perfil-comunicacion', function (\Illuminate\Http\Request $request) {
    // Aquí podrías guardar en base de datos, por ahora solo lo mostramos
    return back()->with('success', 'Respuestas guardadas correctamente.');
})->name('perfil-comunicacion.guardar');


Route::post('/test-mentalidad', function (\Illuminate\Http\Request $request) {
    // Aquí podrías guardar en base o hacer análisis
    return back()->with('success', 'Test enviado correctamente.');
})->name('test-mentalidad.guardar');
