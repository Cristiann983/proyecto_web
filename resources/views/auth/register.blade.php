<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DevTeams - Registro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-purple-50 to-blue-50 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-2xl">
        <!-- Logo y título -->
        <div class="text-center mb-8">
            <div class="flex items-center justify-center gap-2 mb-4">
                <div class="text-4xl text-purple-600">&lt;/&gt;</div>
                <span class="text-3xl font-bold text-gray-900">DevTeams</span>
            </div>
            <p class="text-gray-600">Crea tu cuenta de participante</p>
        </div>

        <!-- Formulario de registro -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Registro de Participante</h2>
            
            <!-- Mostrar errores -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <ul class="text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Información de Usuario -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de Usuario</h3>
                    
                    <div class="grid md:grid-cols-2 gap-4">
                        <!-- Nombre de usuario -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre de usuario *
                            </label>
                            <input 
                                type="text" 
                                name="username"
                                value="{{ old('username') }}"
                                placeholder="usuario123"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            >
                        </div>

                        <!-- Contraseña -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Contraseña *
                            </label>
                            <input 
                                type="password" 
                                name="password"
                                placeholder="••••••••"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            >
                        </div>

                        <!-- Confirmar contraseña -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Confirmar contraseña *
                            </label>
                            <input 
                                type="password" 
                                name="password_confirmation"
                                placeholder="••••••••"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            >
                        </div>
                    </div>
                </div>

                <!-- Información del Participante -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Información Personal</h3>
                    
                    <div class="grid md:grid-cols-2 gap-4">
                        <!-- Nombre completo -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre completo *
                            </label>
                            <input 
                                type="text" 
                                name="nombre"
                                value="{{ old('nombre') }}"
                                placeholder="Juan Pérez García"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            >
                        </div>

                        <!-- No. Control -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                No. Control *
                            </label>
                            <input 
                                type="text" 
                                name="no_control"
                                value="{{ old('no_control') }}"
                                placeholder="20170123"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            >
                        </div>

                        <!-- Carrera -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Carrera *
                            </label>
                            <select 
                                name="carrera_id"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            >
                                <option value="">Selecciona tu carrera</option>
                                <option value="1" {{ old('carrera_id') == '1' ? 'selected' : '' }}>Ingeniería en Sistemas Computacionales</option>
                                <option value="2" {{ old('carrera_id') == '2' ? 'selected' : '' }}>Ingeniería en Tecnologías de la Información</option>
                                <option value="3" {{ old('carrera_id') == '3' ? 'selected' : '' }}>Ingeniería en Desarrollo de Software</option>
                                <option value="4" {{ old('carrera_id') == '4' ? 'selected' : '' }}>Ingeniería Informática</option>
                                <option value="5" {{ old('carrera_id') == '5' ? 'selected' : '' }}>Licenciatura en Administración</option>
                                <option value="6" {{ old('carrera_id') == '6' ? 'selected' : '' }}>Ingeniería Industrial</option>
                            </select>
                        </div>

                        <!-- Correo electrónico -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Correo electrónico *
                            </label>
                            <input 
                                type="email" 
                                name="correo"
                                value="{{ old('correo') }}"
                                placeholder="ejemplo@email.com"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            >
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Teléfono *
                            </label>
                            <input 
                                type="tel" 
                                name="telefono"
                                value="{{ old('telefono') }}"
                                placeholder="6441234567"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            >
                        </div>
                    </div>
                </div>

                <!-- Términos y condiciones -->
                <div class="flex items-start">
                    <input 
                        type="checkbox" 
                        id="terms"
                        name="terms"
                        required
                        class="mt-1 h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded"
                    >
                    <label for="terms" class="ml-2 text-sm text-gray-600">
                        Acepto los <a href="#" class="text-purple-600 hover:text-purple-700 font-medium">términos y condiciones</a> y la <a href="#" class="text-purple-600 hover:text-purple-700 font-medium">política de privacidad</a>
                    </label>
                </div>

                <!-- Botón de registro -->
                <button 
                    type="submit"
                    class="w-full bg-purple-600 hover:bg-purple-700 text-white py-3 rounded-lg font-medium transition flex items-center justify-center gap-2"
                >
                    <span>Crear cuenta</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </button>

                <!-- Link a login -->
                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        ¿Ya tienes una cuenta? 
                        <a href="{{ route('login') }}" class="text-purple-600 hover:text-purple-700 font-medium">
                            Inicia sesión
                        </a>
                    </p>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-sm text-gray-600">
            <p>© 2024 DevTeams. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>