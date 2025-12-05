<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevTeams - {{ $evento->Nombre }}</title>
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

    <!-- Contenido -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Botón volver -->
        <a href="{{ route('eventos.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 mb-6">
            <span>←</span>
            <span>Volver a eventos</span>
        </a>

        <!-- Tarjeta del evento -->
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-purple-600 to-blue-600 p-8">
                <div class="flex gap-2 mb-4">
                    <span class="px-3 py-1 bg-white/20 text-white text-xs font-medium rounded-full flex items-center gap-1">
                        <span>&lt;/&gt;</span> hackathon
                    </span>
                    @if ($evento->estado === 'proximo')
                        <span class="px-3 py-1 bg-white/20 text-white text-xs font-medium rounded-full">
                            Upcoming
                        </span>
                    @elseif ($evento->estado === 'activo')
                        <span class="px-3 py-1 bg-orange-400 text-white text-xs font-medium rounded-full">
                            Active
                        </span>
                    @else
                        <span class="px-3 py-1 bg-gray-400 text-white text-xs font-medium rounded-full">
                            Finalizado
                        </span>
                    @endif
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">{{ $evento->Nombre }}</h1>
            </div>

            <!-- Contenido -->
            <div class="p-8">
                <!-- Descripción -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-3">Descripción</h2>
                    <p class="text-gray-600">{{ $evento->Descripcion }}</p>
                </div>

                <!-- Información del evento -->
                <div class="grid md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-gray-50 rounded-xl p-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <span class="text-purple-600">📅</span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Fecha de inicio</p>
                                <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($evento->Fecha_inicio)->format('d \d\e F \d\e Y') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-xl p-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <span class="text-blue-600">🏁</span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Fecha de fin</p>
                                <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($evento->Fecha_fin)->format('d \d\e F \d\e Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <span class="text-green-600">👥</span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Participantes</p>
                                <p class="font-semibold text-gray-900">0 / 100</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                <span class="text-orange-600">⚡</span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Nivel</p>
                                <p class="font-semibold text-gray-900">Intermedio</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tecnologías -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-3">Tecnologías</h2>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-full">Solidity</span>
                        <span class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-full">React</span>
                        <span class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-full">Web3.js</span>
                    </div>
                </div>

                <!-- Botón de inscripción -->
                @if ($evento->estado !== 'finalizado')
                    <form action="{{ route('eventos.inscribirse', $evento->Id) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="w-full py-4 bg-gray-900 text-white rounded-xl hover:bg-gray-800 transition font-medium text-lg">
                            Unirme al evento
                        </button>
                    </form>
                @else
                    <div class="w-full py-4 bg-gray-200 text-gray-600 rounded-xl text-center font-medium text-lg">
                        Este evento ha finalizado
                    </div>
                @endif

                {{-- Botones de acción --}}
                <div class="mt-8">
                    @if($estaInscrito)
                        {{-- ✅ Ya está inscrito --}}
                        <div class="bg-green-50 border-2 border-green-500 rounded-xl p-6 text-center">
                            <div class="flex items-center justify-center mb-4">
                                <div class="bg-green-500 rounded-full p-4">
                                    <i class="fas fa-check-circle text-white text-4xl"></i>
                                </div>
                            </div>
                            <h3 class="text-2xl font-bold text-green-800 mb-2">¡Ya estás inscrito!</h3>
                            <p class="text-green-700 mb-4">Tu equipo está participando en este evento</p>
                            
                            {{-- Mostrar proyectos inscritos --}}
                            @if($proyectos && $proyectos->count() > 0)
                                <div class="bg-white rounded-lg p-4 mt-4">
                                    <h4 class="font-semibold text-gray-800 mb-2">Tus proyectos:</h4>
                                    @foreach($proyectos as $proyecto)
                                        <div class="flex items-center justify-between bg-gray-50 p-3 rounded mb-2">
                                            <div class="text-left">
                                                <p class="font-medium text-gray-900">{{ $proyecto->Nombre }}</p>
                                                <p class="text-sm text-gray-600">Equipo: {{ $proyecto->nombre_equipo }}</p>
                                            </div>
                                            <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full">
                                                Activo
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            
                            <div class="flex gap-4 mt-6">
                                <a href="{{ route('eventos.index') }}" 
                                   class="flex-1 bg-gray-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-600 transition">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Volver a eventos
                                </a>
                                <a href="{{ route('dashboard') }}" 
                                   class="flex-1 bg-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-purple-700 transition">
                                    <i class="fas fa-tachometer-alt mr-2"></i>
                                    Ir al inicio
                                </a>
                            </div>
                        </div>
                    @else
                        {{-- ✅ No está inscrito - mostrar botón --}}
                        <div class="flex gap-4">
                            <a href="{{ route('eventos.index') }}" 
                               class="flex-1 bg-gray-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-600 transition text-center">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Volver
                            </a>
                            <a href="{{ route('eventos.inscripcion', $evento->Id) }}" 
                               class="flex-1 bg-gradient-to-r from-purple-600 to-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-purple-700 hover:to-blue-700 transition text-center shadow-lg">
                                <i class="fas fa-user-plus mr-2"></i>
                                Inscribirse al Evento
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>