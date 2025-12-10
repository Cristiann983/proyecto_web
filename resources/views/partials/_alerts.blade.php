{{-- Componente de alertas profesionales --}}

@if (session('success'))
    <div class="mb-4 flex items-center gap-3 p-4 bg-green-50 border border-green-200 rounded-xl" role="alert">
        <div class="flex-shrink-0 w-5 h-5 text-green-600">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
            </svg>
        </div>
        <p class="text-green-700 font-medium">{{ session('success') }}</p>
    </div>
@endif

@if (session('error'))
    <div class="mb-4 flex items-center gap-3 p-4 bg-red-50 border border-red-200 rounded-xl" role="alert">
        <div class="flex-shrink-0 w-5 h-5 text-red-600">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
            </svg>
        </div>
        <p class="text-red-700 font-medium">{{ session('error') }}</p>
    </div>
@endif

@if ($errors->any())
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl" role="alert">
        <div class="flex items-center gap-3 mb-2">
            <div class="flex-shrink-0 w-5 h-5 text-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                </svg>
            </div>
            <p class="text-red-700 font-medium">Por favor corrige los siguientes errores:</p>
        </div>
        <ul class="list-disc list-inside text-red-600 text-sm ml-8">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
