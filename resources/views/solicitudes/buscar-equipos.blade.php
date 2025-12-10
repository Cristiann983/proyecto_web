<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DevTeams - Buscar Equipos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    @include('partials._navigation')


        <!-- Búsqueda -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <form method="GET" action="{{ route('solicitudes.buscar-equipos') }}" class="flex gap-3">
                <input type="text" name="buscar" placeholder="Buscar por nombre de equipo..." value="{{ request('buscar') }}" 
                       class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg transition font-medium">
                    🔍 Buscar
                </button>
            </form>
        </div>

        @include('partials._alerts')

        @if($equipos->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($equipos as $equipo)
                    @php
                        $lider = $equipo->participantes()->wherePivot('Id_perfil', \App\Models\Perfil::where('Nombre', 'Líder')->first()->Id ?? 0)->first();
                    @endphp
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-lg transition">
                        <div class="p-6">
                            <!-- Header del equipo -->
                            <div class="mb-4">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $equipo->Nombre }}</h3>
                                <div class="flex items-center gap-2 text-sm text-gray-600 mb-3">
                                    <span>👥</span>
                                    <span>{{ $equipo->participantes->count() }} miembros</span>
                                </div>
                            </div>

                            <!-- Información del líder -->
                            @if($lider)
                                <div class="bg-gray-50 rounded-lg p-3 mb-4 border border-gray-200">
                                    <p class="text-xs text-gray-500 mb-1">Líder del equipo</p>
                                    <p class="font-semibold text-gray-900">{{ $lider->Nombre }}</p>
                                    <p class="text-xs text-gray-600">{{ $lider->usuario->email }}</p>
                                </div>
                            @endif

                            <!-- Botón de acción -->
                            <div class="space-y-2">
                                @php
                                    $usuarioActual = Auth::user();
                                    $participanteActual = \App\Models\Participante::where('user_id', $usuarioActual->id)->first();
                                    $yaEsMiembro = $participanteActual && $equipo->participantes()->where('participante.Id', $participanteActual->Id)->exists();
                                    $tieneSolicitud = \App\Models\SolicitudEquipo::where('Equipo_id', $equipo->Id)
                                        ->where('Usuario_id', $usuarioActual->id)
                                        ->where('Estado', 'Pendiente')
                                        ->exists();
                                @endphp

                                @if($yaEsMiembro)
                                    <div class="w-full bg-green-100 text-green-700 py-2 px-4 rounded-lg text-center text-sm font-semibold">
                                        ✓ Ya eres miembro
                                    </div>
                                @elseif($tieneSolicitud)
                                    <div class="w-full bg-yellow-100 text-yellow-700 py-2 px-4 rounded-lg text-center text-sm font-semibold">
                                        ⏳ Solicitud pendiente
                                    </div>
                                @else
                                    <button onclick="abrirModalSolicitud({{ $equipo->Id }}, '{{ $equipo->Nombre }}')" 
                                            class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 px-4 rounded-lg transition font-semibold">
                                        📬 Solicitar Unirse
                                    </button>
                                @endif
                                <a href="{{ route('equipos.show', $equipo->Id) }}" class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-900 py-2 px-4 rounded-lg transition font-semibold text-center text-sm">
                                    Ver Detalles
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginación -->
            <div class="flex justify-center">
                {{ $equipos->links('pagination::tailwind') }}
            </div>
        @else
            <div class="bg-white border-2 border-gray-200 rounded-2xl p-16">
                <div class="flex flex-col items-center justify-center text-center max-w-2xl mx-auto">
                    <div class="mb-6">
                        <svg class="w-24 h-24 text-gray-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">
                        @if(request('buscar'))
                            No se encontraron equipos
                        @else
                            No hay equipos disponibles
                        @endif
                    </h3>
                    <p class="text-gray-600">
                        @if(request('buscar'))
                            Intenta con otro término de búsqueda
                        @else
                            Vuelve más tarde para encontrar equipos disponibles
                        @endif
                    </p>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal para solicitar unirse -->
    <div id="modalSolicitud" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Solicitar Unirse</h2>
                        <p id="equipoNombre" class="text-sm text-gray-600 mt-1"></p>
                    </div>
                    <button onclick="cerrarModalSolicitud()" class="text-gray-400 hover:text-gray-600 text-2xl">
                        &times;
                    </button>
                </div>
            </div>
            
            <form method="POST" id="formularioSolicitud" class="p-6 space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Mensaje (opcional)
                    </label>
                    <textarea name="mensaje" rows="4" placeholder="Cuéntale al líder del equipo por qué te gustaría unirte..." 
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    ></textarea>
                    <p class="text-xs text-gray-500 mt-2">Máximo 500 caracteres</p>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="cerrarModalSolicitud()"
                            class="flex-1 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Cancelar
                    </button>
                    <button type="submit" class="flex-1 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition font-semibold">
                        Enviar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let equipoIdActual = null;

        function abrirModalSolicitud(equipoId, equipoNombre) {
            equipoIdActual = equipoId;
            document.getElementById('equipoNombre').textContent = equipoNombre;
            document.getElementById('formularioSolicitud').action = `/equipos/${equipoId}/solicitar`;
            document.getElementById('modalSolicitud').classList.remove('hidden');
        }

        function cerrarModalSolicitud() {
            document.getElementById('modalSolicitud').classList.add('hidden');
            document.querySelector('textarea[name="mensaje"]').value = '';
        }

        // Cerrar modal al presionar Escape
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                cerrarModalSolicitud();
            }
        });

        // Cerrar modal al hacer clic fuera
        document.getElementById('modalSolicitud').addEventListener('click', function(event) {
            if (event.target === this) {
                cerrarModalSolicitud();
            }
        });
    </script>

    @include('partials._footer')
</body>
</html>
