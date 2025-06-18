@extends('layouts.app')

@section('title', 'Validar NIP')

@section('content')
<div class="container py-5 text-center">
    <h2 class="mb-4">🔐 Ingresar NIP</h2>
    <p class="text-muted">Introduce tu NIP de 4 dígitos para acceder a tu perfil</p>

    <form id="nipForm" class="mb-4 d-flex justify-content-center gap-2">
        @for ($i = 0; $i < 4; $i++)
            <input type="text"
                   maxlength="1"
                   class="form-control text-center fs-4 nip-input"
                   style="width: 60px;"
                   inputmode="numeric"
                   pattern="[0-9]*"
                   required>
        @endfor
    </form>

    <div class="row mt-4 justify-content-center g-3">
        <div class="col-md-6 col-lg-3">
            <a href="#" class="btn btn-outline-dark w-100">📋 Crear perfil</a>
        </div>
        <div class="col-md-6 col-lg-3">
            <button type="button" class="btn btn-primary w-100" id="continuarBtn">➡️ Continuar</button>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger mt-4">
            {{ session('error') }}
        </div>
    @endif
</div>

<script>
    const continuarBtn = document.getElementById('continuarBtn');
    const nipInputs = document.querySelectorAll('.nip-input');

    continuarBtn.addEventListener('click', function () {
        const pin = Array.from(nipInputs).map(i => i.value.trim()).join('');

        if (pin.length === 4 && /^\d{4}$/.test(pin)) {
            window.location.href = `/acceso?pin=${pin}`;
        } else {
            alert('Por favor ingresa los 4 dígitos del NIP correctamente.');
        }
    });

    // Autoenfoque y retroceso
    nipInputs.forEach((input, index) => {
        input.addEventListener('input', () => {
            input.value = input.value.replace(/\D/, '');
            if (input.value && index < nipInputs.length - 1) {
                nipInputs[index + 1].focus();
            }
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !input.value && index > 0) {
                nipInputs[index - 1].focus();
            }
        });
    });
</script>


<!-- Auto-enfoque al siguiente input -->
<script>
    document.querySelectorAll('.nip-input').forEach((input, index, inputs) => {
        input.addEventListener('input', () => {
            input.value = input.value.replace(/[^0-9]/g, '');
            if (input.value && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !input.value && index > 0) {
                inputs[index - 1].focus();
            }
        });
    });
</script>
@endsection
