<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevTeams - Administrar Equipos</title>
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
                    <a href="{{ route('perfil.show') }}" class="flex items-center gap-2 text-gray-600 hover:text-gray-900">
                        <span>👤</span>
                        <span>Mi Perfil</span>
                    </a>
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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard Administrador</h1>
            <p class="text-gray-600">Gestiona equipos, eventos y jueces del sistema</p>
        </div>

        <!-- Navegación de pestañas -->
        <div class="mb-8 bg-white rounded-full shadow-sm p-2 inline-flex gap-1 border border-gray-200">
            <a href="{{ route('admin.equipos.index') }}" class="px-6 py-2 rounded-full bg-gray-200 text-gray-900 font-medium flex items-center gap-2">
                <span>👥</span>
                <span>Equipos</span>
            </a>
            <a href="{{ route('admin.eventos.index') }}" class="px-6 py-2 rounded-full text-gray-600 hover:bg-gray-50 flex items-center gap-2">
                <span>📅</span>
                <span>Eventos</span>
            </a>
            <a href="{{ route('admin.jueces.list') }}" class="px-6 py-2 rounded-full text-gray-600 hover:bg-gray-50 flex items-center gap-2">
                <span>⚖️</span>
                <span>Jueces</span>
            </a>
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
            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center text-2xl">
                        👥
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-gray-900">{{ $equipos->count() }}</p>
                        <p class="text-sm text-gray-600">Equipos totales</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center text-2xl">
                        👤
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-gray-900">
                            {{ $equipos->sum(function($e) { return $e->participantes->count(); }) }}
                        </p>
                        <p class="text-sm text-gray-600">Participantes totales</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center text-2xl">
                        📊
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-gray-900">
                            {{ $equipos->count() > 0 ? number_format($equipos->avg(function($e) { return $e->participantes->count(); }), 1) : 0 }}
                        </p>
                        <p class="text-sm text-gray-600">Promedio por equipo</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenido principal -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Todos los Equipos</h2>
            <p class="text-gray-600 mb-2">Gestiona todos los equipos del sistema</p>
            <p class="text-sm text-gray-500">A continuación se muestran todos los equipos registrados</p>
        </div>

        <!-- Grid de equipos -->
        <div class="grid md:grid-cols-2 lg:grid-cols-2 gap-6">
            @forelse($equipos as $equipo)
                <div class="bg-white border border-gray-200 rounded-2xl p-6 hover:shadow-lg transition">
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                                {{ $equipo->participantes->count() }} miembro{{ $equipo->participantes->count() != 1 ? 's' : '' }}
                            </span>
                            @if($equipo->proyectos->count() > 0)
                                <span class="text-sm text-purple-600 bg-purple-100 px-3 py-1 rounded-full">
                                    📂 {{ $equipo->proyectos->count() }} proyecto(s)
                                </span>
                            @endif
                        </div>
                        <h3 class="text-xl font-bold mb-2">{{ $equipo->nombre }}</h3>
                        <p class="text-sm text-gray-600">Equipo de desarrollo</p>
                    </div>

                    <div class="mb-4">
                        <h4 class="font-semibold text-sm mb-3">Miembros</h4>
                        <div class="space-y-2">
                            @foreach($equipo->participantes->take(3) as $index => $participante)
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                        {{ substr($participante->Nombre, 0, 1) }}
                                    </div>
                                    <span class="flex-1">{{ $participante->Nombre }}</span>
                                    @if($index === 0)
                                        <span class="inline-flex items-center gap-1 text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded-full">
                                            👑 Líder
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                            @if($equipo->participantes->count() > 3)
                                <p class="text-sm text-gray-500 pl-10">
                                    + {{ $equipo->participantes->count() - 3 }} más
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Proyectos del equipo -->
                    @if($equipo->proyectos->count() > 0)
                        <div class="mb-4 pb-4 border-t border-gray-200 pt-4">
                            <h4 class="font-semibold text-sm mb-2">Proyectos activos</h4>
                            <div class="space-y-2">
                                @foreach($equipo->proyectos->take(2) as $proyecto)
                                    <div class="text-sm bg-purple-50 p-2 rounded">
                                        <p class="font-medium text-gray-900">{{ $proyecto->nombre }}</p>
                                        @if($proyecto->evento)
                                            <p class="text-xs text-gray-600">📅 {{ $proyecto->evento->Nombre }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <div class="space-y-2">
                        <a href="{{ route('admin.equipos.show', $equipo->Id) }}" 
                           class="w-full bg-gray-800 hover:bg-gray-900 text-white py-2 rounded-lg transition text-sm font-medium block text-center">
                            Ver detalles completos
                        </a>
                        <form action="{{ route('admin.equipos.destroy', $equipo->Id) }}" 
                              method="POST" 
                              onsubmit="return confirm('¿Estás seguro de eliminar el equipo {{ $equipo->nombre }}? Se eliminarán todas sus relaciones.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg transition text-sm font-medium">
                                Eliminar equipo
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 bg-white rounded-2xl border border-gray-200">
                    <div class="text-6xl mb-4">👥</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No hay equipos</h3>
                    <p class="text-gray-600">Aún no se han creado equipos en el sistema</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex gap-8 text-sm text-gray-600">
                    <a href="#" class="hover:text-gray-900">Política de Privacidad</a>
                    <a href="#" class="hover:text-gray-900">Términos de Uso</a>
                    <a href="#" class="hover:text-gray-900">Soporte</a>
                </div>
                <div class="flex items-center gap-2 text-purple-600">
                    <div class="text-xl">&lt;/&gt;</div>
                    <div>
                        <p class="font-bold">DevTeams</p>
                        <p class="text-xs">✨ Plataforma de Desarrollo</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>