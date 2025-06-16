@extends('layouts.app')

@section('title', 'Mi Perfil Psicométrico')

@section('content')
<div class="container py-4">
    <div class="text-center mb-5">
        <h2 class="fw-bold">🧠 Mi Perfil Psicométrico</h2>
        <p class="text-muted">Basado en tus respuestas a los tests de comunicación y mentalidad empresarial</p>
    </div>

    <!-- Resumen general -->
    <div class="card shadow-sm mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-title mb-1">👤 Juan Pérez</h5>
                <p class="mb-0 text-muted">Fecha de evaluación: <strong>16 de junio de 2025</strong></p>
            </div>
            <span class="badge bg-success fs-6">Evaluación completada</span>
        </div>
    </div>

    <div class="row">
        <!-- Perfil de Comunicación -->
        <div class="col-md-6 mb-4">
            <div class="card border-info shadow-sm h-100">
                <div class="card-header bg-info text-white">
                    🗣 Perfil de Comunicación
                </div>
                <div class="card-body">
                    <h5 class="fw-bold text-info">Tipo: Delfín</h5>
                    <p class="mb-2">
                        <strong>Fortalezas:</strong><br>
                        Empático, sabe escuchar, cooperativo, busca la armonía.
                    </p>
                    <p class="mb-0">
                        <strong>Áreas de mejora:</strong><br>
                        Evita la confrontación directa, debe fortalecer la toma de decisiones difíciles.
                    </p>
                </div>
            </div>
        </div>

        <!-- Mentalidad Empresarial -->
        <div class="col-md-6 mb-4">
            <div class="card border-success shadow-sm h-100">
                <div class="card-header bg-success text-white">
                    💼 Mentalidad Empresarial
                </div>
                <div class="card-body">
                    <h5 class="fw-bold text-success">Tendencia: Mentalidad de Crecimiento</h5>
                    <p>
                        <span class="badge bg-success me-2">Crecimiento: 26 pts</span>
                        <span class="badge bg-secondary">Fija: 9 pts</span>
                    </p>
                    <p class="mb-0">
                        <strong>Interpretación:</strong><br>
                        Muestras apertura al aprendizaje, tolerancia a la dificultad y resiliencia frente al error.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recomendaciones -->
    <div class="card shadow-sm mb-4 border-dark">
        <div class="card-header bg-dark text-white">
            🎯 Recomendaciones Personalizadas
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">✅ Participa en proyectos colaborativos donde puedas escuchar y guiar.</li>
                <li class="list-group-item">✅ Atrévete a asumir retos aunque impliquen equivocaciones: estás preparado.</li>
                <li class="list-group-item">✅ Practica situaciones de conflicto para mejorar tu seguridad en conversaciones difíciles.</li>
                <li class="list-group-item">✅ Haz seguimiento de tus avances personales y reflexiona semanalmente.</li>
            </ul>
        </div>
    </div>
</div>
@endsection
