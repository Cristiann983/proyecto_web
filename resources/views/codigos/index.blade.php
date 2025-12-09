<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevTeams - Gestión de Código</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    @include('partials._navigation')

        <!-- Botón crear repositorio (arriba a la derecha) -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Gestión de Código</h2>
                <p class="text-gray-600">Repositorios, branches y commits de tus equipos</p>
            </div>
            <button 
                onclick="document.getElementById('modalCrearRepositorio').classList.remove('hidden')"
                class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-3 rounded-lg flex items-center gap-2 font-medium transition">
                <span>+</span>
                <span>Crear repositorio</span>
            </button>
        </div>


        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-600">✅ {{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-red-600">❌ {{ session('error') }}</p>
            </div>
        @endif

        <!-- Grid de repositorios -->
        @if($repositorios->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($repositorios as $repo)
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition">
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-gray-800 to-gray-900 p-6 text-white">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="text-2xl">📦</span>
                                    <h3 class="text-xl font-bold">{{ $repo->getNombreRepositorio() }}</h3>
                                </div>
                            </div>
                            <p class="text-sm opacity-75">{{ $repo->proyecto->Nombre ?? 'Proyecto' }}</p>
                        </div>

                        <!-- Contenido -->
                        <div class="p-6">
                            <!-- URL -->
                            <div class="mb-4">
                                <p class="text-xs text-gray-500 mb-1">URL del Repositorio</p>
                                <a href="{{ $repo->Url }}" target="_blank" class="text-purple-600 hover:text-purple-700 text-sm break-all flex items-center gap-2">
                                    🔗 Ver en GitHub
                                </a>
                            </div>

                            <!-- Proyecto -->
                            <div class="mb-4">
                                <p class="text-xs text-gray-500 mb-1">Proyecto Asociado</p>
                                <p class="text-sm font-medium text-gray-900">{{ $repo->proyecto->Nombre }}</p>
                            </div>

                            <!-- 📎 Archivos Subidos -->
                            @if($repo->archivos && count($repo->archivos) > 0)
                                <div class="mb-4">
                                    <p class="text-xs text-gray-500 mb-2">Archivos ({{ count($repo->archivos) }})</p>
                                    <div class="space-y-2 max-h-40 overflow-y-auto">
                                        @foreach($repo->archivos as $index => $archivo)
                                            <div class="flex items-center justify-between bg-gray-50 p-2 rounded">
                                                <div class="flex items-center gap-2 flex-1 min-w-0">
                                                    @if(in_array($archivo['tipo'], ['jpg', 'jpeg', 'png', 'gif']))
                                                        <img src="{{ asset('storage/' . $archivo['ruta']) }}" class="w-8 h-8 object-cover rounded">
                                                    @else
                                                        <span class="text-xl">📄</span>
                                                    @endif
                                                    <span class="text-xs text-gray-700 truncate">{{ $archivo['nombre'] }}</span>
                                                </div>
                                                <div class="flex gap-1">
                                                    <a href="{{ asset('storage/' . $archivo['ruta']) }}" target="_blank" class="text-purple-600 hover:text-purple-700 text-xs px-2 py-1">
                                                        Ver
                                                    </a>
                                                    <form action="{{ route('repositorios.eliminarArchivo', [$repo->Id, $index]) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar archivo?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-700 text-xs px-2 py-1">×</button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Botones -->
                            <div class="flex gap-2 mb-2">
                                <button onclick="openFileModal({{ $repo->Id }})" class="flex-1 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm rounded-lg transition flex items-center justify-center gap-1">
                                    <span>📎</span>
                                    <span>Subir Archivo</span>
                                </button>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ $repo->Url }}" target="_blank" class="flex-1 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm rounded-lg text-center transition">
                                    Abrir
                                </a>
                                <form action="{{ route('repositorios.destroy', $repo->Id) }}" method="POST" class="flex-1" onsubmit="return confirm('¿Estás seguro?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded-lg transition">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Estado vacío -->
            <div class="bg-white border-2 border-gray-200 rounded-2xl p-16">
                <div class="flex flex-col items-center justify-center text-center max-w-2xl mx-auto">
                    <!-- Icono de código -->
                    <div class="mb-8">
                        <svg class="w-32 h-32 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                        </svg>
                    </div>

                    <!-- Mensaje -->
                    <h3 class="text-xl font-bold text-gray-900 mb-3">No hay repositorios</h3>
                    <p class="text-gray-600 mb-8">Crea tu primer repositorio para comenzar a gestionar código</p>

                    <!-- Botón -->
                    <button 
                        onclick="document.getElementById('modalCrearRepositorio').classList.remove('hidden')"
                        class="bg-gray-800 hover:bg-gray-900 text-white px-8 py-3 rounded-lg flex items-center gap-2 font-medium transition">
                        <span>+</span>
                        <span>Crea tu primer repositorio</span>
                    </button>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal Crear Repositorio -->
    <div id="modalCrearRepositorio" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900">Crear Nuevo Repositorio</h2>
                    <button 
                        onclick="document.getElementById('modalCrearRepositorio').classList.add('hidden')"
                        class="text-gray-400 hover:text-gray-600 text-2xl"
                    >
                        &times;
                    </button>
                </div>
            </div>
            
            <form method="POST" action="{{ route('repositorios.store') }}" class="p-6 space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Proyecto *</label>
                    <select 
                        name="proyecto_id"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    >
                        <option value="">Selecciona un proyecto</option>
                        @forelse($proyectos as $proyecto)
                            <option value="{{ $proyecto->Id }}">{{ $proyecto->Nombre }}</option>
                        @empty
                            <option value="" disabled>No tienes proyectos disponibles</option>
                        @endforelse
                    </select>
                    @error('proyecto_id')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">URL de GitHub *</label>
                    <input 
                        type="url" 
                        name="url"
                        placeholder="https://github.com/usuario/repositorio"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    >
                    <p class="text-xs text-gray-500 mt-1">Ej: https://github.com/usuario/mi-proyecto</p>
                    @error('url')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-4">
                    <button 
                        type="button"
                        onclick="document.getElementById('modalCrearRepositorio').classList.add('hidden')"
                        class="flex-1 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition"
                    >
                        Cancelar
                    </button>
                    <button 
                        type="submit"
                        class="flex-1 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition"
                    >
                        Crear Repositorio
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Subir Archivo -->
    <div id="modalSubirArchivo" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900">Subir Archivo</h2>
                    <button onclick="closeFileModal()" class="text-gray-400 hover:text-gray-600 text-2xl">
                        &times;
                    </button>
                </div>
            </div>
            
            <form id="formSubirArchivo" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <input type="hidden" id="repositorio_id" name="repositorio_id">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Archivo *</label>
                    <input 
                        type="file" 
                        name="archivo"
                        id="inputArchivo"
                        accept="image/*,.pdf"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    >
                    <p class="text-xs text-gray-500 mt-1">Formatos: JPG, PNG, GIF, PDF (Máx 5MB)</p>
                </div>

                <!-- Preview -->
                <div id="previewContainer" class="hidden">
                    <p class="text-sm font-medium text-gray-700 mb-2">Vista previa:</p>
                    <div id="previewContent" class="border border-gray-200 rounded-lg p-4"></div>
                </div>

                <div id="mensajeError" class="hidden p-3 bg-red-50 border border-red-200 rounded-lg text-red-600 text-sm"></div>
                <div id="mensajeExito" class="hidden p-3 bg-green-50 border border-green-200 rounded-lg text-green-600 text-sm"></div>

                <div class="flex gap-3 pt-4">
                    <button 
                        type="button"
                        onclick="closeFileModal()"
                        class="flex-1 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition"
                    >
                        Cancelar
                    </button>
                    <button 
                        type="submit"
                        id="btnSubir"
                        class="flex-1 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition"
                    >
                        Subir Archivo
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('modalCrearRepositorio').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });

        // Modal de archivos
        function openFileModal(repoId) {
            document.getElementById('repositorio_id').value = repoId;
            document.getElementById('modalSubirArchivo').classList.remove('hidden');
            document.getElementById('formSubirArchivo').reset();
            document.getElementById('previewContainer').classList.add('hidden');
            document.getElementById('mensajeError').classList.add('hidden');
            document.getElementById('mensajeExito').classList.add('hidden');
        }

        function closeFileModal() {
            document.getElementById('modalSubirArchivo').classList.add('hidden');
        }

        // Preview de archivo
        document.getElementById('inputArchivo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const previewContainer = document.getElementById('previewContainer');
            const previewContent = document.getElementById('previewContent');
            
            if (file) {
                const reader = new FileReader();
                
                if (file.type.startsWith('image/')) {
                    reader.onload = function(e) {
                        previewContent.innerHTML = `<img src="${e.target.result}" class="max-h-48 mx-auto rounded">`;
                        previewContainer.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewContent.innerHTML = `
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded">
                            <span class="text-4xl">📄</span>
                            <div>
                                <p class="font-medium text-gray-900">${file.name}</p>
                                <p class="text-sm text-gray-600">${(file.size / 1024).toFixed(2)} KB</p>
                            </div>
                        </div>
                    `;
                    previewContainer.classList.remove('hidden');
                }
            }
        });

        // Subir archivo via AJAX
        document.getElementById('formSubirArchivo').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const repoId = document.getElementById('repositorio_id').value;
            const btnSubir = document.getElementById('btnSubir');
            const mensajeError = document.getElementById('mensajeError');
            const mensajeExito = document.getElementById('mensajeExito');
            
            btnSubir.disabled = true;
            btnSubir.textContent = 'Subiendo...';
            mensajeError.classList.add('hidden');
            mensajeExito.classList.add('hidden');
            
            try {
                const response = await fetch(`/repositorios/${repoId}/subir-archivo`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    mensajeExito.textContent = data.message;
                    mensajeExito.classList.remove('hidden');
                    
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    mensajeError.textContent = data.message;
                    mensajeError.classList.remove('hidden');
                }
            } catch (error) {
                mensajeError.textContent = 'Error al subir el archivo';
                mensajeError.classList.remove('hidden');
            } finally {
                btnSubir.disabled = false;
                btnSubir.textContent = 'Subir Archivo';
            }
        });
    </script>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex gap-8 text-sm text-gray-600">
                    <a href="#" class="hover:text-gray-900">Acerca de</a>
                    <a href="#" class="hover:text-gray-900">Acerca de</a>
                    <a href="#" class="hover:text-gray-900">Acerca de</a>
                </div>
                <div class="flex items-center gap-2 text-purple-600">
                    <div class="text-xl">&lt;/&gt;</div>
                    <div>
                        <p class="font-bold">DevTeams</p>
                        <p class="text-xs">✨ Plataforma de Desarrollo</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>