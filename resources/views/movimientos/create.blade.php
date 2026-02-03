@extends('layouts.app')

@section('page-title', 'Gestión de Movimientos')
@section('page-subtitle', '')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-{{ $tipo === 'entrada' ? 'success' : 'danger' }} text-white">
                    <h4 class="mb-0">
                        @if($tipo === 'entrada')
                            <i class="fas fa-arrow-down"></i> Registrar Entrada de Material
                        @else
                            <i class="fas fa-arrow-up"></i> Registrar Salida de Material
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

                    <form action="{{ route('movimientos.store') }}" method="POST" id="formMovimiento">
                        @csrf
                        <input type="hidden" name="tipo" value="{{ $tipo }}">

                        <!-- Información General -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-info-circle"></i> Información General
                                </h5>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required">Material</label>
                                <select name="material_id" id="material_id" class="form-select" required>
                                    <option value="">Seleccione un material...</option>
                                    @foreach($materiales as $material)
                                        <option value="{{ $material->id }}" 
                                                data-unidad="{{ $material->unidad_medida }}"
                                                data-stock="{{ $material->cantidad_actual }}"
                                                {{ old('material_id') == $material->id ? 'selected' : '' }}>
                                            {{ $material->codigo }} - {{ $material->nombre }} 
                                            (Stock: {{ $material->cantidad_actual }} {{ $material->unidad_medida }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted" id="infoStock"></small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required">Fecha</label>
                                <input type="date" name="fecha" class="form-control" 
                                       value="{{ old('fecha', date('Y-m-d')) }}" 
                                       max="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>

                        <!-- Cantidad y Costos -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-calculator"></i> Cantidad y Costos
                                </h5>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label required">Cantidad</label>
                                <input type="number" name="cantidad" id="cantidad" class="form-control" 
                                       value="{{ old('cantidad') }}" min="1" step="1" required>
                                <small class="text-muted" id="unidadMedida"></small>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label required">Costo Unitario (Bs.)</label>
                                <input type="number" name="costo_unitario" id="costo_unitario" 
                                       class="form-control" value="{{ old('costo_unitario') }}" 
                                       min="0" step="0.01" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Total (Bs.)</label>
                                <input type="text" id="total_calculado" class="form-control" 
                                       readonly style="background-color: #e9ecef; font-weight: bold;">
                            </div>
                        </div>

                        @if($tipo === 'entrada')
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
                                           value="{{ old('numero_ingreso') }}" 
                                           placeholder="Ej: ING-001" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Número de Factura</label>
                                    <input type="text" name="numero_factura" class="form-control" 
                                           value="{{ old('numero_factura') }}" 
                                           placeholder="Ej: FACT-12345">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label required">Proveedor</label>
                                    <input type="text" name="proveedor" class="form-control" 
                                           value="{{ old('proveedor') }}" 
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
                                           value="{{ old('numero_salida') }}" 
                                           placeholder="Ej: SAL-001" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label required">Solicitante</label>
                                    <select name="solicitante" class="form-select" required>
                                        <option value="">Seleccione...</option>
                                        <option value="JEFE TECNICO" {{ old('solicitante') == 'JEFE TECNICO' ? 'selected' : '' }}>
                                            JEFE TECNICO
                                        </option>
                                        <option value="SECRETARIA" {{ old('solicitante') == 'SECRETARIA' ? 'selected' : '' }}>
                                            SECRETARIA
                                        </option>
                                        <option value="ADMINISTRADOR" {{ old('solicitante') == 'ADMINISTRADOR' ? 'selected' : '' }}>
                                            ADMINISTRADOR
                                        </option>
                                        <option value="ALMACENERO" {{ old('solicitante') == 'ALMACENERO' ? 'selected' : '' }}>
                                            ALMACENERO
                                        </option>
                                        <option value="OTROS" {{ old('solicitante') == 'OTROS' ? 'selected' : '' }}>
                                            OTROS
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-12 mt-3">
                                    <label class="form-label required">Motivo de Salida</label>
                                    <textarea name="motivo" class="form-control" rows="3" 
                                              placeholder="Describa el motivo de la salida..." required>{{ old('motivo') }}</textarea>
                                </div>
                            </div>
                        @endif

                        <!-- Observaciones -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="form-label">Observaciones</label>
                                <textarea name="observaciones" class="form-control" rows="3" 
                                          placeholder="Observaciones adicionales (opcional)">{{ old('observaciones') }}</textarea>
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
                                    <button type="submit" class="btn btn-{{ $tipo === 'entrada' ? 'success' : 'danger' }}">
                                        <i class="fas fa-save"></i> Registrar {{ ucfirst($tipo) }}
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const materialSelect = document.getElementById('material_id');
    const cantidadInput = document.getElementById('cantidad');
    const costoUnitarioInput = document.getElementById('costo_unitario');
    const totalCalculado = document.getElementById('total_calculado');
    const infoStock = document.getElementById('infoStock');
    const unidadMedida = document.getElementById('unidadMedida');
    const tipo = '{{ $tipo }}';

    // Actualizar información del material seleccionado
    materialSelect.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        const stock = option.dataset.stock || 0;
        const unidad = option.dataset.unidad || '';

        if (this.value) {
            infoStock.textContent = `Stock actual: ${stock} ${unidad}`;
            unidadMedida.textContent = `Unidad: ${unidad}`;
            
            if (tipo === 'salida' && stock == 0) {
                infoStock.classList.add('text-danger');
                infoStock.textContent += ' - ⚠️ Sin stock disponible';
            } else {
                infoStock.classList.remove('text-danger');
            }
        } else {
            infoStock.textContent = '';
            unidadMedida.textContent = '';
        }
    });

    // Calcular total automáticamente
    function calcularTotal() {
        const cantidad = parseFloat(cantidadInput.value) || 0;
        const costoUnitario = parseFloat(costoUnitarioInput.value) || 0;
        const total = cantidad * costoUnitario;
        totalCalculado.value = 'Bs. ' + total.toFixed(2);
    }

    cantidadInput.addEventListener('input', calcularTotal);
    costoUnitarioInput.addEventListener('input', calcularTotal);

    // Validación antes de enviar
    document.getElementById('formMovimiento').addEventListener('submit', function(e) {
        if (tipo === 'salida') {
            const option = materialSelect.options[materialSelect.selectedIndex];
            const stock = parseFloat(option.dataset.stock) || 0;
            const cantidad = parseFloat(cantidadInput.value) || 0;

            if (cantidad > stock) {
                e.preventDefault();
                alert(`No hay suficiente stock. Stock disponible: ${stock}`);
                cantidadInput.focus();
                return false;
            }
        }
    });
});
</script>
@endsection