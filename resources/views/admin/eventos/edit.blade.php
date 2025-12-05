<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevTeams - Editar Evento</title>
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
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Editar Evento</h1>
            <p class="text-gray-600">Modifica la información del evento</p>
        </div>

        <!-- Formulario -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <form class="space-y-6">
                <!-- Título del evento -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Título del evento</label>
                    <input 
                        type="text" 
                        value="Eventos y Retos"
                        placeholder="Ingrese el título del evento"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    >
                </div>

                <!-- Descripción -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                    <textarea 
                        placeholder="Describe el evento"
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    >Desarrolla aplicaciones descentralizadas en 48 horas</textarea>
                </div>

                <!-- Grid de campos -->
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Número de equipos -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Número máximo de equipos</label>
                        <input 
                            type="number" 
                            value="100"
                            min="1"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        >
                    </div>

                    <!-- Dificultad -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nivel de dificultad</label>
                        <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="principiante">Principiante</option>
                            <option value="intermedio" selected>Intermedio</option>
                            <option value="avanzado">Avanzado</option>
                            <option value="experto">Experto</option>
                        </select>
                    </div>
                </div>

                <!-- Fechas -->
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de inicio</label>
                        <input 
                            type="date" 
                            value="2024-12-14"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de fin</label>
                        <input 
                            type="date" 
                            value="2024-12-16"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        >
                    </div>
                </div>

                <!-- Hora -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Hora de inicio</label>
                    <input 
                        type="time" 
                        value="09:00"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    >
                </div>

                <!-- Tecnologías -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tecnologías requeridas</label>
                    <select 
                        multiple 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        size="6"
                    >
                        <option value="react" selected>React</option>
                        <option value="vue">Vue.js</option>
                        <option value="angular">Angular</option>
                        <option value="nodejs">Node.js</option>
                        <option value="python">Python</option>
                        <option value="java">Java</option>
                        <option value="solidity" selected>Solidity</option>
                        <option value="web3" selected>Web3.js</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-2">Mantén presionado Ctrl (Cmd en Mac) para seleccionar múltiples tecnologías</p>
                </div>

                <!-- Estado del evento -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado del evento</label>
                    <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="active" selected>Activo</option>
                        <option value="upcoming">Próximamente</option>
                        <option value="finished">Finalizado</option>
                        <option value="cancelled">Cancelado</option>
                    </select>
                </div>

                <!-- Botones de acción -->
                <div class="flex gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.eventos.index') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-3 rounded-lg font-medium transition text-center">
                        Cancelar
                    </a>
                    <button 
                        type="submit"
                        class="flex-1 bg-purple-600 hover:bg-purple-700 text-white py-3 rounded-lg font-medium transition flex items-center justify-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Guardar cambios
                    </button>
                </div>
            </form>
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