@extends('layouts.app')

@section('page-title', 'Gestión de Roles')
@section('page-subtitle', '')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="mb-0">👥 Administración de roles del sistema</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('roles.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Nuevo Rol
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Tabla de Roles -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Usuarios</th>
                            <th>Fecha Creación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $rol)
                            <tr>
                                <td>{{ $rol->id }}</td>
                                <td>
                                    <strong>{{ $rol->nombre }}</strong>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $rol->descripcion ?? 'Sin descripción' }}
                                    </small>
                                </td>
                                <td>
                                    @if($rol->activo)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> Activo
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-times-circle"></i> Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info">
                                        {{ $rol->usuarios->count() }} usuario(s)
                                    </span>
                                </td>
                                <td>
                                    <small>{{ $rol->created_at->format('d/m/Y') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('roles.edit', $rol) }}" 
                                           class="btn btn-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        @if($rol->activo)
                                            <form action="{{ route('roles.destroy', $rol) }}" 
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('¿Está seguro de desactivar este rol?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" title="Desactivar">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('roles.activar', $rol) }}" 
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('¿Está seguro de activar este rol?')">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-success" title="Activar">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">No hay roles registrados</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection