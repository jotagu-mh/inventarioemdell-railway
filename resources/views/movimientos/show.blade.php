@extends('layouts.app')

@section('page-title', 'Gestión de Movimientos')
@section('page-subtitle', '')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header bg-{{ $movimiento->tipo === 'entrada' ? 'success' : 'danger' }} text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                @if($movimiento->tipo === 'entrada')
                                    <i class="fas fa-arrow-down"></i> Detalle de Entrada
                                @else
                                    <i class="fas fa-arrow-up"></i> Detalle de Salida
                                @endif
                            </h4>
                            <a href="{{ route('movimientos.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <!-- Información del Material -->
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title border-bottom pb-2 mb-3">
                                            <i class="fas fa-box"></i> Material
                                        </h5>
                                        <table class="table table-sm table-borderless mb-0">
                                            <tr>
                                                <td class="text-muted" width="40%">Código:</td>
                                                <td><strong>{{ $movimiento->material->codigo }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Nombre:</td>
                                                <td><strong>{{ $movimiento->material->nombre }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Categoría:</td>
                                                <td>{{ $movimiento->material->categoria }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Unidad Medida:</td>
                                                <td>{{ $movimiento->material->unidad_medida }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Información del Movimiento -->
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title border-bottom pb-2 mb-3">
                                            <i class="fas fa-info-circle"></i> Datos del Movimiento
                                        </h5>
                                        <table class="table table-sm table-borderless mb-0">
                                            <tr>
                                                <td class="text-muted" width="40%">Tipo:</td>
                                                <td>
                                                    @if($movimiento->tipo === 'entrada')
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-arrow-down"></i> ENTRADA
                                                        </span>
                                                    @else
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-arrow-up"></i> SALIDA
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Fecha:</td>
                                                <td><strong>{{ $movimiento->fecha->format('d/m/Y') }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Registrado:</td>
                                                <td>{{ $movimiento->created_at->format('d/m/Y H:i') }}</td>
                                            </tr>
                                            @if($movimiento->updated_at != $movimiento->created_at)
                                                <tr>
                                                    <td class="text-muted">Modificado:</td>
                                                    <td>{{ $movimiento->updated_at->format('d/m/Y H:i') }}</td>
                                                </tr>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Cantidad y Costos -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title border-bottom pb-2 mb-3">
                                            <i class="fas fa-calculator"></i> Cantidad y Costos
                                        </h5>
                                        <div class="row text-center">
                                            <div class="col-md-3">
                                                <div class="p-3 bg-light rounded">
                                                    <h6 class="text-muted mb-2">Cantidad</h6>
                                                    <h3
                                                        class="mb-0 {{ $movimiento->tipo === 'entrada' ? 'text-success' : 'text-danger' }}">
                                                        {{ $movimiento->tipo === 'entrada' ? '+' : '-' }}{{ $movimiento->cantidad }}
                                                    </h3>
                                                    <small
                                                        class="text-muted">{{ $movimiento->material->unidad_medida }}</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="p-3 bg-light rounded">
                                                    <h6 class="text-muted mb-2">Costo Unitario</h6>
                                                    <h4 class="mb-0">Bs. {{ number_format($movimiento->costo_unitario, 2) }}
                                                    </h4>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="p-3 bg-light rounded">
                                                    <h6 class="text-muted mb-2">Total</h6>
                                                    <h4 class="mb-0 text-primary">Bs.
                                                        {{ number_format($movimiento->total, 2) }}</h4>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="p-3 bg-info bg-opacity-10 rounded border border-info">
                                                    <h6 class="text-muted mb-2">Saldo Resultante</h6>
                                                    <h4 class="mb-0 text-info">{{ $movimiento->saldo_cantidad }}</h4>
                                                    <small class="text-muted">
                                                        Bs. {{ number_format($movimiento->saldo_total, 2) }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($movimiento->tipo === 'entrada')
                            <!-- Detalles de Entrada -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="card border-success">
                                        <div class="card-body">
                                            <h5 class="card-title border-bottom pb-2 mb-3">
                                                <i class="fas fa-file-invoice"></i> Detalles de Entrada
                                            </h5>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="text-muted d-block">Número de Ingreso</label>
                                                    <strong class="fs-5">{{ $movimiento->numero_ingreso }}</strong>
                                                </div>
                                                @if($movimiento->numero_factura)
                                                    <div class="col-md-4">
                                                        <label class="text-muted d-block">Número de Factura</label>
                                                        <strong class="fs-5">{{ $movimiento->numero_factura }}</strong>
                                                    </div>
                                                @endif
                                                <div class="col-md-4">
                                                    <label class="text-muted d-block">Proveedor</label>
                                                    <strong class="fs-5">{{ $movimiento->proveedor }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Detalles de Salida -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="card border-danger">
                                        <div class="card-body">
                                            <h5 class="card-title border-bottom pb-2 mb-3">
                                                <i class="fas fa-clipboard-list"></i> Detalles de Salida
                                            </h5>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="text-muted d-block">Número de Salida</label>
                                                    <strong class="fs-5">{{ $movimiento->numero_salida }}</strong>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="text-muted d-block">Solicitante</label>
                                                    <strong class="fs-5">{{ $movimiento->solicitante }}</strong>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <label class="text-muted d-block">Motivo</label>
                                                    <div class="alert alert-light border">
                                                        {{ $movimiento->motivo }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($movimiento->observaciones)
                            <!-- Observaciones -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h5 class="card-title mb-2">
                                                <i class="fas fa-comment-alt"></i> Observaciones
                                            </h5>
                                            <p class="mb-0">{{ $movimiento->observaciones }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Botones de Acción -->
                        <div class="row">
                            <div class="col-12">
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('movimientos.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Volver al Listado
                                    </a>
                                    <div>
                                        <a href="{{ route('movimientos.edit', $movimiento) }}" class="btn btn-warning">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <form action="{{ route('movimientos.destroy', $movimiento) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('¿Está seguro de eliminar este movimiento? Esta acción no se puede deshacer.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection