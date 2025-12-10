<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevTeams - Detalles del Evento</title>
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
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <div class="mb-6">
            <a href="{{ route('admin.eventos.index') }}" class="text-purple-600 hover:text-purple-700 flex items-center gap-2 mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Volver a eventos
            </a>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Detalles del Evento</h1>
            <p class="text-gray-600">Información completa del evento</p>
        </div>

        <!-- Contenedor principal -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header del evento -->
            <div class="bg-gradient-to-r from-purple-600 to-blue-500 p-8 text-white">
                <div class="flex items-center gap-3 mb-4">
                    <span class="bg-white/20 backdrop-blur-sm text-white text-sm px-4 py-1 rounded-full flex items-center gap-2">
                        <span>&lt;/&gt;</span>
                        <span>{{ $evento->Categoria ?? 'hackathon' }}</span>
                    </span>
                    @php
                        $ahora = now();
                        $estadoEvento = 'proximo';
                        $estadoColor = 'bg-blue-500/90';
                        $estadoTexto = 'Próximo';
                        
                        if ($ahora > $evento->Fecha_fin) {
                            $estadoEvento = 'finalizado';
                            $estadoColor = 'bg-gray-500/90';
                            $estadoTexto = 'Finalizado';
                        } elseif ($ahora >= $evento->Fecha_inicio && $ahora <= $evento->Fecha_fin) {
                            $estadoEvento = 'activo';
                            $estadoColor = 'bg-green-500/90';
                            $estadoTexto = 'Active';
                        }
                    @endphp
                    <span class="{{ $estadoColor }} text-white text-sm px-4 py-1 rounded-full flex items-center gap-2">
                        <span>●</span>
                        <span>{{ $estadoTexto }}</span>
                    </span>
                </div>
                <h2 class="text-3xl font-bold mb-3">{{ $evento->Nombre }}</h2>
                <p class="text-lg opacity-90">{{ Str::limit($evento->Descripcion, 80) }}</p>
            </div>

            <!-- Información detallada -->
            <div class="p-8 space-y-6">
                <!-- Fechas y Equipos -->
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="bg-purple-100 text-purple-600 p-2 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium">Fecha de inicio</p>
                                <p class="text-lg font-bold text-gray-900">{{ \Carbon\Carbon::parse($evento->Fecha_inicio)->format('d \d\e F') }}</p>
                                <p class="text-sm text-gray-600">de {{ \Carbon\Carbon::parse($evento->Fecha_inicio)->format('Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="bg-blue-100 text-blue-600 p-2 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium">Fecha de fin</p>
                                <p class="text-lg font-bold text-gray-900">{{ \Carbon\Carbon::parse($evento->Fecha_fin)->format('d \d\e F') }}</p>
                                <p class="text-sm text-gray-600">de {{ \Carbon\Carbon::parse($evento->Fecha_fin)->format('Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="bg-green-100 text-green-600 p-2 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium">Equipos</p>
                                <p class="text-lg font-bold text-gray-900">{{ $evento->proyectos->count() }}</p>
                                <p class="text-sm text-gray-600">Inscritos</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Categoría -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Categoría</h3>
                    <div class="flex items-center gap-2">
                        <span class="bg-orange-100 text-orange-600 px-4 py-2 rounded-lg font-medium">
                            {{ $evento->Categoria ?? 'Sin categoría' }}
                        </span>
                    </div>
                </div>

                <!-- Tecnologías -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Tecnologías Requeridas</h3>
                    <div class="flex flex-wrap gap-2">
                        @if($evento->tecnologias && is_array($evento->tecnologias) && count($evento->tecnologias) > 0)
                            @foreach($evento->tecnologias as $tecnologia)
                                <span class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-medium">{{ $tecnologia }}</span>
                            @endforeach
                        @else
                            <span class="text-gray-500 text-sm">No se han especificado tecnologías</span>
                        @endif
                    </div>
                </div>

                <!-- Descripción completa -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Descripción Completa</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700 leading-relaxed">
                            {{ $evento->Descripcion ?? 'Sin descripción disponible' }}
                        </p>
                    </div>
                </div>

                <!-- Información adicional -->
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Hora de inicio</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-900 font-medium">
                                {{ $evento->hora_inicio ? \Carbon\Carbon::parse($evento->hora_inicio)->format('h:i A') : \Carbon\Carbon::parse($evento->Fecha_inicio)->format('h:i A') }}
                            </p>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Duración estimada</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            @php
                                $inicio = \Carbon\Carbon::parse($evento->Fecha_inicio);
                                $fin = \Carbon\Carbon::parse($evento->Fecha_fin);
                                $diffHoras = number_format($inicio->diffInHours($fin), 0);
                                $diffDias = number_format($inicio->diffInDays($fin), 0);
                                
                                if ($diffDias >= 1) {
                                    $duracion = $diffDias . ' día' . ($diffDias > 1 ? 's' : '');
                                    $horasRestantes = $inicio->diffInHours($fin) % 24;
                                    if ($horasRestantes > 0) {
                                        $duracion .= ' y ' . number_format($horasRestantes, 0) . ' hora' . ($horasRestantes > 1 ? 's' : '');
                                    }
                                } else {
                                    $duracion = $diffHoras . ' hora' . ($diffHoras > 1 ? 's' : '');
                                }
                            @endphp
                            <p class="text-gray-900 font-medium">{{ $duracion }}</p>
                        </div>
                    </div>
                </div>

                <!-- Contador de tiempo -->
                @php
                    $ahora = now();
                    $eventoIniciado = $ahora >= $evento->Fecha_inicio;
                    $eventoFinalizado = $ahora > $evento->Fecha_fin;
                @endphp
                
                @if(!$eventoFinalizado)
                    <div class="mt-6 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-6 border border-indigo-200">
                        <div class="text-center">
                            @if(!$eventoIniciado)
                                <p class="text-sm text-indigo-600 font-medium mb-2">⏳ El evento comienza en:</p>
                            @else
                                <p class="text-sm text-green-600 font-medium mb-2">⏱️ Tiempo restante del evento:</p>
                            @endif
                            <div id="countdown-admin" class="flex justify-center gap-4">
                                <div class="bg-white rounded-lg p-3 shadow-sm min-w-[70px]">
                                    <p id="dias-admin" class="text-2xl font-bold text-gray-900">--</p>
                                    <p class="text-xs text-gray-500">Días</p>
                                </div>
                                <div class="bg-white rounded-lg p-3 shadow-sm min-w-[70px]">
                                    <p id="horas-admin" class="text-2xl font-bold text-gray-900">--</p>
                                    <p class="text-xs text-gray-500">Horas</p>
                                </div>
                                <div class="bg-white rounded-lg p-3 shadow-sm min-w-[70px]">
                                    <p id="minutos-admin" class="text-2xl font-bold text-gray-900">--</p>
                                    <p class="text-xs text-gray-500">Minutos</p>
                                </div>
                                <div class="bg-white rounded-lg p-3 shadow-sm min-w-[70px]">
                                    <p id="segundos-admin" class="text-2xl font-bold text-gray-900">--</p>
                                    <p class="text-xs text-gray-500">Segundos</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        (function() {
                            const fechaInicio = new Date('{{ $evento->Fecha_inicio->toIso8601String() }}');
                            const fechaFin = new Date('{{ $evento->Fecha_fin->toIso8601String() }}');
                            const eventoIniciado = {{ $eventoIniciado ? 'true' : 'false' }};
                            
                            function actualizarContadorAdmin() {
                                const ahora = new Date();
                                const objetivo = eventoIniciado ? fechaFin : fechaInicio;
                                const diferencia = objetivo - ahora;
                                
                                if (diferencia <= 0) {
                                    document.getElementById('dias-admin').textContent = '00';
                                    document.getElementById('horas-admin').textContent = '00';
                                    document.getElementById('minutos-admin').textContent = '00';
                                    document.getElementById('segundos-admin').textContent = '00';
                                    return;
                                }
                                
                                const dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));
                                const horas = Math.floor((diferencia % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                const minutos = Math.floor((diferencia % (1000 * 60 * 60)) / (1000 * 60));
                                const segundos = Math.floor((diferencia % (1000 * 60)) / 1000);
                                
                                document.getElementById('dias-admin').textContent = dias.toString().padStart(2, '0');
                                document.getElementById('horas-admin').textContent = horas.toString().padStart(2, '0');
                                document.getElementById('minutos-admin').textContent = minutos.toString().padStart(2, '0');
                                document.getElementById('segundos-admin').textContent = segundos.toString().padStart(2, '0');
                            }
                            
                            actualizarContadorAdmin();
                            setInterval(actualizarContadorAdmin, 1000);
                        })();
                    </script>
                @else
                    <div class="mt-6 bg-gray-100 rounded-xl p-6 border border-gray-200">
                        <div class="text-center flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                            </svg>
                            <p class="text-gray-600 font-medium">Este evento ya ha finalizado</p>
                        </div>
                    </div>
                @endif

                <!-- Botones de acción -->
                <div class="flex gap-3 pt-6 border-t border-gray-200">
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex gap-8 text-sm text-gray-600">
                    <a href="#" class="hover:text-gray-900">Acerca de</a>
                    <a href="#" class="hover:text-gray-900">Acerca de</a>
                    <a href="#" class="hover:text-gray-900">Acerca de</a>
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