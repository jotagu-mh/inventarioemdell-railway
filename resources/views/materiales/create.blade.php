@extends('layouts.app')

@section('page-title', 'Nuevo Material')
@section('page-subtitle', '')

@section('content')

<div class="container">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2><i class="fa-solid fa-plus-circle"></i>  Registre los datos del nuevo material</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('materiales.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('materiales.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="codigo" class="form-label">Código *</label>
                        <input type="text" class="form-control @error('codigo') is-invalid @enderror" 
                               id="codigo" name="codigo" value="{{ old('codigo') }}" required>
                        @error('codigo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">Nombre *</label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                               id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                              id="descripcion" name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="subcategoria_id" class="form-label">Subcategoría *</label>
                        <select class="form-select @error('subcategoria_id') is-invalid @enderror" 
                                id="subcategoria_id" name="subcategoria_id" required>
                            <option value="">Seleccione una subcategoría</option>
                            @foreach($subcategorias as $subcategoria)
                                <option value="{{ $subcategoria->id }}" {{ old('subcategoria_id') == $subcategoria->id ? 'selected' : '' }}>
                                    {{ $subcategoria->categoria->nombre }} - {{ $subcategoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('subcategoria_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="unidad_medida" class="form-label">Unidad de Medida *</label>
                        <select class="form-select @error('unidad_medida') is-invalid @enderror" 
                                id="unidad_medida" name="unidad_medida" required>
                            <option value="piezas" {{ old('unidad_medida') == 'piezas' ? 'selected' : '' }}>Piezas</option>
                            <option value="kg" {{ old('unidad_medida') == 'kg' ? 'selected' : '' }}>Kilogramos</option>
                            <option value="litros" {{ old('unidad_medida') == 'litros' ? 'selected' : '' }}>Litros</option>
                            <option value="metros" {{ old('unidad_medida') == 'metros' ? 'selected' : '' }}>Metros</option>
                            <option value="cajas" {{ old('unidad_medida') == 'cajas' ? 'selected' : '' }}>Cajas</option>
                            <option value="unidades" {{ old('unidad_medida') == 'unidades' ? 'selected' : '' }}>Unidades</option>
                        </select>
                        @error('unidad_medida')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="cantidad_actual" class="form-label">Cantidad Actual *</label>
                        <input type="number" class="form-control @error('cantidad_actual') is-invalid @enderror" 
                               id="cantidad_actual" name="cantidad_actual" value="{{ old('cantidad_actual', 0) }}" min="0" required>
                        @error('cantidad_actual')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="cantidad_minima" class="form-label">Cantidad Mínima *</label>
                        <input type="number" class="form-control @error('cantidad_minima') is-invalid @enderror" 
                               id="cantidad_minima" name="cantidad_minima" value="{{ old('cantidad_minima', 0) }}" min="0" required>
                        @error('cantidad_minima')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="precio_unitario" class="form-label">Precio Unitario *</label>
                        <input type="number" step="0.01" class="form-control @error('precio_unitario') is-invalid @enderror" 
                               id="precio_unitario" name="precio_unitario" value="{{ old('precio_unitario', 0) }}" min="0" required>
                        @error('precio_unitario')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="estado" class="form-label">Estado *</label>
                    <select class="form-select @error('estado') is-invalid @enderror" 
                            id="estado" name="estado" required>
                        <option value="activo" {{ old('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="inactivo" {{ old('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('estado')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Material
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection