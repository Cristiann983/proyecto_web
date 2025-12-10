<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevTeams - {{ $equipo->Nombre }}</title>
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
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Botón volver -->
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 mb-6">
            <span>←</span>
            <span>Volver a equipos</span>
        </a>

        <!-- Mensajes de éxito y error -->
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

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-red-600 font-medium mb-2">❌ Por favor corrige los siguientes errores:</p>
                <ul class="list-disc list-inside text-red-600 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Información del equipo -->
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden mb-6">
            <!-- Header -->
            <div class="bg-gradient-to-r from-purple-600 to-blue-600 p-8">
                <div class="flex items-center gap-4">
                    <div class="w-20 h-20 bg-white/20 rounded-2xl flex items-center justify-center">
                        <span class="text-4xl">👥</span>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-1">{{ $equipo->Nombre }}</h1>
                        @if($equipo->perfil)
                            <p class="text-white/90">{{ $equipo->perfil->Descripcion }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Contenido -->
            <div class="p-8">
                <!-- Estadísticas -->
                <div class="grid md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-gray-50 rounded-xl p-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <span class="text-purple-600">👥</span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Miembros</p>
                                <p class="font-semibold text-gray-900 text-xl">{{ $equipo->participantes->count() }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-xl p-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <span class="text-blue-600">📁</span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Proyectos</p>
                                <p class="font-semibold text-gray-900 text-xl">{{ $equipo->proyectos->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <span class="text-green-600">📅</span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Eventos activos</p>
                                <p class="font-semibold text-gray-900 text-xl">0</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Miembros del equipo -->
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-900">Miembros del equipo</h2>
                        @if($esLider)
                            <button
                                onclick="openInviteModal({{ $equipo->Id }}, '{{ $equipo->Nombre }}')"
                                class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition text-sm font-medium">
                                + Invitar miembro
                            </button>
                        @endif
                    </div>
                    
                    <div class="space-y-3">
                        @foreach($equipo->participantes as $participante)
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-blue-500 rounded-full flex items-center justify-center">
                                        <span class="text-white font-bold text-lg">{{ substr($participante->Nombre, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $participante->Nombre }}</p>
                                        <p class="text-sm text-gray-500">{{ $participante->Correo }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if(isset($participante->perfil) && $participante->perfil->Nombre === 'Líder')
                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-sm font-medium rounded-full flex items-center gap-1">
                                            <span>👑</span>
                                            <span>{{ $participante->perfil->Nombre }}</span>
                                        </span>
                                    @elseif(isset($participante->perfil))
                                        <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm font-medium rounded-full">
                                            {{ $participante->perfil->Nombre }}
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm font-medium rounded-full">
                                            Miembro
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Proyectos -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Proyectos</h2>
                    @if($equipo->proyectos->count() > 0)
                        <div class="space-y-3">
                            @foreach($equipo->proyectos as $proyecto)
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $proyecto->Nombre ?? 'Proyecto sin nombre' }}</p>
                                        <p class="text-sm text-gray-500">Creado: {{ $proyecto->created_at ? $proyecto->created_at->format('d/m/Y') : 'Fecha no disponible' }}</p>
                                    </div>
                                    <a href="#" class="text-purple-600 hover:text-purple-700 text-sm font-medium">
                                        Ver detalles →
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 bg-gray-50 rounded-xl">
                            <div class="text-4xl mb-3">📁</div>
                            <p class="text-gray-600">No hay proyectos aún</p>
                        </div>
                    @endif
                </div>

                <!-- Calificaciones Detalladas -->
                @php
                    $proyectos = $equipo->proyectos;
                    $todasCalificaciones = collect();
                    $idsUsados = [];
                    
                    foreach($proyectos as $proyecto) {
                        foreach($proyecto->calificaciones as $cal) {
                            // Evitar duplicados por ID
                            if (in_array($cal->Id, $idsUsados)) continue;
                            $idsUsados[] = $cal->Id;
                            
                            // Obtener evento_id del proyecto
                            $eventoId = $proyecto->evento ? $proyecto->evento->Id : 0;
                            
                            // Crear clave única: evento_id + juez_id
                            $cal->evento_juez_key = $eventoId . '_' . $cal->Juez_id;
                            $cal->evento_id = $eventoId;
                            $cal->evento_nombre = $proyecto->evento ? $proyecto->evento->Nombre : 'Sin evento';
                            $cal->criterio_nombre = $cal->criterio ? $cal->criterio->Nombre : 'Criterio';
                            $todasCalificaciones->push($cal);
                        }
                    }
                    
                    // Agrupar por combinación única de evento + juez
                    $porEventoJuez = $todasCalificaciones->groupBy('evento_juez_key');
                @endphp

                @if($todasCalificaciones->count() > 0)
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <span>⭐</span>
                            <span>Calificaciones Recibidas</span>
                        </h2>

                        <!-- Resumen general -->
                        @php
                            $promedioGeneral = $todasCalificaciones->avg('Calificacion');
                            $totalGeneral = $todasCalificaciones->sum('Calificacion');
                            $maxGeneral = $todasCalificaciones->count() * 10;
                            $totalJueces = $todasCalificaciones->unique('Juez_id')->count();
                        @endphp

                        <div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-xl p-6 mb-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-white/80 text-sm mb-1">Promedio General del Equipo</p>
                                    <div class="flex items-baseline gap-2">
                                        <p class="text-5xl font-bold">{{ number_format($promedioGeneral, 1) }}</p>
                                        <p class="text-2xl text-white/80">/10</p>
                                    </div>
                                    <p class="text-white/60 text-sm mt-2">{{ $totalGeneral }} / {{ $maxGeneral }} puntos totales</p>
                                </div>
                                <div class="text-center">
                                    <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center mb-2">
                                        <span class="text-4xl">🏆</span>
                                    </div>
                                    <p class="text-sm text-white/80">{{ $totalJueces }} jueces</p>
                                </div>
                            </div>
                        </div>

                        <!-- Cards de calificaciones: una por cada combinación evento+juez -->
                        <div class="grid md:grid-cols-2 gap-4">
                            @foreach($porEventoJuez as $key => $calificacionesGrupo)
                                @php
                                    $juez = $calificacionesGrupo->first()->juez;
                                    $eventoNombre = $calificacionesGrupo->first()->evento_nombre;
                                    $totalPuntos = $calificacionesGrupo->sum('Calificacion');
                                    $maxPuntos = $calificacionesGrupo->count() * 10;
                                    $promedio = $calificacionesGrupo->avg('Calificacion');
                                @endphp

                                <div class="bg-white border-2 border-gray-200 rounded-xl p-5 hover:border-purple-300 transition">
                                    <!-- Badge del evento -->
                                    <div class="mb-3">
                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded-full">
                                            <span>📅</span>
                                            <span>{{ $eventoNombre }}</span>
                                        </span>
                                    </div>

                                    <!-- Header del juez -->
                                    <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-200">
                                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-blue-500 rounded-full flex items-center justify-center">
                                            <span class="text-white font-bold text-lg">
                                                {{ substr($juez->Nombre, 0, 1) }}
                                            </span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-900">{{ $juez->Nombre }}</p>
                                            <p class="text-sm text-gray-600">{{ $juez->especialidad->Nombre ?? 'Juez' }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-2xl font-bold text-purple-600">{{ number_format($promedio, 1) }}</p>
                                            <p class="text-xs text-gray-500">/10</p>
                                        </div>
                                    </div>

                                    <!-- Criterios calificados -->
                                    <div class="space-y-3">
                                        @foreach($calificacionesGrupo as $calificacion)
                                            <div>
                                                <div class="flex items-center justify-between mb-1">
                                                    <span class="text-sm font-medium text-gray-700">{{ $calificacion->criterio_nombre }}</span>
                                                    <span class="text-sm font-bold text-purple-600">{{ $calificacion->Calificacion }}/10</span>
                                                </div>
                                                <!-- Barra de progreso -->
                                                <div class="w-full bg-gray-200 rounded-full h-2">
                                                    <div class="bg-gradient-to-r from-purple-500 to-blue-500 h-2 rounded-full transition-all"
                                                         style="width: {{ ($calificacion->Calificacion / 10) * 100 }}%">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Total del juez -->
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm font-medium text-gray-700">Total</span>
                                            <span class="text-sm font-bold text-gray-900">{{ $totalPuntos }} / {{ $maxPuntos }} pts</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @elseif($proyectos->count() > 0)
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <span>⭐</span>
                            <span>Calificaciones</span>
                        </h2>
                        <div class="bg-gray-50 border-2 border-gray-200 rounded-xl p-12 text-center">
                            <div class="text-6xl mb-4">⏳</div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Aún no has recibido calificaciones</h3>
                            <p class="text-gray-600">Los jueces calificarán tu proyecto durante el evento</p>
                        </div>
                    </div>
                @endif

                <!-- Botón salir del equipo -->
                <form action="{{ route('equipos.leave', $equipo->Id) }}" 
                      method="POST" 
                      onsubmit="return confirm('¿Estás seguro de salir de este equipo?')">
                    @csrf
                    <button type="submit" 
                            class="w-full py-3 border-2 border-red-600 text-red-600 rounded-lg hover:bg-red-50 transition font-medium">
                        Salir del equipo
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Invitar a Usuario -->
    <div id="modalInvitar" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Invitar a usuario</h2>
                        <p class="text-sm text-gray-600 mt-1">Ingresa el correo del participante que deseas invitar al equipo</p>
                    </div>
                    <button 
                        onclick="closeInviteModal()"
                        class="text-gray-400 hover:text-gray-600 text-2xl"
                    >
                        &times;
                    </button>
                </div>
            </div>
            
            <form id="formInvitar" method="POST" action="{{ route('equipos.invite', $equipo->Id) }}" class="p-6 space-y-4">
                @csrf
                
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rol en el equipo:</label>
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
        function openInviteModal(equipoId, nombreEquipo) {
            document.getElementById('modalInvitar').classList.remove('hidden');
        }

        function closeInviteModal() {
            document.getElementById('modalInvitar').classList.add('hidden');
            document.getElementById('formInvitar').reset();
        }

        document.getElementById('modalInvitar').addEventListener('click', function(e) {
            if (e.target === this) {
                closeInviteModal();
            }
        });
    </script>
</body>
</html>