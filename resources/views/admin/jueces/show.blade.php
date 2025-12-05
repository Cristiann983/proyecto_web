<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DevTeams - Detalles del Juez</title>
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
                    <span class="text-sm text-gray-500 ml-2">/ Panel de Administración</span>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('perfil.show') }}" class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        <span>👤</span>
                        <span>Mi Perfil</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            <span>🚪</span>
                            <span>Cerrar sesión</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Encabezado -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Panel de Administración</h1>
            <p class="text-gray-600">Gestiona equipos, eventos y jueces del sistema</p>
        </div>

        <!-- Navegación de pestañas -->
        <div class="mb-8 bg-gray-100 rounded-full p-1 inline-flex gap-1">
            <a href="{{ route('admin.equipos.index') }}"
               class="px-8 py-3 rounded-full text-gray-600 hover:bg-white flex items-center gap-2 transition">
                <span>👥</span>
                <span>Equipos</span>
            </a>
            <a href="{{ route('admin.eventos.index') }}"
               class="px-8 py-3 rounded-full text-gray-600 hover:bg-white flex items-center gap-2 transition">
                <span>📅</span>
                <span>Eventos</span>
            </a>
            <a href="{{ route('admin.jueces.list') }}"
               class="px-8 py-3 rounded-full bg-white text-gray-900 font-medium shadow-sm flex items-center gap-2">
                <span>⚖️</span>
                <span>Jueces</span>
            </a>
        </div>

        <!-- Breadcrumb -->
        <div class="mb-6 flex items-center gap-2 text-sm text-gray-600">
            <a href="{{ route('admin.jueces.list') }}" class="hover:text-purple-600">Jueces</a>
            <span>/</span>
            <span class="text-gray-900 font-medium">{{ $juez->Nombre }}</span>
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

        <!-- Encabezado con acciones -->
        <div class="mb-8 flex items-start justify-between">
            <div class="flex items-center gap-4">
                <!-- Avatar -->
                <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-blue-500 rounded-full flex items-center justify-center shadow-lg">
                    <span class="text-3xl font-bold text-white">
                        {{ substr($juez->Nombre, 0, 1) }}
                    </span>
                </div>

                <!-- Información básica -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $juez->Nombre }}</h2>
                    <p class="text-gray-600">{{ $juez->especialidad->Nombre ?? 'Sin especialidad' }}</p>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="inline-flex items-center px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-medium">
                            👨‍⚖️ Juez
                        </span>
                        <span class="text-sm text-gray-500">ID: {{ $juez->Id }}</span>
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.jueces.list') }}"
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition">
                    ← Volver a la Lista
                </a>
                <a href="{{ route('admin.jueces.edit', $juez->Id) }}"
                   class="px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white rounded-lg font-medium transition">
                    ✏️ Editar Juez
                </a>
                <form method="POST" action="{{ route('admin.jueces.destroy', $juez->Id) }}"
                      onsubmit="return confirm('¿Estás seguro de eliminar a este juez? Esta acción no se puede deshacer.');"
                      class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition">
                        🗑️ Eliminar Juez
                    </button>
                </form>
            </div>
        </div>

        <!-- Grid de información -->
        <div class="grid lg:grid-cols-3 gap-6 mb-8">
            <!-- Columna izquierda: Información del juez -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Información de contacto -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span>📧</span>
                        <span>Información de Contacto</span>
                    </h3>

                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span>✉️</span>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-600 mb-1">Correo Electrónico</p>
                                <p class="font-medium text-gray-900">{{ $juez->Correo }}</p>
                            </div>
                        </div>

                        @if($juez->telefono)
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span>📱</span>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-600 mb-1">Teléfono</p>
                                <p class="font-medium text-gray-900">{{ $juez->telefono }}</p>
                            </div>
                        </div>
                        @endif

                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span>🎯</span>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-600 mb-1">Especialidad</p>
                                <p class="font-medium text-gray-900">{{ $juez->especialidad->Nombre ?? 'No asignada' }}</p>
                                @if($juez->especialidad && $juez->especialidad->Descripcion)
                                    <p class="text-sm text-gray-500 mt-1">{{ $juez->especialidad->Descripcion }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span>👤</span>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-600 mb-1">Usuario del Sistema</p>
                                <p class="font-medium text-gray-900">{{ $juez->user->name ?? 'No asignado' }}</p>
                                <p class="text-sm text-gray-500 mt-1">{{ $juez->user->email ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Eventos asignados -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <span>📅</span>
                            <span>Eventos Asignados</span>
                        </h3>
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                            {{ $juez->eventos->count() }} eventos
                        </span>
                    </div>

                    @if($juez->eventos->count() > 0)
                        <div class="space-y-3">
                            @foreach($juez->eventos as $evento)
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-2">
                                                <h4 class="font-bold text-gray-900">{{ $evento->Nombre }}</h4>
                                                @if($evento->Estado == 'Activo')
                                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">
                                                        ● Activo
                                                    </span>
                                                @elseif($evento->Estado == 'Finalizado')
                                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">
                                                        ● Finalizado
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">
                                                        ● Cancelado
                                                    </span>
                                                @endif
                                            </div>

                                            @if($evento->Descripcion)
                                                <p class="text-sm text-gray-600 mb-2">{{ Str::limit($evento->Descripcion, 100) }}</p>
                                            @endif

                                            <div class="flex items-center gap-4 text-sm text-gray-500">
                                                <span class="flex items-center gap-1">
                                                    <span>📅</span>
                                                    <span>{{ \Carbon\Carbon::parse($evento->Fecha_inicio)->format('d M Y') }}</span>
                                                </span>
                                                <span>→</span>
                                                <span>{{ \Carbon\Carbon::parse($evento->Fecha_fin)->format('d M Y') }}</span>

                                                @if($evento->Categoria)
                                                    <span class="ml-auto px-2 py-1 bg-purple-100 text-purple-700 rounded text-xs">
                                                        {{ $evento->Categoria }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-4xl mb-2">📅</div>
                            <p class="text-gray-600">No hay eventos asignados a este juez</p>
                        </div>
                    @endif
                </div>

                <!-- Calificaciones realizadas -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <span>⭐</span>
                            <span>Calificaciones Realizadas</span>
                        </h3>
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm font-medium">
                            {{ $juez->calificaciones->count() }} calificaciones
                        </span>
                    </div>

                    @if($juez->calificaciones->count() > 0)
                        <div class="space-y-3">
                            @php
                                $calificacionesAgrupadas = $juez->calificaciones->groupBy('Proyecto_id');
                            @endphp

                            @foreach($calificacionesAgrupadas as $proyectoId => $calificaciones)
                                @php
                                    $proyecto = $calificaciones->first()->proyecto ?? null;
                                    $promedioProyecto = $calificaciones->avg('Calificacion');
                                @endphp

                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="flex-1">
                                            <h4 class="font-bold text-gray-900">
                                                {{ $proyecto->Nombre ?? 'Proyecto #' . $proyectoId }}
                                            </h4>
                                            @if($proyecto && $proyecto->equipo)
                                                <p class="text-sm text-gray-600">Equipo: {{ $proyecto->equipo->Nombre }}</p>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <div class="text-2xl font-bold text-yellow-600">
                                                {{ number_format($promedioProyecto, 1) }}
                                            </div>
                                            <div class="text-xs text-gray-500">Promedio</div>
                                        </div>
                                    </div>

                                    <div class="mt-3 space-y-2">
                                        @foreach($calificaciones as $calificacion)
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-gray-600">
                                                    {{ $calificacion->criterio->descripcion ?? 'Criterio #' . $calificacion->Criterio_id }}
                                                </span>
                                                <div class="flex items-center gap-2">
                                                    <div class="w-32 bg-gray-200 rounded-full h-2">
                                                        <div class="bg-yellow-500 h-2 rounded-full"
                                                             style="width: {{ ($calificacion->Calificacion / 100) * 100 }}%">
                                                        </div>
                                                    </div>
                                                    <span class="font-medium text-gray-900 w-8 text-right">
                                                        {{ $calificacion->Calificacion }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-4xl mb-2">⭐</div>
                            <p class="text-gray-600">Aún no ha realizado calificaciones</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Columna derecha: Estadísticas -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Resumen estadístico -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span>📊</span>
                        <span>Estadísticas</span>
                    </h3>

                    <div class="space-y-4">
                        <!-- Total eventos -->
                        <div class="bg-blue-50 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-blue-600 mb-1">Eventos Totales</p>
                                    <p class="text-2xl font-bold text-blue-900">{{ $juez->eventos->count() }}</p>
                                </div>
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <span class="text-2xl">📅</span>
                                </div>
                            </div>
                        </div>

                        <!-- Eventos activos -->
                        <div class="bg-green-50 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-green-600 mb-1">Eventos Activos</p>
                                    <p class="text-2xl font-bold text-green-900">
                                        {{ $juez->eventos->where('Estado', 'Activo')->count() }}
                                    </p>
                                </div>
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                    <span class="text-2xl">✅</span>
                                </div>
                            </div>
                        </div>

                        <!-- Calificaciones realizadas -->
                        <div class="bg-yellow-50 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-yellow-600 mb-1">Calificaciones</p>
                                    <p class="text-2xl font-bold text-yellow-900">{{ $juez->calificaciones->count() }}</p>
                                </div>
                                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                    <span class="text-2xl">⭐</span>
                                </div>
                            </div>
                        </div>

                        <!-- Proyectos calificados -->
                        <div class="bg-purple-50 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-purple-600 mb-1">Proyectos Calificados</p>
                                    <p class="text-2xl font-bold text-purple-900">
                                        {{ $juez->calificaciones->groupBy('Proyecto_id')->count() }}
                                    </p>
                                </div>
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <span class="text-2xl">🎯</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información del sistema -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span>⚙️</span>
                        <span>Información del Sistema</span>
                    </h3>

                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-gray-600 mb-1">Fecha de Registro</p>
                            <p class="font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($juez->created_at)->format('d M Y, H:i') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-600 mb-1">Última Actualización</p>
                            <p class="font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($juez->updated_at)->diffForHumans() }}
                            </p>
                        </div>

                        <div class="pt-3 border-t border-gray-200">
                            <p class="text-gray-600 mb-1">ID del Juez</p>
                            <p class="font-mono font-medium text-gray-900">{{ $juez->Id }}</p>
                        </div>

                        @if($juez->user)
                        <div>
                            <p class="text-gray-600 mb-1">ID de Usuario</p>
                            <p class="font-mono font-medium text-gray-900">{{ $juez->user->id }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
