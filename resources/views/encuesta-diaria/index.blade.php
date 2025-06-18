@extends('layouts.app')

@section('title', 'Encuesta Diaria')

@section('content')
<div class="container py-4">
    <h2 class="text-center mb-4">📋 Encuesta Diaria</h2>
    <p class="text-muted text-center mb-5">Perfil detectado: <strong>{{ $perfil->respuesta_comunicacion }}</strong> / <strong>{{ $perfil->respuesta_mentalidad }}</strong></p>

    <form method="POST" action="{{ route('encuesta.diaria.store') }}">
        @csrf

        @if ($errors->any())
        <div class="alert alert-danger">
            <strong>⚠️ Se encontraron errores:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <input type="hidden" name="user_id" value="{{ request()->query('user_id') }}">
    
        {{-- Guardar el texto de cada pregunta como campos ocultos --}}
        @foreach ($preguntas as $index => $pregunta)
            <input type="hidden" name="preguntas[{{ $index }}]" value="{{ $pregunta }}">
        @endforeach
    
        {{-- Mostrar las preguntas y opciones tipo Likert --}}
        @foreach ($preguntas as $index => $pregunta)
            <div class="mb-4">
                <label class="form-label fw-bold">{{ $pregunta }}</label>
                <div class="d-flex justify-content-between px-3">
                    @for ($i = 1; $i <= 5; $i++)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="respuestas[{{ $index }}]" id="pregunta_{{ $index }}_{{ $i }}" value="{{ $i }}" required>
                            <label class="form-check-label" for="pregunta_{{ $index }}_{{ $i }}">{{ $i }}</label>
                        </div>
                    @endfor
                </div>
            </div>
        @endforeach
    
        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary">Enviar respuestas</button>
        </div>
    </form>


</div>
@endsection
