@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-cog"></i> Configuración de Cuenta</h4>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <script>
                            swalWithBootstrapButtons.fire({
                                title: '¡Éxito!',
                                text: '{{ session('success') }}',
                                icon: 'success',
                                confirmButtonText: 'Aceptar'
                            });
                        </script>
                    @endif

                    <div class="mb-4">
                        <h5 class="font-weight-bold">Información de la Cuenta</h5>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <p><strong>Fecha de creación:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Última actualización:</strong> {{ $user->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('perfil.update') }}">
                        @csrf
                        <h5 class="font-weight-bold mb-3">Información Básica</h5>
                        
                        <div class="form-group row">
                            <label for="nombre" class="col-md-4 col-form-label">Nombre</label>
                            <div class="col-md-8">
                                <input id="nombre" type="text" class="form-control" name="nombre" value="{{ $user->nombre }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="telefono" class="col-md-4 col-form-label">Teléfono</label>
                            <div class="col-md-8">
                                <input id="telefono" type="text" class="form-control" name="telefono" value="{{ $user->telefono }}">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </form>

                    <hr class="my-4">

                    <form method="POST" action="{{ route('configuracion.update-email') }}">
                        @csrf
                        <h5 class="font-weight-bold mb-3">Cambiar Email</h5>
                        
                        <div class="form-group row">
                            <label for="current_email" class="col-md-4 col-form-label">Email Actual</label>
                            <div class="col-md-8">
                                <input id="current_email" type="email" class="form-control" value="{{ $user->email }}" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label">Nuevo Email</label>
                            <div class="col-md-8">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" required>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-envelope"></i> Actualizar Email
                                </button>
                            </div>
                        </div>
                    </form>

                    <hr class="my-4">

                    <form method="POST" action="{{ route('configuracion.update-password') }}">
                        @csrf
                        <h5 class="font-weight-bold mb-3">Cambiar Contraseña</h5>
                        
                        <div class="form-group row">
                            <label for="current_password" class="col-md-4 col-form-label">Contraseña Actual</label>
                            <div class="col-md-8">
                                <input id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" required>
                                @error('current_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label">Nueva Contraseña</label>
                            <div class="col-md-8">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label">Confirmar Contraseña</label>
                            <div class="col-md-8">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-key"></i> Cambiar Contraseña
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection