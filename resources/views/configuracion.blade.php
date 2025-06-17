@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <!-- Botón de regreso -->
        <div class="row mb-3">
            <div class="col-12">
                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-arrow-left me-2"></i> 
                    <span class="d-none d-sm-inline">Volver al Dashboard</span>
                    <span class="d-sm-none">Volver</span>
                </a>
            </div>
        </div>

        <!-- Tarjeta principal -->
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">
                <div class="card">
                    <div class="card-header text-center text-md-start">
                        <h4 class="mb-0">
                            <i class="fas fa-cog me-2"></i> 
                            <span class="d-none d-sm-inline">Configuración de Cuenta</span>
                            <span class="d-sm-none">Configuración</span>
                        </h4>
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

                        <!-- Información de la cuenta -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3">
                                <i class="fas fa-info-circle me-2 d-none d-sm-inline"></i>
                                Información de la Cuenta
                            </h5>
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <div class="bg-light p-3 rounded">
                                        <small class="text-muted d-block">Fecha de creación</small>
                                        <strong>{{ $user->created_at->format('d/m/Y H:i') }}</strong>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="bg-light p-3 rounded">
                                        <small class="text-muted d-block">Última actualización</small>
                                        <strong>{{ $user->updated_at->format('d/m/Y H:i') }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Formulario de información básica -->
                        <form method="POST" action="{{ route('perfil.update') }}">
                            @csrf
                            <h5 class="fw-bold mb-3">
                                <i class="fas fa-user me-2 d-none d-sm-inline"></i>
                                Información Básica
                            </h5>

                            <div class="row g-3 mb-3">
                                <div class="col-12">
                                    <label for="nombre" class="form-label fw-semibold">
                                        <i class="fas fa-user me-1"></i>Nombre
                                    </label>
                                    <input id="nombre" type="text" class="form-control" name="nombre"
                                        value="{{ $user->nombre }}" required>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <label for="telefono" class="form-label fw-semibold">
                                        <i class="fas fa-phone me-1"></i>Teléfono
                                    </label>
                                    <input id="telefono" type="text" class="form-control" name="telefono"
                                        value="{{ $user->telefono }}" placeholder="Ej: +503 1234-5678">
                                </div>
                            </div>

                            <div class="d-grid d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>
                                    <span class="d-none d-sm-inline">Guardar Cambios</span>
                                    <span class="d-sm-none">Guardar</span>
                                </button>
                            </div>
                        </form>

                        <hr class="my-4">

                        <!-- Formulario de cambio de email -->
                        <form method="POST" action="{{ route('configuracion.update-email') }}">
                            @csrf
                            <h5 class="fw-bold mb-3">
                                <i class="fas fa-envelope me-2 d-none d-sm-inline"></i>
                                Cambiar Email
                            </h5>

                            <div class="row g-3 mb-3">
                                <div class="col-12">
                                    <label for="current_email" class="form-label fw-semibold">
                                        <i class="fas fa-envelope-open me-1"></i>Email Actual
                                    </label>
                                    <input id="current_email" type="email" class="form-control bg-light"
                                        value="{{ $user->email }}" disabled>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <label for="email" class="form-label fw-semibold">
                                        <i class="fas fa-envelope me-1"></i>Nuevo Email
                                    </label>
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" 
                                        name="email" required placeholder="nuevo@ejemplo.com">
                                    @error('email')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-grid d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-envelope me-2"></i>
                                    <span class="d-none d-sm-inline">Actualizar Email</span>
                                    <span class="d-sm-none">Actualizar</span>
                                </button>
                            </div>
                        </form>

                        <hr class="my-4">

                        <!-- Formulario de cambio de contraseña -->
                        <form method="POST" action="{{ route('configuracion.update-password') }}">
                            @csrf
                            <h5 class="fw-bold mb-3">
                                <i class="fas fa-key me-2 d-none d-sm-inline"></i>
                                Cambiar Contraseña
                            </h5>

                            <div class="row g-3 mb-3">
                                <div class="col-12">
                                    <label for="current_password" class="form-label fw-semibold">
                                        <i class="fas fa-lock me-1"></i>Contraseña Actual
                                    </label>
                                    <div class="input-group">
                                        <input id="current_password" type="password"
                                            class="form-control @error('current_password') is-invalid @enderror"
                                            name="current_password" required>
                                        <button class="btn btn-outline-secondary" type="button" 
                                                onclick="togglePassword('current_password')">
                                            <i class="fas fa-eye" id="current_password_icon"></i>
                                        </button>
                                        @error('current_password')
                                            <div class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-12">
                                    <label for="password" class="form-label fw-semibold">
                                        <i class="fas fa-key me-1"></i>Nueva Contraseña
                                    </label>
                                    <div class="input-group">
                                        <input id="password" type="password"
                                            class="form-control @error('password') is-invalid @enderror" 
                                            name="password" required>
                                        <button class="btn btn-outline-secondary" type="button" 
                                                onclick="togglePassword('password')">
                                            <i class="fas fa-eye" id="password_icon"></i>
                                        </button>
                                        @error('password')
                                            <div class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-text">
                                        <small>Mínimo 8 caracteres, incluye mayúsculas, minúsculas y números</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <label for="password-confirm" class="form-label fw-semibold">
                                        <i class="fas fa-check-double me-1"></i>Confirmar Contraseña
                                    </label>
                                    <div class="input-group">
                                        <input id="password-confirm" type="password" class="form-control"
                                            name="password_confirmation" required>
                                        <button class="btn btn-outline-secondary" type="button" 
                                                onclick="togglePassword('password-confirm')">
                                            <i class="fas fa-eye" id="password-confirm_icon"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-key me-2"></i>
                                    <span class="d-none d-sm-inline">Cambiar Contraseña</span>
                                    <span class="d-sm-none">Cambiar</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Función para mostrar/ocultar contraseñas
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '_icon');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Validación en tiempo real de confirmación de contraseña
        document.getElementById('password-confirm').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (confirmPassword && password !== confirmPassword) {
                this.classList.add('is-invalid');
                if (!this.nextElementSibling || !this.nextElementSibling.classList.contains('invalid-feedback')) {
                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    feedback.textContent = 'Las contraseñas no coinciden';
                    this.parentNode.appendChild(feedback);
                }
            } else {
                this.classList.remove('is-invalid');
                const feedback = this.parentNode.querySelector('.invalid-feedback');
                if (feedback && feedback.textContent === 'Las contraseñas no coinciden') {
                    feedback.remove();
                }
            }
        });
    </script>
@endsection