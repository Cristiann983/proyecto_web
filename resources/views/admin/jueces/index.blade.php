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

        <!-- Contenido principal -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Panel de Calificación</h2>
            <p class="text-gray-600">Selecciona un evento activo y califica a los equipos participantes</p>
        </div>

        @if($eventosAsignados->count() > 0)
            <!-- Selector de evento -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">Seleccionar Evento Activo</label>
                <select
                    id="eventoSelector"
                    onchange="mostrarEvento(this.value)"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-lg"
                >
                    <option value="">-- Selecciona un evento --</option>
                    @foreach($eventosAsignados as $evento)
                        <option value="{{ $evento->Id }}">
                            {{ $evento->Nombre }} - {{ $evento->Categoria }}
                            ({{ \Carbon\Carbon::parse($evento->Fecha_inicio)->format('d M') }} - {{ \Carbon\Carbon::parse($evento->Fecha_fin)->format('d M Y') }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Contenedores de eventos -->
            @foreach($eventosAsignados as $evento)
                <div id="evento-{{ $evento->Id }}" class="evento-container hidden">
                    <!-- Información del evento seleccionado -->
                    <div class="bg-gradient-to-r from-purple-600 to-blue-500 rounded-2xl p-6 text-white mb-6">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="bg-white/20 backdrop-blur-sm text-white text-sm px-4 py-1 rounded-full">
                                &lt;/&gt; {{ strtolower($evento->Categoria) }}
                            </span>
                            <span class="bg-green-500/90 text-white text-sm px-4 py-1 rounded-full">
                                ● Activo
                            </span>
                        </div>
                        <h3 class="text-2xl font-bold mb-2">{{ $evento->Nombre }}</h3>
                        <p class="opacity-90">{{ $evento->Descripcion }}</p>
                        <div class="flex items-center gap-4 mt-4 text-sm">
                            <div class="flex items-center gap-2">
                                <span>📅</span>
                                <span>{{ \Carbon\Carbon::parse($evento->Fecha_inicio)->format('d M') }} - {{ \Carbon\Carbon::parse($evento->Fecha_fin)->format('d M Y') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span>👥</span>
                                <span>{{ $evento->equipos->count() }} equipos inscritos</span>
                            </div>
                        </div>
                    </div>

                    <!-- Lista de equipos para calificar -->
                    @if($evento->equipos->count() > 0)
                        <div class="space-y-4">
                            @foreach($evento->equipos as $equipo)
                                @php
                                    $proyecto = $equipo->proyectos->first();
                                    $lider = $equipo->participantes->where('pivot.Id_perfil', \App\Models\Perfil::where('Nombre', 'Líder')->first()->Id ?? 0)->first();

                                    // Obtener calificaciones existentes de este juez para este proyecto
                                    $calificacionesExistentes = $juez ? $proyecto->calificaciones()
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

                                    <!-- Formulario de calificación -->
                                    <form class="calificacion-form" data-proyecto-id="{{ $proyecto->Id }}">
                                        @csrf
                                        <input type="hidden" name="proyecto_id" value="{{ $proyecto->Id }}">

                                        <!-- Criterios de calificación -->
                                        <div class="space-y-4 mb-4">
                                            @foreach($criterios as $criterio)
                                                @php
                                                    $calificacionExistente = $calificacionesExistentes->get($criterio->Id);
                                                    $valorInicial = $calificacionExistente ? $calificacionExistente->Calificacion : 5;
                                                @endphp
                                                <div>
                                                    <div class="flex justify-between items-center mb-2">
                                                        <label class="text-sm font-medium text-gray-700">{{ $criterio->descripcion }}</label>
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
                                            @endforeach
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

                                        <button type="submit" class="btn-guardar w-full {{ $yaCalificado ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-900 hover:bg-gray-800' }} text-white py-3 rounded-lg transition font-medium">
                                            {{ $yaCalificado ? '✓ Actualizar Calificación' : 'Guardar Calificación' }}
                                        </button>
                                    </form>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white border-2 border-gray-200 rounded-2xl p-12 text-center">
                            <p class="text-gray-600">No hay equipos inscritos en este evento todavía.</p>
                        </div>
                    @endif
                </div>
            @endforeach

            <!-- Mensaje cuando no hay evento seleccionado -->
            <div id="noEventoMensaje" class="bg-white border-2 border-gray-200 rounded-2xl p-16">
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
        function mostrarEvento(eventoId) {
            // Ocultar todos los contenedores de eventos
            document.querySelectorAll('.evento-container').forEach(container => {
                container.classList.add('hidden');
            });

            const noEventoMensaje = document.getElementById('noEventoMensaje');

            if (eventoId) {
                const eventoContainer = document.getElementById('evento-' + eventoId);
                if (eventoContainer) {
                    eventoContainer.classList.remove('hidden');
                }
                noEventoMensaje.classList.add('hidden');
            } else {
                noEventoMensaje.classList.remove('hidden');
            }
        }

        function actualizarValor(input) {
            const criterioId = input.dataset.criterioId;
            const form = input.closest('form');
            const valorSpan = form.querySelector(`.valor-criterio[data-criterio="${criterioId}"]`);

            if (valorSpan) {
                valorSpan.textContent = input.value;
            }

            // Calcular total
            const ranges = form.querySelectorAll('.criterio-range');
            let total = 0;
            ranges.forEach(range => {
                total += parseInt(range.value);
            });

            const totalSpan = form.querySelector('.calificacion-total');
            const maxTotal = ranges.length * 10;
            if (totalSpan) {
                totalSpan.textContent = `${total} / ${maxTotal} pts`;
            }
        }

        // Manejar envío de formularios de calificación
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.calificacion-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const btnGuardar = this.querySelector('.btn-guardar');
                    const originalText = btnGuardar.textContent;
                    btnGuardar.disabled = true;
                    btnGuardar.textContent = 'Guardando...';

                    const formData = new FormData(this);

                    fetch('{{ route("admin.calificaciones.store") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success || data.message) {
                            // Mostrar mensaje detallado
                            const mensaje = data.message || 'Calificaciones guardadas';
                            btnGuardar.textContent = '✓ Guardado';
                            btnGuardar.classList.remove('bg-gray-900', 'hover:bg-gray-800', 'bg-green-600', 'hover:bg-green-700');
                            btnGuardar.classList.add('bg-green-600');

                            // Log de verificación en consola
                            console.log('✓ Calificación guardada:', {
                                proyecto_id: formData.get('proyecto_id'),
                                criterios_guardados: data.guardadas || 0,
                                criterios_actualizados: data.actualizadas || 0,
                                total_verificado: data.total_verificado,
                                todas_guardadas: data.todas_guardadas
                            });

                            // Mostrar notificación temporal
                            const notification = document.createElement('div');
                            notification.className = 'fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center gap-2';
                            notification.innerHTML = `
                                <span>✓</span>
                                <span>${mensaje}</span>
                            `;
                            document.body.appendChild(notification);

                            setTimeout(() => {
                                notification.remove();
                            }, 3000);

                            setTimeout(() => {
                                btnGuardar.textContent = '✓ Actualizar Calificación';
                                btnGuardar.classList.remove('bg-gray-900', 'hover:bg-gray-800');
                                btnGuardar.classList.add('bg-green-600', 'hover:bg-green-700');
                                btnGuardar.disabled = false;
                            }, 2000);
                        } else {
                            throw new Error(data.error || 'Error al guardar');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        btnGuardar.textContent = '✗ Error';
                        btnGuardar.classList.remove('bg-gray-900', 'hover:bg-gray-800');
                        btnGuardar.classList.add('bg-red-600');

                        setTimeout(() => {
                            btnGuardar.textContent = originalText;
                            btnGuardar.classList.remove('bg-red-600');
                            btnGuardar.classList.add('bg-gray-900', 'hover:bg-gray-800');
                            btnGuardar.disabled = false;
                        }, 2000);
                    });
                });
            });
        });
    </script>
</body>
</html>
