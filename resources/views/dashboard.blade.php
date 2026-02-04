@extends('layouts.app')

@section('page-title', 'Vista General del Inventario de Bienes de Consumo')
@section('page-subtitle', '')

@section('content')

<!-- Tarjetas de Estadísticas -->
<div class="row mb-4">
    <!-- Total Materiales -->
    <div class="col-md-4 mb-3">
        <div class="stats-card blue">
            <div class="d-flex justify-content-between align-items-center">
                <i class="fas fa-boxes fa-3x" style="color:#f97316;"></i>
                <div>
                    <p class="mb-1">Total Materiales</p>
                    <h3>{{ $totalMateriales }}</h3>
                </div>
                <div class="icon">
                    <i class="fas fa-boxes"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Materiales Activos 
    
    <div class="col-md-3 mb-3">
        <div class="stats-card green">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-1">Materiales Activos</p>
                    <h3>{{ $materialesActivos }}</h3>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
    </div>
    -->

    <!-- Stock Bajo -->
    <div class="col-md-4 mb-3">
        <div class="stats-card orange">
            <div class="d-flex justify-content-between align-items-center">
                <i class="fas fa-exclamation-triangle fa-3x" style="color:#f97316;"></i>
                <div>
                    <p class="mb-1">Materiales de baja Cantidad</p>
                    <h3>{{ $materialesStockBajo }}</h3>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Valor Inventario 
    <div class="col-md-3 mb-3">
        <div class="stats-card red">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-1">Valor Inventario</p>
                    <h3>Bs. {{ number_format($valorInventario, 2) }}</h3>
                </div>
                <div class="icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>
    </div>
    -->
</div>

<div class="row">
    <!-- Materiales con Stock Bajo -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-circle text-warning"></i>
                    Alertas de Materiales de baja Cantidad
                </h5>
            </div>
            <div class="card-body">
                @if($materialesAlerta->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Material</th>
                                    <th>Cantidad</th>
                                    <th>Mínimo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($materialesAlerta as $material)
                                <tr>
                                    <td>
                                        <strong>{{ $material->nombre }}</strong><br>
                                        <small class="text-muted">
                                            {{ $material->subcategoria->categoria->nombre }} - 
                                            {{ $material->subcategoria->nombre }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger">
                                            {{ $material->cantidad_actual }}
                                        </span>
                                    </td>
                                    <td>{{ $material->cantidad_minima }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-check-circle fa-3x mb-3"></i>
                        <p>No hay materiales con cantidad baja</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Materiales Recientes -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0">
                    <i class="fas fa-clock text-primary"></i>
                    Materiales Recientes
                </h5>
            </div>
            <div class="card-body">
                @if($materialesRecientes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Material</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($materialesRecientes as $material)
                                <tr>
                                    <td>
                                        <strong>{{ $material->nombre }}</strong><br>
                                        <small class="text-muted">
                                            {{ $material->subcategoria->categoria->nombre }} - 
                                            {{ $material->subcategoria->nombre }}
                                        </small>
                                    </td>
                                    <td>Bs. {{ number_format($material->precio_unitario, 2) }}</td>
                                    <td>
                                        <span class="badge {{ $material->stockBajo() ? 'bg-danger' : 'bg-success' }}">
                                            {{ $material->cantidad_actual }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-box fa-3x mb-3"></i>
                        <p>No hay materiales registrados</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection