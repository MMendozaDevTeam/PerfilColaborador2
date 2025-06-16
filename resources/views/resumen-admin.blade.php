@extends('layouts.app')

@section('title', 'Perfiles Psicométricos de Colaboradores')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4 text-center">🧑‍💼 Perfiles Psicométricos del Equipo</h2>

    <!-- Selector de colaboradores -->
    <form method="GET"  class="mb-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <select class="form-select" name="colaborador" onchange="this.form.submit()">
                    <option disabled selected>Selecciona un colaborador</option>
                    <option value="juan" {{ request('colaborador') == 'juan' ? 'selected' : '' }}>Juan Pérez</option>
                    <option value="maria" {{ request('colaborador') == 'maria' ? 'selected' : '' }}>María Gómez</option>
                    <option value="carlos" {{ request('colaborador') == 'carlos' ? 'selected' : '' }}>Carlos Ruiz</option>
                </select>
            </div>
        </div>
    </form>

    @if(request('colaborador'))
        @php
            // Simulación de datos por colaborador
            $perfiles = [
                'juan' => [
                    'nombre' => 'Juan Pérez',
                    'comunicacion' => 'Delfín',
                    'mentalidad' => 'Crecimiento',
                    'crecimiento' => 26,
                    'fija' => 9,
                    'recomendaciones' => [
                        'Ofrecer espacios seguros para expresar ideas y emociones.',
                        'Evitar imponer decisiones sin justificación.',
                        'Darle autonomía en tareas y reconocer públicamente sus esfuerzos.',
                    ]
                ],
                'maria' => [
                    'nombre' => 'María Gómez',
                    'comunicacion' => 'León',
                    'mentalidad' => 'Intermedia',
                    'crecimiento' => 20,
                    'fija' => 18,
                    'recomendaciones' => [
                        'Asignar desafíos importantes que involucren liderazgo.',
                        'Evitar supervisión excesiva: confía en su criterio.',
                        'Utiliza feedback directo y centrado en resultados.',
                    ]
                ],
                'carlos' => [
                    'nombre' => 'Carlos Ruiz',
                    'comunicacion' => 'Búho',
                    'mentalidad' => 'Fija',
                    'crecimiento' => 13,
                    'fija' => 22,
                    'recomendaciones' => [
                        'Darle claridad estructural y procedimientos detallados.',
                        'Evitar ambigüedades o cambios sin previo aviso.',
                        'Fomentar poco a poco el pensamiento flexible con preguntas.',
                    ]
                ]
            ];
            $perfil = $perfiles[request('colaborador')];
        @endphp

        <!-- Perfil seleccionado -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                👤 Perfil de: {{ $perfil['nombre'] }}
            </div>
            <div class="card-body row">
                <div class="col-md-6">
                    <h5 class="fw-bold text-info">🗣 Comunicación: {{ $perfil['comunicacion'] }}</h5>
                    <h5 class="fw-bold text-success">💼 Mentalidad: {{ $perfil['mentalidad'] }}</h5>
                    <p>
                        <span class="badge bg-success me-2">Crecimiento: {{ $perfil['crecimiento'] }} pts</span>
                        <span class="badge bg-secondary">Fija: {{ $perfil['fija'] }} pts</span>
                    </p>
                </div>
                <div class="col-md-6">
                    <h5 class="fw-bold text-dark">🎯 Recomendaciones para el gerente</h5>
                    <ul class="list-group list-group-flush">
                        @foreach($perfil['recomendaciones'] as $reco)
                            <li class="list-group-item">✅ {{ $reco }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
