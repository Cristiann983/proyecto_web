<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Error del Servidor | DevTeams</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-purple-50 to-blue-50 min-h-screen flex items-center justify-center p-4">
    <div class="text-center max-w-lg">
        <!-- Logo -->
        <div class="flex items-center justify-center gap-2 mb-8">
            <div class="text-4xl text-purple-600">&lt;/&gt;</div>
            <span class="text-2xl font-bold text-gray-900">DevTeams</span>
        </div>

        <!-- Ilustración -->
        <div class="relative mb-8">
            <div class="w-48 h-48 mx-auto bg-gradient-to-br from-red-100 to-pink-100 rounded-full flex items-center justify-center animate-pulse">
                <div class="text-center">
                    <span class="text-8xl">⚙️</span>
                </div>
            </div>
            <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 bg-red-600 text-white px-6 py-2 rounded-full font-bold text-lg shadow-lg">
                500
            </div>
        </div>

        <!-- Mensaje -->
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Error del Servidor</h1>
        <p class="text-gray-600 mb-8 text-lg">
            Algo salió mal en nuestro servidor. Nuestro equipo ya está trabajando para solucionarlo.
        </p>

        <!-- Mensaje técnico -->
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-8 text-left">
            <div class="flex items-start gap-3">
                <span class="text-red-500 text-xl">⚠️</span>
                <div>
                    <p class="text-red-700 font-medium">Error interno del servidor</p>
                    <p class="text-red-600 text-sm mt-1">
                        Por favor intenta de nuevo en unos minutos. Si el problema persiste, contacta al soporte.
                    </p>
                </div>
            </div>
        </div>

        <!-- Botones -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ url('/') }}" class="px-8 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium transition flex items-center justify-center gap-2">
                <span>🏠</span>
                <span>Ir al Inicio</span>
            </a>
            <button onclick="location.reload()" class="px-8 py-3 border-2 border-gray-300 hover:border-gray-400 text-gray-700 rounded-lg font-medium transition flex items-center justify-center gap-2">
                <span>🔄</span>
                <span>Reintentar</span>
            </button>
        </div>

        <!-- Footer -->
        <p class="mt-12 text-sm text-gray-400">
            © {{ date('Y') }} DevTeams. Todos los derechos reservados.
        </p>
    </div>
</body>
</html>
