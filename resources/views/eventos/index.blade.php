<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DevTeams - Eventos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    @include('partials._navigation')

        <!-- Sección de eventos -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Eventos y Retos</h2>
                <p class="text-gray-600">Participa en hackathons, concursos y desafíos de programación</p>
            </div>
            <div class="text-sm text-gray-500">
                {{ $eventos->count() }} evento{{ $eventos->count() != 1 ? 's' : '' }} encontrado{{ $eventos->count() != 1 ? 's' : '' }}
            </div>
        </div>


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

        <!-- Separador de Mis Eventos Inscritos -->
        @php
            $eventosInscritos = $eventos->filter(fn($e) => $e->estaInscrito);
        @endphp

        @if($eventosInscritos->count() > 0)
            <div class="mb-12">
                <div class="flex items-center gap-3 mb-6">
                    <div class="h-1 w-1 bg-purple-600 rounded-full"></div>
                    <h2 class="text-2xl font-bold text-gray-900">✨ Mis Eventos Inscritos</h2>
                    <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-sm font-semibold">{{ $eventosInscritos->count() }}</span>
                </div>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    @foreach($eventosInscritos as $evento)
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 relative border-2 border-purple-200">

                            {{-- Badge de estado --}}
                            <div class="absolute top-4 left-4 z-10">
                                @if($evento->estadoEvento == 'activo')
                                    <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
                                        🟢 En Curso
                                    </span>
                                @elseif($evento->estadoEvento == 'proximo')
                                    <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
                                        🔵 Próximo
                                    </span>
                                @else
                                    <span class="bg-gray-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
                                        ⚫ Finalizado
                                    </span>
                                @endif
                            </div>

                            {{-- Badge de inscrito --}}
                            <div class="absolute top-4 right-4 z-10">
                                <span class="bg-purple-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
                                    ✓ Inscrito
                                </span>
                            </div>

                            <!-- Header con gradiente -->
                            <div class="bg-gradient-to-r from-purple-600 to-blue-500 p-6 pt-12 text-white">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="bg-white/20 backdrop-blur-sm text-white text-xs px-3 py-1 rounded-full">
                                        &lt;/&gt; {{ strtolower($evento->Categoria) }}
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold mb-2">{{ $evento->Nombre }}</h3>
                                <p class="text-sm opacity-90 line-clamp-2">{{ $evento->Descripcion }}</p>
                            </div>

                            <!-- Contenido -->
                            <div class="p-6">
                                <!-- Fechas -->
                                <div class="flex items-center gap-2 text-sm text-gray-600 mb-3">
                                    <span>📅</span>
                                    <span>
                                        {{ \Carbon\Carbon::parse($evento->Fecha_inicio)->format('d M') }} -
                                        {{ \Carbon\Carbon::parse($evento->Fecha_fin)->format('d M Y') }}
                                    </span>
                                </div>

                                <!-- Duración -->
                                @php
                                    $inicio = \Carbon\Carbon::parse($evento->Fecha_inicio);
                                    $fin = \Carbon\Carbon::parse($evento->Fecha_fin);
                                    $duracionDias = $inicio->diffInDays($fin) + 1;
                                @endphp
                                <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
                                    <span>⏱️</span>
                                    <span>{{ $duracionDias }} día{{ $duracionDias != 1 ? 's' : '' }}</span>
                                </div>

                                <!-- Información de tiempo restante/transcurrido -->
                                @if($evento->estadoEvento == 'activo')
                                    <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-4">
                                        <p class="text-xs text-green-700 font-medium">
                                            ⏰ Termina {{ $fin->diffForHumans() }}
                                        </p>
                                    </div>
                                @elseif($evento->estadoEvento == 'proximo')
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
                                        <p class="text-xs text-blue-700 font-medium">
                                            🚀 Comienza {{ $inicio->diffForHumans() }}
                                        </p>
                                    </div>
                                @endif

                                <!-- Botón de acción -->
                                <a href="{{ route('eventos.show', $evento->Id) }}"
                                   class="block w-full text-center py-3
                                          {{ $evento->estadoEvento == 'finalizado' ? 'bg-gray-200 text-gray-600' : 'bg-purple-600 hover:bg-purple-700 text-white' }}
                                          rounded-lg font-medium transition-colors">
                                    Ver Detalles →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Barra de filtros y búsqueda -->
        <form method="GET" action="{{ route('eventos.index') }}" class="mb-6">
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Búsqueda -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                        <input
                            type="text"
                            name="buscar"
                            value="{{ $buscar }}"
                            placeholder="Nombre o descripción..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        >
                    </div>

                    <!-- Filtro por estado -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                        <select
                            name="filtro"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        >
                            <option value="activos" {{ $filtro == 'activos' ? 'selected' : '' }}>🟢 En Curso</option>
                            <option value="proximos" {{ $filtro == 'proximos' ? 'selected' : '' }}>🔵 Próximos</option>
                            <option value="finalizados" {{ $filtro == 'finalizados' ? 'selected' : '' }}>⚫ Finalizados</option>
                            <option value="todos" {{ $filtro == 'todos' ? 'selected' : '' }}>Todos</option>
                        </select>
                    </div>

                    <!-- Filtro por categoría -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
                        <select
                            name="categoria"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        >
                            <option value="todos" {{ $categoria == 'todos' ? 'selected' : '' }}>Todas</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat }}" {{ $categoria == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex gap-2 mt-4">
                    <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium transition">
                        🔍 Filtrar
                    </button>
                    <a href="{{ route('eventos.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium transition">
                        🔄 Limpiar
                    </a>
                </div>
            </div>
        </form>

        <!-- Sección de Todos los Eventos -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Explora Todos los Eventos</h2>
            <p class="text-gray-600">Descubre y participa en nuevos retos</p>
        </div>

        <!-- Grid de eventos -->
        @if($eventos->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($eventos as $evento)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 relative">

                        {{-- Badge de estado --}}
                        <div class="absolute top-4 left-4 z-10">
                            @if($evento->estadoEvento == 'activo')
                                <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
                                    🟢 En Curso
                                </span>
                            @elseif($evento->estadoEvento == 'proximo')
                                <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
                                    🔵 Próximo
                                </span>
                            @else
                                <span class="bg-gray-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
                                    ⚫ Finalizado
                                </span>
                            @endif
                        </div>

                        {{-- Badge de inscrito --}}
                        @if($evento->estaInscrito)
                            <div class="absolute top-4 right-4 z-10">
                                <span class="bg-purple-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
                                    ✓ Inscrito
                                </span>
                            </div>
                        @endif

                        <!-- Header con gradiente -->
                        <div class="bg-gradient-to-r from-purple-600 to-blue-500 p-6 pt-12 text-white">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="bg-white/20 backdrop-blur-sm text-white text-xs px-3 py-1 rounded-full">
                                    &lt;/&gt; {{ strtolower($evento->Categoria) }}
                                </span>
                            </div>
                            <h3 class="text-xl font-bold mb-2">{{ $evento->Nombre }}</h3>
                            <p class="text-sm opacity-90 line-clamp-2">{{ $evento->Descripcion }}</p>
                        </div>

                        <!-- Contenido -->
                        <div class="p-6">
                            <!-- Fechas -->
                            <div class="flex items-center gap-2 text-sm text-gray-600 mb-3">
                                <span>📅</span>
                                <span>
                                    {{ \Carbon\Carbon::parse($evento->Fecha_inicio)->format('d M') }} -
                                    {{ \Carbon\Carbon::parse($evento->Fecha_fin)->format('d M Y') }}
                                </span>
                            </div>

                            <!-- Duración -->
                            @php
                                $inicio = \Carbon\Carbon::parse($evento->Fecha_inicio);
                                $fin = \Carbon\Carbon::parse($evento->Fecha_fin);
                                $duracionDias = $inicio->diffInDays($fin) + 1;
                            @endphp
                            <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
                                <span>⏱️</span>
                                <span>{{ $duracionDias }} día{{ $duracionDias != 1 ? 's' : '' }}</span>
                            </div>

                            <!-- Información de tiempo restante/transcurrido -->
                            @if($evento->estadoEvento == 'activo')
                                <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-4">
                                    <p class="text-xs text-green-700 font-medium">
                                        ⏰ Termina {{ $fin->diffForHumans() }}
                                    </p>
                                </div>
                            @elseif($evento->estadoEvento == 'proximo')
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
                                    <p class="text-xs text-blue-700 font-medium">
                                        🚀 Comienza {{ $inicio->diffForHumans() }}
                                    </p>
                                </div>
                            @endif

                            <!-- Botón de acción -->
                            <a href="{{ route('eventos.show', $evento->Id) }}"
                               class="block w-full text-center py-3
                                      {{ $evento->estadoEvento == 'finalizado' ? 'bg-gray-200 text-gray-600' : 'bg-gray-900 hover:bg-gray-800 text-white' }}
                                      rounded-lg font-medium transition-colors">
                                {{ $evento->estaInscrito ? 'Ver Detalles' : 'Explorar Evento' }} →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginación -->
            <div class="mt-8 flex justify-center">
                {{ $eventos->links('pagination::tailwind') }}
            </div>
        @else
            <!-- Estado vacío -->
            <div class="bg-white border-2 border-gray-200 rounded-2xl p-16">
                <div class="flex flex-col items-center justify-center text-center max-w-2xl mx-auto">
                    <div class="mb-8">
                        <svg class="w-32 h-32 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">No se encontraron eventos</h3>
                    <p class="text-gray-600">No hay eventos que coincidan con los filtros seleccionados. Intenta con otros criterios de búsqueda.</p>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
