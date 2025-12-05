<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mi Perfil - DevTeams</title>
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

                <div class="flex items-center gap-4">
                    @php
                        $user = Auth::user();
                        if ($user->hasRole('Administrador')) {
                            $dashboardRoute = route('admin.eventos.index');
                        } elseif ($user->hasRole('Juez')) {
                            $dashboardRoute = route('admin.jueces.index');
                        } else {
                            $dashboardRoute = route('dashboard');
                        }
                    @endphp
                    <a href="{{ $dashboardRoute }}" class="text-gray-600 hover:text-gray-900">
                        Volver al Dashboard
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
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Encabezado -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Mi Perfil</h1>
            <p class="text-gray-600">Visualiza y edita tu información personal</p>
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

        <!-- Card de Perfil -->
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <!-- Header del Card -->
            <div class="bg-gradient-to-r from-purple-600 to-blue-600 px-8 py-6">
                <div class="flex items-center gap-4">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center text-3xl font-bold text-purple-600">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="text-white">
                        <h2 class="text-2xl font-bold">{{ $user->name }}</h2>
                        <p class="text-purple-100">{{ $user->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Información del Perfil -->
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Información Personal</h3>
                    <a href="{{ route('perfil.edit') }}"
                       class="bg-gray-900 hover:bg-gray-800 text-white px-5 py-2.5 rounded-lg font-medium flex items-center gap-2">
                        <span>✏️</span>
                        <span>Editar Perfil</span>
                    </a>
                </div>

                <div class="space-y-6">
                    <!-- Nombre -->
                    <div class="border-b border-gray-200 pb-4">
                        <label class="text-sm font-medium text-gray-500 block mb-1">Nombre Completo</label>
                        <p class="text-lg text-gray-900">{{ $user->name }}</p>
                    </div>

                    <!-- Email -->
                    <div class="border-b border-gray-200 pb-4">
                        <label class="text-sm font-medium text-gray-500 block mb-1">Correo Electrónico</label>
                        <p class="text-lg text-gray-900">{{ $user->email }}</p>
                    </div>

                    <!-- Información específica según el rol -->
                    @if($participante)
                        <!-- No. Control -->
                        @if($participante->No_Control)
                        <div class="border-b border-gray-200 pb-4">
                            <label class="text-sm font-medium text-gray-500 block mb-1">Número de Control</label>
                            <p class="text-lg text-gray-900">{{ $participante->No_Control }}</p>
                        </div>
                        @endif

                        <!-- Teléfono -->
                        <div class="border-b border-gray-200 pb-4">
                            <label class="text-sm font-medium text-gray-500 block mb-1">Teléfono</label>
                            <p class="text-lg text-gray-900">{{ $participante->telefono ?? 'No especificado' }}</p>
                        </div>

                        <!-- Carrera -->
                        @if($participante->carrera)
                        <div class="border-b border-gray-200 pb-4">
                            <label class="text-sm font-medium text-gray-500 block mb-1">Carrera</label>
                            <p class="text-lg text-gray-900">{{ $participante->carrera->Nombre }}</p>
                        </div>
                        @endif
                    @elseif($juez)
                        <!-- Teléfono -->
                        <div class="border-b border-gray-200 pb-4">
                            <label class="text-sm font-medium text-gray-500 block mb-1">Teléfono</label>
                            <p class="text-lg text-gray-900">{{ $juez->telefono ?? 'No especificado' }}</p>
                        </div>

                        <!-- Especialidad -->
                        @if($juez->especialidad)
                        <div class="border-b border-gray-200 pb-4">
                            <label class="text-sm font-medium text-gray-500 block mb-1">Especialidad</label>
                            <p class="text-lg text-gray-900">{{ $juez->especialidad->Nombre }}</p>
                        </div>
                        @endif
                    @else
                        <!-- Teléfono genérico para administradores -->
                        <div class="border-b border-gray-200 pb-4">
                            <label class="text-sm font-medium text-gray-500 block mb-1">Teléfono</label>
                            <p class="text-lg text-gray-900">No especificado</p>
                        </div>
                    @endif

                    <!-- Rol -->
                    <div class="border-b border-gray-200 pb-4">
                        <label class="text-sm font-medium text-gray-500 block mb-1">Rol</label>
                        <div class="flex gap-2">
                            @foreach($user->roles as $role)
                                <span class="px-3 py-1 bg-purple-100 text-purple-700 text-sm font-medium rounded-full">
                                    {{ $role->Nombre }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Estado de la cuenta -->
                    <div class="pb-4">
                        <label class="text-sm font-medium text-gray-500 block mb-1">Estado de la Cuenta</label>
                        <span class="inline-flex items-center gap-2 px-3 py-1 {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} text-sm font-medium rounded-full">
                            <span>{{ $user->is_active ? '✓' : '✗' }}</span>
                            <span>{{ $user->is_active ? 'Activa' : 'Inactiva' }}</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas (opcional) -->
        @if($participante && $participante->equipos->count() > 0)
        <div class="mt-6 bg-white rounded-2xl border border-gray-200 p-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Mis Equipos</h3>
            <div class="grid md:grid-cols-2 gap-4">
                @foreach($participante->equipos as $equipo)
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-purple-300 transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-bold text-gray-900">{{ $equipo->Nombre }}</h4>
                                @if($equipo->pivot && $equipo->pivot->Id_perfil)
                                    <p class="text-sm text-gray-600">
                                        Perfil: {{ $equipo->pivot->perfil->Nombre ?? 'Sin especificar' }}
                                    </p>
                                @endif
                            </div>
                            <a href="{{ route('equipos.show', $equipo->Id) }}"
                               class="text-purple-600 hover:text-purple-700 font-medium">
                                Ver →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</body>
</html>
