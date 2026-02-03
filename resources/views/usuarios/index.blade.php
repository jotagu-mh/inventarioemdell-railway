@extends('layouts.app')

@section('page-title', 'Gestión de usuarios')
@section('page-subtitle', '')

@section('content')

<div class="row mb-3">
    <div class="col-md-6">
        <!-- Buscador -->
        <div class="input-group">
            <span class="input-group-text bg-white">
                <i class="fas fa-search"></i>
            </span>
            <input type="text" 
                   id="searchInput" 
                   class="form-control" 
                   placeholder="Buscar por nombre, CI, email o teléfono...">
        </div>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Usuario
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="usuariosTable">
                <thead class="table-light">
                    <tr>
                        <th width="5%">ID</th>
                        <th width="20%">Nombre Completo</th>
                        <th width="10%">CI</th>
                        <th width="20%">Email</th>
                        <th width="12%">Teléfono</th>
                        <th width="13%">Rol</th>
                        <th width="20%" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->id }}</td>
                        <td>
                            <i class="fas fa-user-circle text-primary me-2"></i>
                            <strong>{{ $usuario->name }} {{ $usuario->apellido }}</strong>
                        </td>
                        <td>{{ $usuario->ci }}</td>
                        <td>
                            <small>{{ $usuario->email }}</small>
                        </td>
                        <td>
                            @if($usuario->telefono)
                                <i class="fas fa-phone text-muted me-1"></i>
                                {{ $usuario->telefono }}
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            @if($usuario->rol)
                                @php
                                    $rolNombre = strtolower($usuario->rol->nombre);
                                @endphp
                                @if(str_contains($rolNombre, 'administrador'))
                                    <span class="badge bg-danger">
                                        <i class="fas fa-user-shield"></i> {{ $usuario->rol->nombre }}
                                    </span>
                                @elseif(str_contains($rolNombre, 'supervisor'))
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-user-tie"></i> {{ $usuario->rol->nombre }}
                                    </span>
                                @else
                                    <span class="badge bg-info">
                                        <i class="fas fa-user"></i> {{ $usuario->rol->nombre }}
                                    </span>
                                @endif
                            @else
                                <span class="badge bg-secondary">
                                    <i class="fas fa-user-times"></i> Sin rol
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('usuarios.show', $usuario) }}" 
                                   class="btn btn-info" 
                                   title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('usuarios.edit', $usuario) }}" 
                                   class="btn btn-warning" 
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($usuario->id !== auth()->id())
                                <form action="{{ route('usuarios.destroy', $usuario) }}" 
                                      method="POST" 
                                      class="d-inline form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-danger" 
                                            title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @else
                                <button class="btn btn-secondary" 
                                        disabled 
                                        title="No puedes eliminarte a ti mismo">
                                    <i class="fas fa-ban"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="fas fa-users fa-4x mb-3 d-block" style="opacity: 0.3;"></i>
                            <h5>No hay usuarios registrados</h5>
                            <p class="mb-3">Comienza agregando el primer usuario al sistema</p>
                            <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Crear Primer Usuario
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($usuarios->hasPages())
        <div class="mt-3 d-flex justify-content-center">
            {{ $usuarios->links() }}
        </div>
        @endif
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Buscador en tiempo real
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#usuariosTable tbody tr');
        
        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });

    // Confirmación de eliminación con SweetAlert2
    document.querySelectorAll('.form-delete').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción eliminará permanentemente al usuario",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
</script>
@endsection