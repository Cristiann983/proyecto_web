<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Eventos</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 15px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #333; font-size: 18px; }
        .header p { margin: 5px 0; color: #666; }
        .stats { margin: 15px 0; background: #f5f5f5; padding: 10px; border-radius: 5px; }
        .stats-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px; }
        .stat-item { text-align: center; }
        .stat-value { font-size: 22px; font-weight: bold; color: #333; }
        .stat-label { font-size: 9px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background: #333; color: white; padding: 6px; text-align: left; font-size: 10px; }
        td { padding: 6px; border-bottom: 1px solid #ddd; font-size: 10px; }
        tr:nth-child(even) { background: #f9f9f9; }
        .badge { padding: 2px 6px; border-radius: 3px; font-size: 8px; font-weight: bold; }
        .badge-activo { background: #d4edda; color: #155724; }
        .badge-finalizado { background: #d1ecf1; color: #0c5460; }
        .badge-cancelado { background: #f8d7da; color: #721c24; }
        .footer { margin-top: 20px; text-align: center; font-size: 9px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <h1>DevTeams - Reporte de Eventos</h1>
        <p>Generado el {{ date('d/m/Y H:i') }}</p>
    </div>

    <div class="stats">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-value">{{ $estadisticas['total'] }}</div>
                <div class="stat-label">Total</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $estadisticas['activos'] }}</div>
                <div class="stat-label">Activos</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $estadisticas['finalizados'] }}</div>
                <div class="stat-label">Finalizados</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $estadisticas['cancelados'] }}</div>
                <div class="stat-label">Cancelados</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $estadisticas['total_equipos'] }}</div>
                <div class="stat-label">Total Equipos</div>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Ubicación</th>
                <th>Equipos</th>
                <th>Jueces</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($eventos as $evento)
            <tr>
                <td>{{ $evento->Id }}</td>
                <td>{{ $evento->Nombre }}</td>
                <td>{{ \Carbon\Carbon::parse($evento->Fecha_inicio)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($evento->Fecha_fin)->format('d/m/Y') }}</td>
                <td>{{ $evento->Ubicacion ?? 'N/A' }}</td>
                <td>{{ $evento->proyectos->count() }}</td>
                <td>{{ $evento->jueces->count() }}</td>
                <td>
                    <span class="badge badge-{{ strtolower($evento->Estado) }}">{{ $evento->Estado }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>DevTeams - Sistema de Gestión de Hackathons | Página 1 de 1</p>
    </div>
</body>
</html>
