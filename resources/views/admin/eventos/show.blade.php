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
                        <span>hackathon</span>
                    </span>
                    <span class="bg-green-500/90 text-white text-sm px-4 py-1 rounded-full flex items-center gap-2">
                        <span>●</span>
                        <span>Active</span>
                    </span>
                </div>
                <h2 class="text-3xl font-bold mb-3">Eventos y Retos</h2>
                <p class="text-lg opacity-90">Desarrolla aplicaciones descentralizadas en 48 horas</p>
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
                                <p class="text-lg font-bold text-gray-900">14 de diciembre</p>
                                <p class="text-sm text-gray-600">de 2024</p>
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
                                <p class="text-lg font-bold text-gray-900">16 de diciembre</p>
                                <p class="text-sm text-gray-600">de 2024</p>
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
                                <p class="text-lg font-bold text-gray-900">0 / 100</p>
                                <p class="text-sm text-gray-600">Inscritos</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dificultad -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Nivel de Dificultad</h3>
                    <div class="flex items-center gap-2">
                        <span class="bg-orange-100 text-orange-600 px-4 py-2 rounded-lg font-medium">
                            Intermedio
                        </span>
                    </div>
                </div>

                <!-- Tecnologías -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Tecnologías Requeridas</h3>
                    <div class="flex flex-wrap gap-2">
                        <span class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-medium">Solidity</span>
                        <span class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-medium">React</span>
                        <span class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-medium">Web3.js</span>
                    </div>
                </div>

                <!-- Descripción completa -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Descripción Completa</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700 leading-relaxed">
                            Desarrolla aplicaciones descentralizadas en 48 horas. Este evento está diseñado para desarrolladores que quieran explorar el mundo de blockchain y crear soluciones innovadoras utilizando tecnologías como Solidity, React y Web3.js.
                        </p>
                    </div>
                </div>

                <!-- Información adicional -->
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Hora de inicio</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-900 font-medium">09:00 AM</p>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Duración estimada</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-900 font-medium">48 horas</p>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.eventos.edit', 1) }}" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white py-3 rounded-lg font-medium transition text-center">
                        Editar evento
                    </a>
                    <a href="{{ route('admin.equipos.index') }}" class="flex-1 bg-gray-800 hover:bg-gray-900 text-white py-3 rounded-lg font-medium transition text-center">
                        Ver equipos inscritos
                    </a>
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