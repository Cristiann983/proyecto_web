<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DevTeams - Gestión de Jueces</title>
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

        <!-- Navegación de pestañas con botón -->
        <div class="mb-8 flex items-center justify-between">
            <div class="bg-gray-100 rounded-full p-1 inline-flex gap-1">
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

            <a href="{{ route('admin.jueces.create') }}"
               class="flex items-center gap-2 px-6 py-3 bg-gray-900 hover:bg-gray-800 text-white rounded-lg font-medium shadow-sm transition">
                <span>+</span>
                <span>Registrar Nuevo Juez</span>
            </a>
        </div>

        <!-- Título de sección -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Gestión de Jueces</h2>
            <p class="text-gray-600">Administra los jueces registrados en el sistema</p>
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

        <!-- Estadísticas -->
        <div class="grid md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <span class="text-2xl">👨‍⚖️</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total de Jueces</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $jueces->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <span class="text-2xl">📅</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Jueces Activos</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ $jueces->filter(function($juez) {
                                return $juez->eventos->where('Estado', 'Activo')->count() > 0;
                            })->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <span class="text-2xl">🎯</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Especialidades</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ $jueces->pluck('especialidad.Nombre')->unique()->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de jueces -->
        @if($jueces->count() > 0)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Juez
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Especialidad
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Contacto
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Eventos Asignados
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Calificaciones
                                </th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($jueces as $juez)
                                <tr class="hover:bg-gray-50 transition">
                                    <!-- Juez -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                                <span class="text-lg font-bold text-purple-600">
                                                    {{ substr($juez->Nombre, 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $juez->Nombre }}</p>
                                                <p class="text-sm text-gray-500">ID: {{ $juez->Id }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Especialidad -->
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                                            {{ $juez->especialidad->Nombre ?? 'Sin especialidad' }}
                                        </span>
                                    </td>

                                    <!-- Contacto -->
                                    <td class="px-6 py-4">
                                        <div class="text-sm">
                                            <p class="text-gray-900">{{ $juez->Correo }}</p>
                                            @if($juez->telefono)
                                                <p class="text-gray-500">{{ $juez->telefono }}</p>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Eventos asignados -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <span class="text-lg">📅</span>
                                            <span class="font-medium text-gray-900">{{ $juez->eventos->count() }}</span>
                                            <span class="text-sm text-gray-500">
                                                ({{ $juez->eventos->where('Estado', 'Activo')->count() }} activos)
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Calificaciones -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <span class="text-lg">⭐</span>
                                            <span class="font-medium text-gray-900">{{ $juez->calificaciones->count() }}</span>
                                        </div>
                                    </td>

                                    <!-- Acciones -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.jueces.show', $juez->Id) }}"
                                               class="px-3 py-1 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition"
                                               title="Ver detalles">
                                                👁️ Ver
                                            </a>
                                            <a href="{{ route('admin.jueces.edit', $juez->Id) }}"
                                               class="px-3 py-1 text-sm bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition"
                                               title="Editar juez">
                                                ✏️ Editar
                                            </a>
                                            <form method="POST" action="{{ route('admin.jueces.destroy', $juez->Id) }}"
                                                  onsubmit="return confirm('¿Estás seguro de eliminar a este juez? Esta acción no se puede deshacer.');"
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="px-3 py-1 text-sm bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition"
                                                        title="Eliminar juez">
                                                    🗑️ Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <!-- Estado vacío -->
            <div class="bg-white border-2 border-gray-200 rounded-2xl p-16">
                <div class="flex flex-col items-center justify-center text-center max-w-2xl mx-auto">
                    <div class="mb-8">
                        <svg class="w-32 h-32 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">No hay jueces registrados</h3>
                    <p class="text-gray-600 mb-6">Comienza registrando el primer juez en el sistema.</p>
                    <a href="{{ route('admin.jueces.create') }}"
                       class="px-6 py-3 bg-gray-900 hover:bg-gray-800 text-white rounded-lg font-medium transition">
                        + Registrar Primer Juez
                    </a>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
