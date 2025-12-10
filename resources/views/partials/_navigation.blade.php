{{-- Shared Navigation Component --}}
{{-- Top Navigation Bar --}}
<nav class="bg-white/80 backdrop-blur-md shadow-lg border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-600 to-purple-800 rounded-lg flex items-center justify-center shadow-md">
                    <span class="text-xl text-white font-bold">&lt;/&gt;</span>
                </div>
                <div>
                    <span class="text-xl font-bold text-gray-900">DevTeams</span>
                    <p class="text-xs text-gray-500">Plataforma de hackathons</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('perfil.show') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg text-gray-700 hover:bg-purple-50 transition-all duration-200 group">
                    <span class="group-hover:scale-110 transition-transform">👤</span>
                    <span class="font-medium">Mi Perfil</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-gray-800 to-gray-900 text-white rounded-lg hover:from-gray-900 hover:to-black transition-all duration-200 shadow-md hover:shadow-lg">
                        <span>🚪</span>
                        <span>Cerrar sesión</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

{{-- Main Content Wrapper --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Header Banner --}}
    <div class="mb-8 relative overflow-hidden rounded-2xl bg-gradient-to-r from-purple-600 to-purple-800 p-8 shadow-xl">
        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
        <div class="relative">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd" />
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-white">Hola, {{ Auth::user()->name }}</h1>
            </div>
            <p class="text-purple-100 ml-15">Gestiona tus equipos, eventos, invitaciones y código en un solo lugar</p>
        </div>
    </div>

    {{-- Tab Navigation --}}
    <div class="mb-8 bg-gray-100 rounded-full p-1 inline-flex gap-1 flex-wrap">
        <a href="{{ route('dashboard') }}"
           class="px-6 py-3 rounded-full flex items-center gap-2 transition font-medium
                  {{ request()->routeIs('dashboard') ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:bg-white' }}">
            <span>👥</span>
            <span>Equipos</span>
        </a>
        <a href="{{ route('eventos.index') }}"
           class="px-6 py-3 rounded-full flex items-center gap-2 transition font-medium
                  {{ request()->routeIs('eventos.*') ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:bg-white' }}">
            <span>📅</span>
            <span>Eventos</span>
        </a>
        <a href="{{ route('codigos.index') }}"
           class="px-6 py-3 rounded-full flex items-center gap-2 transition font-medium
                  {{ request()->routeIs('codigos.*') || request()->routeIs('repositorios.*') ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:bg-white' }}">
            <span>&lt;/&gt;</span>
            <span>Códigos</span>
        </a>
        <a href="{{ route('invitaciones.index') }}"
           class="px-6 py-3 rounded-full flex items-center gap-2 transition font-medium relative
                  {{ request()->routeIs('invitaciones.*') ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:bg-white' }}">
            <span>✉️</span>
            <span>Invitaciones</span>
            @if(isset($invitacionesPendientes) && $invitacionesPendientes > 0)
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center animate-pulse">
                    {{ $invitacionesPendientes > 9 ? '9+' : $invitacionesPendientes }}
                </span>
            @endif
        </a>
        <a href="{{ route('solicitudes.buscar-equipos') }}"
           class="px-6 py-3 rounded-full flex items-center gap-2 transition font-medium
                  {{ request()->routeIs('solicitudes.buscar-equipos') ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:bg-white' }}">
            <span>🔍</span>
            <span>Explorar</span>
        </a>
        <a href="{{ route('solicitudes.mi-estado') }}"
           class="px-6 py-3 rounded-full flex items-center gap-2 transition font-medium
                  {{ request()->routeIs('solicitudes.mi-estado') ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:bg-white' }}">
            <span>📋</span>
            <span>Mis Solicitudes</span>
        </a>
    </div>
