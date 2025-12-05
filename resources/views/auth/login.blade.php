<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevTeams - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-purple-600 to-blue-500 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-6xl grid md:grid-cols-2 gap-0 bg-white rounded-2xl shadow-2xl overflow-hidden">
        <!-- Panel izquierdo -->
        <div class="bg-gradient-to-br from-purple-600 to-blue-500 p-12 text-white flex flex-col justify-center">
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="text-4xl">&lt;/&gt;</div>
                    <h1 class="text-3xl font-bold">DevTeams</h1>
                </div>
                <p class="text-xl opacity-90">✨ Plataforma de Desarrollo</p>
            </div>
            
            <div class="space-y-6">
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6 border border-white/20">
                    <h3 class="font-semibold mb-2 flex items-center gap-2">
                        <span>👥</span>
                        Gestión de Equipos
                    </h3>
                    <p class="text-sm opacity-90">Crea y administra equipos de desarrollo con roles especializados: líder, diseñador, frontend y backend</p>
                </div>
                
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6 border border-white/20">
                    <h3 class="font-semibold mb-2 flex items-center gap-2">
                        <span>📅</span>
                        Eventos y Retos
                    </h3>
                    <p class="text-sm opacity-90">Participa en hackathons, concursos y desafíos de programación con tu equipo</p>
                </div>
                
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6 border border-white/20">
                    <h3 class="font-semibold mb-2 flex items-center gap-2">
                        <span>⚡</span>
                        Sistema de Código
                    </h3>
                    <p class="text-sm opacity-90">Gestiona repositorios, branches y commits con colaboración en tiempo real</p>
                </div>
            </div>
        </div>

        <!-- Panel derecho - Formulario -->
        <div class="p-12 flex flex-col justify-center">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">BIENVENIDO DE VUELTA</h2>
                <p class="text-gray-600">Ingresa tus credenciales para acceder a tu cuenta</p>
            </div>

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Correo electrónico</label>
                    <input 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        placeholder="Ingresa tu correo electrónico"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contraseña</label>
                    <input 
                        type="password" 
                        name="password" 
                        required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        placeholder="Ingresa una contraseña válida"
                    >
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 rounded-lg transition duration-200 flex items-center justify-center gap-2"
                >
                    <span>👤</span> Iniciar Sesion
                </button>
            </form>

            <!-- Link a registro -->
            <div class="text-center mb-4">
                <p class="text-sm text-gray-600">
                    ¿No tienes una cuenta? 
                    <a href="{{ route('register') }}" class="text-purple-600 hover:text-purple-700 font-medium">
                        Regístrate aquí
                    </a>
                </p>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200 text-center text-sm text-gray-600">
                <p>Acerca de <span class="mx-2">|</span> Acerca de <span class="mx-2">|</span> Acerca de</p>
            </div>
        </div>
    </div>

    <div class="fixed bottom-8 right-8 flex flex-col items-center">
        <div class="bg-white p-3 rounded-full shadow-lg">
            <div class="text-2xl text-purple-600">&lt;/&gt;</div>
        </div>
        <p class="text-white text-xs mt-2">DevTeams</p>
        <p class="text-white text-xs">✨ Plataforma de Desarrollo</p>
    </div>
</body>
</html>