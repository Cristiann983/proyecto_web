<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Evento;
use App\Models\Participante;
use App\Models\Equipo;
use App\Models\Proyecto;
use App\Models\Asesor;

class EventoController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $participante = Participante::where('user_id', $user->id)->first();

        // Obtener filtros de la petición
        $filtro = $request->get('filtro', 'activos'); // activos, proximos, finalizados, todos
        $categoria = $request->get('categoria', 'todos');
        $buscar = $request->get('buscar', '');

        // Query base
        $query = Evento::query();

        // Aplicar filtro por estado/fecha
        $hoy = now();
        switch ($filtro) {
            case 'activos':
                // Eventos que están en curso (fecha inicio <= hoy <= fecha fin)
                $query->where('Fecha_inicio', '<=', $hoy)
                      ->where('Fecha_fin', '>=', $hoy)
                      ->where('Estado', 'Activo');
                break;
            case 'proximos':
                // Eventos que aún no han comenzado
                $query->where('Fecha_inicio', '>', $hoy)
                      ->where('Estado', 'Activo');
                break;
            case 'finalizados':
                // Eventos que ya terminaron
                $query->where('Fecha_fin', '<', $hoy);
                break;
            case 'todos':
                // Sin filtro de fecha
                break;
        }

        // Filtrar por categoría
        if ($categoria !== 'todos') {
            $query->where('Categoria', $categoria);
        }

        // Buscar por nombre o descripción
        if (!empty($buscar)) {
            $query->where(function($q) use ($buscar) {
                $q->where('Nombre', 'like', "%{$buscar}%")
                  ->orWhere('Descripcion', 'like', "%{$buscar}%");
            });
        }

        // Ordenar: primero los activos, luego por fecha de inicio
        $eventos = $query->orderByRaw("
            CASE
                WHEN Fecha_inicio <= NOW() AND Fecha_fin >= NOW() THEN 1
                WHEN Fecha_inicio > NOW() THEN 2
                ELSE 3
            END
        ")
        ->orderBy('Fecha_inicio', 'asc')
        ->paginate(6);

        // Obtener categorías únicas para el filtro
        $categorias = Evento::select('Categoria')->distinct()->pluck('Categoria');

        // ✅ Verificar en qué eventos está inscrito el participante
        $eventosInscritos = [];
        if ($participante) {
            $eventosInscritos = DB::table('proyecto')
                ->join('participante_equipo', 'proyecto.Equipo_id', '=', 'participante_equipo.Id_equipo')
                ->where('participante_equipo.Id_participante', $participante->Id)
                ->pluck('proyecto.Evento_id')
                ->toArray();
        }

        // Marcar eventos inscritos y clasificar por estado
        foreach ($eventos as $evento) {
            $evento->estaInscrito = in_array($evento->Id, $eventosInscritos);

            // Clasificar estado del evento dinámicamente
            $ahora = now();
            if ($ahora > $evento->Fecha_fin) {
                $evento->estadoEvento = 'finalizado';
            } elseif ($ahora >= $evento->Fecha_inicio && $ahora <= $evento->Fecha_fin) {
                $evento->estadoEvento = 'activo';
            } else {
                $evento->estadoEvento = 'proximo';
            }
        }

        return view('eventos.index', compact('eventos', 'participante', 'categorias', 'filtro', 'categoria', 'buscar'));
    }

    public function show($id)
    {
        $evento = Evento::with('jueces')->findOrFail($id);
        $user = Auth::user();
        
        // Determinar estado dinámicamente basado en fechas
        $ahora = now();
        if ($ahora > $evento->Fecha_fin) {
            $evento->estado = 'finalizado';
        } elseif ($ahora >= $evento->Fecha_inicio && $ahora <= $evento->Fecha_fin) {
            $evento->estado = 'activo';
        } else {
            $evento->estado = 'proximo';
        }
        
        $participante = Participante::where('user_id', $user->id)->first();
        
        $estaInscrito = false;
        $proyectos = collect();
        
        if ($participante) {
            $proyectos = DB::table('proyecto')
                ->join('equipo', 'proyecto.Equipo_id', '=', 'equipo.Id')
                ->join('participante_equipo', 'equipo.Id', '=', 'participante_equipo.Id_equipo')
                ->where('proyecto.Evento_id', $evento->Id)
                ->where('participante_equipo.Id_participante', $participante->Id)
                ->select('proyecto.*', 'equipo.Nombre as nombre_equipo')
                ->get();
            
            $estaInscrito = $proyectos->isNotEmpty();
        }

        // 🏆 Cargar proyectos del evento con ranking
        $proyectosRanking = collect();
        // Verificar si el evento está finalizado (por fecha O por estado en BD)
        if ($evento->estado === 'finalizado' || $evento->Estado === 'Finalizado') {
            $proyectosRanking = Proyecto::where('Evento_id', $evento->Id)
                ->with(['equipo'])
                ->whereNotNull('ranking_posicion')
                ->orderBy('ranking_posicion', 'asc')
                ->get();
        }
        
        return view('eventos.show', compact('evento', 'estaInscrito', 'proyectos', 'participante', 'proyectosRanking'));
    }

    public function inscripcion($id)
    {
        $evento = Evento::findOrFail($id);
        $user = Auth::user();
        
        $participante = Participante::where('user_id', $user->id)->first();
        
        if (!$participante) {
            return redirect()->back()
                ->with('error', 'No se encontró tu perfil de participante. Contacta al administrador.');
        }
        
        $equiposIds = DB::table('participante_equipo')
            ->where('Id_participante', $participante->Id)
            ->pluck('Id_equipo');
        
        $equipos = Equipo::whereIn('Id', $equiposIds)
            ->withCount('participantes')
            ->get();
        
        if ($equipos->isEmpty()) {
            return redirect()->back()
                ->with('error', 'Debes pertenecer a un equipo para inscribirte. Crea o únete a un equipo primero.');
        }

        $asesores = Asesor::all();
        
        return view('eventos.inscripcion', compact('evento', 'equipos', 'asesores', 'participante'));
    }


    public function inscribirse(Request $request, $id)
    {
        // Verificar que el evento no haya finalizado
        $evento = Evento::findOrFail($id);
        $ahora = now();
        
        if ($ahora > $evento->Fecha_fin) {
            return redirect()->back()
                ->with('error', 'No puedes inscribirse. Este evento ya ha finalizado.');
        }
        
        // Validar datos del formulario
        $validated = $request->validate([
            'equipo_id' => 'required|exists:equipo,Id',
            'asesor_id' => 'required|exists:asesor,Id',
            'nombre_proyecto' => 'required|string|max:255',
            'categoria' => 'required|string|max:100',
        ], [
            'equipo_id.required' => 'Debes seleccionar un equipo',
            'asesor_id.required' => 'Debes seleccionar un asesor',
            'nombre_proyecto.required' => 'El nombre del proyecto es obligatorio',
            'categoria.required' => 'Debes seleccionar una categoría',
        ]);

        $user = Auth::user();
        $participante = Participante::where('user_id', $user->id)->first();

        if (!$participante) {
            return redirect()->back()
                ->with('error', 'Debes ser un participante para inscribirte.')
                ->withInput();
        }

        // Verificar que el participante pertenece al equipo
        $perteneceAlEquipo = DB::table('participante_equipo')
            ->where('Id_participante', $participante->Id)
            ->where('Id_equipo', $request->equipo_id)
            ->exists();

        if (!$perteneceAlEquipo) {
            return redirect()->back()
                ->with('error', 'No perteneces al equipo seleccionado.')
                ->withInput();
        }

        DB::beginTransaction();

        try {
            // Verificar si el equipo ya está inscrito en este evento
            $yaInscrito = Proyecto::where('Evento_id', $id)
                ->where('Equipo_id', $request->equipo_id)
                ->exists();

            if ($yaInscrito) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Este equipo ya está inscrito en el evento.')
                    ->withInput();
            }

            // Crear el proyecto
            $proyecto = Proyecto::create([
                'Equipo_id' => $request->equipo_id,
                'Evento_id' => $id,
                'Asesor_id' => $request->asesor_id,
                'Nombre' => $request->nombre_proyecto,
                'Categoria' => $request->categoria,
            ]);

            DB::commit();

            // ✅ Redirigir a la vista de eventos con mensaje de éxito
            return redirect()->route('eventos.index')
                ->with('success', '¡Inscripción exitosa! Tu equipo "' . $proyecto->Nombre . '" ha sido inscrito al evento "' . Evento::find($id)->Nombre . '".');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Error al inscribir: ' . $e->getMessage())
                ->withInput();
        }
    }
}
