<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Página No Encontrada | DevTeams</title>
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
            <div class="w-48 h-48 mx-auto bg-gradient-to-br from-purple-100 to-blue-100 rounded-full flex items-center justify-center">
                <div class="text-center">
                    <span class="text-8xl">🔍</span>
                </div>
            </div>
            <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 bg-purple-600 text-white px-6 py-2 rounded-full font-bold text-lg shadow-lg">
                404
            </div>
        </div>

        <!-- Mensaje -->
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Página No Encontrada</h1>
        <p class="text-gray-600 mb-8 text-lg">
            ¡Ups! La página que buscas no existe o fue movida a otra ubicación.
        </p>

        <!-- Código divertido -->
        <div class="bg-gray-900 rounded-lg p-4 mb-8 text-left max-w-xs mx-auto">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                <div class="w-3 h-3 rounded-full bg-green-500"></div>
            </div>
            <code class="text-green-400 text-sm">
                <span class="text-purple-400">if</span> (page.<span class="text-blue-400">exists</span>()) {<br>
                &nbsp;&nbsp;<span class="text-gray-500">// TODO: show page</span><br>
                } <span class="text-purple-400">else</span> {<br>
                &nbsp;&nbsp;<span class="text-yellow-400">return</span> <span class="text-orange-400">"404"</span>;<br>
                }
            </code>
        </div>

        <!-- Botones -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ url('/') }}" class="px-8 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium transition flex items-center justify-center gap-2">
                <span>🏠</span>
                <span>Ir al Inicio</span>
            </a>
            <button onclick="history.back()" class="px-8 py-3 border-2 border-gray-300 hover:border-gray-400 text-gray-700 rounded-lg font-medium transition flex items-center justify-center gap-2">
                <span>←</span>
                <span>Volver Atrás</span>
            </button>
        </div>

        <!-- Footer -->
        <p class="mt-12 text-sm text-gray-400">
            © {{ date('Y') }} DevTeams. Todos los derechos reservados.
        </p>
    </div>
</body>
</html>
