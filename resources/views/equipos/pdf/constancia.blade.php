<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page {
            margin: 0;
            size: letter;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            color: #333;
            width: 100%;
            height: 100%;
        }
        
        .page-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #1e1e2e 0%, #2d1b4e 50%, #1a1a2e 100%);
            z-index: -1;
        }
        
        .certificate-outer-frame {
            position: absolute;
            top: 12mm;
            left: 12mm;
            right: 12mm;
            bottom: 12mm;
            border: 2px solid rgba(139, 92, 246, 0.4);
            border-radius: 8px;
            padding: 4mm;
            background: transparent;
        }
        
        .certificate-inner-frame {
            position: relative;
            border: 3px solid #8b5cf6;
            border-radius: 6px;
            background: linear-gradient(180deg, #ffffff 0%, #faf8ff 100%);
            width: 100%;
            height: 100%;
            overflow: hidden;
        }
        
        /* Decorative corner elements */
        .corner {
            position: absolute;
            width: 50px;
            height: 50px;
            border: 3px solid #8b5cf6;
            z-index: 2;
        }
        
        .corner-tl {
            top: 6px;
            left: 6px;
            border-right: none;
            border-bottom: none;
            border-top-left-radius: 8px;
        }
        
        .corner-tr {
            top: 6px;
            right: 6px;
            border-left: none;
            border-bottom: none;
            border-top-right-radius: 8px;
        }
        
        .corner-bl {
            bottom: 6px;
            left: 6px;
            border-right: none;
            border-top: none;
            border-bottom-left-radius: 8px;
        }
        
        .corner-br {
            bottom: 6px;
            right: 6px;
            border-left: none;
            border-top: none;
            border-bottom-right-radius: 8px;
        }
        
        .certificate-content {
            position: relative;
            padding: 20px 30px;
            text-align: center;
            z-index: 1;
            height: 100%;
        }
        
        .header {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .logo-container {
            display: inline-block;
        }
        
        .logo {
            font-size: 32px;
            color: #8b5cf6;
            font-weight: bold;
            letter-spacing: -2px;
        }
        
        .logo-text {
            font-size: 24px;
            font-weight: bold;
            color: #8b5cf6;
        }
        
        .subtitle {
            font-size: 8px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-top: 2px;
        }
        
        .title {
            font-size: 26px;
            font-weight: bold;
            color: #1f2937;
            margin: 10px 0 8px 0;
            text-transform: uppercase;
            letter-spacing: 4px;
        }
        
        .decorative-line {
            width: 150px;
            height: 3px;
            background: linear-gradient(90deg, transparent, #8b5cf6, transparent);
            margin: 5px auto;
        }
        
        .body-text {
            font-size: 11px;
            line-height: 1.4;
            color: #4b5563;
            margin: 8px auto;
            max-width: 90%;
        }
        
        .participant-name {
            font-size: 22px;
            font-weight: bold;
            color: #8b5cf6;
            margin: 10px 0;
            text-transform: uppercase;
            letter-spacing: 2px;
            padding: 5px 20px;
            display: inline-block;
            border-bottom: 2px solid #8b5cf6;
            border-top: 2px solid #8b5cf6;
        }
        
        .event-details {
            background: #f8f7ff;
            border: 1px solid #e0d4fc;
            border-radius: 6px;
            padding: 10px 15px;
            margin: 10px auto;
            margin-bottom: 100px;
            max-width: 90%;
            text-align: left;
        }
        
        .event-details-title {
            font-weight: bold;
            color: #8b5cf6;
            font-size: 10px;
            text-transform: uppercase;
            margin-bottom: 5px;
            letter-spacing: 1px;
            text-align: center;
            border-bottom: 1px solid #e0d4fc;
            padding-bottom: 4px;
        }
        
        .event-info-grid {
            width: 100%;
        }
        
        .event-info-row {
            margin-bottom: 2px;
        }
        
        .event-info {
            display: inline-block;
            width: 48%;
            padding: 2px 4px;
            font-size: 9px;
            color: #4b5563;
            vertical-align: top;
        }
        
        .event-info strong {
            color: #1f2937;
            font-weight: 600;
        }
        
        .winner-badge {
            background: #fef3c7;
            border: 2px solid #f59e0b;
            border-radius: 8px;
            padding: 8px 20px;
            margin: 8px auto;
            margin-bottom: 50   px;
            max-width: 50%;
            text-align: center;
            position: relative;
        }
        
        .winner-badge::before {
            content: '';
            position: absolute;
            top: -6px;
            left: 50%;
            transform: translateX(-50%);
            width: 30px;
            height: 12px;
            background: #f59e0b;
            border-radius: 6px 6px 0 0;
        }
        
        .winner-icon {
            font-size: 20px;
            margin-bottom: 2px;
        }
        
        .winner-badge-position {
            font-size: 24px;
            font-weight: bold;
            color: #92400e;
            line-height: 1;
        }
        
        .winner-badge-text {
            font-size: 8px;
            color: #b45309;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 2px;
        }
        
        .closing-text {
            font-size: 10px;
            line-height: 1.3;
            color: #6b7280;
            margin: 8px auto;
            font-style: italic;
            max-width: 85%;
        }
        
        .footer {
            margin-top: 10px;
            padding-top: 8px;
            border-top: 1px solid #e5e7eb;
        }
        
        .signature-section {
            text-align: center;
            margin-bottom: 8px;
        }
        
        .signature-line {
            width: 150px;
            border-top: 1px solid #1f2937;
            margin: 0 auto 4px auto;
        }
        
        .signature-label {
            font-size: 9px;
            color: #6b7280;
            font-weight: 600;
        }
        
        .footer-info-table {
            width: 100%;
            table-layout: fixed;
        }
        
        .footer-info-cell {
            width: 33.33%;
            text-align: center;
            vertical-align: top;
            padding: 0 5px;
        }
        
        .footer-text {
            font-size: 8px;
            color: #6b7280;
        }
        
        .footer-text strong {
            color: #4b5563;
        }
        
        .verification-code {
            background: #f9fafb;
            border: 1px dashed #d1d5db;
            border-radius: 4px;
            padding: 4px 10px;
            font-family: 'Courier New', monospace;
            display: inline-block;
        }
        
        .verification-label {
            font-size: 7px;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .verification-number {
            font-size: 10px;
            font-weight: bold;
            color: #4b5563;
            letter-spacing: 1px;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 60px;
            color: rgba(139, 92, 246, 0.03);
            font-weight: bold;
            z-index: 0;
            letter-spacing: 10px;
            white-space: nowrap;
        }

        .seal {
            position: absolute;
            bottom: 25px;
            right: 25px;
            width: 60px;
            height: 60px;
            border: 3px solid #8b5cf6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(139, 92, 246, 0.05);
            z-index: 5;
        }
        
        .seal-inner {
            width: 45px;
            height: 45px;
            border: 2px solid #8b5cf6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        
        .seal-text {
            font-size: 7px;
            color: #8b5cf6;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.1;
        }
    </style>
</head>
<body>
    <div class="page-wrapper"></div>
    
    <div class="certificate-outer-frame">
        <div class="certificate-inner-frame">
            <!-- Decorative corners -->
            <div class="corner corner-tl"></div>
            <div class="corner corner-tr"></div>
            <div class="corner corner-bl"></div>
            <div class="corner corner-br"></div>
            
            <!-- Watermark -->
            <div class="watermark">DEVTEAMS</div>
            
            <div class="certificate-content">
                <!-- Header -->
                <div class="header">
                    <div class="logo-container">
                        <div class="logo">&lt;/&gt;</div>
                        <div class="logo-text">DevTeams</div>
                    </div>
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
                    <div class="event-details-title">Detalles del Evento</div>
                    <div class="event-info-grid">
                        <div class="event-info-row">
                            <div class="event-info">
                                <strong>Evento:</strong> {{ $evento->Nombre }}
                            </div>
                            <div class="event-info">
                                <strong>Proyecto:</strong> {{ $proyecto->Nombre }}
                            </div>
                        </div>
                        <div class="event-info-row">
                            <div class="event-info">
                                <strong>Categoría:</strong> {{ $evento->Categoria ?? 'General' }}
                            </div>
                            <div class="event-info">
                                <strong>Período:</strong> 
                                {{ \Carbon\Carbon::parse($evento->Fecha_inicio)->format('d/m/Y') }} 
                                - 
                                {{ \Carbon\Carbon::parse($evento->Fecha_fin)->format('d/m/Y') }}
                            </div>
                        </div>
                        @if($evento->Ubicacion)
                        <div class="event-info-row">
                            <div class="event-info" style="width: 100%;">
                                <strong>Ubicación:</strong> {{ $evento->Ubicacion }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                
                @if($es_ganador)
                <!-- Winner Badge -->
                <div class="winner-badge">
                    <div class="winner-icon">🏆</div>
                    <div class="winner-badge-position">
                        {{ $posicion_ranking }}° Lugar
                    </div>
                    <div class="winner-badge-text">
                        Ranking del Evento
                    </div>
                </div>
                @endif
                
                <p class="closing-text">
                    Demostrando compromiso, trabajo en equipo y habilidades técnicas
                    en el desarrollo de soluciones tecnológicas innovadoras.
                </p>
                
                <!-- Footer -->
                <div class="footer">
                    <div class="signature-section">
                        <div class="signature-line"></div>
                        <div class="signature-label">DevTeams Platform</div>
                    </div>
                    
                    <table class="footer-info-table">
                        <tr>
                            <td class="footer-info-cell">
                                <div class="footer-text">
                                    <strong>Fecha de emisión</strong>
                                    {{ $fecha_emision->format('d/m/Y') }}
                                </div>
                            </td>
                            <td class="footer-info-cell">
                                <div class="footer-text">
                                    <strong>Equipo</strong>
                                    {{ $equipo->participantes->count() }} miembros
                                </div>
                            </td>
                            <td class="footer-info-cell">
                                <div class="verification-code">
                                    <div class="verification-label">Código de Verificación</div>
                                    <div class="verification-number">{{ $codigo_verificacion }}</div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- Seal -->
            <div class="seal">
                <div class="seal-inner">
                    <div class="seal-text">Dev<br>Teams<br>✓</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
