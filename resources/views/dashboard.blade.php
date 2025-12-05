<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DevTeams - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
        }
        .gradient-border {
            position: relative;
            background: linear-gradient(white, white) padding-box,
                        linear-gradient(135deg, #667eea 0%, #764ba2 100%) border-box;
            border: 2px solid transparent;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <!-- Navegación -->
    <nav class="bg-white/80 backdrop-blur-md shadow-lg border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 gradient-bg rounded-lg flex items-center justify-center shadow-md">
                        <span class="text-xl text-white font-bold">&lt;/&gt;</span>
                    </div>
                    <div>
                        <span class="text-xl font-bold text-gray-900">DevTeams</span>
                        <p class="text-xs text-gray-500">Plataforma de hackathons</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('perfil.show') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg text-gray-700 hover:bg-purple-50 transition-all duration-200 group">
                        <span class="group-hover:scale-110 transition-transform">👤</span>
                        <span class="font-medium">Mi Perfil</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-gray-800 to-gray-900 text-white rounded-lg hover:from-gray-900 hover:to-black transition-all duration-200 shadow-md hover:shadow-lg">
                            <span>🚪</span>
                            <span>Cerrar sesión</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenido -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Encabezado con gradiente -->
        <div class="mb-8 relative overflow-hidden rounded-2xl gradient-bg p-8 shadow-xl">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="relative">
                <h1 class="text-4xl font-bold text-white mb-2">¡Bienvenido de vuelta! 👋</h1>
                <p class="text-purple-100">Gestiona tus equipos, eventos, invitaciones y código en un solo lugar</p>
            </div>
        </div>

        <!-- Pestañas de navegación -->
        <div class="mb-8 bg-gray-100 rounded-full p-1 inline-flex gap-1">
            <a href="{{ route('dashboard') }}"
               class="px-8 py-3 rounded-full bg-white text-gray-900 font-medium shadow-sm flex items-center gap-2">
                <span>👥</span>
                <span>Equipos</span>
            </a>
            <a href="{{ route('eventos.index') }}"
               class="px-8 py-3 rounded-full text-gray-600 hover:bg-white flex items-center gap-2 transition">
                <span>📅</span>
                <span>Eventos</span>
            </a>
            <a href="{{ route('codigos.index') }}"
               class="px-8 py-3 rounded-full text-gray-600 hover:bg-white flex items-center gap-2 transition">
                <span>&lt;/&gt;</span>
                <span>Códigos</span>
            </a>
            <a href="{{ route('invitaciones.index') }}"
               class="px-8 py-3 rounded-full text-gray-600 hover:bg-white flex items-center gap-2 transition">
                <span>✉️</span>
                <span>Invitaciones</span>
            </a>
        </div>

        <!-- Sección de equipos con botón crear -->
        <div class="flex justify-between items-center mb-6 fade-in">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                    <span class="w-1 h-8 bg-purple-600 rounded-full"></span>
                    Mis equipos
                </h2>
                <p class="text-gray-600 ml-3">Gestiona tus equipos de desarrollo con roles especializados</p>
            </div>
            <button
                onclick="document.getElementById('modalCrearEquipo').classList.remove('hidden')"
                class="bg-gray-900 hover:bg-gray-800 text-white px-5 py-2.5 rounded-lg font-medium flex items-center gap-2 shadow-md hover:shadow-lg transition-all duration-200"
            >
                <span>+</span>
                <span>Crear equipo</span>
            </button>
        </div>

        <!-- Mensajes -->
        @if (session('success'))
            <div class="mb-6 p-5 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 rounded-xl shadow-md fade-in">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0 w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                        <span class="text-white text-xl">✓</span>
                    </div>
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-5 bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 rounded-xl shadow-md fade-in">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0 w-10 h-10 bg-red-500 rounded-full flex items-center justify-center">
                        <span class="text-white text-xl">!</span>
                    </div>
                    <p class="text-red-800 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Grid de equipos -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($equipos as $equipo)
                <div class="bg-white rounded-2xl border-2 border-gray-100 p-6 card-hover shadow-md hover:border-purple-200 fade-in">
                    <!-- Cantidad de miembros -->
                    <div class="flex items-center gap-2 mb-4">
                        <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm font-medium rounded-full">
                            {{ $equipo->participantes->count() }} Miembro{{ $equipo->participantes->count() != 1 ? 's' : '' }}
                        </span>
                    </div>

                    <!-- Nombre del equipo -->
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $equipo->Nombre }}</h3>

                    <!-- Perfil (desde tabla intermedia) -->
                    @if($equipo->perfil)
                        <p class="text-sm text-gray-600 mb-4">{{ $equipo->perfil->Descripcion }}</p>
                    @else
                        <p class="text-sm text-gray-600 mb-4">Equipo de desarrollo</p>
                    @endif

                    <!-- Miembros -->
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-900 mb-2">Miembros</p>
                        <div class="space-y-2">
                            @foreach($equipo->participantes as $participante)
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-600">👤</span>
                                    <span class="text-sm text-gray-700">{{ $participante->Nombre }}</span>
                                    @if($loop->first)
                                        <span class="ml-auto px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full flex items-center gap-1">
                                            <span>👑</span>
                                            <span>Líder</span>
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Calificaciones -->
                    @php
                        $proyecto = $equipo->proyectos->first();
                        $calificaciones = $proyecto ? $proyecto->calificaciones : collect();
                        $juecesCalificadores = $calificaciones->groupBy('Juez_id');
                    @endphp

                    @if($proyecto && $calificaciones->count() > 0)
                        <div class="mb-4 pt-4 border-t border-gray-200">
                            <p class="text-sm font-medium text-gray-900 mb-3 flex items-center gap-2">
                                <span>⭐</span>
                                <span>Calificaciones Recibidas</span>
                            </p>

                            <div class="space-y-3">
                                @foreach($juecesCalificadores as $juezId => $calificacionesJuez)
                                    @php
                                        $juez = $calificacionesJuez->first()->juez;
                                        $totalPuntos = $calificacionesJuez->sum('Calificacion');
                                        $maxPuntos = $calificacionesJuez->count() * 10;
                                        $promedio = $calificacionesJuez->avg('Calificacion');
                                    @endphp

                                    <div class="bg-purple-50 rounded-lg p-3">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 bg-purple-200 rounded-full flex items-center justify-center">
                                                    <span class="text-xs font-bold text-purple-700">
                                                        {{ substr($juez->Nombre, 0, 1) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ $juez->Nombre }}</p>
                                                    <p class="text-xs text-gray-600">{{ $juez->especialidad->Nombre ?? 'Juez' }}</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-lg font-bold text-purple-600">{{ number_format($promedio, 1) }}/10</p>
                                                <p class="text-xs text-gray-600">{{ $totalPuntos }}/{{ $maxPuntos }} pts</p>
                                            </div>
                                        </div>

                                        <!-- Detalles por criterio -->
                                        <div class="mt-2 pt-2 border-t border-purple-200 space-y-1">
                                            @foreach($calificacionesJuez as $calificacion)
                                                <div class="flex items-center justify-between text-xs">
                                                    <span class="text-gray-700">{{ $calificacion->criterio->descripcion }}</span>
                                                    <span class="font-medium text-purple-600">{{ $calificacion->Calificacion }}/10</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Promedio general -->
                            @php
                                $promedioGeneral = $calificaciones->avg('Calificacion');
                                $totalGeneral = $calificaciones->sum('Calificacion');
                                $maxGeneral = $calificaciones->count() * 10;
                            @endphp
                            <div class="mt-3 bg-gray-900 rounded-lg p-3 text-white">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium">Promedio General</span>
                                    <div class="text-right">
                                        <p class="text-xl font-bold">{{ number_format($promedioGeneral, 1) }}/10</p>
                                        <p class="text-xs opacity-75">{{ $totalGeneral }}/{{ $maxGeneral }} pts totales</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($proyecto)
                        <div class="mb-4 pt-4 border-t border-gray-200">
                            <div class="bg-gray-50 rounded-lg p-3 text-center">
                                <p class="text-xs text-gray-600">⏳ Aún no has recibido calificaciones</p>
                            </div>
                        </div>
                    @endif

                    <!-- Botones de acción -->
                    <div class="space-y-2 pt-2">
                        <button 
                            onclick="openInviteModal({{ $equipo->Id }}, '{{ $equipo->Nombre }}')"
                            class="w-full py-2.5 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition font-medium">
                            Invitar
                        </button>
                        <a href="{{ route('equipos.show', $equipo->Id) }}" 
                           class="block w-full py-2.5 bg-gray-900 text-white text-center rounded-lg hover:bg-gray-800 transition font-medium">
                            Gestionar
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 bg-white rounded-2xl border border-gray-200">
                    <div class="text-6xl mb-4">👥</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No tienes equipos</h3>
                    <p class="text-gray-600 mb-4">Crea tu primer equipo para comenzar</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Modal Crear Equipo -->
    <div id="modalCrearEquipo" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900">Crear Nuevo Equipo</h2>
                    <button 
                        onclick="document.getElementById('modalCrearEquipo').classList.add('hidden')"
                        class="text-gray-400 hover:text-gray-600 text-2xl"
                    >
                        &times;
                    </button>
                </div>
            </div>
            
            <form method="POST" action="{{ route('equipos.store') }}" class="p-6 space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del equipo *</label>
                    <input 
                        type="text" 
                        name="nombre"
                        placeholder="Ej: Los Programadores"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    >
                </div>

                <div class="flex gap-3 pt-4">
                    <button 
                        type="button"
                        onclick="document.getElementById('modalCrearEquipo').classList.add('hidden')"
                        class="flex-1 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition"
                    >
                        Cancelar
                    </button>
                    <button 
                        type="submit"
                        class="flex-1 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition"
                    >
                        Crear Equipo
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Invitar a Usuario -->
    <div id="modalInvitar" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Invitar a usuario</h2>
                        <p class="text-sm text-gray-600 mt-1">Crea un equipo para colaborar en proyectos y eventos</p>
                    </div>
                    <button 
                        onclick="closeInviteModal()"
                        class="text-gray-400 hover:text-gray-600 text-2xl"
                    >
                        &times;
                    </button>
                </div>
            </div>
            
            <form id="formInvitar" method="POST" action="" class="p-6 space-y-4">
                @csrf
                <input type="hidden" id="equipo_id" name="equipo_id">

                <!-- Mensajes de error -->
                @if ($errors->any())
                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-red-600 font-medium text-sm mb-2">❌ Por favor corrige los siguientes errores:</p>
                        <ul class="list-disc list-inside text-red-600 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Correo electrónico:</label>
                    <input 
                        type="email" 
                        name="email"
                        placeholder="email@ejemplo.com"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rol:</label>
                    <select
                        name="perfil_id"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent appearance-none bg-white"
                    >
                        <option value="">Selecciona un rol</option>
                        @foreach($perfiles as $perfil)
                            @if($perfil->Nombre != 'Líder')
                                <option value="{{ $perfil->Id }}">{{ $perfil->Nombre }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <button 
                    type="submit"
                    class="w-full py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition font-medium"
                >
                    Enviar invitación
                </button>
            </form>
        </div>
    </div>

    <script>
        // Modal crear equipo
        document.getElementById('modalCrearEquipo').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });

        // Modal invitar usuario
        document.getElementById('modalInvitar').addEventListener('click', function(e) {
            if (e.target === this) {
                closeInviteModal();
            }
        });

        function openInviteModal(equipoId, nombreEquipo) {
            document.getElementById('equipo_id').value = equipoId;
            document.getElementById('formInvitar').action = '/equipos/' + equipoId + '/invitar';
            document.getElementById('modalInvitar').classList.remove('hidden');
        }

        function closeInviteModal() {
            document.getElementById('modalInvitar').classList.add('hidden');
            document.getElementById('formInvitar').reset();
        }

        // Reabrir el modal si hay errores de validación
        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                // Intentar obtener el ID del equipo de la sesión old
                const equipoId = '{{ old("equipo_id") }}';
                if (equipoId) {
                    openInviteModal(equipoId, '');
                }
            });
        @endif
    </script>
</body>
</html>