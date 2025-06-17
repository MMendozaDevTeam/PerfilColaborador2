@extends('layouts.app')

@section('title', 'Encuesta de Perfil de Comunicación')

@section('content')
<h2 class="mb-4 text-center">Encuesta de Perfil de Comunicación</h2>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger mt-4">
        {{ session('error') }}
    </div>
@endif

<form method="POST" action="{{ route('encuesta.comunicacion.guardar') }}">
    @csrf
    <input type="hidden" name="user_id" value="{{ request()->query('user_id') }}">
    <div class="table-responsive">
        <table class="table table-bordered text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Pregunta</th>
                    <th>Respuesta A</th>
                    <th>Respuesta B</th>
                    <th>Respuesta C</th>
                    <th>Respuesta D</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $preguntas = [
                        '¿De qué hablas?' => ['de metas y resultados', 'sueños y aspiraciones', 'sentimientos y experiencias', 'datos y cantidades'],
                        'Ritmo con que hablas' => ['muy rápido', 'rápido', 'despacio', 'moderado'],
                        'Forma en que se viste / apariencia personal' => ['marcas de lujo, buen gusto, formal, elegante', 'colores fuertes, moderno, casual', 'suave, colores neutro. casual, informal', 'conservador, tradicional, profesional'],
                        'Estilo de comunicación' => ['directo al punto', 'animado, impulsivo', 'reflexivo, amigable', 'específico, conciso, preciso'],
                        'Lo motiva' => ['los resultados', 'el conocimiento', 'la aprobación', 'la actividad'],
                        'Lo estimula' => ['las presiones, los cambios', 'lo interesante, lo divertido', 'el compañerismo, el apoyo', 'la precisión, la información'],
                        'Expresa su enojo mostrándose' => ['impaciente, agresivo', 'frustrado, puede estallar', 'callado. Se siente confundido', 'tarda en enojarse. Enfoque racional'],
                        'Estilo de trabajo' => ['intenso, dirigido. Hace varias cosas a la vez', 'le gusta la libertad. Interactúa con muchas personas', 'buena disposición. Cooperativo. Servicial', 'cuidadoso, atento a los detalles. Una cosa a la vez'],
                        '¿Qué hay en tu área de trabajo?' => ['ordenadas por prioridades. Solo cosas funcionales', 'arte, tecnología vistosa. Proyectos organizados por colores', 'recuerdos y souvenirs. Personaliza todo', 'referencia al alcance, libros y computadora potente'],
                        'Ritmo de trabajo' => ['rápido y preciso. Le gusta el cambio', 'muy variable. Se aburre fácilmente', 'sin prisa. Le disgustan las presiones', 'metódico. Ritmo constante'],
                        'Le disgusta' => ['perder el tiempo', 'hacer lo ya hecho antes', 'la confrontación', 'equivocarse'],
                        'Rol en el grupo' => ['controlar y dirigir', 'animar y motivar', 'mediador y colaborador', 'proveedor de información'],
                        'Desea ser apreciado por' => ['su productividad', 'su contribución', 'su involucramiento', 'la calidad de su trabajo'],
                        'Recompensado con' => ['poder', 'reconocimiento', 'aprobación', 'responsabilidad'],
                    ];
                @endphp

                @foreach ($preguntas as $texto => $opciones)
                <tr>
                    <td class="text-start">{{ $texto }}</td>
                    @foreach ($opciones as $indice => $opcion)
                    <td>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="respuestas[{{ $loop->parent->index }}]" id="p{{ $loop->parent->index }}_{{ $indice }}" value="{{ chr(65 + $indice) }}" required>
                            <label class="form-check-label" for="p{{ $loop->parent->index }}_{{ $indice }}">
                                {{ $opcion }}
                            </label>
                        </div>
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="text-center mt-4">
        <button type="submit" class="btn btn-primary">Enviar respuestas</button>
    </div>
</form>
@endsection
