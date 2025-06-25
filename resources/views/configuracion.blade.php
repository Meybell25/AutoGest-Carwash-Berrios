@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <!-- Botón de regreso mejorado -->
        <div class="row mb-4">
            <div class="col-12">
                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-arrow-left me-2"></i>
                    <span class="d-none d-sm-inline fs-6">Volver al Dashboard</span>
                    <span class="d-sm-none fs-6">Volver</span>
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

                        @if ($errors->any())
                            <script>
                                swalWithBootstrapButtons.fire({
                                    title: '¡Error!',
                                    html: `{!! implode('<br>', $errors->all()) !!}`,
                                    icon: 'error',
                                    confirmButtonText: 'Entendido'
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
                        <form method="POST" action="{{ route('perfil.update') }}" id="basicInfoForm">
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
                                    <input id="nombre" type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre"
                                        value="{{ old('nombre', $user->nombre) }}" required>
                                    @error('nombre')
                                        <div class="invalid-feedback">
                                            <strong>Por favor ingresa un nombre válido (solo letras y espacios).</strong>
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <label for="telefono" class="form-label fw-semibold">
                                        <i class="fas fa-phone me-1"></i>Teléfono
                                    </label>
                                    <input id="telefono" type="text" class="form-control @error('telefono') is-invalid @enderror" name="telefono"
                                        value="{{ old('telefono', $user->telefono) }}" placeholder="Ej: +503 1234-5678">
                                    @error('telefono')
                                        <div class="invalid-feedback">
                                            <strong>Por favor ingresa un número de teléfono válido.</strong>
                                        </div>
                                    @enderror
                                    <div class="form-text">
                                        <small>Formato: +código país número (ej: +503 1234-5678)</small>
                                    </div>
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
                        <form method="POST" action="{{ route('configuracion.update-email') }}" id="emailForm">
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
                                        class="form-control @error('email') is-invalid @enderror" name="email" 
                                        value="{{ old('email') }}" required
                                        placeholder="nuevo@ejemplo.com">
                                    @error('email')
                                        <div class="invalid-feedback">
                                            @if($errors->first('email') == 'validation.unique')
                                                <strong>Este correo electrónico ya está en uso. Por favor usa otro.</strong>
                                            @else
                                                <strong>Por favor ingresa un correo electrónico válido.</strong>
                                            @endif
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
                        <form method="POST" action="{{ route('configuracion.update-password') }}" id="passwordForm">
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
                                                <strong>La contraseña actual no es correcta.</strong>
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
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            required>
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="togglePassword('password')">
                                            <i class="fas fa-eye" id="password_icon"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback d-block">
                                            @if(str_contains($message, 'validation.min.string'))
                                                <strong>La contraseña debe tener al menos 8 caracteres.</strong>
                                            @elseif(str_contains($message, 'validation.password.mixed'))
                                                <strong>La contraseña debe contener mayúsculas y minúsculas.</strong>
                                            @elseif(str_contains($message, 'validation.password.numbers'))
                                                <strong>La contraseña debe contener al menos un número.</strong>
                                            @else
                                                <strong>La contraseña no cumple con los requisitos de seguridad.</strong>
                                            @endif
                                        </div>
                                    @enderror
                                    <div class="form-text">
                                        <small>Mínimo 8 caracteres, incluye mayúsculas, minúsculas y números</small>
                                    </div>

                                    <div class="password-strength mt-2">
                                        <div class="progress" style="height: 5px;">
                                            <div class="progress-bar" id="password-strength-bar" role="progressbar"
                                                style="width: 0%"></div>
                                        </div>
                                        <small class="text-muted" id="password-strength-text">Seguridad de la
                                            contraseña</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <label for="password-confirm" class="form-label fw-semibold">
                                        <i class="fas fa-check-double me-1"></i>Confirmar Contraseña
                                    </label>
                                    <div class="input-group">
                                        <input id="password-confirm" type="password"
                                            class="form-control @error('password_confirmation') is-invalid @enderror"
                                            name="password_confirmation" required>
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="togglePassword('password-confirm')">
                                            <i class="fas fa-eye" id="password-confirm_icon"></i>
                                        </button>
                                    </div>
                                    @error('password_confirmation')
                                        <div class="invalid-feedback d-block">
                                            <strong>Las contraseñas no coinciden.</strong>
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Botón de cambiar contraseña con espaciado mejorado -->
                            <div class="mt-4">
                                <div class="d-grid d-md-flex justify-content-md-end">
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-key me-2"></i>
                                        <span class="d-none d-sm-inline">Cambiar Contraseña</span>
                                        <span class="d-sm-none">Cambiar</span>
                                    </button>
                                </div>
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

        // Validación de nombre (solo letras y espacios)
        document.getElementById('nombre').addEventListener('input', function() {
            const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
            if (!regex.test(this.value)) {
                this.classList.add('is-invalid');
                const feedback = this.parentNode.querySelector('.invalid-feedback') || document.createElement('div');
                feedback.className = 'invalid-feedback';
                feedback.innerHTML = '<strong>Por favor ingresa solo letras y espacios.</strong>';
                if (!this.parentNode.querySelector('.invalid-feedback')) {
                    this.parentNode.appendChild(feedback);
                }
            } else {
                this.classList.remove('is-invalid');
                const feedback = this.parentNode.querySelector('.invalid-feedback');
                if (feedback) feedback.remove();
            }
        });

        // Validación de teléfono
        document.getElementById('telefono').addEventListener('input', function() {
            const regex = /^\+?[\d\s\-]+$/;
            if (!regex.test(this.value)) {
                this.classList.add('is-invalid');
                const feedback = this.parentNode.querySelector('.invalid-feedback') || document.createElement('div');
                feedback.className = 'invalid-feedback';
                feedback.innerHTML = '<strong>Por favor ingresa un número de teléfono válido.</strong>';
                if (!this.parentNode.querySelector('.invalid-feedback')) {
                    this.parentNode.appendChild(feedback);
                }
            } else {
                this.classList.remove('is-invalid');
                const feedback = this.parentNode.querySelector('.invalid-feedback');
                if (feedback) feedback.remove();
            }
        });

        // Validación de email
        document.getElementById('email').addEventListener('input', function() {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!regex.test(this.value)) {
                this.classList.add('is-invalid');
                const feedback = this.parentNode.querySelector('.invalid-feedback') || document.createElement('div');
                feedback.className = 'invalid-feedback';
                feedback.innerHTML = '<strong>Por favor ingresa un correo electrónico válido.</strong>';
                if (!this.parentNode.querySelector('.invalid-feedback')) {
                    this.parentNode.appendChild(feedback);
                }
            } else {
                this.classList.remove('is-invalid');
                const feedback = this.parentNode.querySelector('.invalid-feedback');
                if (feedback) feedback.remove();
            }
        });

        // Validación de contraseña actual
        document.getElementById('current_password').addEventListener('input', function() {
            if (this.value.length > 0) {
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
            }
        });

        // Validación en tiempo real de la nueva contraseña
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const hasMinLength = password.length >= 8;
            const hasUpperCase = /[A-Z]/.test(password);
            const hasLowerCase = /[a-z]/.test(password);
            const hasNumber = /\d/.test(password);
            const hasSpecialChar = /[^A-Za-z0-9]/.test(password);

            // Validación de fortaleza
            if (hasMinLength && hasUpperCase && hasLowerCase && hasNumber) {
                this.classList.add('is-valid');
                this.classList.remove('is-invalid');
                const feedback = this.parentNode.querySelector('.invalid-feedback');
                if (feedback) feedback.remove();
            } else {
                this.classList.remove('is-valid');
                
                let errorMessage = '';
                if (!hasMinLength) errorMessage = 'La contraseña debe tener al menos 8 caracteres.';
                else if (!hasUpperCase || !hasLowerCase) errorMessage = 'La contraseña debe contener mayúsculas y minúsculas.';
                else if (!hasNumber) errorMessage = 'La contraseña debe contener al menos un número.';
                
                const feedback = this.parentNode.querySelector('.invalid-feedback') || document.createElement('div');
                feedback.className = 'invalid-feedback';
                feedback.innerHTML = `<strong>${errorMessage}</strong>`;
                if (!this.parentNode.querySelector('.invalid-feedback')) {
                    this.parentNode.appendChild(feedback);
                }
            }

            // Indicador de fortaleza
            const strength = calculatePasswordStrength(password);
            const strengthBar = document.getElementById('password-strength-bar');
            const strengthText = document.getElementById('password-strength-text');

            strengthBar.style.width = strength.percentage + '%';
            strengthBar.className = 'progress-bar ' + strength.class;
            strengthText.textContent = strength.text;
            strengthText.className = strength.textClass;

            // Disparar validación de confirmación si hay valor
            const confirmField = document.getElementById('password-confirm');
            if (confirmField.value) {
                confirmField.dispatchEvent(new Event('input'));
            }
        });

        // Función para calcular fortaleza de contraseña
        function calculatePasswordStrength(password) {
            let strength = 0;
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/\d/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            const percentage = (strength / 5) * 100;

            if (password.length === 0) return {
                percentage: 0,
                class: '',
                text: 'Seguridad de la contraseña',
                textClass: 'text-muted'
            };
            if (strength <= 1) return {
                percentage,
                class: 'bg-danger',
                text: 'Muy débil',
                textClass: 'text-danger'
            };
            if (strength <= 3) return {
                percentage,
                class: 'bg-warning',
                text: 'Moderada',
                textClass: 'text-warning'
            };
            return {
                percentage,
                class: 'bg-success',
                text: 'Fuerte',
                textClass: 'text-success'
            };
        }

        // Validación en tiempo real de confirmación de contraseña
        document.getElementById('password-confirm').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            const feedbackDiv = this.parentNode.querySelector('.invalid-feedback');

            // Eliminar cualquier mensaje existente primero
            if (feedbackDiv && feedbackDiv.textContent === 'Las contraseñas no coinciden') {
                feedbackDiv.remove();
            }

            if (confirmPassword && password !== confirmPassword) {
                this.classList.add('is-invalid');
                const feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                feedback.innerHTML = '<strong>Las contraseñas no coinciden.</strong>';
                this.parentNode.appendChild(feedback);
            } else {
                this.classList.remove('is-invalid');
                const feedback = this.parentNode.querySelector('.invalid-feedback');
                if (feedback) feedback.remove();
            }
        });

        // Validación antes de enviar formularios
        document.getElementById('basicInfoForm').addEventListener('submit', function(e) {
            const nombre = document.getElementById('nombre');
            const telefono = document.getElementById('telefono');
            
            if (nombre.value.trim() === '') {
                e.preventDefault();
                swalWithBootstrapButtons.fire({
                    title: 'Error',
                    text: 'Por favor ingresa tu nombre.',
                    icon: 'error',
                    confirmButtonText: 'Entendido'
                });
                nombre.focus();
                return false;
            }
            
            if (telefono.value && !/^\+?[\d\s\-]+$/.test(telefono.value)) {
                e.preventDefault();
                swalWithBootstrapButtons.fire({
                    title: 'Error',
                    text: 'Por favor ingresa un número de teléfono válido.',
                    icon: 'error',
                    confirmButtonText: 'Entendido'
                });
                telefono.focus();
                return false;
            }
        });

        document.getElementById('emailForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email');
            
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
                e.preventDefault();
                swalWithBootstrapButtons.fire({
                    title: 'Error',
                    text: 'Por favor ingresa un correo electrónico válido.',
                    icon: 'error',
                    confirmButtonText: 'Entendido'
                });
                email.focus();
                return false;
            }
        });

        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            const currentPassword = document.getElementById('current_password');
            const password = document.getElementById('password');
            const passwordConfirm = document.getElementById('password-confirm');
            
            if (currentPassword.value.trim() === '') {
                e.preventDefault();
                swalWithBootstrapButtons.fire({
                    title: 'Error',
                    text: 'Por favor ingresa tu contraseña actual.',
                    icon: 'error',
                    confirmButtonText: 'Entendido'
                });
                currentPassword.focus();
                return false;
            }
            
            if (password.value.length < 8) {
                e.preventDefault();
                swalWithBootstrapButtons.fire({
                    title: 'Error',
                    text: 'La nueva contraseña debe tener al menos 8 caracteres.',
                    icon: 'error',
                    confirmButtonText: 'Entendido'
                });
                password.focus();
                return false;
            }
            
            if (!/[A-Z]/.test(password.value) || !/[a-z]/.test(password.value)) {
                e.preventDefault();
                swalWithBootstrapButtons.fire({
                    title: 'Error',
                    text: 'La contraseña debe contener mayúsculas y minúsculas.',
                    icon: 'error',
                    confirmButtonText: 'Entendido'
                });
                password.focus();
                return false;
            }
            
            if (!/\d/.test(password.value)) {
                e.preventDefault();
                swalWithBootstrapButtons.fire({
                    title: 'Error',
                    text: 'La contraseña debe contener al menos un número.',
                    icon: 'error',
                    confirmButtonText: 'Entendido'
                });
                password.focus();
                return false;
            }
            
            if (password.value !== passwordConfirm.value) {
                e.preventDefault();
                swalWithBootstrapButtons.fire({
                    title: 'Error',
                    text: 'Las contraseñas no coinciden.',
                    icon: 'error',
                    confirmButtonText: 'Entendido'
                });
                passwordConfirm.focus();
                return false;
            }
        });
    </script>
@endsection