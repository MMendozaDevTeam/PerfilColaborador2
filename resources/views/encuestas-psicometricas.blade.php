@extends('layouts.app')

@section('title', 'Encuestas Psicométricas')

@section('content')
<div class="container py-5 text-center">
    <h2 class="mb-4">🧠 Completa tus pruebas psicométricas</h2>
    <p class="text-muted">Selecciona la prueba que deseas realizar primero.</p>

    <div class="row justify-content-center g-4">
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('encuesta.mentalidad', ['user_id' => request()->query('user_id')]) }}" class="btn btn-outline-primary w-100 py-3">
                🧠 Encuesta de Mentalidad Empresarial
            </a>
        </div>
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('encuesta.comunicacion', ['user_id' => request()->query('user_id')]) }}" class="btn btn-outline-success w-100 py-3">
                🗣 Encuesta de Perfil de Comunicación
            </a>
        </div>
    </div>

    <div class="mt-5">
        <p class="text-muted">Después de completar ambas encuestas, se te redirigirá automáticamente a tu seguimiento diario.</p>
    </div>
</div>
@endsection
