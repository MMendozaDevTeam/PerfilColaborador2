@extends('layouts.app')

@section('title', 'Resumen del Colaborador para el Gerente')

@section('content')
<div class="container py-4">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary">📋 Informe de Colaborador para Gerencia</h2>
        <p class="text-muted">Análisis estratégico para un mejor acompañamiento del colaborador.</p>
    </div>

    <!-- Info general del colaborador -->
    <div class="card shadow-sm mb-4 border-start border-5 border-primary">
        <div class="card-body">
            <h4 class="fw-bold mb-1">👤 {{ $perfil->nombre }}</h4>
            <p class="mb-0 text-muted">Área: <strong>{{ $perfil->area }}</strong></p>
            <p class="mb-0 text-muted">
                Antigüedad: 
                <strong>
                    {{ $perfil->antiguedad_anios == 0 ? 'Es nuevo en la empresa' : $perfil->antiguedad_anios . ' años' }}
                </strong>
            </p>           
            <p class="text-muted mb-1">
                Semana evaluada: <strong>{{ $fechaInicioSemana }} al {{ $fechaFinSemana }}</strong>
            </p>      
        </div>
    </div>

    <!-- Perfil psicométrico -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-info shadow-sm h-100">
                <div class="card-header bg-info text-white">
                    🗣 Estilo de Comunicación
                </div>
                <div class="card-body">
                    <h5 class="fw-bold">{{ $perfil->respuesta_comunicacion }}</h5>
                    <p class="mb-0">Interpretación basada en sus respuestas de comunicación.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-success shadow-sm h-100">
                <div class="card-header bg-success text-white">
                    💼 Mentalidad Empresarial
                </div>
                <div class="card-body">
                    <h5 class="fw-bold">{{ $perfil->respuesta_mentalidad }}</h5>
                    <p class="mb-0">Resultado basado en su enfoque frente al cambio, esfuerzo y error.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Informe generado por IA -->
    <div class="card border-primary shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            📊 Informe de IA sobre el estado actual
        </div>
        <div class="card-body">
            <p class="mb-0 lh-lg">{!! nl2br(e($informeGerente['informe'] ?? 'No disponible')) !!}</p>
        </div>
    </div>

    <!-- Recomendaciones estratégicas -->
    @if (!empty($informeGerente['recomendaciones']))
    <div class="card border-primary shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            🎯 Recomendaciones para el gerente
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                @foreach ($informeGerente['recomendaciones'] as $reco)
                    <li class="list-group-item">🛠️ {{ $reco }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif
</div>
@endsection
