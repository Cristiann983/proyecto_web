<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page {
            margin: 0cm 0cm

;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #333;
        }
        
        .certificate-border {
            position: absolute;
            top: 15mm;
            left: 15mm;
            right: 15mm;
            bottom: 15mm;
            border: 8px double #8b5cf6;
            border-radius: 10px;
            background: #ffffff;
        }
        
        .certificate-content {
            position: relative;
            padding: 40px 60px;
            text-align: center;
        }
        
        .header {
            margin-bottom: 25px;
            border-bottom: 3px solid #8b5cf6;
            padding-bottom: 20px;
        }
        
        .logo {
            font-size: 48px;
            color: #8b5cf6;
            font-weight: bold;
            margin-bottom: 10px;
            letter-spacing: -2px;
        }
        
        .logo-text {
            font-size: 32px;
            font-weight: bold;
            color: #1f2937;
            margin-top: -5px;
        }
        
        .subtitle {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .title {
            font-size: 42px;
            font-weight: bold;
            color: #1f2937;
            margin: 30px 0 20px 0;
            text-transform: uppercase;
            letter-spacing: 4px;
        }
        
        .decorative-line {
            width: 150px;
            height: 3px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            margin: 20px auto;
        }
        
        .body-text {
            font-size: 16px;
            line-height: 1.8;
            color: #4b5563;
            margin: 25px auto;
            max-width: 85%;
        }
        
        .participant-name {
            font-size: 28px;
            font-weight: bold;
            color: #8b5cf6;
            margin: 25px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .event-details {
            background: #f3f4f6;
            border-left: 4px solid #8b5cf6;
            padding: 20px 30px;
            margin: 30px auto;
            max-width: 80%;
            text-align: left;
        }
        
        .event-details-title {
            font-weight: bold;
            color: #1f2937;
            font-size: 14px;
            text-transform: uppercase;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }
        
        .event-info {
            margin: 8px 0;
            font-size: 13px;
            color: #4b5563;
        }
        
        .event-info strong {
            color: #1f2937;
            font-weight: 600;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
        }
        
        .footer-info {
            display: inline-block;
            margin: 0 20px;
            font-size: 11px;
            color: #6b7280;
        }
        
        .verification-code {
            margin-top: 20px;
            padding: 15px;
            background: #f9fafb;
            border: 1px dashed #d1d5db;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
        }
        
        .verification-label {
            font-size: 10px;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .verification-number {
            font-size: 16px;
            font-weight: bold;
            color: #4b5563;
            margin-top: 5px;
            letter-spacing: 2px;
        }
        
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            color: rgba(139, 92, 246, 0.05);
            font-weight: bold;
            z-index: 0;
        }
        
        .signature-line {
            width: 250px;
            border-top: 2px solid #1f2937;
            margin: 40px auto 10px auto;
        }
        
        .signature-label {
            font-size: 12px;
            color: #6b7280;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="watermark">DEVTEAMS</div>
    
    <div class="certificate-border">
        <div class="certificate-content">
            <!-- Header -->
            <div class="header">
                <div class="logo">&lt;/&gt;</div>
                <div class="logo-text">DevTeams</div>
                <div class="subtitle">Plataforma de Gestión de Equipos de Desarrollo</div>
            </div>
            
            <!-- Title -->
            <h1 class="title">Constancia de Participación</h1>
            <div class="decorative-line"></div>
            
            <!-- Body Text -->
            <p class="body-text">
                Por medio de la presente se hace constar que
            </p>
            
            <div class="participant-name">
                {{ strtoupper($participante->Nombre) }}
            </div>
            
            <p class="body-text">
                Participó activamente como <strong>{{ $perfil->Nombre }}</strong> en el equipo 
                <strong>{{ $equipo->Nombre }}</strong> durante el evento
            </p>
            
            <!-- Event Details -->
            <div class="event-details">
                <div class="event-details-title">📋 Detalles del Evento</div>
                <div class="event-info">
                    <strong>Evento:</strong> {{ $evento->Nombre }}
                </div>
                <div class="event-info">
                    <strong>Proyecto:</strong> {{ $proyecto->Nombre }}
                </div>
                <div class="event-info">
                    <strong>Categoría:</strong> {{ $evento->Categoria ?? 'General' }}
                </div>
                <div class="event-info">
                    <strong>Período:</strong> 
                    {{ \Carbon\Carbon::parse($evento->Fecha_inicio)->format('d/m/Y') }} 
                    - 
                    {{ \Carbon\Carbon::parse($evento->Fecha_fin)->format('d/m/Y') }}
                </div>
                @if($evento->Ubicacion)
                <div class="event-info">
                    <strong>Ubicación:</strong> {{ $evento->Ubicacion }}
                </div>
                @endif
            </div>
            
            <p class="body-text">
                Demostrando compromiso, trabajo en equipo y habilidades técnicas<br>
                en el desarrollo de soluciones tecnológicas innovadoras.
            </p>
            
            <!-- Footer -->
            <div class="footer">
                <div class="signature-line"></div>
                <div class="signature-label">DevTeams Platform</div>
                <div style="margin-top: 30px;">
                    <div class="footer-info">
                        <strong>Fecha de emisión:</strong><br>
                        {{ $fecha_emision->format('d/m/Y') }}
                    </div>
                    <div class="footer-info">
                        <strong>Participantes del equipo:</strong><br>
                        {{ $equipo->participantes->count() }} miembros
                    </div>
                </div>
                
                <!-- Verification Code -->
                <div class="verification-code">
                    <div class="verification-label">Código de Verificación</div>
                    <div class="verification-number">{{ $codigo_verificacion }}</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
