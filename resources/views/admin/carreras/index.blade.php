<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DevTeams - Gestión de Carreras</title>
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
            <p class="text-gray-600">Gestiona equipos, eventos, jueces, usuarios y carreras del sistema</p>
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
                   class="px-8 py-3 rounded-full text-gray-600 hover:bg-white flex items-center gap-2 transition">
                    <span>⚖️</span>
                    <span>Jueces</span>
                </a>
                <a href="{{ route('admin.usuarios.index') }}"
                   class="px-8 py-3 rounded-full text-gray-600 hover:bg-white flex items-center gap-2 transition">
                    <span>👤</span>
                    <span>Usuarios</span>
                </a>
                <a href="{{ route('admin.carreras.index') }}"
                   class="px-8 py-3 rounded-full bg-white text-gray-900 font-medium shadow-sm flex items-center gap-2">
                    <span>🎓</span>
                    <span>Carreras</span>
                </a>
                <a href="{{ route('admin.reportes.index') }}"
                   class="px-8 py-3 rounded-full text-gray-600 hover:bg-white flex items-center gap-2 transition">
                    <span>📊</span>
                    <span>Reportes</span>
                </a>
            </div>

            <button 
                onclick="document.getElementById('modalCrearCarrera').classList.remove('hidden')"
                class="flex items-center gap-2 px-6 py-3 bg-gray-900 hover:bg-gray-800 text-white rounded-lg font-medium shadow-sm transition">
                <span>+</span>
                <span>Nueva Carrera</span>
            </button>
        </div>

        <!-- Título de sección -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Gestión de Carreras</h2>
            <p class="text-gray-600">Administra las carreras disponibles para los participantes</p>
        </div>

        <!-- Mensajes -->
        @include('partials._alerts')

        <!-- Estadísticas -->
        <div class="grid md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <span class="text-2xl">🎓</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Carreras</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $carreras->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <span class="text-2xl">👥</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Participantes</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $carreras->sum('participantes_count') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de carreras -->
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-900">ID</th>
                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-900">Nombre de la Carrera</th>
                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-900">Participantes</th>
                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-900">Fecha Creación</th>
                        <th class="text-right px-6 py-4 text-sm font-semibold text-gray-900">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($carreras as $carrera)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-600">#{{ $carrera->Id }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <span class="text-lg">🎓</span>
                                    </div>
                                    <span class="font-medium text-gray-900">{{ $carrera->Nombre }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $carrera->participantes_count }} participantes
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-600">
                                    {{ $carrera->created_at ? $carrera->created_at->format('d/m/Y') : 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-2">
                                    <button 
                                        onclick="openEditModal({{ $carrera->Id }}, '{{ addslashes($carrera->Nombre) }}')"
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                        title="Editar">
                                        ✏️
                                    </button>
                                    @if($carrera->participantes_count == 0)
                                        <form action="{{ route('admin.carreras.destroy', $carrera->Id) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar esta carrera?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Eliminar">
                                                🗑️
                                            </button>
                                        </form>
                                    @else
                                        <button 
                                            class="p-2 text-gray-400 cursor-not-allowed rounded-lg"
                                            title="No se puede eliminar porque tiene participantes asociados"
                                            disabled>
                                            🗑️
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-4xl mb-4">🎓</span>
                                    <p class="text-gray-600 mb-2">No hay carreras registradas</p>
                                    <p class="text-sm text-gray-500">Crea una nueva carrera para comenzar</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Crear Carrera -->
    <div id="modalCrearCarrera" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900">Nueva Carrera</h2>
                    <button 
                        onclick="document.getElementById('modalCrearCarrera').classList.add('hidden')"
                        class="text-gray-400 hover:text-gray-600 text-2xl"
                    >
                        &times;
                    </button>
                </div>
            </div>
            
            <form method="POST" action="{{ route('admin.carreras.store') }}" class="p-6 space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre de la Carrera *</label>
                    <input 
                        type="text" 
                        name="nombre"
                        placeholder="Ej: Ingeniería en Sistemas Computacionales"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    >
                </div>

                <div class="flex gap-3 pt-4">
                    <button 
                        type="button"
                        onclick="document.getElementById('modalCrearCarrera').classList.add('hidden')"
                        class="flex-1 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition"
                    >
                        Cancelar
                    </button>
                    <button 
                        type="submit"
                        class="flex-1 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition"
                    >
                        Crear Carrera
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar Carrera -->
    <div id="modalEditarCarrera" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900">Editar Carrera</h2>
                    <button 
                        onclick="document.getElementById('modalEditarCarrera').classList.add('hidden')"
                        class="text-gray-400 hover:text-gray-600 text-2xl"
                    >
                        &times;
                    </button>
                </div>
            </div>
            
            <form id="formEditarCarrera" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre de la Carrera *</label>
                    <input 
                        type="text" 
                        name="nombre"
                        id="editNombreCarrera"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    >
                </div>

                <div class="flex gap-3 pt-4">
                    <button 
                        type="button"
                        onclick="document.getElementById('modalEditarCarrera').classList.add('hidden')"
                        class="flex-1 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition"
                    >
                        Cancelar
                    </button>
                    <button 
                        type="submit"
                        class="flex-1 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition"
                    >
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Cerrar modales al hacer clic fuera
        document.getElementById('modalCrearCarrera').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });

        document.getElementById('modalEditarCarrera').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });

        // Abrir modal de edición
        function openEditModal(id, nombre) {
            document.getElementById('editNombreCarrera').value = nombre;
            document.getElementById('formEditarCarrera').action = `/admin/carreras/${id}`;
            document.getElementById('modalEditarCarrera').classList.remove('hidden');
        }
    </script>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex gap-8 text-sm text-gray-600">
                    <a href="#" class="hover:text-gray-900">Acerca de</a>
                    <a href="#" class="hover:text-gray-900">Soporte</a>
                    <a href="#" class="hover:text-gray-900">Contacto</a>
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
