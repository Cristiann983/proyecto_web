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
    @include('partials._navigation')

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
                                    @if(isset($participante->perfil) && $participante->perfil->Nombre === 'Líder')
                                        <span class="ml-auto px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full flex items-center gap-1">
                                            <span>👑</span>
                                            <span>{{ $participante->perfil->Nombre }}</span>
                                        </span>
                                    @elseif(isset($participante->perfil))
                                        <span class="ml-auto px-2 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded-full">
                                            {{ $participante->perfil->Nombre }}
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
                        @php
                            $nombreEvento = $proyecto->evento ? $proyecto->evento->Nombre : 'Sin evento';
                            $promedioGeneral = $calificaciones->avg('Calificacion');
                            $totalGeneral = $calificaciones->sum('Calificacion');
                            $maxGeneral = $calificaciones->count() * 10;
                        @endphp
                        
                        <div class="mb-4 pt-4 border-t border-gray-200">
                            <div class="bg-gradient-to-r from-purple-600 to-blue-500 rounded-lg p-4 text-white">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm opacity-90 mb-1">📅 {{ $nombreEvento }}</p>
                                        <p class="text-xs opacity-75">Calificación del evento</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-3xl font-bold">{{ number_format($promedioGeneral, 1) }}/10</p>
                                        <p class="text-xs opacity-75">{{ $totalGeneral }}/{{ $maxGeneral }} pts</p>
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
                    <div class="p-4 bg-red-50 border border-red-200 rounded-xl">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="flex-shrink-0 w-5 h-5 text-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <p class="text-red-700 font-medium text-sm">Por favor corrige los siguientes errores:</p>
                        </div>
                        <ul class="list-disc list-inside text-red-600 text-sm ml-8">
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

    @include('partials._footer')
</body>
</html>