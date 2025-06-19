@extends('layouts.app')

@section('title', 'Validar NIP')

@section('content')
<!-- Modal para crear perfil -->
<div class="modal fade" id="crearPerfilModal" tabindex="-1" aria-labelledby="crearPerfilModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('perfil.nuevo') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Crear nuevo perfil</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nombre completo</label>
            <input type="text" class="form-control" name="nombre" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Número de empleado (NIP)</label>
            <input type="number" class="form-control" name="user_id" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Guardar perfil</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Modal para Panel de Administrador -->
<div class="modal fade" id="adminModal" tabindex="-1" aria-labelledby="adminModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('admin.validar.nip') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">🔑 Acceso al Panel de Administrador</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <label class="form-label">NIP de Administrador</label>
          <input type="password" name="nip_admin" class="form-control" maxlength="4" required>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Acceder</button>
        </div>
      </div>
    </form>
  </div>
</div>

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
    <div class="col-md-4 col-lg-3">
      <button type="button" class="btn btn-outline-dark w-100" data-bs-toggle="modal" data-bs-target="#crearPerfilModal">
        📋 Crear perfil
      </button>
    </div>
    <div class="col-md-4 col-lg-3">
      <button type="button" class="btn btn-primary w-100" id="continuarBtn">Continuar</button>
    </div>
    <div class="col-md-4 col-lg-3">
      <button type="button" class="btn btn-secondary w-100" data-bs-toggle="modal" data-bs-target="#adminModal">
        🔑 Panel de administrador
      </button>
    </div>
  </div>
</div>

@if(session('error'))
  <div class="alert alert-danger mt-4 text-center">
    {{ session('error') }}
  </div>
@endif

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show text-center mt-4" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
  </div>
@endif

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
@endsection
