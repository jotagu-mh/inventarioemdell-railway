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
                        <i class="fas fa-user-edit text-warning"></i> Editar Usuario
                    </h5>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('usuarios.update', $usuario) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Información Personal -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-id-card"></i> Información Personal
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">
                                    Nombre <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $usuario->name) }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="apellido" class="form-label">
                                    Apellido <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('apellido') is-invalid @enderror" 
                                       id="apellido" 
                                       name="apellido" 
                                       value="{{ old('apellido', $usuario->apellido) }}" 
                                       required>
                                @error('apellido')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="ci" class="form-label">
                                    Carnet de Identidad <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('ci') is-invalid @enderror" 
                                       id="ci" 
                                       name="ci" 
                                       value="{{ old('ci', $usuario->ci) }}" 
                                       placeholder="Ej: 12345678"
                                       required>
                                @error('ci')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">
                                    Teléfono
                                </label>
                                <input type="text" 
                                       class="form-control @error('telefono') is-invalid @enderror" 
                                       id="telefono" 
                                       name="telefono" 
                                       value="{{ old('telefono', $usuario->telefono) }}" 
                                       placeholder="Ej: 70123456">
                                @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Credenciales de Acceso -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-key"></i> Credenciales de Acceso
                        </h6>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="email" class="form-label">
                                    Correo Electrónico <span class="text-danger">*</span>
                                </label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $usuario->email) }}" 
                                       placeholder="ejemplo@correo.com"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Alerta para cambio de contraseña -->
                        <div class="alert alert-info d-flex align-items-center" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            <div>
                                <strong>Cambiar contraseña:</strong> 
                                Deja los campos en blanco si no deseas modificar la contraseña actual.
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    Nueva Contraseña
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password">
                                    <button class="btn btn-outline-secondary" 
                                            type="button" 
                                            id="togglePassword">
                                        <i class="fas fa-eye" id="eyeIcon"></i>
                                    </button>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">Mínimo 8 caracteres</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">
                                    Confirmar Nueva Contraseña
                                </label>
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Rol del Usuario -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-user-tag"></i> Permisos
                        </h6>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="rol_id" class="form-label">
                                    Rol <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('rol_id') is-invalid @enderror" 
                                        id="rol_id" 
                                        name="rol_id" 
                                        required>
                                    <option value="">Seleccione un rol</option>
                                    @foreach($roles as $rol)
                                        <option value="{{ $rol->id }}" {{ old('rol_id', $usuario->rol_id) == $rol->id ? 'selected' : '' }}>
                                            {{ $rol->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('rol_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> 
                                    El rol determina los permisos del usuario en el sistema
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Información adicional -->
                    <div class="mb-4">
                        <div class="alert alert-light border">
                            <small class="text-muted">
                                <i class="far fa-clock me-1"></i>
                                <strong>Usuario registrado:</strong> {{ $usuario->created_at->format('d/m/Y H:i') }}
                                @if($usuario->updated_at != $usuario->created_at)
                                <br>
                                <i class="far fa-clock me-1"></i>
                                <strong>Última actualización:</strong> {{ $usuario->updated_at->format('d/m/Y H:i') }}
                                @endif
                            </small>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save"></i> Actualizar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Mostrar/Ocultar contraseña
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    });

    // Validación en tiempo real del CI (solo números)
    document.getElementById('ci').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Validación en tiempo real del teléfono (solo números)
    document.getElementById('telefono').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
</script>
@endsection