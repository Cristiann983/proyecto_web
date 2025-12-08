<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Equipos</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #333; }
        .header p { margin: 5px 0; color: #666; }
        .stats { margin: 20px 0; background: #f5f5f5; padding: 10px; border-radius: 5px; }
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
        .stat-item { text-align: center; }
        .stat-value { font-size: 24px; font-weight: bold; color: #333; }
        .stat-label { font-size: 10px; color: #666; }
        .equipo-card { border: 1px solid #ddd; margin: 15px 0; padding: 10px; border-radius: 5px; }
        .equipo-card h3 { margin: 0 0 10px 0; color: #333; font-size: 14px; }
        .equipo-info { font-size: 11px; color: #666; margin: 5px 0; }
        .miembros { margin-top: 10px; }
        .miembros-list { font-size: 11px; color: #444; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <h1>DevTeams - Reporte de Equipos</h1>
        <p>Generado el {{ date('d/m/Y H:i') }}</p>
    </div>

    <div class="stats">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-value">{{ $estadisticas['total'] }}</div>
                <div class="stat-label">Total Equipos</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $estadisticas['con_proyectos'] }}</div>
                <div class="stat-label">Con Proyectos</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $estadisticas['total_miembros'] }}</div>
                <div class="stat-label">Total Miembros</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $estadisticas['promedio_miembros'] }}</div>
                <div class="stat-label">Promedio/Equipo</div>
            </div>
        </div>
    </div>

    @foreach($equipos as $equipo)
    <div class="equipo-card">
        <h3>{{ $equipo->Nombre }}</h3>
        <div class="equipo-info">
            <strong>ID:</strong> {{ $equipo->Id }} | 
            <strong>Miembros:</strong> {{ $equipo->participantes->count() }} |
            <strong>Proyectos:</strong> {{ $equipo->proyectos->count() }}
        </div>
        
        @if($equipo->participantes->count() > 0)
        <div class="miembros">
            <strong>Miembros del equipo:</strong>
            <div class="miembros-list">
                @foreach($equipo->participantes as $participante)
                    • {{ $participante->Nombre }} ({{ $participante->usuario->email ?? 'N/A' }})
                    @if(!$loop->last)<br>@endif
                @endforeach
            </div>
        </div>
        @endif

        @if($equipo->proyectos->count() > 0)
        <div class="miembros">
            <strong>Proyectos:</strong>
            <div class="miembros-list">
                @foreach($equipo->proyectos as $proyecto)
                    • {{ $proyecto->Nombre }} - {{ $proyecto->evento->Nombre ?? 'N/A' }}
                    @if(!$loop->last)<br>@endif
                @endforeach
            </div>
        </div>
        @endif
    </div>
    @endforeach

    <div class="footer">
        <p>DevTeams - Sistema de Gestión de Hackathons | Total: {{ $equipos->count() }} equipos</p>
    </div>
</body>
</html>
