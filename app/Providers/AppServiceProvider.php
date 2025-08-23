<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;
use App\Models\Usuario;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

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
        // Configurar longitud de cadenas por defecto para MySQL
        Schema::defaultStringLength(191);
        
        // Forzar HTTPS en producción (Railway)
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }


        // 🔐 Definir Gate para verificar rol de administrador
        Gate::define('is-admin', function (Usuario $user) {
            return $user->isAdmin();

        // Compartir la información del usuario y su rol con todas las vistas
        View::composer('*', function ($view) {
            $userRole = Auth::check() ? Auth::user()->rol : 'guest';
            $view->with('userRole', $userRole);

        });
    }
}
