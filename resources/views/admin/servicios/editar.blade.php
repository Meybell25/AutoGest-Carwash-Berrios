@extends('layouts.admin')

@section('title', $servicio->id ? 'Editar Servicio' : 'Crear Servicio')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-0 text-gray-800">
                {{ $servicio->id ? 'Editar Servicio' : 'Crear Nuevo Servicio' }}
            </h1>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ $servicio->id ? route('admin.servicios.update', $servicio->id) : route('admin.servicios.store') }}" 
                  method="POST">
                @csrf
                @if($servicio->id)
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nombre">Nombre del Servicio *</label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                   id="nombre" name="nombre" 
                                   value="{{ old('nombre', $servicio->nombre) }}" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="categoria">Categoría *</label>
                            <select class="form-control @error('categoria') is-invalid @enderror" 
                                    id="categoria" name="categoria" required>
                                <option value="">Seleccione una categoría</option>
                                @foreach(['lavado', 'pulido', 'interior', 'completo'] as $categoria)
                                    <option value="{{ $categoria }}" 
                                        {{ old('categoria', $servicio->categoria) == $categoria ? 'selected' : '' }}>
                                        {{ ucfirst($categoria) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('categoria')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                              id="descripcion" name="descripcion" rows="2">{{ old('descripcion', $servicio->descripcion) }}</textarea>
                    @error('descripcion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="precio">Precio ($) *</label>
                            <input type="number" step="0.01" min="0.01" 
                                   class="form-control @error('precio') is-invalid @enderror" 
                                   id="precio" name="precio" 
                                   value="{{ old('precio', $servicio->precio) }}" required>
                            @error('precio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="duracion_min">Duración (minutos) *</label>
                            <input type="number" min="5" 
                                   class="form-control @error('duracion_min') is-invalid @enderror" 
                                   id="duracion_min" name="duracion_min" 
                                   value="{{ old('duracion_min', $servicio->duracion_min) }}" required>
                            @error('duracion_min')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="activo">Estado</label>
                            <select class="form-control" id="activo" name="activo">
                                <option value="1" {{ old('activo', $servicio->activo) ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ !old('activo', $servicio->activo) ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ $servicio->id ? 'Actualizar' : 'Guardar' }}
                    </button>
                    <a href="{{ route('admin.servicios.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection