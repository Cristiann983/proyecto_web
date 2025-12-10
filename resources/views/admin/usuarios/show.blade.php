@extends('layouts.app', ['title' => 'Ver Usuario'])

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevTeams - Usuario: {{ $usuario->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="text-2xl text-purple-600">&lt;/&gt;</div>
                    <span class="text-xl font-bold">DevTeams</span>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('perfil.show') }}" class="px-4 py-2 border rounded-lg">Mi Perfil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="px-4 py-2 border rounded-lg">Cerrar sesión</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @include('partials._alerts')

        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $usuario->name }}</h1>
                <p class="text-gray-600">Detalles del usuario</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.usuarios.edit', $usuario->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Editar</a>
                <form method="POST" action="{{ route('admin.usuarios.destroy', $usuario->id) }}" onsubmit="return confirm('¿Eliminar usuario?')">
                    @csrf
                    @method('DELETE')
                    <button class="px-4 py-2 bg-red-600 text-white rounded-lg">Eliminar</button>
                </form>
                <a href="{{ route('admin.usuarios.index') }}" class="px-4 py-2 border rounded-lg">Volver</a>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl p-6 shadow">
                <h3 class="font-bold text-lg mb-4">Información Personal</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm text-gray-600">Email</dt>
                        <dd class="font-medium">{{ $usuario->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-600">Estado</dt>
                        <dd>
                            @if($usuario->is_active)
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm">Activo</span>
                            @else
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">Inactivo</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-600">Roles</dt>
                        <dd class="flex gap-2">
                            @foreach($usuario->roles as $rol)
                                <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm">{{ $rol->Nombre }}</span>
                            @endforeach
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-600">Fecha de registro</dt>
                        <dd>{{ $usuario->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                </dl>
            </div>

            @if($usuario->participante)
            <div class="bg-white rounded-xl p-6 shadow">
                <h3 class="font-bold text-lg mb-4">Datos de Participante</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm text-gray-600">No. Control</dt>
                        <dd>{{ $usuario->participante->No_Control }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-600">Carrera</dt>
                        <dd>{{ $usuario->participante->carrera->Nombre ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-600">Equipos</dt>
                        <dd>{{ $usuario->participante->equipos->count() }}</dd>
                    </div>
                </dl>
            </div>
            @endif

            @if($usuario->juez)
            <div class="bg-white rounded-xl p-6 shadow">
                <h3 class="font-bold text-lg mb-4">Datos de Juez</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm text-gray-600">Especialidad</dt>
                        <dd>{{ $usuario->juez->especialidad->Nombre ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-600">Eventos asignados</dt>
                        <dd>{{ $usuario->juez->eventos->count() }}</dd>
                    </div>
                </dl>
            </div>
            @endif
        </div>
    </div>
</body>
</html>
