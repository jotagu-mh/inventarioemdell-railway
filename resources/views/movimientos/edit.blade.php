@extends('layouts.app')

@section('page-title', 'Gestión de Movimientos')
@section('page-subtitle', '')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header bg-{{ $movimiento->tipo === 'entrada' ? 'success' : 'danger' }} text-white">
                        <h4 class="mb-0">
                            @if($movimiento->tipo === 'entrada')
                                <i class="fas fa-edit"></i> Editar Entrada de Material
                            @else
                                <i class="fas fa-edit"></i> Editar Salida de Material
                            @endif
                        </h4>
                    </div>
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle"></i>
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Nota:</strong> No se puede modificar el material ni la cantidad una vez registrado el
                            movimiento.
                            Solo puede editar la fecha, costos y datos del documento.
                        </div>

                        <form action="{{ route('movimientos.update', $movimiento) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Información del Material (Solo lectura) -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3">
                                        <i class="fas fa-box"></i> Material (No editable)
                                    </h5>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Material</label>
                                    <input type="text" class="form-control"
                                        value="{{ $movimiento->material->codigo }} - {{ $movimiento->material->nombre }}"
                                        readonly style="background-color: #e9ecef;">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Cantidad</label>
                                    <input type="text" class="form-control"
                                        value="{{ $movimiento->cantidad }} {{ $movimiento->material->unidad_medida }}"
                                        readonly style="background-color: #e9ecef;">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Tipo</label>
                                    <input type="text" class="form-control" value="{{ strtoupper($movimiento->tipo) }}"
                                        readonly style="background-color: #e9ecef;">
                                </div>
                            </div>

                            <!-- Información Editable -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3">
                                        <i class="fas fa-edit"></i> Información Editable
                                    </h5>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label required">Fecha</label>
                                    <input type="date" name="fecha" class="form-control"
                                        value="{{ old('fecha', $movimiento->fecha->format('Y-m-d')) }}"
                                        max="{{ date('Y-m-d') }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label required">Costo Unitario (Bs.)</label>
                                    <input type="number" name="costo_unitario" class="form-control"
                                        value="{{ old('costo_unitario', $movimiento->costo_unitario) }}" min="0" step="0.01"
                                        required>
                                    <small class="text-muted">
                                        Total será: Bs.
                                        {{ number_format($movimiento->cantidad * old('costo_unitario', $movimiento->costo_unitario), 2) }}
                                    </small>
                                </div>
                            </div>

                            @if($movimiento->tipo === 'entrada')
                                <!-- Campos Específicos de ENTRADA -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h5 class="border-bottom pb-2 mb-3">
                                            <i class="fas fa-file-invoice"></i> Información de Entrada
                                        </h5>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label required">Número de Ingreso</label>
                                        <input type="text" name="numero_ingreso" class="form-control"
                                            value="{{ old('numero_ingreso', $movimiento->numero_ingreso) }}"
                                            placeholder="Ej: ING-001" required>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Número de Factura</label>
                                        <input type="text" name="numero_factura" class="form-control"
                                            value="{{ old('numero_factura', $movimiento->numero_factura) }}"
                                            placeholder="Ej: FACT-12345">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label required">Proveedor</label>
                                        <input type="text" name="proveedor" class="form-control"
                                            value="{{ old('proveedor', $movimiento->proveedor) }}"
                                            placeholder="Nombre del proveedor" required>
                                    </div>
                                </div>
                            @else
                                <!-- Campos Específicos de SALIDA -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h5 class="border-bottom pb-2 mb-3">
                                            <i class="fas fa-clipboard-list"></i> Información de Salida
                                        </h5>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label required">Número de Salida</label>
                                        <input type="text" name="numero_salida" class="form-control"
                                            value="{{ old('numero_salida', $movimiento->numero_salida) }}"
                                            placeholder="Ej: SAL-001" required>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label required">Solicitante</label>
                                        <select name="solicitante" class="form-select" required>
                                            <option value="">Seleccione...</option>
                                            <option value="JEFE TECNICO" {{ old('solicitante', $movimiento->solicitante) == 'JEFE TECNICO' ? 'selected' : '' }}>
                                                JEFE TECNICO
                                            </option>
                                            <option value="SECRETARIA" {{ old('solicitante', $movimiento->solicitante) == 'SECRETARIA' ? 'selected' : '' }}>
                                                SECRETARIA
                                            </option>
                                            <option value="ADMINISTRADOR" {{ old('solicitante', $movimiento->solicitante) == 'ADMINISTRADOR' ? 'selected' : '' }}>
                                                ADMINISTRADOR
                                            </option>
                                            <option value="ALMACENERO" {{ old('solicitante', $movimiento->solicitante) == 'ALMACENERO' ? 'selected' : '' }}>
                                                ALMACENERO
                                            </option>
                                            <option value="OTROS" {{ old('solicitante', $movimiento->solicitante) == 'OTROS' ? 'selected' : '' }}>
                                                OTROS
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <label class="form-label required">Motivo de Salida</label>
                                        <textarea name="motivo" class="form-control" rows="3"
                                            placeholder="Describa el motivo de la salida..."
                                            required>{{ old('motivo', $movimiento->motivo) }}</textarea>
                                    </div>
                                </div>
                            @endif

                            <!-- Observaciones -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <label class="form-label">Observaciones</label>
                                    <textarea name="observaciones" class="form-control" rows="3"
                                        placeholder="Observaciones adicionales (opcional)">{{ old('observaciones', $movimiento->observaciones) }}</textarea>
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="row">
                                <div class="col-12">
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('movimientos.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Actualizar Movimiento
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .required::after {
            content: " *";
            color: red;
        }
    </style>
@endsection