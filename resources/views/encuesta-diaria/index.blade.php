@extends('layouts.app')

@section('title', 'Encuesta Diaria')

@section('content')
<div class="container py-4">
    <h2 class="text-center mb-4">📋 Encuesta Diaria</h2>
    <p class="text-muted text-center mb-5">Perfil detectado: <strong>{{ $perfil->respuesta_comunicacion }}</strong> / <strong>{{ $perfil->respuesta_mentalidad }}</strong></p>

    <form action="#" method="POST">
        @csrf
        @foreach ($preguntas as $i => $pregunta)
            <div class="mb-4">
                <label class="form-label">{{ $pregunta }}</label>
                <textarea name="respuesta_{{ $i }}" class="form-control" rows="2" required></textarea>
            </div>
        @endforeach

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Enviar encuesta</button>
        </div>
    </form>
</div>
@endsection
