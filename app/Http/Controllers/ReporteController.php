<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Juez;
use App\Models\Evento;
use App\Models\Equipo;
use App\Models\Participante;
use App\Models\Role;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    /**
     * Mostrar página principal de reportes
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->hasRole('Administrador')) {
            return redirect()->route('dashboard')
                ->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        return view('admin.reportes.index');
    }

    /**
     * Generar reporte de usuarios en PDF
     */
    public function usuarios()
    {
        $user = Auth::user();
        
        if (!$user->hasRole('Administrador')) {
            return redirect()->route('dashboard')
                ->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        $usuarios = User::with(['roles', 'participante.carrera', 'juez.especialidad'])
            ->orderBy('created_at', 'desc')
            ->get();

        $estadisticas = [
            'total' => $usuarios->count(),
            'activos' => $usuarios->where('is_active', true)->count(),
            'participantes' => $usuarios->filter(fn($u) => $u->hasRole('Participante'))->count(),
            'jueces' => $usuarios->filter(fn($u) => $u->hasRole('Juez'))->count(),
            'administradores' => $usuarios->filter(fn($u) => $u->hasRole('Administrador'))->count(),
        ];

        $pdf = Pdf::loadView('admin.reportes.pdf.usuarios', compact('usuarios', 'estadisticas'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('reporte-usuarios-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Generar reporte de jueces en PDF
     */
    public function jueces()
    {
        $user = Auth::user();
        
        if (!$user->hasRole('Administrador')) {
            return redirect()->route('dashboard')
                ->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        $jueces = Juez::with(['user', 'especialidad', 'eventos', 'calificaciones'])
            ->get();

        $estadisticas = [
            'total' => $jueces->count(),
            'con_eventos' => $jueces->filter(fn($j) => $j->eventos->count() > 0)->count(),
            'especialidades' => $jueces->pluck('especialidad.Nombre')->unique()->count(),
            'total_calificaciones' => $jueces->sum(fn($j) => $j->calificaciones->count()),
        ];

        $pdf = Pdf::loadView('admin.reportes.pdf.jueces', compact('jueces', 'estadisticas'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('reporte-jueces-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Generar reporte de eventos en PDF
     */
    public function eventos()
    {
        $user = Auth::user();
        
        if (!$user->hasRole('Administrador')) {
            return redirect()->route('dashboard')
                ->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        $eventos = Evento::with(['jueces', 'proyectos.equipo'])
            ->orderBy('Fecha_inicio', 'desc')
            ->get();

        $estadisticas = [
            'total' => $eventos->count(),
            'activos' => $eventos->where('Estado', 'Activo')->count(),
            'finalizados' => $eventos->where('Estado', 'Finalizado')->count(),
            'cancelados' => $eventos->where('Estado', 'Cancelado')->count(),
            'total_equipos' => $eventos->sum(fn($e) => $e->proyectos->count()),
        ];

        $pdf = Pdf::loadView('admin.reportes.pdf.eventos', compact('eventos', 'estadisticas'));
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download('reporte-eventos-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Generar reporte de equipos en PDF
     */
    public function equipos()
    {
        $user = Auth::user();
        
        if (!$user->hasRole('Administrador')) {
            return redirect()->route('dashboard')
                ->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        $equipos = Equipo::with(['participantes.usuario', 'proyectos.evento', 'proyectos.asesor'])
            ->get();

        $estadisticas = [
            'total' => $equipos->count(),
            'con_proyectos' => $equipos->filter(fn($e) => $e->proyectos->count() > 0)->count(),
            'total_miembros' => $equipos->sum(fn($e) => $e->participantes->count()),
            'promedio_miembros' => $equipos->count() > 0 ? round($equipos->sum(fn($e) => $e->participantes->count()) / $equipos->count(), 1) : 0,
        ];

        $pdf = Pdf::loadView('admin.reportes.pdf.equipos', compact('equipos', 'estadisticas'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('reporte-equipos-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Generar reporte de estadísticas generales en PDF
     */
    public function estadisticas()
    {
        $user = Auth::user();
        
        if (!$user->hasRole('Administrador')) {
            return redirect()->route('dashboard')
                ->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        $usuarios = User::with('roles')->get();
        $eventos = Evento::all();
        $equipos = Equipo::with('participantes')->get();
        $jueces = Juez::with('especialidad')->get();
        $participantes = Participante::with('carrera')->get();

        $estadisticas = [
            'usuarios' => [
                'total' => $usuarios->count(),
                'activos' => $usuarios->where('is_active', true)->count(),
                'por_rol' => [
                    'participantes' => $usuarios->filter(fn($u) => $u->hasRole('Participante'))->count(),
                    'jueces' => $usuarios->filter(fn($u) => $u->hasRole('Juez'))->count(),
                    'administradores' => $usuarios->filter(fn($u) => $u->hasRole('Administrador'))->count(),
                ],
            ],
            'eventos' => [
                'total' => $eventos->count(),
                'activos' => $eventos->where('Estado', 'Activo')->count(),
                'finalizados' => $eventos->where('Estado', 'Finalizado')->count(),
                'cancelados' => $eventos->where('Estado', 'Cancelado')->count(),
            ],
            'equipos' => [
                'total' => $equipos->count(),
                'total_miembros' => $equipos->sum(fn($e) => $e->participantes->count()),
                'promedio_miembros' => $equipos->count() > 0 ? round($equipos->sum(fn($e) => $e->participantes->count()) / $equipos->count(), 1) : 0,
            ],
            'jueces' => [
                'total' => $jueces->count(),
                'especialidades' => $jueces->pluck('especialidad.Nombre')->unique()->values()->all(),
            ],
            'participantes_por_carrera' => $participantes->groupBy('carrera.Nombre')->map(fn($group) => $group->count())->all(),
        ];

        $pdf = Pdf::loadView('admin.reportes.pdf.estadisticas', compact('estadisticas'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('reporte-estadisticas-' . date('Y-m-d') . '.pdf');
    }
}
