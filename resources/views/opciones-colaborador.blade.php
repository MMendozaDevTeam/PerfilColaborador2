@extends('layouts.app')

@section('title', 'Acciones del Colaborador')

@section('content')
<div class="container py-5">
    <div class="text-center mb-4">
        <h2 class="fw-bold">👋 ¡Hola, {{ $colaborador->nombre }}!</h2>
        <p class="text-muted">¿Qué deseas hacer hoy?</p>
    </div>

    {{-- Mensajes de sesión --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-6 d-grid gap-3">
            <a href="{{ route('encuesta.diaria', ['user_id' => $colaborador->user_id]) }}" class="btn btn-primary btn-lg">
                📝 Realizar Encuesta Diaria
            </a>

            <a href="{{ route('perfil.colaborador', ['user_id' => $colaborador->user_id]) }}" class="btn btn-outline-secondary btn-lg">
                📊 Ver Resumen Semanal
            </a>
        </div>
    </div>
</div>
@endsection
