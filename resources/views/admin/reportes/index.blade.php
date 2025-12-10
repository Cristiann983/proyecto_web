<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevTeams - Reportes</title>
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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Panel de Administración</h1>
            <p class="text-gray-600">Genera reportes en PDF del sistema</p>
        </div>

        <!-- Navegación de pestañas -->
        <div class="mb-8 bg-gray-100 rounded-full p-1 inline-flex gap-1">
            <a href="{{ route('admin.equipos.index') }}" class="px-8 py-3 rounded-full text-gray-600 hover:bg-white flex items-center gap-2 transition">
                <span>👥</span>
                <span>Equipos</span>
            </a>
            <a href="{{ route('admin.eventos.index') }}" class="px-8 py-3 rounded-full text-gray-600 hover:bg-white flex items-center gap-2 transition">
                <span>📅</span>
                <span>Eventos</span>
            </a>
            <a href="{{ route('admin.jueces.list') }}" class="px-8 py-3 rounded-full text-gray-600 hover:bg-white flex items-center gap-2 transition">
                <span>⚖️</span>
                <span>Jueces</span>
            </a>
            <a href="{{ route('admin.usuarios.index') }}" class="px-8 py-3 rounded-full text-gray-600 hover:bg-white flex items-center gap-2 transition">
                <span>👤</span>
                <span>Usuarios</span>
            </a>
            <a href="{{ route('admin.carreras.index') }}" class="px-8 py-3 rounded-full text-gray-600 hover:bg-white flex items-center gap-2 transition">
                <span>🎓</span>
                <span>Carreras</span>
            </a>
            <a href="{{ route('admin.reportes.index') }}" class="px-8 py-3 rounded-full bg-white text-gray-900 font-medium shadow-sm flex items-center gap-2">
                <span>📊</span>
                <span>Reportes</span>
            </a>
        </div>

        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Reportes en PDF</h2>
            <p class="text-gray-600">Selecciona el tipo de reporte que deseas generar</p>
        </div>

        <!-- Grid de reportes -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Reporte de Usuarios -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                    <span class="text-2xl">👥</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Reporte de Usuarios</h3>
                <p class="text-sm text-gray-600 mb-4">Listado completo de todos los usuarios con sus roles, email y estado de cuenta</p>
                <a href="{{ route('admin.reportes.usuarios') }}" class="block w-full py-2.5 bg-purple-600 text-white text-center rounded-lg hover:bg-purple-700 transition font-medium">
                    📄 Generar PDF
                </a>
            </div>

            <!-- Reporte de Jueces -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mb-4">
                    <span class="text-2xl">⚖️</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Reporte de Jueces</h3>
                <p class="text-sm text-gray-600 mb-4">Información detallada de jueces, especialidades, eventos asignados y calificaciones</p>
                <a href="{{ route('admin.reportes.jueces') }}" class="block w-full py-2.5 bg-orange-600 text-white text-center rounded-lg hover:bg-orange-700 transition font-medium">
                    📄 Generar PDF
                </a>
            </div>

            <!-- Reporte de Eventos -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                    <span class="text-2xl">📅</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Reporte de Eventos</h3>
                <p class="text-sm text-gray-600 mb-4">Listado de eventos con fechas, equipos inscritos, jueces y estado actual</p>
                <a href="{{ route('admin.reportes.eventos') }}" class="block w-full py-2.5 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700 transition font-medium">
                    📄 Generar PDF
                </a>
            </div>

            <!-- Reporte de Equipos -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                    <span class="text-2xl">👨‍👩‍👧‍👦</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Reporte de Equipos</h3>
                <p class="text-sm text-gray-600 mb-4">Información de equipos, miembros, proyectos y asesores asignados</p>
                <a href="{{ route('admin.reportes.equipos') }}" class="block w-full py-2.5 bg-green-600 text-white text-center rounded-lg hover:bg-green-700 transition font-medium">
                    📄 Generar PDF
                </a>
            </div>

            <!-- Reporte de Estadísticas -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-4">
                    <span class="text-2xl">📊</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Estadísticas Generales</h3>
                <p class="text-sm text-gray-600 mb-4">Resumen estadístico completo del sistema con métricas clave y distribuciones</p>
                <a href="{{ route('admin.reportes.estadisticas') }}" class="block w-full py-2.5 bg-red-600 text-white text-center rounded-lg hover:bg-red-700 transition font-medium">
                    📄 Generar PDF
                </a>
            </div>
        </div>
    </div>
</body>
</html>
