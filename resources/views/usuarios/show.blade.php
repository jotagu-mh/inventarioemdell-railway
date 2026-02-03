@extends('layouts.app')

@section('page-title', 'Gestión de usuarios')
@section('page-subtitle', '')

@section('content')

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-user-circle text-info"></i> Información completa del Usuario
                        </h5>
                        <div>
                            <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    <!-- Avatar y nombre principal -->
                    <div class="text-center mb-4 pb-4 border-bottom">
                        <div class="mb-3">
                            <i class="fas fa-user-circle text-primary" style="font-size: 80px;"></i>
                        </div>
                        <h3 class="mb-1">{{ $usuario->name }} {{ $usuario->apellido }}</h3>
                        <div class="mb-2">
                            @if($usuario->rol)
                                @php
                                    $rolNombre = strtolower($usuario->rol->nombre);
                                @endphp
                                @if(str_contains($rolNombre, 'administrador'))
                                    <span class="badge bg-danger fs-6">
                                        <i class="fas fa-user-shield"></i> {{ $usuario->rol->nombre }}
                                    </span>
                                @elseif(str_contains($rolNombre, 'supervisor'))
                                    <span class="badge bg-warning text-dark fs-6">
                                        <i class="fas fa-user-tie"></i> {{ $usuario->rol->nombre }}
                                    </span>
                                @else
                                    <span class="badge bg-info fs-6">
                                        <i class="fas fa-user"></i> {{ $usuario->rol->nombre }}
                                    </span>
                                @endif
                            @else
                                <span class="badge bg-secondary fs-6">
                                    <i class="fas fa-user-times"></i> Sin rol asignado
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Información Personal -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-id-card"></i> Información Personal
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="border rounded p-3 bg-light">
                                    <small class="text-muted d-block mb-1">
                                        <i class="fas fa-user"></i> Nombre
                                    </small>
                                    <strong>{{ $usuario->name }}</strong>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="border rounded p-3 bg-light">
                                    <small class="text-muted d-block mb-1">
                                        <i class="fas fa-user"></i> Apellido
                                    </small>
                                    <strong>{{ $usuario->apellido }}</strong>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="border rounded p-3 bg-light">
                                    <small class="text-muted d-block mb-1">
                                        <i class="fas fa-id-card"></i> Carnet de Identidad
                                    </small>
                                    <strong>{{ $usuario->ci }}</strong>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="border rounded p-3 bg-light">
                                    <small class="text-muted d-block mb-1">
                                        <i class="fas fa-phone"></i> Teléfono
                                    </small>
                                    <strong>{{ $usuario->telefono ?? 'No registrado' }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Información de Acceso -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-key"></i> Información de Acceso
                        </h6>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="border rounded p-3 bg-light">
                                    <small class="text-muted d-block mb-1">
                                        <i class="fas fa-envelope"></i> Correo Electrónico
                                    </small>
                                    <strong>{{ $usuario->email }}</strong>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="border rounded p-3 bg-light">
                                    <small class="text-muted d-block mb-1">
                                        <i class="fas fa-user-tag"></i> Rol en el Sistema
                                    </small>
                                    <strong>{{ $usuario->rol->nombre ?? 'Sin rol asignado' }}</strong>
                                    @if($usuario->rol && $usuario->rol->descripcion)
                                        <br>
                                        <small class="text-muted">{{ $usuario->rol->descripcion }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Información de Registro -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-clock"></i> Historial
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="border rounded p-3 bg-light">
                                    <small class="text-muted d-block mb-1">
                                        <i class="far fa-calendar-plus"></i> Fecha de Registro
                                    </small>
                                    <strong>{{ $usuario->created_at->format('d/m/Y') }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $usuario->created_at->format('H:i:s') }}</small>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="border rounded p-3 bg-light">
                                    <small class="text-muted d-block mb-1">
                                        <i class="far fa-calendar-check"></i> Última Actualización
                                    </small>
                                    <strong>{{ $usuario->updated_at->format('d/m/Y') }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $usuario->updated_at->format('H:i:s') }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="border rounded p-3 bg-light">
                                    <small class="text-muted d-block mb-1">
                                        <i class="fas fa-info-circle"></i> Tiempo en el Sistema
                                    </small>
                                    <strong>{{ $usuario->created_at->diffForHumans() }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="d-flex justify-content-between gap-2 pt-3 border-top">
                        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver al Listado
                        </a>
                        <div class="d-flex gap-2">
                            <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Editar Usuario
                            </a>
                            @if($usuario->id !== auth()->id())
                                <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" class="d-inline"
                                    onsubmit="return confirmarEliminacion(event)">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Eliminar Usuario
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta adicional de estadísticas (opcional) -->
            <!-- Tarjeta adicional de estadísticas -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">
                    <h6 class="text-muted mb-3">
                        <i class="fas fa-chart-bar"></i> Actividad del Usuario
                    </h6>
                    <div class="row text-center">
                        <div class="col-md-4">
                            <div class="p-3 border-end">
                                <i class="fas fa-exchange-alt fa-2x text-primary mb-2"></i>
                                <h4 class="mb-0">{{ $totalMovimientos ?? 0 }}</h4>
                                <small class="text-muted">Total Movimientos</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 border-end">
                                <i class="fas fa-arrow-up fa-2x text-success mb-2"></i>
                                <h4 class="mb-0">{{ $totalEntradas ?? 0 }}</h4>
                                <small class="text-muted">Entradas</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3">
                                <i class="fas fa-arrow-down fa-2x text-danger mb-2"></i>
                                <h4 class="mb-0">{{ $totalSalidas ?? 0 }}</h4>
                                <small class="text-muted">Salidas</small>
                            </div>
                        </div>
                    </div>

                    <!-- Último movimiento registrado -->
                    @if($ultimoMovimiento)
                        <hr>
                        <div class="alert alert-light mb-0">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <small class="text-muted d-block">
                                        <i class="fas fa-clock"></i> Último movimiento registrado:
                                    </small>
                                    <strong>{{ $ultimoMovimiento->created_at->diffForHumans() }}</strong>
                                </div>
                                <div class="col-md-6 text-end">
                                    <a href="{{ route('movimientos.index') }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-list"></i> Ver todos los movimientos
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <hr>
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-inbox fa-2x mb-2 d-block" style="opacity: 0.3;"></i>
                            <small>Este usuario aún no ha registrado movimientos</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection