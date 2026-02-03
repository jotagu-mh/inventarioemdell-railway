@extends('layouts.app')

@section('page-title', 'Gestión de Movimientos')
@section('page-subtitle', '')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2><i class="fa-solid fa-arrow-right-arrow-left"></i> Listado completo de movimientos</h2>
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group" role="group">
                <a href="{{ route('movimientos.create', ['tipo' => 'entrada']) }}" class="btn btn-success">
                    <i class="fas fa-plus-circle"></i> Nueva Entrada
                </a>
                <a href="{{ route('movimientos.create', ['tipo' => 'salida']) }}" class="btn btn-danger">
                    <i class="fas fa-minus-circle"></i> Nueva Salida
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-filter"></i> Filtros de Búsqueda</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('movimientos.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Material</label>
                        <select name="material_id" class="form-select">
                            <option value="">Todos los materiales</option>
                            @foreach($materiales as $material)
                                <option value="{{ $material->id }}" 
                                    {{ request('material_id') == $material->id ? 'selected' : '' }}>
                                    {{ $material->codigo }} - {{ $material->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tipo</label>
                        <select name="tipo" class="form-select">
                            <option value="">Todos</option>
                            <option value="entrada" {{ request('tipo') == 'entrada' ? 'selected' : '' }}>
                                Entradas
                            </option>
                            <option value="salida" {{ request('tipo') == 'salida' ? 'selected' : '' }}>
                                Salidas
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Fecha Inicio</label>
                        <input type="date" name="fecha_inicio" class="form-control" 
                               value="{{ request('fecha_inicio') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Fecha Fin</label>
                        <input type="date" name="fecha_fin" class="form-control" 
                               value="{{ request('fecha_fin') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="fas fa-search"></i> Buscar
                            </button>
                            <a href="{{ route('movimientos.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Limpiar
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Movimientos -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Material</th>
                            <th>Cantidad</th>
                            <th>Costo Unit.</th>
                            <th>Total</th>
                            <th>Documento</th>
                            <th>Responsable</th>
                            <th>Saldo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movimientos as $movimiento)
                            <tr>
                                <td>{{ $movimiento->fecha->format('d/m/Y') }}</td>
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
                                <td>
                                    <strong>{{ $movimiento->material->codigo }}</strong><br>
                                    <small class="text-muted">{{ $movimiento->material->nombre }}</small>
                                </td>
                                <td class="text-end">
                                    @if($movimiento->tipo === 'entrada')
                                        <span class="text-success">+{{ $movimiento->cantidad }}</span>
                                    @else
                                        <span class="text-danger">-{{ $movimiento->cantidad }}</span>
                                    @endif
                                    {{ $movimiento->material->unidad_medida }}
                                </td>
                                <td class="text-end">Bs. {{ number_format($movimiento->costo_unitario, 2) }}</td>
                                <td class="text-end">
                                    <strong>Bs. {{ number_format($movimiento->total, 2) }}</strong>
                                </td>
                                <td>
                                    @if($movimiento->tipo === 'entrada')
                                        <small>
                                            <strong>Ingreso:</strong> {{ $movimiento->numero_ingreso }}<br>
                                            @if($movimiento->numero_factura)
                                                <strong>Factura:</strong> {{ $movimiento->numero_factura }}
                                            @endif
                                        </small>
                                    @else
                                        <small>
                                            <strong>Salida:</strong> {{ $movimiento->numero_salida }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <small>
                                        @if($movimiento->tipo === 'entrada')
                                            {{ $movimiento->proveedor }}
                                        @else
                                            {{ $movimiento->solicitante }}
                                        @endif
                                    </small>
                                </td>
                                <td class="text-end">
                                    <strong>{{ $movimiento->saldo_cantidad }}</strong>
                                    {{ $movimiento->material->unidad_medida }}<br>
                                    <small class="text-muted">
                                        Bs. {{ number_format($movimiento->saldo_total, 2) }}
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('movimientos.show', $movimiento) }}" 
                                           class="btn btn-info" title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('movimientos.edit', $movimiento) }}" 
                                           class="btn btn-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('movimientos.destroy', $movimiento) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('¿Está seguro de eliminar este movimiento?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">No hay movimientos registrados</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="mt-3">
                {{ $movimientos->links() }}
            </div>
        </div>
    </div>
</div>
@endsection