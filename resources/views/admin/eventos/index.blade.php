<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DevTeams - Dashboard Admin</title>
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
                
                <div class="flex items-center gap-3">
                    <a href="{{ route('perfil.show') }}" class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        <span>👤</span>
                        <span>Mi Perfil</span>
                    </a>
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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Encabezado -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-gray-600">Gestiona tus equipos, eventos, invitaciones y código</p>
        </div>

        <!-- Pestañas de navegación -->
        <div class="mb-8 bg-gray-100 rounded-full p-1 inline-flex gap-1">
            <a href="{{ route('admin.equipos.index') }}" 
               class="px-8 py-3 rounded-full text-gray-600 hover:bg-white flex items-center gap-2 transition">
                <span>👥</span>
                <span>Equipos</span>
            </a>
            <a href="{{ route('admin.eventos.index') }}" 
               class="px-8 py-3 rounded-full bg-white text-gray-900 font-medium shadow-sm flex items-center gap-2">
                <span>📅</span>
                <span>Eventos</span>
            </a>
            <a href="{{ route('admin.jueces.list') }}"
               class="px-8 py-3 rounded-full text-gray-600 hover:bg-white flex items-center gap-2 transition">
                <span>⚖️</span>
                <span>Jueces</span>
            </a>
        </div>

        <!-- Sección de eventos -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Eventos</h2>
                <p class="text-gray-600">Gestiona los eventos</p>
            </div>
            <button 
                onclick="document.getElementById('modalCrearEvento').classList.remove('hidden')"
                class="bg-gray-900 hover:bg-gray-800 text-white px-5 py-2.5 rounded-lg font-medium flex items-center gap-2"
            >
                <span>+</span>
                <span>Crear evento</span>
            </button>
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

        <!-- Grid de eventos -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($eventos as $evento)
                <div class="bg-white rounded-2xl border border-gray-200 p-6 hover:shadow-md transition">
                    <!-- Etiquetas -->
                    <div class="flex gap-2 mb-4">
                        <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded-full flex items-center gap-1">
                            <span>&lt;/&gt;</span> hackathon
                        </span>
                        <span class="px-3 py-1 {{ $evento->estadoLabel['clase'] }} text-xs font-medium rounded-full">
                            {{ $evento->estadoLabel['texto'] }}
                        </span>
                    </div>

                    <!-- Título y descripción -->
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $evento->Nombre }}</h3>
                    <p class="text-gray-600 text-sm mb-4">{{ Str::limit($evento->Descripcion, 80) }}</p>

                    <!-- Fechas -->
                    <div class="flex items-center gap-4 mb-4 text-sm text-gray-600">
                        <div class="flex items-center gap-1">
                            <span>📅</span>
                            <span>{{ \Carbon\Carbon::parse($evento->Fecha_inicio)->format('d \d\e F \d\e Y') }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <span>⏰</span>
                            <span>{{ \Carbon\Carbon::parse($evento->Fecha_fin)->format('d \d\e F \d\e Y') }}</span>
                        </div>
                    </div>

                    <!-- Participantes y nivel -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <span>👥</span>
                            <span>{{ $evento->cantidadEquipos }} equipos</span>
                        </div>
                        <span class="text-orange-500 text-sm font-medium">intermedio</span>
                    </div>

                    <!-- Tecnologías -->
                    <div class="mb-4">
                        <p class="text-xs text-gray-500 mb-2">Tecnologías</p>
                        <div class="flex gap-2">
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">Solidity</span>
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">React</span>
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">Web3.js</span>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="space-y-2">
                        <a href="{{ route('admin.eventos.equipos', $evento->Id) }}" 
                           class="block w-full py-2.5 bg-purple-600 text-white text-center rounded-lg hover:bg-purple-700 transition font-medium">
                            Ver equipos inscritos ({{ $evento->cantidadEquipos }})
                        </a>
                        <a href="{{ route('admin.eventos.show', $evento->Id) }}" 
                           class="block w-full py-2.5 bg-gray-900 text-white text-center rounded-lg hover:bg-gray-800 transition">
                            Ver detalles
                        </a>
                        <a href="{{ route('admin.eventos.edit', $evento->Id) }}" 
                           class="block w-full py-2.5 bg-gray-900 text-white text-center rounded-lg hover:bg-gray-800 transition">
                            Editar
                        </a>
                        <form action="{{ route('admin.eventos.destroy', $evento->Id) }}" 
                              method="POST" 
                              onsubmit="return confirm('¿Estás seguro de eliminar este evento?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 bg-white rounded-2xl border border-gray-200">
                    <div class="text-6xl mb-4">📅</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No hay eventos</h3>
                    <p class="text-gray-600 mb-4">Crea tu primer evento para comenzar</p>
                    <button 
                        onclick="document.getElementById('modalCrearEvento').classList.remove('hidden')"
                        class="bg-gray-900 hover:bg-gray-800 text-white px-6 py-3 rounded-lg font-medium"
                    >
                        + Crear evento
                    </button>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Modal Crear Evento -->
    <div id="modalCrearEvento" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900">Crear Nuevo Evento</h2>
                    <button 
                        onclick="document.getElementById('modalCrearEvento').classList.add('hidden')"
                        class="text-gray-400 hover:text-gray-600 text-2xl"
                    >
                        &times;
                    </button>
                </div>
            </div>
            
            <form method="POST" action="{{ route('admin.eventos.store') }}" class="p-6 space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del evento *</label>
                    <input 
                        type="text" 
                        name="nombre"
                        placeholder="Ej: Hackathon 2024"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Descripción *</label>
                    <textarea 
                        name="descripcion"
                        rows="3"
                        placeholder="Describe el evento..."
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    ></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de inicio *</label>
                        <input 
                            type="date" 
                            name="fecha_inicio"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de fin *</label>
                        <input 
                            type="date" 
                            name="fecha_fin"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        >
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Juez asignado</label>
                    <select 
                        name="id_juez"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    >
                        <option value="">Sin juez asignado</option>
                        @foreach ($jueces as $juez)
                            <option value="{{ $juez->Id }}">{{ $juez->Nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-3 pt-4">
                    <button 
                        type="button"
                        onclick="document.getElementById('modalCrearEvento').classList.add('hidden')"
                        class="flex-1 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition"
                    >
                        Cancelar
                    </button>
                    <button 
                        type="submit"
                        class="flex-1 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition"
                    >
                        Crear Evento
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('modalCrearEvento').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    </script>
</body>
</html>