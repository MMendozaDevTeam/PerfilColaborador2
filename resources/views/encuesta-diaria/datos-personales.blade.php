@extends('layouts.app')

@section('title', 'Registro de Datos Personales')

@section('content')
<div class="container py-4">
    <h2 class="text-center mb-4">📝 Antes de continuar, registra tus datos personales</h2>

    <form action="{{ route('perfil.psicometrico.store') }}" method="POST">
        @csrf
        <input type="hidden" name="user_id" value="{{ request()->query('user_id') }}">
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="edad" class="form-label">Edad</label>
                <input type="number" name="edad" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label for="sexo" class="form-label">Sexo</label>
                <select name="sexo" class="form-select" required>
                    <option disabled selected>Selecciona</option>
                    <option value="Masculino">Masculino</option>
                    <option value="Femenino">Femenino</option>
                    <option value="No binario">No binario</option>
                    <option value="Otro">Otro</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label for="nivel_educativo">Nivel educativo</label>
            <select name="nivel_educativo" id="nivel_educativo" class="form-control">
                <option value="" disabled selected>Seleccione una opción</option>
                <option value="primaria">Primaria</option>
                <option value="secundaria">Secundaria</option>
                <option value="bachillerato">Bachillerato / Preparatoria</option>
                <option value="tecnico">Técnico</option>
                <option value="universitario">Universitario / Licenciatura</option>
                <option value="postgrado">Posgrado / Maestría / Doctorado</option>
            </select>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="antiguedad_anios" class="form-label">Antigüedad en la empresa (años)</label>
                <input type="number" name="antiguedad_anios" id="antiguedad_anios" class="form-control"
                       value="{{ old('antiguedad_anios') }}" {{ old('es_nuevo') ? 'disabled' : '' }}>
            
                <div class="form-check mt-2">
                    <input type="checkbox" name="es_nuevo" id="es_nuevo" class="form-check-input"
                           value="1" {{ old('es_nuevo') ? 'checked' : '' }} onchange="toggleAntiguedad()">
                    <label class="form-check-label" for="es_nuevo">Soy nuevo en la empresa</label>
                </div>
            </div>
            <div class="col-md-6">
                <label for="area" class="form-label">Área</label>
                <input type="text" name="area" class="form-control" required>
            </div>
        </div>

        <div class="mb-3">
             <label for="estado_civil">Estado civil</label>
             <select name="estado_civil" id="estado_civil" class="form-control">
                 <option value="" disabled selected>Seleccione una opción</option>
                 <option value="soltero">Soltero/a</option>
                 <option value="casado">Casado/a</option>
                 <option value="divorciado">Divorciado/a</option>
                 <option value="viudo">Viudo/a</option>
                 <option value="union_libre">Unión libre</option>
             </select>
        </div>

        {{-- Las respuestas psicométricas serán simuladas por ahora --}}
        <input type="hidden" name="respuesta_mentalidad" value="Intermedia">
        <input type="hidden" name="respuesta_comunicacion" value="Delfín">

        <div class="text-center">
            <button type="submit" class="btn btn-success">Guardar y continuar</button>
        </div>
    </form>
</div>
<script>
    function toggleAntiguedad() {
        const checkbox = document.getElementById('es_nuevo');
        const input = document.getElementById('antiguedad_anios');

        if (checkbox.checked) {
            input.disabled = true;
            input.value = '';
        } else {
            input.disabled = false;
        }
    }

    // Ejecutar al cargar la página para mantener estado después de errores
    document.addEventListener('DOMContentLoaded', toggleAntiguedad);
</script>
@endsection
