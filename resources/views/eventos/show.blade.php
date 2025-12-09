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

                {{-- Botón de Descarga de Constancia (si está inscrito y el evento ha finalizado) --}}
                @if($estaInscrito && $proyectos->isNotEmpty())
                    @php
                        $ahora = now();
                        $eventoFinalizado = $ahora > $evento->Fecha_fin;
                        $proyecto = $proyectos->first();
                        $equipo = $proyecto->equipo ?? null;
                        $tieneCalificaciones = $proyecto && $proyecto->calificaciones && $proyecto->calificaciones->count() > 0;
                    @endphp

                    @if($eventoFinalizado && $equipo)
                        <div class="mb-8 bg-gradient-to-r from-purple-50 to-blue-50 rounded-2xl border border-purple-200 p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center text-3xl shadow-sm">
                                        📜
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">Constancia de Participación</h3>
                                        <p class="text-sm text-gray-600">Descarga tu certificado oficial del evento</p>
                                        @if(!$tieneCalificaciones)
                                            <p class="text-xs text-orange-600 mt-1">⏳ Esperando calificaciones de los jueces</p>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    @if($tieneCalificaciones)
                                        <a href="{{ route('equipos.constancia', $equipo->Id) }}" 
                                           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-medium rounded-lg shadow-sm transition">
                                            <span>📥</span>
                                            <span>Descargar Constancia</span>
                                        </a>
                                    @else
                                        <button disabled
                                                class="inline-flex items-center gap-2 px-6 py-3 bg-gray-300 text-gray-500 font-medium rounded-lg cursor-not-allowed">
                                            <span>📥</span>
                                            <span>Descargar Constancia</span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                <!-- 🏆 RANKING DEL EVENTO (solo si está finalizado) -->
                @if(($evento->estado === 'finalizado' || $evento->Estado === 'Finalizado') && $proyectosRanking->count() > 0)
                    <div class="mb-8 bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl border-2 border-yellow-300 p-8">
                        <div class="text-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-900 mb-2 flex items-center justify-center gap-2">
                                <span class="text-3xl">🏆</span>
                                <span>Ranking Final</span>
                            </h2>
                            <p class="text-gray-600">Equipos clasificados por puntuación</p>
                        </div>

                        <!-- Podio (Top 3) -->
                        @if($proyectosRanking->count() >= 3)
                            <div class="grid grid-cols-3 gap-4 mb-8 max-w-3xl mx-auto">
                                <!-- 2do Lugar -->
                                <div class="flex flex-col items-center justify-end">
                                    <div class="bg-gradient-to-br from-gray-300 to-gray-400 text-white rounded-full w-16 h-16 flex items-center justify-center text-2xl font-bold mb-2 shadow-lg">
                                        2°
                                    </div>
                                    <div class="bg-white rounded-xl p-4 w-full text-center shadow-md border-2 border-gray-300">
                                        <p class="font-bold text-gray-900 text-sm mb-1">{{ $proyectosRanking[1]->equipo->Nombre }}</p>
                                        <p class="text-xs text-gray-600 mb-2">{{ $proyectosRanking[1]->Nombre }}</p>
                                        <p class="text-lg font-bold text-gray-700">{{ number_format($proyectosRanking[1]->ranking_puntuacion, 1) }}</p>
                                        <p class="text-xs text-gray-500">puntos</p>
                                    </div>
                                </div>

                                <!-- 1er Lugar -->
                                <div class="flex flex-col items-center justify-end -mt-8">
                                    <div class="bg-gradient-to-br from-yellow-400 to-yellow-600 text-white rounded-full w-20 h-20 flex items-center justify-center text-3xl font-bold mb-2 shadow-xl ring-4 ring-yellow-200">
                                        1°
                                    </div>
                                    <div class="bg-white rounded-xl p-5 w-full text-center shadow-xl border-2 border-yellow-400">
                                        <p class="font-bold text-gray-900 mb-1">{{ $proyectosRanking[0]->equipo->Nombre }}</p>
                                        <p class="text-xs text-gray-600 mb-2">{{ $proyectosRanking[0]->Nombre }}</p>
                                        <p class="text-2xl font-bold text-yellow-600">{{ number_format($proyectosRanking[0]->ranking_puntuacion, 1) }}</p>
                                        <p class="text-xs text-gray-500">puntos</p>
                                    </div>
                                </div>

                                <!-- 3er Lugar -->
                                <div class="flex flex-col items-center justify-end">
                                    <div class="bg-gradient-to-br from-orange-400 to-orange-600 text-white rounded-full w-16 h-16 flex items-center justify-center text-2xl font-bold mb-2 shadow-lg">
                                        3°
                                    </div>
                                    <div class="bg-white rounded-xl p-4 w-full text-center shadow-md border-2 border-orange-300">
                                        <p class="font-bold text-gray-900 text-sm mb-1">{{ $proyectosRanking[2]->equipo->Nombre }}</p>
                                        <p class="text-xs text-gray-600 mb-2">{{ $proyectosRanking[2]->Nombre }}</p>
                                        <p class="text-lg font-bold text-orange-700">{{ number_format($proyectosRanking[2]->ranking_puntuacion, 1) }}</p>
                                        <p class="text-xs text-gray-500">puntos</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Lista completa de rankings -->
                        <div class="bg-white rounded-xl shadow-md overflow-hidden">
                            <div class="bg-gray-900 text-white px-6 py-3">
                                <h3 class="font-bold">Clasificación Completa</h3>
                            </div>
                            <div class="divide-y divide-gray-200">
                                @foreach($proyectosRanking as $index => $proyecto)
                                    <div class="px-6 py-4 hover:bg-gray-50 transition {{ $index < 3 ? 'bg-yellow-50/30' : '' }}">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-4 flex-1">
                                                <div class="flex items-center justify-center w-10 h-10 rounded-full font-bold text-lg
                                                    {{ $index === 0 ? 'bg-yellow-100 text-yellow-700' : '' }}
                                                    {{ $index === 1 ? 'bg-gray-100 text-gray-700' : '' }}
                                                    {{ $index === 2 ? 'bg-orange-100 text-orange-700' : '' }}
                                                    {{ $index > 2 ? 'bg-gray-50 text-gray-600' : '' }}
                                                ">
                                                    {{ $proyecto->ranking_posicion }}°
                                                </div>
                                                <div class="flex-1">
                                                    <p class="font-semibold text-gray-900">{{ $proyecto->equipo->Nombre }}</p>
                                                    <p class="text-sm text-gray-600">{{ $proyecto->Nombre }}</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-xl font-bold text-purple-600">{{ number_format($proyecto->ranking_puntuacion, 1) }}</p>
                                                <p class="text-xs text-gray-500">puntos</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

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