<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DevTeams - Invitaciones</title>
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

    <!-- Dashboard -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard</h1>
            <p class="text-gray-600">Gestiona tus equipos, eventos, invitaciones y código</p>
        </div>

        <!-- Navegación de pestañas -->
        <div class="mb-8 bg-gray-100 rounded-full p-1 inline-flex gap-1">
            <a href="{{ route('dashboard') }}"
               class="px-8 py-3 rounded-full text-gray-600 hover:bg-white flex items-center gap-2 transition">
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
               class="px-8 py-3 rounded-full bg-white text-gray-900 font-medium shadow-sm flex items-center gap-2">
                <span>✉️</span>
                <span>Invitaciones</span>
                @if(isset($invitaciones) && $invitaciones->where('Estado', 'Pendiente')->count() > 0)
                    <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">
                        {{ $invitaciones->where('Estado', 'Pendiente')->count() }}
                    </span>
                @endif
            </a>
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

        <!-- Contenido principal -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Invitaciones</h2>
            <p class="text-gray-600">Gestiona las invitaciones a equipos que has recibido</p>
        </div>

        @if(isset($invitaciones) && $invitaciones->count() > 0)
            <!-- Tabs para filtrar invitaciones -->
            <div class="mb-6 flex gap-2">
                <button onclick="filtrarInvitaciones('todas')" class="tab-btn px-4 py-2 rounded-lg bg-gray-900 text-white">
                    Todas ({{ $invitaciones->count() }})
                </button>
                <button onclick="filtrarInvitaciones('pendientes')" class="tab-btn px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300">
                    Pendientes ({{ $invitaciones->where('Estado', 'Pendiente')->count() }})
                </button>
                <button onclick="filtrarInvitaciones('aceptadas')" class="tab-btn px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300">
                    Aceptadas ({{ $invitaciones->where('Estado', 'Aceptada')->count() }})
                </button>
                <button onclick="filtrarInvitaciones('rechazadas')" class="tab-btn px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300">
                    Rechazadas ({{ $invitaciones->where('Estado', 'Rechazada')->count() }})
                </button>
            </div>

            <!-- Lista de invitaciones -->
            <div class="space-y-4">
                @foreach($invitaciones as $invitacion)
                    <div class="invitacion-card bg-white rounded-xl border border-gray-200 p-6 hover:shadow-md transition"
                         data-estado="{{ strtolower($invitacion->Estado) }}">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <!-- Header de la invitación -->
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                        <span class="text-2xl">👥</span>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">{{ $invitacion->equipo->Nombre }}</h3>
                                        <p class="text-sm text-gray-600">
                                            Invitado por <span class="font-medium">{{ $invitacion->invitadoPor->Nombre }}</span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Detalles de la invitación -->
                                <div class="ml-15 space-y-2">
                                    <div class="flex items-center gap-2 text-sm text-gray-700">
                                        <span class="font-medium">Rol asignado:</span>
                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                                            {{ $invitacion->perfil->Nombre }}
                                        </span>
                                    </div>

                                    @if($invitacion->Mensaje)
                                    <div class="bg-gray-50 rounded-lg p-3 mt-2">
                                        <p class="text-sm text-gray-600 italic">"{{ $invitacion->Mensaje }}"</p>
                                    </div>
                                    @endif

                                    <div class="flex items-center gap-2 text-xs text-gray-500 mt-2">
                                        <span>📅</span>
                                        <span>Recibida {{ \Carbon\Carbon::parse($invitacion->created_at)->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Estado y acciones -->
                            <div class="flex flex-col items-end gap-3">
                                <!-- Badge de estado -->
                                @if($invitacion->Estado == 'Pendiente')
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-medium rounded-full">
                                        ⏳ Pendiente
                                    </span>
                                @elseif($invitacion->Estado == 'Aceptada')
                                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">
                                        ✓ Aceptada
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-medium rounded-full">
                                        ✗ Rechazada
                                    </span>
                                @endif

                                <!-- Botones de acción -->
                                @if($invitacion->Estado == 'Pendiente')
                                    <div class="flex gap-2">
                                        <form method="POST" action="{{ route('invitaciones.aceptar', $invitacion->Id) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition">
                                                ✓ Aceptar
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('invitaciones.rechazar', $invitacion->Id) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition">
                                                ✗ Rechazar
                                            </button>
                                        </form>
                                    </div>
                                @elseif($invitacion->Estado == 'Aceptada')
                                    <a href="{{ route('equipos.show', $invitacion->Equipo_id) }}"
                                       class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition">
                                        Ver Equipo →
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Estado vacío -->
            <div class="bg-white border-2 border-gray-200 rounded-2xl p-16">
                <div class="flex flex-col items-center justify-center text-center max-w-2xl mx-auto">
                    <div class="mb-8">
                        <svg class="w-32 h-32 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">No tienes invitaciones</h3>
                    <p class="text-gray-600">Las invitaciones a equipos aparecerán aquí cuando las recibas</p>
                </div>
            </div>
        @endif
    </div>

    <script>
        function filtrarInvitaciones(filtro) {
            const invitaciones = document.querySelectorAll('.invitacion-card');
            const botones = document.querySelectorAll('.tab-btn');

            // Actualizar estilos de botones
            botones.forEach(btn => {
                btn.classList.remove('bg-gray-900', 'text-white');
                btn.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
            });
            event.target.classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
            event.target.classList.add('bg-gray-900', 'text-white');

            // Filtrar invitaciones
            invitaciones.forEach(card => {
                if (filtro === 'todas') {
                    card.style.display = 'block';
                } else if (filtro === card.dataset.estado) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
