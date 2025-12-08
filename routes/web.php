<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\AdminEventoController;
use App\Http\Controllers\AdminEquipoController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\JuezController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\CalificacionController;
use App\Http\Controllers\RepositorioController;
use App\Http\Controllers\InvitacionController;
use App\Http\Controllers\AdminJuezController;
use App\Http\Controllers\SolicitudEquipoController;

// Rutas públicas
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas de Perfil - Accesibles para todos los usuarios autenticados
Route::middleware(['auth'])->group(function () {
    Route::get('/perfil', [PerfilController::class, 'show'])->name('perfil.show');
    Route::get('/perfil/editar', [PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');
});

// Rutas para PARTICIPANTES
Route::middleware(['role:Participante'])->group(function () {
    Route::get('/dashboard', [EquipoController::class, 'index'])->name('dashboard');
    Route::post('/equipos', [EquipoController::class, 'store'])->name('equipos.store');
    Route::get('/equipos/{id}', [EquipoController::class, 'show'])->name('equipos.show');
    Route::post('/equipos/{id}/salir', [EquipoController::class, 'leave'])->name('equipos.leave');
    Route::post('/equipos/{id}/invitar', [EquipoController::class, 'invite'])->name('equipos.invite');

    Route::get('/eventos', [EventoController::class, 'index'])->name('eventos.index');
    Route::get('/eventos/{id}', [EventoController::class, 'show'])->name('eventos.show');
    Route::get('/eventos/{id}/inscripcion', [EventoController::class, 'inscripcion'])->name('eventos.inscripcion');
    Route::post('/eventos/{id}/inscribir', [EventoController::class, 'inscribirse'])->name('eventos.inscribirse');

    Route::get('/codigos', [RepositorioController::class, 'index'])->name('codigos.index');
    Route::post('/repositorios', [RepositorioController::class, 'store'])->name('repositorios.store');
    Route::delete('/repositorios/{id}', [RepositorioController::class, 'destroy'])->name('repositorios.destroy');

    // Rutas de Invitaciones
    Route::get('/invitaciones', [InvitacionController::class, 'index'])->name('invitaciones.index');
    Route::post('/invitaciones/{id}/aceptar', [InvitacionController::class, 'aceptar'])->name('invitaciones.aceptar');
    Route::post('/invitaciones/{id}/rechazar', [InvitacionController::class, 'rechazar'])->name('invitaciones.rechazar');
    Route::delete('/invitaciones/{id}/cancelar', [InvitacionController::class, 'cancelar'])->name('invitaciones.cancelar');

    // Rutas de Solicitudes para unirse a equipos
    Route::post('/equipos/{id}/solicitar', [SolicitudEquipoController::class, 'store'])->name('solicitudes.store');
    Route::get('/mis-solicitudes', [SolicitudEquipoController::class, 'misSolicitudes'])->name('solicitudes.mis-solicitudes');
    Route::get('/mi-estado-solicitudes', [SolicitudEquipoController::class, 'miEstado'])->name('solicitudes.mi-estado');
    Route::get('/buscar-equipos', [SolicitudEquipoController::class, 'buscarEquipos'])->name('solicitudes.buscar-equipos');
    Route::post('/solicitudes/{id}/aceptar', [SolicitudEquipoController::class, 'aceptar'])->name('solicitudes.aceptar');
    Route::post('/solicitudes/{id}/rechazar', [SolicitudEquipoController::class, 'rechazar'])->name('solicitudes.rechazar');
});

// Rutas ADMIN (prefix 'admin')
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Ruta de Jueces - Accesible por Administrador Y Juez
    Route::middleware(['auth'])->group(function () {
        Route::get('/jueces', [JuezController::class, 'index'])->name('jueces.index');

        // Rutas de Calificaciones - Solo para Jueces
        Route::post('/calificaciones', [CalificacionController::class, 'store'])->name('calificaciones.store');
        Route::get('/calificaciones/{proyecto_id}', [CalificacionController::class, 'getCalificaciones'])->name('calificaciones.get');
    });

    // Rutas SOLO para Administradores
    Route::middleware(['role:Administrador'])->group(function () {
        // Equipos
        Route::get('/equipos', [AdminEquipoController::class, 'index'])->name('equipos.index');
        Route::get('/equipos/{id}', [AdminEquipoController::class, 'show'])->name('equipos.show');
        Route::delete('/equipos/{id}', [AdminEquipoController::class, 'destroy'])->name('equipos.destroy');

        // Eventos
        Route::get('/eventos', [AdminEventoController::class, 'index'])->name('eventos.index');
        Route::post('/eventos', [AdminEventoController::class, 'store'])->name('eventos.store');
        Route::get('/eventos/{id}', [AdminEventoController::class, 'show'])->name('eventos.show');
        Route::get('/eventos/{id}/equipos', [AdminEventoController::class, 'equiposInscritos'])->name('eventos.equipos');
        Route::get('/eventos/{id}/edit', [AdminEventoController::class, 'edit'])->name('eventos.edit');
        Route::put('/eventos/{id}', [AdminEventoController::class, 'update'])->name('eventos.update');
        Route::delete('/eventos/{id}', [AdminEventoController::class, 'destroy'])->name('eventos.destroy');

        // Gestión de Jueces (solo para administradores)
        Route::get('/jueces/list', [AdminJuezController::class, 'index'])->name('jueces.list');
        Route::get('/jueces/create', [AdminJuezController::class, 'create'])->name('jueces.create');
        Route::post('/jueces', [AdminJuezController::class, 'store'])->name('jueces.store');
        Route::get('/jueces/{id}/show', [AdminJuezController::class, 'show'])->name('jueces.show');
        Route::get('/jueces/{id}/edit', [AdminJuezController::class, 'edit'])->name('jueces.edit');
        Route::put('/jueces/{id}', [AdminJuezController::class, 'update'])->name('jueces.update');
        Route::delete('/jueces/{id}', [AdminJuezController::class, 'destroy'])->name('jueces.destroy');

        // Gestión de Usuarios (solo para administradores)
        Route::get('/usuarios', [App\Http\Controllers\AdminUsuarioController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/create', [App\Http\Controllers\AdminUsuarioController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios', [App\Http\Controllers\AdminUsuarioController::class, 'store'])->name('usuarios.store');
        Route::get('/usuarios/{id}', [App\Http\Controllers\AdminUsuarioController::class, 'show'])->name('usuarios.show');
        Route::get('/usuarios/{id}/edit', [App\Http\Controllers\AdminUsuarioController::class, 'edit'])->name('usuarios.edit');
        Route::put('/usuarios/{id}', [App\Http\Controllers\AdminUsuarioController::class, 'update'])->name('usuarios.update');
        Route::delete('/usuarios/{id}', [App\Http\Controllers\AdminUsuarioController::class, 'destroy'])->name('usuarios.destroy');

        // Reportes (solo para administradores)
        Route::get('/reportes', [App\Http\Controllers\ReporteController::class, 'index'])->name('reportes.index');
        Route::get('/reportes/usuarios', [App\Http\Controllers\ReporteController::class, 'usuarios'])->name('reportes.usuarios');
        Route::get('/reportes/jueces', [App\Http\Controllers\ReporteController::class, 'jueces'])->name('reportes.jueces');
        Route::get('/reportes/eventos', [App\Http\Controllers\ReporteController::class, 'eventos'])->name('reportes.eventos');
        Route::get('/reportes/equipos', [App\Http\Controllers\ReporteController::class, 'equipos'])->name('reportes.equipos');
        Route::get('/reportes/estadisticas', [App\Http\Controllers\ReporteController::class, 'estadisticas'])->name('reportes.estadisticas');
    });
});