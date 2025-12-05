<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripción - {{ $evento->Nombre }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Navegación -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="text-2xl text-purple-600">&lt;/&gt;</div>
                    <span class="text-xl font-bold">DevTeams</span>
                </div>
                
                <div class="flex items-center">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            <span>📋</span>
                            <span>Cerrar sesión</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="min-h-screen bg-gradient-to-br from-purple-50 to-blue-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                {{-- Header --}}
                <div class="bg-gradient-to-r from-purple-600 to-blue-600 px-8 py-6">
                    <h1 class="text-3xl font-bold text-white">Inscripción al Evento</h1>
                    <p class="text-purple-100 mt-2">{{ $evento->Nombre }}</p>
                </div>

                {{-- Contenido --}}
                <div class="px-8 py-6">
                    {{-- Información del evento --}}
                    <div class="bg-purple-50 rounded-lg p-6 mb-8">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Información del Evento</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-gray-700">Fecha inicio:</span>
                                <span class="text-gray-600">{{ $evento->Fecha_inicio->format('d/m/Y H:i') }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Fecha fin:</span>
                                <span class="text-gray-600">{{ $evento->Fecha_fin->format('d/m/Y H:i') }}</span>
                            </div>
                            @if($evento->Ubicacion)
                            <div class="md:col-span-2">
                                <span class="font-medium text-gray-700">Ubicación:</span>
                                <span class="text-gray-600">{{ $evento->Ubicacion }}</span>
                            </div>
                            @endif
                            @if($evento->Descripcion)
                            <div class="md:col-span-2">
                                <span class="font-medium text-gray-700">Descripción:</span>
                                <p class="text-gray-600 mt-2">{{ $evento->Descripcion }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Mensajes de error/éxito --}}
                    @if(session('success'))
                        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">¡Éxito!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">¡Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">¡Errores en el formulario!</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Formulario de inscripción --}}
                    <form method="POST" action="/eventos/{{ $evento->Id }}/inscribir" class="space-y-6">
                        @csrf

                        {{-- Selección de equipo --}}
                        <div>
                            <label for="equipo_id" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-users text-purple-600 mr-2"></i>
                                Selecciona tu equipo *
                            </label>
                            <select name="equipo_id" id="equipo_id" required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                                <option value="">-- Selecciona un equipo --</option>
                                @foreach($equipos as $equipo)
                                    <option value="{{ $equipo->Id }}">
                                        {{ $equipo->Nombre }} ({{ $equipo->participantes_count }} miembros)
                                    </option>
                                @endforeach
                            </select>
                            @error('equipo_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Selección de asesor --}}
                        <div>
                            <label for="asesor_id" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-chalkboard-teacher text-purple-600 mr-2"></i>
                                Selecciona un asesor *
                            </label>
                            <select name="asesor_id" id="asesor_id" required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                                <option value="">-- Selecciona un asesor --</option>
                                @foreach($asesores as $asesor)
                                    <option value="{{ $asesor->Id }}">
                                        {{ $asesor->Nombre }} 
                                        @if($asesor->Especialidad)
                                            - {{ $asesor->Especialidad }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('asesor_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nombre del proyecto --}}
                        <div>
                            <label for="nombre_proyecto" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-project-diagram text-purple-600 mr-2"></i>
                                Nombre del proyecto *
                            </label>
                            <input type="text" 
                                   name="nombre_proyecto" 
                                   id="nombre_proyecto" 
                                   required 
                                   maxlength="255"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                   placeholder="Ej: Sistema de gestión inteligente">
                            @error('nombre_proyecto')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Categoría del proyecto --}}
                        <div>
                            <label for="categoria" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-tag text-purple-600 mr-2"></i>
                                Categoría del proyecto *
                            </label>
                            <select name="categoria" id="categoria" required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                                <option value="">-- Selecciona una categoría --</option>
                                <option value="Desarrollo de Software">Desarrollo de Software</option>
                                <option value="Inteligencia Artificial">Inteligencia Artificial</option>
                                <option value="Robótica">Robótica</option>
                                <option value="Internet de las Cosas (IoT)">Internet de las Cosas (IoT)</option>
                                <option value="Ciberseguridad">Ciberseguridad</option>
                                <option value="Aplicaciones Móviles">Aplicaciones Móviles</option>
                                <option value="Realidad Virtual/Aumentada">Realidad Virtual/Aumentada</option>
                                <option value="Ciencia de Datos">Ciencia de Datos</option>
                                <option value="Otro">Otro</option>
                            </select>
                            @error('categoria')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Botones --}}
                        <div class="flex gap-4 pt-6">
                            <a href="{{ route('eventos.show', $evento->Id) }}" 
                               class="flex-1 px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition text-center">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="flex-1 px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-medium rounded-lg hover:from-purple-700 hover:to-blue-700 transition shadow-lg">
                                <i class="fas fa-check mr-2"></i>
                                Inscribir Equipo
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Información adicional --}}
            <div class="mt-6 bg-blue-50 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                    <div class="text-sm text-blue-800">
                        <p class="font-medium mb-1">Información importante:</p>
                        <ul class="list-disc list-inside space-y-1 text-blue-700">
                            <li>Solo el líder del equipo puede inscribir al equipo en eventos</li>
                            <li>Cada equipo solo puede inscribirse una vez por evento</li>
                            <li>Asegúrate de seleccionar el asesor correcto para tu proyecto</li>
                            <li>Una vez inscrito, podrás gestionar tu proyecto desde el panel</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>