<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DevTeams - Estado de Mis Solicitudes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    @include('partials._navigation')


        @if($solicitudes->count() > 0)
            <div class="space-y-4">
                @foreach($solicitudes as $solicitud)
                    @php
                        $estados = [
                            'Pendiente' => ['bg' => 'bg-yellow-50', 'border' => 'border-yellow-200', 'text' => 'text-yellow-700', 'icon' => '⏳', 'label' => 'Pendiente de revisión'],
                            'Aceptada' => ['bg' => 'bg-green-50', 'border' => 'border-green-200', 'text' => 'text-green-700', 'icon' => '✓', 'label' => 'Aceptada'],
                            'Rechazada' => ['bg' => 'bg-red-50', 'border' => 'border-red-200', 'text' => 'text-red-700', 'icon' => '✗', 'label' => 'Rechazada'],
                        ];
                        $estado = $estados[$solicitud->Estado] ?? $estados['Pendiente'];
                    @endphp

                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-xl font-bold text-gray-900">{{ $solicitud->equipo->Nombre }}</h3>
                                    <span class="text-xs px-3 py-1 rounded-full {{ $estado['bg'] }} {{ $estado['border'] }} border {{ $estado['text'] }}">
                                        {{ $estado['icon'] }} {{ $estado['label'] }}
                                    </span>
                                </div>
                                <p class="text-gray-600 mb-2">
                                    Solicitado hace: {{ $solicitud->created_at->diffForHumans() }}
                                </p>
                                @if($solicitud->Mensaje)
                                    <div class="text-sm text-gray-600 italic">
                                        Tu mensaje: "{{ $solicitud->Mensaje }}"
                                    </div>
                                @endif
                            </div>

                            <!-- Resumen del estado -->
                            <div class="text-right">
                                @if($solicitud->Estado === 'Aceptada')
                                    <div class="text-green-700 font-semibold">
                                        ¡Felicidades! <br>
                                        <span class="text-sm">Tu solicitud ha sido  aceptada</span>
                                    </div>
                                @elseif($solicitud->Estado === 'Rechazada')
                                    <div class="text-red-700 font-semibold">
                                        Solicitud rechazada<br>
                                        <span class="text-sm">Puedes intentar con otro equipo</span>
                                    </div>
                                @else
                                    <div class="text-yellow-700 font-semibold">
                                        En revisión<br>
                                        <span class="text-sm">El líder está revisando</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginación -->
            <div class="mt-8 flex justify-center">
                {{ $solicitudes->links('pagination::tailwind') }}
            </div>
        @else
            <div class="bg-white border-2 border-gray-200 rounded-2xl p-16">
                <div class="flex flex-col items-center justify-center text-center max-w-2xl mx-auto">
                    <div class="mb-6">
                        <svg class="w-24 h-24 text-gray-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No has enviado solicitudes</h3>
                    <p class="text-gray-600">
                        <a href="{{ route('eventos.index') }}" class="text-purple-600 hover:text-purple-700 font-semibold">
                            Explora equipos disponibles
                        </a> y envía solicitudes para unirte
                    </p>
                </div>
            </div>
        @endif
    </div>

    @include('partials._footer')
</body>
</html>
