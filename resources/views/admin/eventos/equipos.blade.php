<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipos Inscritos - {{ $evento->Nombre }}</title>
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
                
                <div class="flex items-center">
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

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Botón volver -->
        <a href="{{ route('admin.eventos.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 mb-6">
            <span>←</span>
            <span>Volver a eventos</span>
        </a>

        <!-- Encabezado del evento -->
        <div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-2xl p-8 mb-8 text-white">
            <div class="flex items-center gap-2 mb-2">
                <span class="px-3 py-1 bg-white bg-opacity-20 text-white text-xs font-medium rounded-full">
                    {{ $evento->estadoLabel['texto'] }}
                </span>
            </div>
            <h1 class="text-3xl font-bold mb-2">{{ $evento->Nombre }}</h1>
            <p class="text-white text-opacity-90 mb-4">{{ $evento->Descripcion }}</p>
            <div class="flex gap-6 text-sm">
                <div class="flex items-center gap-2">
                    <span>📅</span>
                    <span>{{ \Carbon\Carbon::parse($evento->Fecha_inicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($evento->Fecha_fin)->format('d/m/Y') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span>👥</span>
                    <span>{{ $evento->proyectos->count() }} equipos inscritos</span>
                </div>
            </div>
        </div>

        <!-- Lista de equipos inscritos -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Equipos Inscritos</h2>
        </div>

        @if($evento->proyectos->isEmpty())
            <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center">
                <div class="text-6xl mb-4">👥</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No hay equipos inscritos</h3>
                <p class="text-gray-600">Aún no se han inscrito equipos a este evento</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($evento->proyectos as $proyecto)
                    <div class="bg-white rounded-2xl border border-gray-200 p-6 hover:shadow-md transition">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-xl font-bold text-gray-900">{{ $proyecto->equipo->nombre }}</h3>
                                    @if($proyecto->Categoria)
                                        <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs font-medium rounded-full">
                                            {{ $proyecto->Categoria }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-gray-600 text-sm mb-3">
                                    <span class="font-medium">Proyecto:</span> {{ $proyecto->nombre }}
                                </p>
                            </div>
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm font-medium rounded-full">
                                {{ $proyecto->equipo->participantes->count() }} miembros
                            </span>
                        </div>

                        <!-- Integrantes del equipo -->
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-900 mb-3 flex items-center gap-2">
                                <span>👥</span>
                                <span>Integrantes del equipo</span>
                            </p>
                            <div class="grid md:grid-cols-2 gap-3">
                                @foreach($proyecto->equipo->participantes as $index => $participante)
                                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                        <div class="w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                                            {{ substr($participante->Nombre, 0, 1) }}
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-900">{{ $participante->Nombre }}</p>
                                            <p class="text-sm text-gray-600">{{ $participante->Correo }}</p>
                                        </div>
                                        @if($index === 0)
                                            <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">
                                                Líder
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Asesor -->
                        @if($proyecto->asesor)
                            <div class="pt-4 border-t border-gray-200">
                                <p class="text-sm font-medium text-gray-900 mb-2 flex items-center gap-2">
                                    <span>👨‍🏫</span>
                                    <span>Asesor académico</span>
                                </p>
                                <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg">
                                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                                        {{ substr($proyecto->asesor->Nombre, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $proyecto->asesor->Nombre }}</p>
                                        <p class="text-sm text-gray-600">{{ $proyecto->asesor->Correo }}</p>
                                        @if($proyecto->asesor->Telefono)
                                            <p class="text-sm text-gray-600">📞 {{ $proyecto->asesor->Telefono }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Resumen -->
            <div class="mt-8 bg-white rounded-2xl border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Resumen de inscripciones</h3>
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <p class="text-3xl font-bold text-purple-600 mb-1">{{ $evento->proyectos->count() }}</p>
                        <p class="text-sm text-gray-600">Equipos inscritos</p>
                    </div>
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <p class="text-3xl font-bold text-blue-600 mb-1">
                            {{ $evento->proyectos->sum(function($p) { return $p->equipo->participantes->count(); }) }}
                        </p>
                        <p class="text-sm text-gray-600">Total de participantes</p>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <p class="text-3xl font-bold text-green-600 mb-1">
                            {{ $evento->proyectos->filter(function($p) { return $p->asesor != null; })->count() }}
                        </p>
                        <p class="text-sm text-gray-600">Asesores registrados</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</body>
</html>