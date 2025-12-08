<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Jueces</title>
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
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #333; color: white; padding: 8px; text-align: left; font-size: 11px; }
        td { padding: 8px; border-bottom: 1px solid #ddd; font-size: 11px; }
        tr:nth-child(even) { background: #f9f9f9; }
        .badge { padding: 3px 8px; border-radius: 3px; font-size: 9px; background: #e3f2fd; color: #1976d2; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <h1>DevTeams - Reporte de Jueces</h1>
        <p>Generado el {{ date('d/m/Y H:i') }}</p>
    </div>

    <div class="stats">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-value">{{ $estadisticas['total'] }}</div>
                <div class="stat-label">Total Jueces</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $estadisticas['con_eventos'] }}</div>
                <div class="stat-label">Con Eventos</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $estadisticas['especialidades'] }}</div>
                <div class="stat-label">Especialidades</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $estadisticas['total_calificaciones'] }}</div>
                <div class="stat-label">Calificaciones</div>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Especialidad</th>
                <th>Eventos</th>
                <th>Calificaciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jueces as $juez)
            <tr>
                <td>{{ $juez->Id }}</td>
                <td>{{ $juez->Nombre }}</td>
                <td>{{ $juez->Correo }}</td>
                <td>
                    <span class="badge">{{ $juez->especialidad->Nombre ?? 'N/A' }}</span>
                </td>
                <td>{{ $juez->eventos->count() }}</td>
                <td>{{ $juez->calificaciones->count() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>DevTeams - Sistema de Gestión de Hackathons | Página 1 de 1</p>
    </div>
</body>
</html>
