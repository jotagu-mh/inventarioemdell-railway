@extends('layouts.app')

@section('page-title', 'Nueva Categoría')
@section('page-subtitle', 'Crear una nueva categoría')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0">
                    <i class="fas fa-plus-circle"></i> Registrar Nueva Categoría
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('categorias.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Nombre de la Categoría *</label>
                        <input type="text" 
                               name="nombre" 
                               class="form-control @error('nombre') is-invalid @enderror" 
                               value="{{ old('nombre') }}" 
                               required
                               placeholder="Ej: Administración, Técnica">
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" 
                                  class="form-control @error('descripcion') is-invalid @enderror" 
                                  rows="3"
                                  placeholder="Descripción opcional de la categoría">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Categoría
                        </button>
                        <a href="{{ route('categorias.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection