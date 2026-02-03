@extends('layouts.app')

@section('page-title', 'Gestión de Categorías: ' . $categoria->nombre)
@section('page-subtitle', '')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3>
                <i class="fas fa-box-open"></i> <span style="color: black;">{{ $categoria->descripcion }}</span>

            </h3>
        </div>
        <a href="{{ route('subcategorias.create', $categoria->id) }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Subcategoría
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
                            <th width="150">Materiales</th>
                            <th width="150">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subcategorias as $subcategoria)
                            <tr>
                                <td>{{ $subcategoria->id }}</td>
                                <td><strong>{{ $subcategoria->nombre }}</strong></td>
                                <td>{{ $subcategoria->descripcion ?? 'Sin descripción' }}</td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $subcategoria->materiales->count() }} materiales
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('subcategorias.edit', $subcategoria) }}" class="btn btn-sm btn-warning"
                                        title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('subcategorias.destroy', $subcategoria) }}" method="POST"
                                        class="d-inline" onsubmit="confirmarEliminacion(event)">
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
                                    No hay subcategorías registradas en esta categoría
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection