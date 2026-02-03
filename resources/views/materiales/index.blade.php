@extends('layouts.app')

@section('page-title', 'Gestión de Materiales')
@section('page-subtitle', '')

@section('content')
    <div class="container">
        <div class="row mb-3">
            <div class="col-md-6">
                <h2><i class="fa-solid fa-boxes-stacked"></i> Listado completo de materiales</h2>

            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('materiales.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo Material
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Formulario de búsqueda -->
        <div class="card mb-3">
            <div class="card-body">
                <form action="{{ route('materiales.index') }}" method="GET" class="row g-3">
                    <div class="col-md-10">
                        <input type="text" class="form-control" name="buscar"
                            placeholder="Buscar por código, nombre, descripción, categoría o subcategoría..."
                            value="{{ request('buscar') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                    @if(request('buscar'))
                        <div class="col-12">
                            <a href="{{ route('materiales.index') }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-times"></i> Limpiar búsqueda
                            </a>
                            <small class="text-muted ms-2">
                                Mostrando resultados para: <strong>"{{ request('buscar') }}"</strong>
                            </small>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Subcategoría</th>
                                <th>Categoría</th>
                                <th>Stock Actual</th>
                                <th>Stock Mínimo</th>
                                <th>Unidad</th>
                                <th>Precio</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($materiales as $material)
                                <tr class="{{ $material->stock_bajo ? 'table-warning' : '' }}">
                                    <td>{{ $material->codigo }}</td>
                                    <td>{{ $material->nombre }}</td>
                                    <td>{{ $material->subcategoria->nombre }}</td>
                                    <td>{{ $material->subcategoria->categoria->nombre }}</td>
                                    <td>
                                        {{ $material->cantidad_actual }}
                                        @if($material->stock_bajo)
                                            <i class="fas fa-exclamation-triangle text-warning" title="Stock bajo"></i>
                                        @endif
                                    </td>
                                    <td>{{ $material->cantidad_minima }}</td>
                                    <td>{{ $material->unidad_medida }}</td>
                                    <td>${{ number_format($material->precio_unitario, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $material->estado == 'activo' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($material->estado) }}
                                        </span>
                                    </td>
                                    <td style="white-space: nowrap;">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('materiales.show', $material) }}" class="btn btn-sm btn-info"
                                                title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('materiales.edit', $material) }}" class="btn btn-sm btn-warning"
                                                title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('materiales.destroy', $material) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('¿Estás seguro de eliminar este material?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">No hay materiales registrados</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $materiales->appends(['buscar' => request('buscar')])->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection