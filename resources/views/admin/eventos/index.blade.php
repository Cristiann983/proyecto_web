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
            <a href="{{ route('admin.usuarios.index') }}"
               class="px-8 py-3 rounded-full text-gray-600 hover:bg-white flex items-center gap-2 transition">
                <span>👤</span>
                <span>Usuarios</span>
            </a>
            <a href="{{ route('admin.carreras.index') }}"
               class="px-8 py-3 rounded-full text-gray-600 hover:bg-white flex items-center gap-2 transition">
                <span>🎓</span>
                <span>Carreras</span>
            </a>
            <a href="{{ route('admin.reportes.index') }}"
               class="px-8 py-3 rounded-full text-gray-600 hover:bg-white flex items-center gap-2 transition">
                <span>📈</span>
                <span>Reportes</span>
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
        @include('partials._alerts')

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

                    <!-- Fechas y Horas -->
                    <div class="space-y-2 mb-4 text-sm text-gray-600">
                        <div class="flex items-center gap-2">
                            <span>📅</span>
                            <span>Inicio: {{ \Carbon\Carbon::parse($evento->Fecha_inicio)->format('d/m/Y') }}</span>
                            @if($evento->hora_inicio)
                                <span class="text-purple-600 font-medium">{{ $evento->hora_inicio }}</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <span>⏰</span>
                            <span>Fin: {{ \Carbon\Carbon::parse($evento->Fecha_fin)->format('d/m/Y') }}</span>
                            @if($evento->hora_fin)
                                <span class="text-purple-600 font-medium">{{ $evento->hora_fin }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Participantes y nivel -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <span>👥</span>
                            <span>{{ $evento->cantidadEquipos }} equipos</span>
                        </div>
                        @if($evento->Categoria)
                            <span class="text-orange-500 text-sm font-medium">{{ $evento->Categoria }}</span>
                        @endif
                    </div>

                    <!-- Tecnologías -->
                    @if($evento->tecnologias && count($evento->tecnologias) > 0)
                    <div class="mb-4">
                        <p class="text-xs text-gray-500 mb-2">Tecnologías</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach(array_slice($evento->tecnologias, 0, 4) as $tech)
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">{{ $tech }}</span>
                            @endforeach
                            @if(count($evento->tecnologias) > 4)
                                <span class="px-3 py-1 bg-gray-200 text-gray-600 text-xs rounded-full font-medium">+{{ count($evento->tecnologias) - 4 }}</span>
                            @endif
                        </div>
                    </div>
                    @endif

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

            @if ($errors->any())
                <div class="mx-6 mt-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="flex-shrink-0 w-5 h-5 text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <p class="font-semibold text-red-800">Por favor corrige los siguientes errores:</p>
                    </div>
                    <ul class="list-disc list-inside text-sm text-red-600 ml-8">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Número máximo de equipos</label>
                        <input 
                            type="number" 
                            name="max_equipos"
                            value="100"
                            min="1"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nivel de dificultad</label>
                        <select name="dificultad" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="Principiante">Principiante</option>
                            <option value="Intermedio" selected>Intermedio</option>
                            <option value="Avanzado">Avanzado</option>
                            <option value="Experto">Experto</option>
                        </select>
                    </div>
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

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hora de inicio</label>
                        <input 
                            type="time" 
                            name="hora_inicio"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        >
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hora de fin</label>
                        <input 
                            type="time" 
                            name="hora_fin"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        >
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ubicación</label>
                    <input 
                        type="text" 
                        name="ubicacion"
                        placeholder="Ej: Virtual, Bogotá, etc."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tecnologías requeridas</label>
                    <div class="grid grid-cols-2 gap-2 p-4 border border-gray-300 rounded-lg max-h-48 overflow-y-auto">
                        <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" name="tecnologias[]" value="React" class="rounded">
                            <span class="text-sm">React</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" name="tecnologias[]" value="Vue.js" class="rounded">
                            <span class="text-sm">Vue.js</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" name="tecnologias[]" value="Angular" class="rounded">
                            <span class="text-sm">Angular</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" name="tecnologias[]" value="Node.js" class="rounded">
                            <span class="text-sm">Node.js</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" name="tecnologias[]" value="Python" class="rounded">
                            <span class="text-sm">Python</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" name="tecnologias[]" value="Java" class="rounded">
                            <span class="text-sm">Java</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" name="tecnologias[]" value="Laravel" class="rounded">
                            <span class="text-sm">Laravel</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" name="tecnologias[]" value="Solidity" class="rounded">
                            <span class="text-sm">Solidity</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" name="tecnologias[]" value="Web3.js" class="rounded">
                            <span class="text-sm">Web3.js</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" name="tecnologias[]" value="MySQL" class="rounded">
                            <span class="text-sm">MySQL</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" name="tecnologias[]" value="MongoDB" class="rounded">
                            <span class="text-sm">MongoDB</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" name="tecnologias[]" value="Docker" class="rounded">
                            <span class="text-sm">Docker</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado del evento</label>
                    <select name="estado" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="Activo" selected>Activo</option>
                        <option value="Finalizado">Finalizado</option>
                        <option value="Cancelado">Cancelado</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jueces asignados</label>
                    <select 
                        name="jueces[]"
                        multiple
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        size="3"
                    >
                        @foreach ($jueces as $juez)
                            <option value="{{ $juez->Id }}">{{ $juez->user->name ?? $juez->Nombre }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-2">Mantén presionado Ctrl (Cmd en Mac) para seleccionar múltiples</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Criterios de Evaluación</label>
                    <div id="criterios-container" class="space-y-3 mb-3">
                        <div class="criterio-item bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <div class="flex gap-2 mb-2">
                                <input 
                                    type="text" 
                                    name="criterios[0][nombre]"
                                    placeholder="Nombre del criterio"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                >
                                <button type="button" onclick="removerCriterio(this)" class="text-red-600 hover:text-red-700 px-2 py-2">🗑️</button>
                            </div>
                            <textarea 
                                name="criterios[0][descripcion]"
                                placeholder="Descripción (opcional)"
                                rows="2"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            ></textarea>
                        </div>
                    </div>
                    <button 
                        type="button"
                        onclick="agregarCriterio()"
                        class="text-sm text-purple-600 hover:text-purple-700 font-medium"
                    >
                        + Agregar otro criterio
                    </button>
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
        // Mantener el modal abierto si hay errores de validación
        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('modalCrearEvento').classList.remove('hidden');
            });
        @endif

        document.getElementById('modalCrearEvento').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });

        let criterioCount = 1;

        function agregarCriterio() {
            const container = document.getElementById('criterios-container');
            const nuevoItem = document.createElement('div');
            nuevoItem.className = 'criterio-item bg-gray-50 p-4 rounded-lg border border-gray-200';
            nuevoItem.innerHTML = `
                <div class="flex gap-2 mb-2">
                    <input 
                        type="text" 
                        name="criterios[${criterioCount}][nombre]"
                        placeholder="Nombre del criterio"
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    >
                    <button type="button" onclick="removerCriterio(this)" class="text-red-600 hover:text-red-700 px-2 py-2">🗑️</button>
                </div>
                <textarea 
                    name="criterios[${criterioCount}][descripcion]"
                    placeholder="Descripción (opcional)"
                    rows="2"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                ></textarea>
            `;
            container.appendChild(nuevoItem);
            criterioCount++;
        }

        function removerCriterio(button) {
            const container = document.getElementById('criterios-container');
            const items = container.querySelectorAll('.criterio-item');
            
            // No permitir eliminar si solo hay un criterio
            if (items.length > 1) {
                button.closest('.criterio-item').remove();
            } else {
                alert('Debes tener al menos un criterio');
            }
        }
    </script>
</body>
</html>