@extends('layouts.app')

@section('title', 'Colaboradores - Panel de Administración')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-center mb-4">👥 Panel de Colaboradores</h2>
    <p class="text-muted text-center mb-4">Selecciona un colaborador para ver su informe personalizado.</p>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="list-group shadow-sm">
                @foreach ($colaboradores as $colaborador)
                    <a href="{{ route('admin.colaborador.show', ['user_id' => $colaborador->user_id]) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <span>
                            <i class="bi bi-person-circle me-2 text-primary"></i>
                            {{ $colaborador->nombre }}
                        </span>
                        <span class="badge bg-secondary">{{ $colaborador->area }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
@endsection
