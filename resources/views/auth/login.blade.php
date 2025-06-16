<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - AutoGest Carwash Berrios</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .wave-animation {
            background: linear-gradient(-45deg, #667eea, #764ba2, #6B73FF, #9A9CE3);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .float-animation {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .car-silhouette {
            fill: white;
            opacity: 0.1;
        }

        .water-drop {
            animation: drop 2s ease-in-out infinite;
        }

        @keyframes drop {
            0%, 100% { transform: translateY(0px) scaleY(1); }
            50% { transform: translateY(10px) scaleY(0.8); }
        }

        .password-toggle {
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .password-toggle:hover {
            color: #3b82f6;
            transform: scale(1.1);
        }

        /* Responsive adjustments */
        @media (max-width: 640px) {
            .container-mobile {
                min-height: 100vh;
                padding: 1rem;
            }
            
            .logo-mobile {
                width: 60px;
                height: 60px;
            }
            
            .title-mobile {
                font-size: 1.5rem;
            }
            
            .form-mobile {
                padding: 1.5rem;
            }
            
            .decorative-element {
                display: none;
            }
        }
        
        @media (max-height: 700px) and (max-width: 640px) {
            .compact-spacing {
                margin-bottom: 1rem;
            }
            
            .compact-form {
                padding: 1rem;
            }
        }
    </style>
</head>
<body class="wave-animation">
    <!-- Elementos decorativos de fondo - Solo en desktop -->
    <div class="absolute inset-0 overflow-hidden decorative-element">
        <!-- Burbujas flotantes -->
        <div class="absolute top-20 left-20 w-4 h-4 bg-white bg-opacity-20 rounded-full float-animation"></div>
        <div class="absolute top-40 right-32 w-6 h-6 bg-white bg-opacity-15 rounded-full float-animation" style="animation-delay: 2s;"></div>
        <div class="absolute bottom-32 left-16 w-3 h-3 bg-white bg-opacity-25 rounded-full float-animation" style="animation-delay: 4s;"></div>
        <div class="absolute bottom-20 right-20 w-5 h-5 bg-white bg-opacity-10 rounded-full float-animation" style="animation-delay: 1s;"></div>
        
        <!-- Siluetas de autos -->
        <div class="absolute top-1/4 left-10 opacity-5">
            <svg width="100" height="60" viewBox="0 0 100 60">
                <path class="car-silhouette" d="M20 50h60M15 40h70c2 0 4-2 4-4v-8c0-2-2-4-4-4h-10l-5-10h-30l-5 10h-15c-2 0-4 2-4 4v8c0 2 2 4 4 4z"/>
                <circle class="car-silhouette" cx="25" cy="45" r="5"/>
                <circle class="car-silhouette" cx="75" cy="45" r="5"/>
            </svg>
        </div>
        
        <div class="absolute bottom-1/4 right-10 opacity-5 transform rotate-12">
            <svg width="80" height="50" viewBox="0 0 80 50">
                <path class="car-silhouette" d="M15 40h50M10 30h60c2 0 3-1 3-3v-6c0-2-1-3-3-3h-8l-4-8h-24l-4 8h-12c-2 0-3 1-3 3v6c0 2 1 3 3 3z"/>
                <circle class="car-silhouette" cx="20" cy="35" r="4"/>
                <circle class="car-silhouette" cx="60" cy="35" r="4"/>
            </svg>
        </div>

        <!-- Gotas de agua -->
        <div class="absolute top-16 left-1/3">
            <svg width="20" height="30" class="water-drop">
                <path d="M10 25c-5 0-8-4-8-8 0-6 8-15 8-15s8 9 8 15c0 4-3 8-8 8z" fill="white" opacity="0.2"/>
            </svg>
        </div>
        <div class="absolute top-1/3 right-1/4 water-drop" style="animation-delay: 1s;">
            <svg width="15" height="25">
                <path d="M7.5 20c-3.5 0-6-3-6-6 0-4.5 6-12 6-12s6 7.5 6 12c0 3-2.5 6-6 6z" fill="white" opacity="0.15"/>
            </svg>
        </div>
    </div>

    <!-- Contenedor principal responsive -->
    <div class="container-mobile min-h-screen flex flex-col justify-center items-center p-4 sm:p-6 relative z-10">
        <div class="w-full max-w-md">
            <!-- Logo y título -->
            <div class="text-center compact-spacing mb-6 sm:mb-8">
                <div class="mx-auto logo-mobile w-16 h-16 sm:w-20 sm:h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center mb-3 sm:mb-4 backdrop-blur-sm border border-white border-opacity-30">
                    <svg width="32" height="32" class="sm:w-10 sm:h-10" viewBox="0 0 40 40" fill="white">
                        <path d="M8 30h24M5 25h30c1.5 0 3-1.5 3-3v-6c0-1.5-1.5-3-3-3h-7l-3-7h-18l-3 7h-9c-1.5 0-3 1.5-3 3v6c0 1.5 1.5 3 3 3z"/>
                        <circle cx="12" cy="27" r="3"/>
                        <circle cx="28" cy="27" r="3"/>
                        <!-- Gotas de agua cayendo -->
                        <circle cx="10" cy="8" r="1.5" opacity="0.7"/>
                        <circle cx="15" cy="6" r="1" opacity="0.5"/>
                        <circle cx="25" cy="7" r="1.2" opacity="0.6"/>
                        <circle cx="30" cy="5" r="0.8" opacity="0.4"/>
                    </svg>
                </div>
                <h1 class="title-mobile text-2xl sm:text-3xl font-bold text-white mb-1 sm:mb-2">AutoGest Carwash</h1>
                <p class="text-white text-opacity-80 text-sm sm:text-base">Berrios - Sistema de Gestión</p>
            </div>

            <!-- Formulario de login -->
            <div class="glass-effect rounded-2xl shadow-2xl form-mobile compact-form p-6 sm:p-8">
                <div class="text-center compact-spacing mb-4 sm:mb-6">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Iniciar Sesión</h2>
                    <p class="text-gray-600 mt-1 sm:mt-2 text-sm sm:text-base">Accede a tu panel de control</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-4 sm:space-y-6">
                    @csrf
                    
                    <!-- Campo Email -->
                    <div class="space-y-1 sm:space-y-2">
                        <label for="email" class="block text-sm font-semibold text-gray-700">
                            Correo Electrónico
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                            </div>
                            <input id="email" name="email" type="email" required
                                   class="block w-full pl-9 sm:pl-10 pr-3 py-2.5 sm:py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-sm sm:text-base @error('email') border-red-500 @enderror"
                                   placeholder="tu@email.com" value="{{ old('email') }}">
                        </div>
                        @error('email')
                            <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Campo Contraseña -->
                    <div class="space-y-1 sm:space-y-2">
                        <label for="password" class="block text-sm font-semibold text-gray-700">
                            Contraseña
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input id="password" name="password" type="password" required
                                   class="block w-full pl-9 sm:pl-10 pr-12 py-2.5 sm:py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-sm sm:text-base @error('password') border-red-500 @enderror"
                                   placeholder="••••••••">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button" class="password-toggle text-gray-400 hover:text-blue-500" onclick="togglePassword('password', 'eyeIcon')">
                                    <svg id="eyeIcon" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Checkbox Recordarme -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
                        <div class="flex items-center">
                            <input id="remember" name="remember" type="checkbox" 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="remember" class="ml-2 block text-sm text-gray-700">
                                Recordarme
                            </label>
                        </div>
                        <div>
                            <a href="#" class="text-sm text-blue-600 hover:text-blue-500 transition-colors">
                                ¿Olvidaste tu contraseña?
                            </a>
                        </div>
                    </div>

                    <!-- Botón de login -->
                    <button type="submit"
                            class="w-full flex justify-center py-2.5 sm:py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Ingresar al Sistema
                    </button>

                    <!-- Enlace a registro -->
                    <div class="text-center pt-3 sm:pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-600">
                            ¿No tienes cuenta? 
                            <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500 transition-colors">
                                Regístrate aquí
                            </a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="text-center compact-spacing mt-6 sm:mt-8 mb-4">
                <p class="text-white text-opacity-70 text-xs sm:text-sm">
                    © 2024 AutoGest Carwash Berrios. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L8.464 8.464m1.414 1.414L8.464 8.464m5.656 5.656l1.414 1.414m-1.414-1.414l1.414 1.414M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"/>
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                `;
            }
        }

        // Prevenir zoom en iOS en inputs
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    if (window.innerWidth < 768) {
                        document.querySelector('meta[name="viewport"]').setAttribute('content', 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no');
                    }
                });
                
                input.addEventListener('blur', function() {
                    if (window.innerWidth < 768) {
                        document.querySelector('meta[name="viewport"]').setAttribute('content', 'width=device-width, initial-scale=1.0');
                    }
                });
            });
        });
    </script>
</body>
</html>