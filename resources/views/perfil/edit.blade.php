<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Editar Perfil - DevTeams</title>
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
                    <a href="{{ route('perfil.show') }}" class="text-gray-600 hover:text-gray-900">
                        Cancelar
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
            <h1 class="text-3xl font-bold text-gray-900">Editar Perfil</h1>
            <p class="text-gray-600">Actualiza tu información personal</p>
        </div>

        <!-- Formulario de Edición -->
        <div class="bg-white rounded-2xl border border-gray-200 p-8">
            <form method="POST" action="{{ route('perfil.update') }}">
                @csrf
                @method('PUT')

                <!-- Nombre -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre Completo <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="{{ old('name', $user->name) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('name') border-red-500 @enderror"
                        required
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Correo Electrónico <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email', $user->email) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('email') border-red-500 @enderror"
                        required
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Teléfono -->
                <div class="mb-6">
                    <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">
                        Teléfono
                    </label>
                    <input
                        type="text"
                        name="telefono"
                        id="telefono"
                        value="{{ old('telefono', $participante ? $participante->telefono : ($juez ? $juez->telefono : '')) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('telefono') border-red-500 @enderror"
                        placeholder="Ej: 1234567890"
                    >
                    @error('telefono')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Separador -->
                <div class="border-t border-gray-200 my-8"></div>

                <!-- Sección de Contraseña -->
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Cambiar Contraseña</h3>
                    <p class="text-sm text-gray-600 mb-4">Deja estos campos en blanco si no deseas cambiar tu contraseña</p>

                    <!-- Nueva Contraseña -->
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Nueva Contraseña
                        </label>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('password') border-red-500 @enderror"
                            placeholder="Mínimo 8 caracteres"
                        >
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirmar Nueva Contraseña
                        </label>
                        <input
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            placeholder="Repite la contraseña"
                        >
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="flex gap-4 justify-end">
                    <a href="{{ route('perfil.show') }}"
                       class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">
                        Cancelar
                    </a>
                    <button
                        type="submit"
                        class="bg-gray-900 hover:bg-gray-800 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2"
                    >
                        <span>💾</span>
                        <span>Guardar Cambios</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Información de Ayuda -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex gap-3">
                <div class="text-blue-600 text-xl">ℹ️</div>
                <div class="text-sm text-blue-800">
                    <p class="font-medium mb-1">Información importante:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Los campos marcados con <span class="text-red-500">*</span> son obligatorios</li>
                        <li>Si cambias tu correo electrónico, úsalo para iniciar sesión la próxima vez</li>
                        <li>La contraseña debe tener al menos 8 caracteres</li>
                        @if($participante && $participante->No_Control)
                        <li>El número de control no puede ser modificado. Contacta al administrador si es necesario</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
