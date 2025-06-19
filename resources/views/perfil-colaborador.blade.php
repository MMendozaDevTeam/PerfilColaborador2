@extends('layouts.app')

@section('title', 'Mi Perfil Semanal Generado por IA')

@section('content')
<div class="container py-4">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary">🤖 Perfil Semanal del Colaborador</h2>
        <p class="text-muted">Generado automáticamente por IA según tus respuestas y desempeño de esta semana</p>
        <div class="d-inline-block px-3 py-2 bg-light rounded border border-primary mt-2">
            <i class="bi bi-lightning-charge-fill text-warning me-1"></i>
            <span class="text-primary fw-semibold">IA en acción</span>
        </div>
    </div>

    <!-- Resumen General -->

    <div class="card shadow-sm border-start border-5 border-secondary mb-4">
        <div class="card-header bg-secondary text-white">
            📋 Informe Emocional y de Actitud
        </div>
        <div class="card-body lh-lg">
            <p><strong>🧠 Estado emocional:</strong> {{ $informeGeneral['resumen']['emociones'] }}</p>
            <p><strong>🚀 Motivación:</strong> {{ $informeGeneral['resumen']['motivacion'] }}</p>
            <p><strong>🌟 Actitud:</strong> {{ $informeGeneral['resumen']['actitud'] }}</p>
        </div>
    </div>

    <div class="card shadow-sm border-start border-5 border-secondary mb-4">
        <div class="card-header bg-secondary text-white">
            📋 Informe Emocional y de Actitud
        </div>
        <div class="card-body lh-lg">
            <p><strong>🧠 Estado emocional:</strong> {{ $informeGeneral['resumen']['emociones'] }}</p>
            <p><strong>🚀 Motivación:</strong> {{ $informeGeneral['resumen']['motivacion'] }}</p>
            <p><strong>🌟 Actitud:</strong> {{ $informeGeneral['resumen']['actitud'] }}</p>
        </div>
    </div>

    <!-- Recomendaciones -->
    <div class="card shadow-sm border-start border-5 border-dark mb-4">
        <div class="card-header bg-dark text-white">
            🎯 Recomendaciones para tu bienestar
        </div>
        <div class="card-body px-4">
            <ul class="list-group list-group-flush">
                @foreach ($informeGeneral['recomendaciones'] as $r)
                    <li class="list-group-item border-0 ps-0">
                        <i class="bi bi-check-circle-fill text-success me-2"></i>{{ $r }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Consejos Prácticos -->
    <div class="card shadow-sm border-start border-5 border-info mb-5">
        <div class="card-header bg-info text-white">
            💡 Consejos prácticos para esta semana
        </div>
        <div class="card-body px-4">
            <ul class="list-group list-group-flush">
                @foreach ($informeGeneral['consejos_practicos'] as $c)
                    <li class="list-group-item border-0 ps-0">
                        <i class="bi bi-lightbulb-fill text-warning me-2"></i>{{ $c }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
