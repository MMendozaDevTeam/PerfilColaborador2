@extends('layouts.app')

@section('title', 'Análisis de Colaboradores')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4 text-center">📊 Análisis Inteligente de Colaboradores</h2>

    <!-- Selector dinámico -->
    <form method="GET" class="mb-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <select class="form-select" name="colaborador_id" onchange="this.form.submit()">
                    <option disabled selected>Selecciona un colaborador</option>
                    @foreach ($colaboradores as $col)
                        <option value="{{ $col->id }}" {{ request('colaborador_id') == $col->id ? 'selected' : '' }}>
                            {{ $col->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    @if(isset($perfil) && $informeGerente)
        <!-- Detalles del colaborador -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                👤 Perfil de: {{ $perfil->nombre }}
            </div>
            <div class="card-body row">
                <div class="col-md-6">
                    <h5 class="fw-bold text-info">🗣 Comunicación: {{ $perfil->respuesta_comunicacion }}</h5>
                    <h5 class="fw-bold text-success">💼 Mentalidad: {{ $perfil->respuesta_mentalidad }}</h5>
                    <p>
                        <span class="badge bg-success me-2">Edad: {{ $perfil->edad }}</span>
                        <span class="badge bg-secondary">Área: {{ $perfil->area }}</span>
                    </p>
                </div>
                <div class="col-md-6">
                    <h5 class="fw-bold text-dark">🧠 Resumen generado por IA para el gerente</h5>
                    <p class="lh-lg">{!! nl2br(e($informeGerente)) !!}</p>
                </div>
            </div>
        </div>
    @elseif(request('colaborador_id'))
        <div class="alert alert-warning text-center">
            No se pudo generar el análisis del colaborador. Intenta nuevamente más tarde.
        </div>
    @endif
</div>
@endsection
