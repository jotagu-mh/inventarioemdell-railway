@extends('layouts.app')

@section('page-title', 'Detalle del Material')
@section('page-subtitle', '')

@section('content')

<div class="container">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2><i class="fa-solid fa-info-circle"></i> Información completa del material</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('materiales.edit', $material) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="{{ route('materiales.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">{{ $material->nombre }}</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="border-bottom pb-2 mb-3">Información General</h5>
                    
                    <div class="mb-3">
                        <strong>Código:</strong>
                        <p class="mb-1">{{ $material->codigo }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Nombre:</strong>
                        <p class="mb-1">{{ $material->nombre }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Descripción:</strong>
                        <p class="mb-1">{{ $material->descripcion ?? 'Sin descripción' }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Estado:</strong>
                        <p class="mb-1">
                            <span class="badge bg-{{ $material->estado == 'activo' ? 'success' : 'secondary' }}">
                                {{ ucfirst($material->estado) }}
                            </span>
                        </p>
                    </div>
                </div>

                <div class="col-md-6">
                    <h5 class="border-bottom pb-2 mb-3">Clasificación</h5>
                    
                    <div class="mb-3">
                        <strong>Categoría:</strong>
                        <p class="mb-1">{{ $material->subcategoria->categoria->nombre }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Subcategoría:</strong>
                        <p class="mb-1">{{ $material->subcategoria->nombre }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Unidad de Medida:</strong>
                        <p class="mb-1">{{ ucfirst($material->unidad_medida) }}</p>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-12">
                    <h5 class="border-bottom pb-2 mb-3">Inventario y Precios</h5>
                </div>

                <div class="col-md-3">
                    <div class="card text-center {{ $material->stock_bajo ? 'border-warning' : 'border-success' }}">
                        <div class="card-body">
                            <h6 class="card-title">Cantidad Actual</h6>
                            <h3 class="card-text {{ $material->stock_bajo ? 'text-warning' : 'text-success' }}">
                                {{ $material->cantidad_actual }}
                                @if($material->stock_bajo)
                                    <i class="fas fa-exclamation-triangle"></i>
                                @endif
                            </h3>
                            <small class="text-muted">{{ $material->unidad_medida }}</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-center border-info">
                        <div class="card-body">
                            <h6 class="card-title">Cantidad Mínima</h6>
                            <h3 class="card-text text-info">{{ $material->cantidad_minima }}</h3>
                            <small class="text-muted">{{ $material->unidad_medida }}</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-center border-primary">
                        <div class="card-body">
                            <h6 class="card-title">Precio Unitario</h6>
                            <h3 class="card-text text-primary">${{ number_format($material->precio_unitario, 2) }}</h3>
                            <small class="text-muted">por {{ $material->unidad_medida }}</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-center border-dark">
                        <div class="card-body">
                            <h6 class="card-title">Valor Total</h6>
                            <h3 class="card-text text-dark">
                                ${{ number_format($material->cantidad_actual * $material->precio_unitario, 2) }}
                            </h3>
                            <small class="text-muted">inventario</small>
                        </div>
                    </div>
                </div>
            </div>

            @if($material->stock_bajo)
                <div class="alert alert-warning mt-3" role="alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Advertencia:</strong> El stock actual está por debajo del mínimo establecido. 
                    Se recomienda reabastecer este material.
                </div>
            @endif

            <hr>

            <div class="row">
                <div class="col-md-6">
                    <small class="text-muted">
                        <strong>Creado:</strong> {{ $material->created_at->format('d/m/Y H:i') }}
                    </small>
                </div>
                <div class="col-md-6 text-end">
                    <small class="text-muted">
                        <strong>Última actualización:</strong> {{ $material->updated_at->format('d/m/Y H:i') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection