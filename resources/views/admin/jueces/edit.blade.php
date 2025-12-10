<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DevTeams - Editar Juez</title>
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
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Encabezado -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Panel de Administración</h1>
            <p class="text-gray-600">Gestiona equipos, eventos y jueces del sistema</p>
        </div>

        <!-- Navegación de pestañas -->
        <div class="mb-8 bg-gray-100 rounded-full p-1 inline-flex gap-1">
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
               class="px-8 py-3 rounded-full bg-white text-gray-900 font-medium shadow-sm flex items-center gap-2">
                <span>⚖️</span>
                <span>Jueces</span>
            </a>
        </div>

        <!-- Breadcrumb -->
        <div class="mb-6 flex items-center gap-2 text-sm text-gray-600">
            <a href="{{ route('admin.jueces.list') }}" class="hover:text-purple-600">Jueces</a>
            <span>/</span>
            <a href="{{ route('admin.jueces.show', $juez->Id) }}" class="hover:text-purple-600">{{ $juez->Nombre }}</a>
            <span>/</span>
            <span class="text-gray-900 font-medium">Editar</span>
        </div>

        <!-- Título de sección -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Editar Juez</h2>
            <p class="text-gray-600">Actualiza la información del juez y sus eventos asignados</p>
        </div>

        <!-- Mensajes de error -->
        @include('partials._alerts')

        <!-- Formulario -->
        <form method="POST" action="{{ route('admin.jueces.update', $juez->Id) }}" class="bg-white rounded-xl shadow-lg p-8">
            @csrf
            @method('PUT')

            <!-- Sección: Información Personal -->
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                    📋 Información Personal
                </h2>

                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Nombre completo -->
                    <div class="md:col-span-2">
                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre Completo <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="nombre"
                            name="nombre"
                            value="{{ old('nombre', $juez->Nombre) }}"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('nombre') border-red-500 @enderror"
                            placeholder="Ej: Dr. Juan Pérez García"
                        >
                        @error('nombre')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Correo Electrónico <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $juez->Correo) }}"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('email') border-red-500 @enderror"
                            placeholder="juez@ejemplo.com"
                        >
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">
                            Teléfono (Opcional)
                        </label>
                        <input
                            type="tel"
                            id="telefono"
                            name="telefono"
                            value="{{ old('telefono', $juez->telefono) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            placeholder="+52 123 456 7890"
                        >
                    </div>

                    <!-- Especialidad -->
                    <div class="md:col-span-2">
                        <label for="especialidad_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Especialidad <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="especialidad_id"
                            name="especialidad_id"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('especialidad_id') border-red-500 @enderror"
                        >
                            <option value="">-- Selecciona una especialidad --</option>
                            @foreach($especialidades as $especialidad)
                                <option value="{{ $especialidad->Id }}"
                                    {{ old('especialidad_id', $juez->Id_especialidad) == $especialidad->Id ? 'selected' : '' }}>
                                    {{ $especialidad->Nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('especialidad_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Sección: Cambiar Contraseña (Opcional) -->
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                    🔐 Cambiar Contraseña (Opcional)
                </h2>

                <p class="text-sm text-gray-600 mb-4">
                    Deja estos campos en blanco si no deseas cambiar la contraseña
                </p>

                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Contraseña -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Nueva Contraseña
                        </label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('password') border-red-500 @enderror"
                            placeholder="Mínimo 8 caracteres"
                        >
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">La contraseña debe tener al menos 8 caracteres</p>
                    </div>

                    <!-- Confirmar contraseña -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirmar Nueva Contraseña
                        </label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            placeholder="Repite la contraseña"
                        >
                    </div>
                </div>
            </div>

            <!-- Sección: Asignación de Eventos -->
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                    📅 Asignación de Eventos
                </h2>

                <p class="text-sm text-gray-600 mb-4">
                    Selecciona los eventos en los que este juez participará. Los eventos actualmente asignados están marcados.
                </p>

                @php
                    $eventosAsignadosIds = $juez->eventos->pluck('Id')->toArray();
                @endphp

                @if($eventos->count() > 0)
                    <div class="grid md:grid-cols-2 gap-4">
                        @foreach($eventos as $evento)
                            @php
                                $estaAsignado = in_array($evento->Id, $eventosAsignadosIds);
                            @endphp
                            <label class="flex items-start gap-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition {{ $estaAsignado ? 'bg-blue-50 border-blue-300' : '' }}">
                                <input
                                    type="checkbox"
                                    name="eventos[]"
                                    value="{{ $evento->Id }}"
                                    {{ in_array($evento->Id, old('eventos', $eventosAsignadosIds)) ? 'checked' : '' }}
                                    class="mt-1 w-4 h-4 text-purple-600 rounded focus:ring-purple-500"
                                >
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">{{ $evento->Nombre }}</p>
                                    <p class="text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($evento->Fecha_inicio)->format('d M Y') }} -
                                        {{ \Carbon\Carbon::parse($evento->Fecha_fin)->format('d M Y') }}
                                    </p>
                                    <div class="flex items-center gap-2 mt-2">
                                        @if($evento->Categoria)
                                            <span class="inline-block text-xs px-2 py-1 bg-purple-100 text-purple-700 rounded-full">
                                                {{ $evento->Categoria }}
                                            </span>
                                        @endif
                                        @if($estaAsignado)
                                            <span class="inline-block text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded-full">
                                                ✓ Asignado actualmente
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                @else
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 text-center">
                        <p class="text-gray-600">No hay eventos disponibles en este momento.</p>
                    </div>
                @endif
            </div>

            <!-- Botones de acción -->
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.jueces.show', $juez->Id) }}"
                   class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-6 py-3 bg-gray-900 hover:bg-gray-800 text-white rounded-lg font-medium transition">
                    ✓ Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</body>
</html>
