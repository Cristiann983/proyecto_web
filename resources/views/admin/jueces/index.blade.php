<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DevTeams - Panel de Jueces</title>
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
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard de Jueces</h1>
            <p class="text-gray-600">Califica los proyectos de los equipos en tus eventos asignados</p>
        </div>

        <!-- Navegación de pestañas (solo para Admin) -->
        @if($isAdmin)
            <div class="mb-8 flex items-center justify-between">
                <div class="bg-white rounded-full shadow-sm p-2 inline-flex gap-1 border border-gray-200">
                    <a href="{{ route('admin.equipos.index') }}" class="px-6 py-2 rounded-full text-gray-600 hover:bg-gray-50 flex items-center gap-2">
                        <span>👥</span>
                        <span>Equipos</span>
                    </a>
                    <a href="{{ route('admin.eventos.index') }}" class="px-6 py-2 rounded-full text-gray-600 hover:bg-gray-50 flex items-center gap-2">
                        <span>📅</span>
                        <span>Eventos</span>
                    </a>
                    <a href="{{ route('admin.jueces.list') }}" class="px-6 py-2 rounded-full bg-gray-200 text-gray-900 font-medium flex items-center gap-2">
                        <span>⚖️</span>
                        <span>Jueces</span>
                    </a>
                </div>

                <!-- Botón de crear juez -->
                <a href="{{ route('admin.jueces.create') }}"
                   class="flex items-center gap-2 px-6 py-3 bg-gray-900 hover:bg-gray-800 text-white rounded-lg font-medium shadow-sm transition">
                    <span>+</span>
                    <span>Registrar Nuevo Juez</span>
                </a>
            </div>
        @endif

        <!-- Mensajes -->
        @include('partials._alerts')

        <!-- Contenido principal -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Panel de Calificación</h2>
            <p class="text-gray-600">Selecciona un evento activo y califica a los equipos participantes</p>
        </div>

        @if($eventosAsignados->count() > 0)
            <!-- Selector de evento -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <form method="GET" action="{{ route('admin.jueces.index') }}" class="flex gap-3">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Seleccionar Evento Activo</label>
                        <select
                            name="evento_id"
                            onchange="this.form.submit()"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-lg"
                        >
                            <option value="">-- Selecciona un evento --</option>
                            @foreach($eventosAsignados as $evento)
                                <option value="{{ $evento->Id }}" {{ $eventoSeleccionado && $eventoSeleccionado->Id == $evento->Id ? 'selected' : '' }}>
                                    {{ $evento->Nombre }} - {{ $evento->Categoria }}
                                    ({{ \Carbon\Carbon::parse($evento->Fecha_inicio)->format('d M') }} - {{ \Carbon\Carbon::parse($evento->Fecha_fin)->format('d M Y') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>

            @if($eventoSeleccionado)
                {{-- Calcular estado del evento basado en fechas --}}
                @php
                    $ahora = now();
                    $fechaInicio = \Carbon\Carbon::parse($eventoSeleccionado->Fecha_inicio);
                    $fechaFin = \Carbon\Carbon::parse($eventoSeleccionado->Fecha_fin);
                    
                    if ($ahora < $fechaInicio) {
                        $estadoEvento = 'proximo';
                        $estadoTexto = 'Próximo';
                        $estadoClase = 'bg-blue-500/90';
                    } elseif ($ahora > $fechaFin) {
                        $estadoEvento = 'finalizado';
                        $estadoTexto = 'Finalizado';
                        $estadoClase = 'bg-gray-500/90';
                    } else {
                        $estadoEvento = 'activo';
                        $estadoTexto = 'En Curso';
                        $estadoClase = 'bg-green-500/90';
                    }
                @endphp
                
                <!-- Información del evento seleccionado -->
                <div class="bg-gradient-to-r from-purple-600 to-blue-500 rounded-2xl p-6 text-white mb-6">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="bg-white/20 backdrop-blur-sm text-white text-sm px-4 py-1 rounded-full">
                            &lt;/&gt; {{ strtolower($eventoSeleccionado->Categoria) }}
                        </span>
                        <span class="{{ $estadoClase }} text-white text-sm px-4 py-1 rounded-full">
                            ● {{ $estadoTexto }}
                        </span>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">{{ $eventoSeleccionado->Nombre }}</h3>
                    <p class="opacity-90">{{ $eventoSeleccionado->Descripcion }}</p>
                    <div class="flex items-center gap-4 mt-4 text-sm flex-wrap">
                        <div class="flex items-center gap-2">
                            <span>📅</span>
                            <span>{{ \Carbon\Carbon::parse($eventoSeleccionado->Fecha_inicio)->format('d M') }} - {{ \Carbon\Carbon::parse($eventoSeleccionado->Fecha_fin)->format('d M Y') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span>👥</span>
                            <span>{{ $eventoSeleccionado->equipos->count() }} equipos inscritos</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ $criterios->count() }} criterios de evaluación</span>
                        </div>
                    </div>
                </div>

                <!-- Sección de Criterios -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Criterios de Evaluación</h3>

                    @if($criterios->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($criterios as $criterio)
                                <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <p class="font-medium text-gray-900">{{ $criterio->Nombre }}</p>
                                    <p class="text-xs text-gray-500">{{ $criterio->Descripcion }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600 text-sm">No hay criterios definidos para este evento.</p>
                    @endif
                </div>

                <!-- Lista de equipos para calificar -->
                @if($equiposPaginados && $equiposPaginados->count() > 0)
                    <div class="space-y-4 mb-6">
                        @foreach($equiposPaginados as $equipo)
                            @php
                                // Filtrar para obtener solo el proyecto del evento seleccionado
                                $proyecto = $equipo->proyectos->where('Evento_id', $eventoSeleccionado->Id)->first();
                                $lider = $equipo->participantes->where('pivot.Id_perfil', \App\Models\Perfil::where('Nombre', 'Líder')->first()->Id ?? 0)->first();

                                // Obtener calificaciones existentes de este juez para este proyecto
                                $calificacionesExistentes = $juez && $proyecto ? $proyecto->calificaciones()
                                    ->where('Juez_id', $juez->Id)
                                    ->with('criterio')
                                    ->get()
                                    ->keyBy('Criterio_id') : collect();

                                $yaCalificado = $calificacionesExistentes->count() > 0;
                            @endphp

                            @if($proyecto)
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <h3 class="text-xl font-bold text-gray-900">{{ $equipo->Nombre }}</h3>
                                            <span class="bg-blue-100 text-blue-600 text-xs px-3 py-1 rounded-full">
                                                {{ $equipo->participantes->count() }} miembro{{ $equipo->participantes->count() != 1 ? 's' : '' }}
                                            </span>
                                            @if($yaCalificado)
                                                <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full flex items-center gap-1">
                                                    ✓ Ya calificado
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-600 mb-3">Proyecto: {{ $proyecto->Nombre }}</p>
                                        @if($lider)
                                        <div class="flex items-center gap-2 text-sm text-gray-500">
                                            <span>👤</span>
                                            <span>Líder: {{ $lider->Nombre }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- 📋 Información Detallada del Equipo -->
                                <div class="grid md:grid-cols-2 gap-4 mb-6">
                                    <!-- Miembros del equipo -->
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <h4 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                            <span>👥</span>
                                            <span>Miembros del Equipo</span>
                                        </h4>
                                        <div class="space-y-2">
                                            @foreach($equipo->participantes as $miembro)
                                                <div class="flex items-start gap-3 p-2 bg-white rounded">
                                                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                                                        <span class="text-purple-600 font-bold text-sm">{{ substr($miembro->Nombre, 0, 1) }}</span>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="font-medium text-gray-900 text-sm">{{ $miembro->Nombre }}</p>
                                                        <p class="text-xs text-gray-500">{{ $miembro->Correo }}</p>
                                                        @if($miembro->carrera)
                                                            <p class="text-xs text-gray-600">📚 {{ $miembro->carrera->Nombre }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Repositorio -->
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <h4 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                            <span>📦</span>
                                            <span>Repositorio</span>
                                        </h4>
                                        @if($proyecto->repositorio)
                                            <div class="space-y-3">
                                                <div class="bg-white rounded p-3">
                                                    <a href="{{ $proyecto->repositorio->Url }}" target="_blank" 
                                                       class="text-purple-600 hover:text-purple-700 font-medium text-sm flex items-center gap-2 mb-2">
                                                        <span>🔗</span>
                                                        <span class="truncate">{{ $proyecto->repositorio->getNombreRepositorio() }}</span>
                                                    </a>
                                                    <p class="text-xs text-gray-500 break-all">{{ $proyecto->repositorio->Url }}</p>
                                                </div>

                                                <!-- Archivos del repositorio -->
                                                @if($proyecto->repositorio->archivos && count($proyecto->repositorio->archivos) > 0)
                                                    <div>
                                                        <p class="text-xs font-medium text-gray-700 mb-2">📎 Evidencias ({{ count($proyecto->repositorio->archivos) }})</p>
                                                        <div class="space-y-1 max-h-40 overflow-y-auto">
                                                            @foreach($proyecto->repositorio->archivos as $archivo)
                                                                <a href="{{ asset('storage/' . $archivo['ruta']) }}" target="_blank"
                                                                   class="flex items-center gap-2 p-2 bg-white rounded hover:bg-purple-50 transition group">
                                                                    @if(in_array($archivo['tipo'], ['jpg', 'jpeg', 'png', 'gif']))
                                                                        <img src="{{ asset('storage/' . $archivo['ruta']) }}" class="w-10 h-10 object-cover rounded">
                                                                    @else
                                                                        <span class="text-2xl">📄</span>
                                                                    @endif
                                                                    <div class="flex-1 min-w-0">
                                                                        <p class="text-xs font-medium text-gray-900 truncate group-hover:text-purple-600">{{ $archivo['nombre'] }}</p>
                                                                        <p class="text-xs text-gray-500">{{ number_format($archivo['tamano'] / 1024, 1) }} KB</p>
                                                                    </div>
                                                                    <span class="text-xs text-purple-600 opacity-0 group-hover:opacity-100">Ver →</span>
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <p class="text-sm text-gray-500 italic bg-white rounded p-3">No hay repositorio vinculado</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Formulario de calificación -->
                                <form class="calificacion-form" data-proyecto-id="{{ $proyecto->Id }}">
                                    @csrf
                                    <input type="hidden" name="proyecto_id" value="{{ $proyecto->Id }}">

                                    <!-- Criterios de calificación -->
                                    <div class="space-y-4 mb-4">
                                        @forelse($criterios as $criterio)
                                            @php
                                                $calificacionExistente = $calificacionesExistentes->get($criterio->Id);
                                                $valorInicial = $calificacionExistente ? $calificacionExistente->Calificacion : 5;
                                            @endphp
                                            <div>
                                                <div class="flex justify-between items-center mb-2">
                                                    <label class="text-sm font-medium text-gray-700">{{ $criterio->Nombre }}</label>
                                                    <span class="valor-criterio text-sm font-bold text-purple-600" data-criterio="{{ $criterio->Id }}">{{ $valorInicial }}</span>
                                                </div>
                                                <input
                                                    type="range"
                                                    name="calificaciones[{{ $loop->index }}][puntuacion]"
                                                    min="0"
                                                    max="10"
                                                    value="{{ $valorInicial }}"
                                                    class="criterio-range w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-purple-600"
                                                    data-criterio-id="{{ $criterio->Id }}"
                                                    oninput="actualizarValor(this)"
                                                >
                                                <input type="hidden" name="calificaciones[{{ $loop->index }}][criterio_id]" value="{{ $criterio->Id }}">
                                                <div class="flex justify-between text-xs text-gray-500 mt-1">
                                                    <span>0</span>
                                                    <span>10</span>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-sm text-gray-500 italic">No hay criterios disponibles. Agrega criterios para calificar.</p>
                                        @endforelse
                                    </div>

                                    <!-- Calificación total -->
                                    @php
                                        $totalInicial = $calificacionesExistentes->sum('Calificacion') ?: ($criterios->count() * 5);
                                        $maxTotal = $criterios->count() * 10;
                                    @endphp
                                    <div class="bg-purple-50 rounded-lg p-4 mb-4">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm font-medium text-gray-700">Calificación Total:</span>
                                            <span class="calificacion-total text-2xl font-bold text-purple-600">{{ $totalInicial }} / {{ $maxTotal }} pts</span>
                                        </div>
                                    </div>

                                    @if($criterios->count() > 0)
                                        <button type="submit" class="btn-guardar w-full {{ $yaCalificado ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-900 hover:bg-gray-800' }} text-white py-3 rounded-lg transition font-medium">
                                            {{ $yaCalificado ? '✓ Actualizar Calificación' : 'Guardar Calificación' }}
                                        </button>
                                    @endif
                                </form>
                            </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Paginación -->
                    <div class="mt-8 flex justify-center">
                        {{ $equiposPaginados->links('pagination::tailwind') }}
                    </div>
                @else
                    <div class="bg-white border-2 border-gray-200 rounded-2xl p-12 text-center">
                        <p class="text-gray-600">No hay equipos inscritos en este evento todavía.</p>
                    </div>
                @endif
            @else
                <!-- Mensaje cuando no hay evento seleccionado -->
                <div class="bg-white border-2 border-gray-200 rounded-2xl p-16">
                    <div class="flex flex-col items-center justify-center text-center max-w-2xl mx-auto">
                        <div class="mb-8">
                            <svg class="w-32 h-32 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Selecciona un evento para comenzar</h3>
                        <p class="text-gray-600">Elige un evento activo del menú desplegable para calificar a los equipos participantes</p>
                    </div>
                </div>
            @endif
        @else
            <!-- No hay eventos asignados -->
            <div class="bg-yellow-50 border-2 border-yellow-200 rounded-2xl p-12">
                <div class="flex flex-col items-center justify-center text-center max-w-2xl mx-auto">
                    <div class="mb-6">
                        <svg class="w-24 h-24 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">No tienes eventos asignados</h3>
                    <p class="text-gray-600">Actualmente no tienes eventos asignados para calificar. Contacta al administrador para que te asigne a eventos activos.</p>
                </div>
            </div>
        @endif

        </div>

    <script>
        // Actualizar el valor mostrado cuando se mueve el slider
        function actualizarValor(input) {
            const criterioId = input.dataset.criterioId;
            const form = input.closest('form');
            const valorSpan = form.querySelector(`[data-criterio="${criterioId}"]`);
            
            if (valorSpan) {
                valorSpan.textContent = input.value;
            }

            // Actualizar calificación total
            actualizarTotal(form);
        }

        // Calcular y actualizar la calificación total
        function actualizarTotal(form) {
            const inputs = form.querySelectorAll('input[type="range"]');
            let total = 0;

            inputs.forEach(input => {
                total += parseInt(input.value) || 0;
            });

            const maxTotal = inputs.length * 10;
            const totalSpan = form.querySelector('.calificacion-total');
            if (totalSpan) {
                totalSpan.textContent = `${total} / ${maxTotal} pts`;
            }
        }

        // Manejar envío de formulario de calificación
        document.querySelectorAll('.calificacion-form').forEach(form => {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const proyectoId = this.dataset.proyectoId;
                const btnGuardar = this.querySelector('.btn-guardar');

                try {
                    const response = await fetch('{{ route("admin.calificaciones.store") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': formData.get('_token'),
                            'Accept': 'application/json',
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (response.ok) {
                        // Cambiar botón a "Actualizar"
                        btnGuardar.textContent = '✓ Actualizar Calificación';
                        btnGuardar.classList.remove('bg-gray-900', 'hover:bg-gray-800');
                        btnGuardar.classList.add('bg-green-600', 'hover:bg-green-700');

                        // Mostrar notificación
                        mostrarNotificacion('Calificación guardada exitosamente', 'success');
                    } else {
                        mostrarNotificacion(data.message || 'Error al guardar', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    mostrarNotificacion('Error al guardar la calificación', 'error');
                }
            });
        });

        // Mostrar notificación
        function mostrarNotificacion(mensaje, tipo) {
            const notificacion = document.createElement('div');
            notificacion.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 ${
                tipo === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;
            notificacion.textContent = mensaje;

            document.body.appendChild(notificacion);

            setTimeout(() => {
                notificacion.remove();
            }, 3000);
        }
    </script>
</body>
</html>
