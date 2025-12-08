<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Estadísticas</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #333; }
        .header p { margin: 5px 0; color: #666; }
        .section { margin: 20px 0; }
        .section-title { background: #333; color: white; padding: 8px; font-weight: bold; margin-bottom: 10px; }
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin: 15px 0; }
        .stat-card { background: #f9f9f9; border: 1px solid #ddd; padding: 15px; border-radius: 5px; text-align: center; }
        .stat-value { font-size: 32px; font-weight: bold; color: #333; }
        .stat-label { font-size: 11px; color: #666; margin-top: 5px; }
        .detail-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
        .detail-item { background: #fff; border: 1px solid #eee; padding: 10px; border-radius: 3px; }
        .detail-value { font-size: 20px; font-weight: bold; color: #555; }
        .detail-label { font-size: 10px; color: #888; }
        .list-item { padding: 5px; border-bottom: 1px solid #eee; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <h1>DevTeams - Estadísticas Generales del Sistema</h1>
        <p>Generado el {{ date('d/m/Y H:i') }}</p>
    </div>

    <!-- Resumen Principal -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value">{{ $estadisticas['usuarios']['total'] }}</div>
            <div class="stat-label">Total Usuarios</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $estadisticas['eventos']['total'] }}</div>
            <div class="stat-label">Total Eventos</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $estadisticas['equipos']['total'] }}</div>
            <div class="stat-label">Total Equipos</div>
        </div>
    </div>

    <!-- Usuarios -->
    <div class="section">
        <div class="section-title">👥 Usuarios</div>
        <div class="detail-grid">
            <div class="detail-item">
                <div class="detail-value">{{ $estadisticas['usuarios']['activos'] }}</div>
                <div class="detail-label">Usuarios Activos</div>
            </div>
            <div class="detail-item">
                <div class="detail-value">{{ $estadisticas['usuarios']['por_rol']['participantes'] }}</div>
                <div class="detail-label">Participantes</div>
            </div>
            <div class="detail-item">
                <div class="detail-value">{{ $estadisticas['usuarios']['por_rol']['jueces'] }}</div>
                <div class="detail-label">Jueces</div>
            </div>
        </div>
    </div>

    <!-- Eventos -->
    <div class="section">
        <div class="section-title">📅 Eventos</div>
        <div class="detail-grid">
            <div class="detail-item">
                <div class="detail-value">{{ $estadisticas['eventos']['activos'] }}</div>
                <div class="detail-label">Activos</div>
            </div>
            <div class="detail-item">
                <div class="detail-value">{{ $estadisticas['eventos']['finalizados'] }}</div>
                <div class="detail-label">Finalizados</div>
            </div>
            <div class="detail-item">
                <div class="detail-value">{{ $estadisticas['eventos']['cancelados'] }}</div>
                <div class="detail-label">Cancelados</div>
            </div>
        </div>
    </div>

    <!-- Equipos -->
    <div class="section">
        <div class="section-title">👨‍👩‍👧‍👦 Equipos</div>
        <div class="detail-grid">
            <div class="detail-item">
                <div class="detail-value">{{ $estadisticas['equipos']['total'] }}</div>
                <div class="detail-label">Total Equipos</div>
            </div>
            <div class="detail-item">
                <div class="detail-value">{{ $estadisticas['equipos']['total_miembros'] }}</div>
                <div class="detail-label">Total Miembros</div>
            </div>
            <div class="detail-item">
                <div class="detail-value">{{ $estadisticas['equipos']['promedio_miembros'] }}</div>
                <div class="detail-label">Promedio por Equipo</div>
            </div>
        </div>
    </div>

    <!-- Jueces y Especialidades -->
    <div class="section">
        <div class="section-title">⚖️ Jueces y Especialidades</div>
        <p><strong>Total Jueces:</strong> {{ $estadisticas['jueces']['total'] }}</p>
        <p><strong>Especialidades:</strong></p>
        @foreach($estadisticas['jueces']['especialidades'] as $especialidad)
            <div class="list-item">• {{ $especialidad }}</div>
        @endforeach
    </div>

    <!-- Participantes por Carrera -->
    @if(count($estadisticas['participantes_por_carrera']) > 0)
    <div class="section">
        <div class="section-title">🎓 Participantes por Carrera</div>
        @foreach($estadisticas['participantes_por_carrera'] as $carrera => $cantidad)
            <div class="list-item"><strong>{{ $carrera }}:</strong> {{ $cantidad }} participantes</div>
        @endforeach
    </div>
    @endif

    <div class="footer">
        <p>DevTeams - Sistema de Gestión de Hackathons | Reporte Generado Automáticamente</p>
    </div>
</body>
</html>
