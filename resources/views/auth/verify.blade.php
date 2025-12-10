<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DevTeams - Verificar Cuenta</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .code-input {
            width: 50px;
            height: 60px;
            font-size: 24px;
            text-align: center;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            outline: none;
            transition: all 0.2s;
        }
        .code-input:focus {
            border-color: #9333ea;
            box-shadow: 0 0 0 3px rgba(147, 51, 234, 0.1);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-purple-50 to-blue-50 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo y título -->
        <div class="text-center mb-8">
            <div class="flex items-center justify-center gap-2 mb-4">
                <div class="text-4xl text-purple-600">&lt;/&gt;</div>
                <span class="text-3xl font-bold text-gray-900">DevTeams</span>
            </div>
        </div>

        <!-- Card de verificación -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <div class="text-center mb-6">
                <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-4xl">📧</span>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Verifica tu correo</h2>
                <p class="text-gray-600">
                    Hemos enviado un código de 6 dígitos a<br>
                    <span class="font-semibold text-purple-600">{{ $email }}</span>
                </p>
            </div>
            
            <!-- Mensajes de éxito -->
            @if (session('success'))
                <div class="mb-6 flex items-center justify-center gap-3 p-4 bg-green-50 border border-green-200 rounded-xl">
                    <div class="flex-shrink-0 w-5 h-5 text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <p class="text-green-700 font-medium text-sm">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Errores -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                    <div class="flex items-center justify-center gap-3 mb-2">
                        <div class="flex-shrink-0 w-5 h-5 text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <ul class="text-sm text-red-600 text-center">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form method="POST" action="{{ route('verify.code') }}" class="space-y-6">
                @csrf
                
                <!-- Input de código (6 dígitos en inputs separados) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3 text-center">
                        Ingresa el código de verificación
                    </label>
                    <div class="flex justify-center gap-2" id="code-inputs">
                        <input type="text" maxlength="1" class="code-input" data-index="0" autofocus>
                        <input type="text" maxlength="1" class="code-input" data-index="1">
                        <input type="text" maxlength="1" class="code-input" data-index="2">
                        <input type="text" maxlength="1" class="code-input" data-index="3">
                        <input type="text" maxlength="1" class="code-input" data-index="4">
                        <input type="text" maxlength="1" class="code-input" data-index="5">
                    </div>
                    <input type="hidden" name="code" id="full-code">
                </div>

                <!-- Botón verificar -->
                <button 
                    type="submit"
                    class="w-full bg-purple-600 hover:bg-purple-700 text-white py-3 rounded-lg font-medium transition flex items-center justify-center gap-2"
                >
                    <span>Verificar código</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </button>
            </form>

            <!-- Reenviar código -->
            <div class="mt-6 pt-6 border-t border-gray-200 text-center">
                <p class="text-sm text-gray-600 mb-3">¿No recibiste el código?</p>
                <form method="POST" action="{{ route('verify.resend') }}">
                    @csrf
                    <button 
                        type="submit"
                        class="text-purple-600 hover:text-purple-700 font-medium text-sm"
                    >
                        Reenviar código
                    </button>
                </form>
            </div>

            <!-- Volver -->
            <div class="text-center mt-4">
                <a href="{{ route('register') }}" class="text-sm text-gray-500 hover:text-gray-700">
                    ← Volver al registro
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-sm text-gray-600">
            <p>© {{ date('Y') }} DevTeams. Todos los derechos reservados.</p>
        </div>
    </div>

    <script>
        const inputs = document.querySelectorAll('.code-input');
        const fullCode = document.getElementById('full-code');

        inputs.forEach((input, index) => {
            // Solo permitir números
            input.addEventListener('input', (e) => {
                let value = e.target.value.replace(/\D/g, '');
                e.target.value = value;
                
                if (value && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
                updateFullCode();
            });

            // Manejar backspace
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });

            // Manejar pegado
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pastedData = e.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6);
                
                for (let i = 0; i < pastedData.length && i < inputs.length; i++) {
                    inputs[i].value = pastedData[i];
                }
                
                if (pastedData.length > 0) {
                    inputs[Math.min(pastedData.length, inputs.length - 1)].focus();
                }
                updateFullCode();
            });
        });

        function updateFullCode() {
            let code = '';
            inputs.forEach(input => {
                code += input.value;
            });
            fullCode.value = code;
        }
    </script>
</body>
</html>
