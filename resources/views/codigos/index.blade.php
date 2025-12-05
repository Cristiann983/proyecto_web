<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevTeams - Gestión de Código</title>
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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard</h1>
        </div>

        <!-- Navegación de pestañas -->
        <div class="mb-8 bg-white rounded-full shadow-sm p-2 inline-flex gap-1 border border-gray-200">
            <a href="{{ route('dashboard') }}" class="px-6 py-2 rounded-full text-gray-600 hover:bg-gray-50 flex items-center gap-2">
                <span>👥</span>
                <span>Equipos</span>
            </a>
            <a href="{{ route('eventos.index') }}" class="px-6 py-2 rounded-full text-gray-600 hover:bg-gray-50 flex items-center gap-2">
                <span>📅</span>
                <span>Eventos</span>
            </a>
            <a href="{{ route('codigos.index') }}" class="px-6 py-2 rounded-full bg-gray-200 text-gray-900 font-medium flex items-center gap-2">
                <span>&lt;/&gt;</span>
                <span>Códigos</span>
            </a>
            <a href="{{ route('invitaciones.index') }}" class="px-6 py-2 rounded-full text-gray-600 hover:bg-gray-50 flex items-center gap-2">
                <span>✉️</span>
                <span>Invitaciones</span>
            </a>
        </div>

        <!-- Botón crear repositorio (arriba a la derecha) -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Gestión de Código</h2>
                <p class="text-gray-600">Repositorios, branches y commits de tus equipos</p>
            </div>
            <button class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-3 rounded-lg flex items-center gap-2 font-medium transition">
                <span>+</span>
                <span>Crear repositorio</span>
            </button>
        </div>

        <!-- Estado vacío -->
        <div class="bg-white border-2 border-gray-200 rounded-2xl p-16">
            <div class="flex flex-col items-center justify-center text-center max-w-2xl mx-auto">
                <!-- Icono de código -->
                <div class="mb-8">
                    <svg class="w-32 h-32 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                    </svg>
                </div>

                <!-- Mensaje -->
                <h3 class="text-xl font-bold text-gray-900 mb-3">No hay resppositorios</h3>
                <p class="text-gray-600 mb-8">Crea tu primer repositorio para comenzar a gestionar código</p>

                <!-- Botón -->
                <button class="bg-gray-800 hover:bg-gray-900 text-white px-8 py-3 rounded-lg flex items-center gap-2 font-medium transition">
                    <span>+</span>
                    <span>Crea tu primer repositorio</span>
                </button>
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