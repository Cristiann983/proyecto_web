<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevTeams - {{ $equipo->nombre }}</title>
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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Botón volver -->
        <a href="{{ route('admin.equipos.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 mb-6">
            <span>←</span>
            <span>Volver a equipos</span>
        </a>

        <!-- Encabezado del equipo -->
        <div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-2xl p-8 mb-8 text-white">
            <div class="flex items-center gap-2 mb-2">
                <span class="px-3 py-1 bg-white bg-opacity-20 text-white text-xs font-medium rounded-full">
                    {{ $equipo->participantes->count() }} miembros
                </span>
                @if($equipo->proyectos->count() > 0)
                    <span class="px-3 py-1 bg-white bg-opacity-20 text-white text-xs font-medium rounded-full">
                        📂 {{ $equipo->proyectos->count() }} proyecto(s)
                    </span>
                @endif
            </div>
            <h1 class="text-3xl font-bold mb-2">{{ $equipo->nombre }}</h1>
            <p class="text-white text-opacity-90">Detalles completos del equipo</p>
        </div>

        <!-- Contenido principal -->
        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Integrantes -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                        <span>👥</span>
                        <span>Integrantes del equipo</span>
                    </h2>
                </div>

                <div class="space-y-3">
                    @foreach($equipo->participantes as $index => $participante)
                        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                {{ substr($participante->Nombre, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $participante->Nombre }}</p>
                                <p class="text-sm text-gray-600">📧 {{ $participante->Correo }}</p>
                                @if($participante->telefono)
                                    <p class="text-sm text-gray-600">📞 {{ $participante->telefono }}</p>
                                @endif
                                @if($participante->No_Control)
                                    <p class="text-sm text-gray-600">🎓 {{ $participante->No_Control }}</p>
                                @endif
                            </div>
                            @if($index === 0)
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 text-sm font-medium rounded-full flex items-center gap-1">
                                    <span>👑</span>
                                    <span>Líder</span>
                                </span>
                            @else
                                <span class="px-3 py-1 bg-gray-100 text-gray-600 text-sm font-medium rounded-full">
                                    Miembro
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Proyectos -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <span>📂</span>
                    <span>Proyectos</span>
                </h2>

                @if($equipo->proyectos->isEmpty())
                    <div class="text-center py-8">
                        <div class="text-4xl mb-2">📂</div>
                        <p class="text-gray-600 text-sm">Sin proyectos activos</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($equipo->proyectos as $proyecto)
                            <div class="p-4 bg-purple-50 rounded-lg border border-purple-100">
                                <p class="font-medium text-gray-900 mb-2">{{ $proyecto->nombre }}</p>
                                
                                @if($proyecto->evento)
                                    <div class="flex items-center gap-2 text-sm text-gray-600 mb-1">
                                        <span>📅</span>
                                        <span>{{ $proyecto->evento->Nombre }}</span>
                                    </div>
                                @endif
                                
                                @if($proyecto->Categoria)
                                    <div class="flex items-center gap-2 text-sm text-gray-600 mb-1">
                                        <span>🏷️</span>
                                        <span>{{ $proyecto->Categoria }}</span>
                                    </div>
                                @endif

                                @if($proyecto->asesor)
                                    <div class="mt-3 pt-3 border-t border-purple-200">
                                        <p class="text-xs text-gray-500 mb-1">Asesor:</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $proyecto->asesor->Nombre }}</p>
                                        <p class="text-xs text-gray-600">{{ $proyecto->asesor->Correo }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Acciones del equipo -->
        <div class="mt-8 bg-white rounded-2xl border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Acciones administrativas</h3>
            <div class="flex gap-4">
                <form action="{{ route('admin.equipos.destroy', $equipo->Id) }}" 
                      method="POST" 
                      onsubmit="return confirm('¿Estás seguro de eliminar el equipo {{ $equipo->nombre }}? Esta acción no se puede deshacer y se eliminarán todos sus proyectos y relaciones.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg transition font-medium flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Eliminar equipo
                    </button>
                </form>
            </div>
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