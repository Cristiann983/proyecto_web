<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Invitacion;
use App\Models\Participante;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Compartir contadores de notificaciones con la navegación
        View::composer('partials._navigation', function ($view) {
            $invitacionesPendientes = 0;
            
            if (Auth::check()) {
                $user = Auth::user();
                $participante = Participante::where('user_id', $user->id)->first();
                
                if ($participante) {
                    // Contar invitaciones pendientes recibidas por el usuario
                    $invitacionesPendientes = Invitacion::where('Participante_id', $participante->Id)
                        ->where('Estado', 'Pendiente')
                        ->count();
                }
            }
            
            $view->with('invitacionesPendientes', $invitacionesPendientes);
        });
    }
}
