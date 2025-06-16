@extends('layouts.app')

@section('title', 'Test de Mentalidad Empresarial')

@section('content')
<h2 class="mb-4 text-center">Test de Mentalidad Empresarial</h2>

<p class="text-center text-muted mb-4">
    Selecciona el punto de la escala que represente con cuál afirmación te identificas más. 
    La escala va de estar totalmente de acuerdo con la afirmación de la izquierda hasta estar totalmente de acuerdo con la afirmación de la derecha.
</p>

<form method="POST" action="{{ route('test-mentalidad.guardar') }}">
    @csrf

    @php
        $pares = [
            [
                "left" => "Cuando no consigo completar una tarea difícil, me propongo esforzarme más la próxima vez que trabaje en ella.",
                "right" => "Soy más feliz en el trabajo cuando realizo una tarea en la que sé que no voy a cometer ningún error."
            ],
            [
                "left" => "Prefiero trabajar en tareas que me obliguen a aprender cosas nuevas.",
                "right" => "Las cosas que más disfruto son las que mejor hago."
            ],
            [
                "left" => "La oportunidad de aprender cosas nuevas es importante para mí.",
                "right" => "Las opiniones de los demás sobre lo bien que hago ciertas cosas son importantes."
            ],
            [
                "left" => "Doy lo mejor de mí cuando estoy trabajando en una tarea difícil.",
                "right" => "Me siento inteligente cuando hago algo sin cometer errores."
            ],
            [
                "left" => "Me esfuerzo por mejorar mis resultados anteriores.",
                "right" => "Me gusta estar bastante seguro de que puedo realizar una tarea con éxito antes de intentarla."
            ],
            [
                "left" => "Para mí, es importante tener la oportunidad de ampliar el abanico de cosas que puedo hacer.",
                "right" => "Me gusta trabajar en tareas que he hecho bien en el pasado."
            ]
        ];
    @endphp

    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th style="width: 30%">Mentalidad de crecimiento</th>
                    <th colspan="5">Escala de identificación</th>
                    <th style="width: 30%">Mentalidad fija</th>
                </tr>
                <tr class="text-muted small">
                    <td></td>
                    <td>←</td><td></td><td>●</td><td></td><td>→</td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                @foreach ($pares as $i => $par)
                <tr>
                    <td class="text-start">{{ $par['left'] }}</td>
                    @for ($j = 1; $j <= 5; $j++)
                        <td>
                            <input class="form-check-input" type="radio" name="respuestas[{{ $i }}]" id="q{{ $i }}_{{ $j }}" value="{{ $j }}" required>
                            <label class="form-check-label d-block" for="q{{ $i }}_{{ $j }}">•</label>
                        </td>
                    @endfor
                    <td class="text-start">{{ $par['right'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="text-center mt-4">
        <button type="submit" class="btn btn-success">Enviar respuestas</button>
    </div>
</form>
@endsection
