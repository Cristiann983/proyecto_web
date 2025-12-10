<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DevTeams - Solicitudes de Equipos</title>
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
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-gray-600 hover:text-gray-900">
                        <span>← Volver al Dashboard</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 text-gray-600 hover:text-gray-900">
                            <span>🚪</span>
                            <span>Cerrar sesión</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Solicitudes de Unión</h1>
            <p class="text-gray-600">Revisa y gestiona las solicitudes de usuarios que desean unirse a tus equipos</p>
        </div>

        @include('partials._alerts')

        @if($solicitudes->count() > 0)
            <div class="space-y-4">
                @foreach($solicitudes as $solicitud)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-xl font-bold text-gray-900">{{ $solicitud->usuario->name }}</h3>
                                    <span class="bg-purple-100 text-purple-700 text-xs px-3 py-1 rounded-full">
                                        📧 {{ $solicitud->usuario->email }}
                                    </span>
                                </div>
                                <p class="text-gray-600 mb-3">
                                    <span class="font-semibold">Equipo:</span> {{ $solicitud->equipo->Nombre }}
                                </p>
                                @if($solicitud->Mensaje)
                                    <div class="bg-gray-50 rounded-lg p-3 mb-4 border border-gray-200">
                                        <p class="text-sm text-gray-700">
                                            <span class="font-semibold">Mensaje:</span><br>
                                            {{ $solicitud->Mensaje }}
                                        </p>
                                    </div>
                                @endif
                                <p class="text-xs text-gray-500">
                                    Solicitado hace: {{ $solicitud->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <span class="bg-yellow-100 text-yellow-700 text-xs px-3 py-1 rounded-full">
                                ⏳ Pendiente
                            </span>
                        </div>

                        <!-- Opciones de acción -->
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <p class="text-sm font-semibold text-gray-700 mb-3">Asignar rol al aceptar:</p>
                            <form method="POST" action="{{ route('solicitudes.aceptar', $solicitud->Id) }}" class="flex gap-2 flex-wrap">
                                @csrf
                                <select name="perfil_id" required class="flex-1 min-w-[200px] px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    <option value="">-- Selecciona un rol --</option>
                                    <option value="1">Líder</option>
                                    <option value="2">Miembro</option>
                                    <option value="3">Especialista</option>
                                </select>
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition font-medium">
                                    ✓ Aceptar
                                </button>
                                <form method="POST" action="{{ route('solicitudes.rechazar', $solicitud->Id) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg transition font-medium" onclick="return confirm('¿Rechazar esta solicitud?')">
                                        ✗ Rechazar
                                    </button>
                                </form>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginación -->
            <div class="mt-8 flex justify-center">
                {{ $solicitudes->links('pagination::tailwind') }}
            </div>
        @else
            <div class="bg-white border-2 border-gray-200 rounded-2xl p-16">
                <div class="flex flex-col items-center justify-center text-center max-w-2xl mx-auto">
                    <div class="mb-6">
                        <svg class="w-24 h-24 text-gray-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No hay solicitudes pendientes</h3>
                    <p class="text-gray-600">Cuando alguien solicite unirse a tus equipos, verás sus solicitudes aquí</p>
                </div>
            </div>
        @endif
    </div>

    @include('partials._footer')
</body>
</html>
