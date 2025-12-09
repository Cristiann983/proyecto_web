<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DevTeams - Editar Evento</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Navegación -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-8">
                    <div class="flex items-center gap-2">
                        <div class="text-2xl text-purple-600">&lt;/&gt;</div>
                        <span class="text-xl font-bold">DevTeams</span>
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 text-gray-600 hover:text-gray-900">
                            <span>📋</span>
                            <span>Cerrar sesión</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Dashboard -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <div class="mb-6">
            <a href="{{ route('admin.eventos.index') }}" class="text-purple-600 hover:text-purple-700 flex items-center gap-2 mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Volver a eventos
            </a>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Editar Evento</h1>
            <p class="text-gray-600">Modifica la información del evento</p>
        </div>

        <!-- Mensajes -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-600">✅ {{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-red-600">❌ {{ session('error') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="font-semibold text-red-800 mb-2">❌ Por favor corrige los siguientes errores:</p>
                <ul class="list-disc list-inside text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulario -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <form method="POST" action="{{ route('admin.eventos.update', $evento->Id) }}" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Título del evento -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del evento *</label>
                    <input 
                        type="text"
                        name="nombre"
                        value="{{ old('nombre', $evento->Nombre) }}"
                        placeholder="Ej: Hackathon 2024"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    >
                </div>

                <!-- Descripción -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Descripción *</label>
                    <textarea 
                        name="descripcion"
                        placeholder="Describe el evento..."
                        rows="4"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    >{{ old('descripcion', $evento->Descripcion) }}</textarea>
                </div>

                <!-- Nivel de dificultad -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nivel de dificultad</label>
                    <select name="dificultad" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="Principiante" {{ old('dificultad', $evento->Categoria) == 'Principiante' ? 'selected' : '' }}>Principiante</option>
                        <option value="Intermedio" {{old('dificultad', $evento->Categoria) == 'Intermedio' ? 'selected' : '' }}>Intermedio</option>
                        <option value="Avanzado" {{ old('dificultad', $evento->Categoria) == 'Avanzado' ? 'selected' : '' }}>Avanzado</option>
                        <option value="Experto" {{ old('dificultad', $evento->Categoria) == 'Experto' ? 'selected' : '' }}>Experto</option>
                    </select>
                </div>

                <!-- Fechas -->
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de inicio *</label>
                        <input 
                            type="date"
                            name="fecha_inicio"
                            value="{{ old('fecha_inicio', $evento->Fecha_inicio ? $evento->Fecha_inicio->format('Y-m-d') : '') }}"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de fin *</label>
                        <input 
                            type="date"
                            name="fecha_fin"
                            value="{{ old('fecha_fin', $evento->Fecha_fin ? $evento->Fecha_fin->format('Y-m-d') : '') }}"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        >
                    </div>
                </div>

                <!-- Horas -->
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hora de inicio</label>
                        <input 
                            type="time"
                            name="hora_inicio"
                            value="{{ old('hora_inicio', $evento->hora_inicio) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hora de fin</label>
                        <input 
                            type="time"
                            name="hora_fin"
                            value="{{ old('hora_fin', $evento->hora_fin) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        >
                    </div>
                </div>

                <!-- Ubicación -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ubicación</label>
                    <input 
                        type="text"
                        name="ubicacion"
                        value="{{ old('ubicacion', $evento->Ubicacion) }}"
                        placeholder="Ej: Virtual, Bogotá, etc."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    >
                </div>

                <!-- Tecnologías -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tecnologías requeridas</label>
                    <div class="grid grid-cols-2 gap-2 p-4 border border-gray-300 rounded-lg max-h-48 overflow-y-auto">
                        @php
                            $tecnologiasEvento = old('tecnologias', $evento->tecnologias ?? []);
                            $tecnologiasDisponibles = ['React', 'Vue.js', 'Angular', 'Node.js', 'Python', 'Java', 'Laravel', 'Solidity', 'Web3.js', 'MySQL', 'MongoDB', 'Docker'];
                        @endphp
                        
                        @foreach($tecnologiasDisponibles as $tech)
                            <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-2 rounded">
                                <input 
                                    type="checkbox" 
                                    name="tecnologias[]" 
                                    value="{{ $tech }}"
                                    {{ in_array($tech, $tecnologiasEvento) ? 'checked' : '' }}
                                    class="rounded"
                                >
                                <span class="text-sm">{{ $tech }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Estado del evento -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado del evento</label>
                    <select name="estado" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="Activo" {{ old('estado', $evento->Estado) == 'Activo' ? 'selected' : '' }}>Activo</option>
                        <option value="Finalizado" {{ old('estado', $evento->Estado) == 'Finalizado' ? 'selected' : '' }}>Finalizado</option>
                        <option value="Cancelado" {{ old('estado', $evento->Estado) == 'Cancelado' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>

                <!-- Jueces asignados -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jueces asignados</label>
                    <select 
                        name="jueces[]"
                        multiple
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        size="6"
                    >
                        @foreach($jueces as $juez)
                            <option 
                                value="{{ $juez->Id }}"
                                {{ in_array($juez->Id, old('jueces', $juecesAsignados ?? [])) ? 'selected' : '' }}
                            >
                                {{ $juez->Nombre }} - {{ $juez->especialidad->Nombre ?? 'Sin especialidad' }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-2">Mantén presionado Ctrl (Cmd en Mac) para seleccionar múltiples jueces</p>
                </div>

                <!-- Botones de acción -->
                <div class="flex gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.eventos.index') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-3 rounded-lg font-medium transition text-center">
                        Cancelar
                    </a>
                    <button 
                        type="submit"
                        class="flex-1 bg-purple-600 hover:bg-purple-700 text-white py-3 rounded-lg font-medium transition flex items-center justify-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        💾 Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>