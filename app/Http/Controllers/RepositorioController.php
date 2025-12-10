<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Repositorio;
use App\Models\Proyecto;
use App\Models\Participante;
use App\Models\Equipo;

class RepositorioController extends Controller
{
    /**
     * Mostrar lista de repositorios del participante
     */
    public function index()
    {
        $user = Auth::user();
        $participante = Participante::where('user_id', $user->id)->first();

        if (!$participante) {
            return view('codigos.index', ['repositorios' => collect(), 'proyectos' => collect()]);
        }

        // Obtener todos los proyectos del participante a través de sus equipos
        $proyectos = Proyecto::whereIn('Equipo_id', function($query) use ($participante) {
            $query->select('Id_equipo')
                  ->from('participante_equipo')
                  ->where('Id_participante', $participante->Id);
        })->with('evento')->get();

        // Obtener repositorios de esos proyectos con el evento
        $repositorios = Repositorio::whereIn('Proyecto_id', $proyectos->pluck('Id'))
            ->with(['proyecto.evento'])
            ->get();

        return view('codigos.index', compact('repositorios', 'proyectos'));
    }

    /**
     * Crear nuevo repositorio
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'proyecto_id' => 'required|exists:proyecto,Id',
            'url' => 'required|url|regex:/^https?:\/\/(www\.)?github\.com\//',
        ], [
            'proyecto_id.required' => 'Debes seleccionar un proyecto',
            'proyecto_id.exists' => 'El proyecto seleccionado no existe',
            'url.required' => 'La URL es obligatoria',
            'url.url' => 'Debes ingresar una URL válida',
            'url.regex' => 'La URL debe ser de GitHub',
        ]);

        $user = Auth::user();
        $participante = Participante::where('user_id', $user->id)->first();

        // Verificar que el participante tenga acceso al proyecto
        $proyecto = Proyecto::findOrFail($validated['proyecto_id']);
        $perteneceAlEquipo = DB::table('participante_equipo')
            ->where('Id_participante', $participante->Id)
            ->where('Id_equipo', $proyecto->Equipo_id)
            ->exists();

        if (!$perteneceAlEquipo) {
            return back()->with('error', 'No tienes acceso para agregar repositorio a este proyecto.');
        }

        // Verificar si ya existe repositorio para este proyecto
        $yaExiste = Repositorio::where('Proyecto_id', $validated['proyecto_id'])->exists();
        
        if ($yaExiste) {
            return back()->with('error', 'Este proyecto ya tiene un repositorio vinculado.');
        }

        try {
            Repositorio::create([
                'Proyecto_id' => $validated['proyecto_id'],
                'Url' => $validated['url'],
            ]);

            return back()->with('success', 'Repositorio agregado exitosamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al agregar repositorio: ' . $e->getMessage()]);
        }
    }

    /**
     * Eliminar repositorio
     */
    public function destroy($id)
    {
        $repositorio = Repositorio::findOrFail($id);
        $user = Auth::user();
        $participante = Participante::where('user_id', $user->id)->first();

        // Verificar acceso
        $proyecto = $repositorio->proyecto;
        $perteneceAlEquipo = DB::table('participante_equipo')
            ->where('Id_participante', $participante->Id)
            ->where('Id_equipo', $proyecto->Equipo_id)
            ->exists();

        if (!$perteneceAlEquipo) {
            return back()->with('error', 'No tienes permisos para eliminar este repositorio.');
        }

        $repositorio->delete();

        return back()->with('success', 'Repositorio eliminado correctamente.');
    }

    /**
     * Subir archivo al repositorio
     */
    public function subirArchivo(Request $request, $id)
    {
        $repositorio = Repositorio::findOrFail($id);
        $user = Auth::user();
        $participante = Participante::where('user_id', $user->id)->first();

        // Verificar acceso
        $proyecto = $repositorio->proyecto;
        $perteneceAlEquipo = DB::table('participante_equipo')
            ->where('Id_participante', $participante->Id)
            ->where('Id_equipo', $proyecto->Equipo_id)
            ->exists();

        if (!$perteneceAlEquipo) {
            return response()->json(['success' => false, 'message' => 'No tienes permisos para subir archivos.'], 403);
        }

        // Verificar que el evento ya haya comenzado
        $evento = $proyecto->evento;
        if ($evento && now() < $evento->Fecha_inicio) {
            return response()->json([
                'success' => false, 
                'message' => 'No puedes subir archivos hasta que el evento haya comenzado. El evento inicia el ' . $evento->Fecha_inicio->format('d/m/Y H:i') . '.'
            ], 403);
        }

        // Verificar que el evento no haya finalizado
        if ($evento && now() > $evento->Fecha_fin) {
            return response()->json([
                'success' => false, 
                'message' => 'No puedes subir archivos porque el evento ya ha finalizado.'
            ], 403);
        }

        // Validar archivo
        $request->validate([
            'archivo' => 'required|file|mimes:jpg,jpeg,png,gif,pdf|max:5120', // 5MB máximo
        ], [
            'archivo.required' => 'Debes seleccionar un archivo',
            'archivo.mimes' => 'Solo se permiten archivos JPG, PNG, GIF y PDF',
            'archivo.max' => 'El archivo no puede pesar más de 5MB',
        ]);

        try {
            $archivo = $request->file('archivo');
            
            // Crear directorio si no existe
            $directorioPath = "repositorios/{$repositorio->Id}";
            
            // Guardar archivo con nombre único
            $nombreOriginal = $archivo->getClientOriginalName();
            $extension = $archivo->getClientOriginalExtension();
            $nombreUnico = time() . '_' . str_replace(' ', '_', pathinfo($nombreOriginal, PATHINFO_FILENAME)) . '.' . $extension;
            
            $rutaArchivo = $archivo->storeAs($directorioPath, $nombreUnico, 'public');

            // Agregar info del archivo al array
            $archivos = $repositorio->archivos ?? [];
            $archivos[] = [
                'nombre' => $nombreOriginal,
                'ruta' => $rutaArchivo,
                'tipo' => $extension,
                'tamano' => $archivo->getSize(),
                'fecha' => now()->toDateTimeString(),
            ];

            $repositorio->archivos = $archivos;
            $repositorio->save();

            return response()->json([
                'success' => true,
                'message' => 'Archivo subido exitosamente',
                'archivo' => $archivos[count($archivos) - 1]
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al subir archivo: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Eliminar archivo del repositorio
     */
    public function eliminarArchivo($id, $index)
    {
        $repositorio = Repositorio::findOrFail($id);
        $user = Auth::user();
        $participante = Participante::where('user_id', $user->id)->first();

        // Verificar acceso
        $proyecto = $repositorio->proyecto;
        $perteneceAlEquipo = DB::table('participante_equipo')
            ->where('Id_participante', $participante->Id)
            ->where('Id_equipo', $proyecto->Equipo_id)
            ->exists();

        if (!$perteneceAlEquipo) {
            return back()->with('error', 'No tienes permisos para eliminar archivos.');
        }

        // Verificar que el evento ya haya comenzado
        $evento = $proyecto->evento;
        if ($evento && now() < $evento->Fecha_inicio) {
            return back()->with('error', 'No puedes eliminar archivos hasta que el evento haya comenzado. El evento inicia el ' . $evento->Fecha_inicio->format('d/m/Y H:i') . '.');
        }

        // Verificar que el evento no haya finalizado
        if ($evento && now() > $evento->Fecha_fin) {
            return back()->with('error', 'No puedes eliminar archivos porque el evento ya ha finalizado.');
        }

        try {
            $archivos = $repositorio->archivos ?? [];
            
            if (!isset($archivos[$index])) {
                return back()->with('error', 'Archivo no encontrado.');
            }

            // Eliminar archivo físico
            $rutaArchivo = $archivos[$index]['ruta'];
            if (\Storage::disk('public')->exists($rutaArchivo)) {
                \Storage::disk('public')->delete($rutaArchivo);
            }

            // Eliminar del array
            unset($archivos[$index]);
            $repositorio->archivos = array_values($archivos); // Reindexar
            $repositorio->save();

            return back()->with('success', 'Archivo eliminado correctamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar archivo: ' . $e->getMessage());
        }
    }

    /**
     * Ver/Descargar archivo del repositorio (solución para producción)
     */
    public function verArchivo($id, $index)
    {
        $repositorio = Repositorio::findOrFail($id);
        $user = Auth::user();
        $participante = Participante::where('user_id', $user->id)->first();

        // Verificar acceso
        $proyecto = $repositorio->proyecto;
        $perteneceAlEquipo = DB::table('participante_equipo')
            ->where('Id_participante', $participante->Id)
            ->where('Id_equipo', $proyecto->Equipo_id)
            ->exists();

        if (!$perteneceAlEquipo) {
            abort(403, 'No tienes permisos para ver este archivo.');
        }

        $archivos = $repositorio->archivos ?? [];
        
        if (!isset($archivos[$index])) {
            abort(404, 'Archivo no encontrado.');
        }

        $rutaArchivo = $archivos[$index]['ruta'];
        
        if (!\Storage::disk('public')->exists($rutaArchivo)) {
            abort(404, 'El archivo no existe en el servidor.');
        }

        // Retornar el archivo para visualización
        return response()->file(storage_path('app/public/' . $rutaArchivo));
    }
}
