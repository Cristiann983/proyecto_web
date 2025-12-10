<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de Verificación</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #9333ea 0%, #3b82f6 100%);
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            color: white;
            margin: 0;
            font-size: 28px;
        }
        .header .logo {
            font-size: 40px;
            margin-bottom: 10px;
        }
        .content {
            padding: 40px 30px;
            text-align: center;
        }
        .greeting {
            font-size: 18px;
            color: #374151;
            margin-bottom: 20px;
        }
        .message {
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .code-container {
            background: linear-gradient(135deg, #f3e8ff 0%, #dbeafe 100%);
            border: 2px dashed #9333ea;
            border-radius: 12px;
            padding: 25px;
            margin: 20px 0;
        }
        .code {
            font-size: 42px;
            font-weight: bold;
            color: #9333ea;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
        }
        .expiry {
            color: #ef4444;
            font-size: 14px;
            margin-top: 20px;
        }
        .footer {
            background: #f9fafb;
            padding: 20px;
            text-align: center;
            color: #9ca3af;
            font-size: 12px;
        }
        .warning {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            color: #92400e;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">&lt;/&gt;</div>
            <h1>DevTeams</h1>
        </div>
        
        <div class="content">
            <p class="greeting">¡Hola, <strong>{{ $userName }}</strong>!</p>
            
            <p class="message">
                Gracias por registrarte en DevTeams. Para completar tu registro y verificar tu cuenta, ingresa el siguiente código de verificación:
            </p>
            
            <div class="code-container">
                <div class="code">{{ $code }}</div>
            </div>
            
            <p class="expiry">
                ⏰ Este código expirará en <strong>15 minutos</strong>
            </p>
            
            <div class="warning">
                ⚠️ Si no solicitaste este código, puedes ignorar este correo.
            </div>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} DevTeams. Todos los derechos reservados.</p>
            <p>Este es un correo automático, por favor no respondas.</p>
        </div>
    </div>
</body>
</html>
