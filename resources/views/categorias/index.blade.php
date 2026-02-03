@extends('layouts.app')

@section('page-title', 'Categorías')
@section('page-subtitle', 'Gestión de categorías principales')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="fas fa-th-large"></i> Lista de Categorías</h3>
        <a href="{{ route('categorias.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Categoría
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="80">#</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th width="150">Subcategorías</th>
                            <th width="150">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categorias as $categoria)
                            <tr>
                                <td>{{ $categoria->id }}</td>
                                <td><strong>{{ $categoria->nombre }}</strong></td>
                                <td>{{ $categoria->descripcion ?? 'Sin descripción' }}</td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $categoria->subcategorias_count }} subcategorías
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('categorias.edit', $categoria) }}" class="btn btn-sm btn-warning"
                                        title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('categorias.destroy', $categoria) }}" method="POST" class="d-inline"
                                        onsubmit="confirmarEliminacion(event)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                    No hay categorías registradas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection