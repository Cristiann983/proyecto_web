<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DevTeams - Editar Usuario</title>
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
            <p class="text-gray-600">Gestiona equipos, eventos, jueces y usuarios del sistema</p>
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
               class="px-8 py-3 rounded-full text-gray-600 hover:bg-white flex items-center gap-2 transition">
                <span>⚖️</span>
                <span>Jueces</span>
            </a>
            <a href="{{ route('admin.usuarios.index') }}"
               class="px-8 py-3 rounded-full bg-white text-gray-900 font-medium shadow-sm flex items-center gap-2">
                <span>👤</span>
                <span>Usuarios</span>
            </a>
        </div>

        <!-- Breadcrumb -->
        <div class="mb-6 flex items-center gap-2 text-sm text-gray-600">
            <a href="{{ route('admin.usuarios.index') }}" class="hover:text-purple-600">Usuarios</a>
            <span>/</span>
            <span class="text-gray-900 font-medium">Editar Usuario</span>
        </div>

        <!-- Título de sección -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Editar Usuario</h2>
            <p class="text-gray-600">Modifica la información del usuario</p>
        </div>

        <!-- Mensajes de error -->
        @include('partials._alerts')

        <!-- Formulario -->
        <form method="POST" action="{{ route('admin.usuarios.update', $usuario->id) }}" class="bg-white rounded-xl shadow-lg p-8" id="userForm">
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
                            value="{{ old('nombre', $usuario->name) }}"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('nombre') border-red-500 @enderror"
                            placeholder="Ej: Juan Pérez García"
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
                            value="{{ old('email', $usuario->email) }}"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('email') border-red-500 @enderror"
                            placeholder="usuario@ejemplo.com"
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
                            value="{{ old('telefono', $usuario->participante->telefono ?? $usuario->juez->telefono ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            placeholder="+52 123 456 7890"
                        >
                    </div>

                    <!-- Rol -->
                    <div class="md:col-span-2">
                        <label for="rol_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Rol <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="rol_id"
                            name="rol_id"
                            required
                            onchange="toggleRoleFields()"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('rol_id') border-red-500 @enderror"
                        >
                            <option value="">-- Selecciona un rol --</option>
                            @foreach($roles as $rol)
                                <option value="{{ $rol->Id }}" {{ old('rol_id', $usuario->roles->first()->Id ?? '') == $rol->Id ? 'selected' : '' }}>
                                    {{ $rol->Nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('rol_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Estado Activo -->
                    <div class="md:col-span-2">
                        <label class="flex items-center gap-2">
                            <input
                                type="checkbox"
                                name="is_active"
                                {{ old('is_active', $usuario->is_active) ? 'checked' : '' }}
                                class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500"
                            >
                            <span class="text-sm font-medium text-gray-700">Usuario activo</span>
                        </label>
                        <p class="text-xs text-gray-500 mt-1 ml-6">Si está desactivado, el usuario no podrá iniciar sesión</p>
                    </div>
                </div>
            </div>

            <!-- Sección: Campos específicos por rol -->
            <!-- Campos para Participante -->
            <div id="participante-fields" class="mb-8 hidden">
                <h2 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                    🎓 Información de Participante
                </h2>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label for="no_control" class="block text-sm font-medium text-gray-700 mb-2">
                            Número de Control <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="no_control"
                            name="no_control"
                            value="{{ old('no_control', $usuario->participante->No_Control ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            placeholder="Ej: 12345678"
                        >
                    </div>

                    <div>
                        <label for="carrera_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Carrera <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="carrera_id"
                            name="carrera_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        >
                            <option value="">-- Selecciona una carrera --</option>
                            @foreach($carreras as $carrera)
                                <option value="{{ $carrera->Id }}" {{ old('carrera_id', $usuario->participante->Carrera_id ?? '') == $carrera->Id ? 'selected' : '' }}>
                                    {{ $carrera->Nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Campos para Juez -->
            <div id="juez-fields" class="mb-8 hidden">
                <h2 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                    ⚖️ Información de Juez
                </h2>

                <div>
                    <label for="especialidad_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Especialidad <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="especialidad_id"
                        name="especialidad_id"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    >
                        <option value="">-- Selecciona una especialidad --</option>
                        @foreach($especialidades as $especialidad)
                            <option value="{{ $especialidad->Id }}" {{ old('especialidad_id', $usuario->juez->Id_especialidad ?? '') == $especialidad->Id ? 'selected' : '' }}>
                                {{ $especialidad->Nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Sección: Credenciales de Acceso -->
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                    🔐 Credenciales de Acceso
                </h2>

                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Contraseña -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Nueva Contraseña (dejar en blanco para mantener la actual)
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
                        <p class="mt-1 text-xs text-gray-500">Deja en blanco para mantener la contraseña actual</p>
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

            <!-- Botones de acción -->
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.usuarios.index') }}"
                   class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-6 py-3 bg-gray-900 hover:bg-gray-800 text-white rounded-lg font-medium transition">
                    ✓ Actualizar Usuario
                </button>
            </div>
        </form>
    </div>

    <script>
        function toggleRoleFields() {
            const rolSelect = document.getElementById('rol_id');
            const rolText = rolSelect.options[rolSelect.selectedIndex].text;
            const participanteFields = document.getElementById('participante-fields');
            const juezFields = document.getElementById('juez-fields');

            // Ocultar todos primero
            participanteFields.classList.add('hidden');
            juezFields.classList.add('hidden');

            // Mostrar según el rol seleccionado
            if (rolText === 'Participante') {
                participanteFields.classList.remove('hidden');
                document.getElementById('no_control').setAttribute('required', 'required');
                document.getElementById('carrera_id').setAttribute('required', 'required');
                document.getElementById('especialidad_id').removeAttribute('required');
            } else if (rolText === 'Juez') {
                juezFields.classList.remove('hidden');
                document.getElementById('especialidad_id').setAttribute('required', 'required');
                document.getElementById('no_control').removeAttribute('required');
                document.getElementById('carrera_id').removeAttribute('required');
            } else {
                document.getElementById('no_control').removeAttribute('required');
                document.getElementById('carrera_id').removeAttribute('required');
                document.getElementById('especialidad_id').removeAttribute('required');
            }
        }

        // Ejecutar al cargar la página por si hay un valor pre-seleccionado
        document.addEventListener('DOMContentLoaded', toggleRoleFields);
    </script>
</body>
</html>
