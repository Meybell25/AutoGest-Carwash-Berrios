<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\DiaNoLaborableController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Ruta principal
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Rutas de autenticación
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard general
    Route::get('/dashboard', function () {
        $user = Auth::user();
        switch ($user->rol) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'empleado':
                return redirect()->route('empleado.dashboard');
            case 'cliente':
                return redirect()->route('cliente.dashboard');
            default:
                return redirect('/');
        }
    })->name('dashboard');

    Route::resource('vehiculos', VehiculoController::class);

    // Rutas para Servicios
    Route::prefix('servicios')->name('servicios.')->group(function () {
        Route::get('/', [ServicioController::class, 'index'])->name('index');
        Route::get('/categoria/{categoria}', [ServicioController::class, 'porCategoria'])->name('categoria');
        Route::get('/{id}', [ServicioController::class, 'show'])->name('show');
    });

    // Rutas para Días No Laborables
    Route::prefix('dias-no-laborables')->name('dias-no-laborables.')->group(function () {
        Route::get('/', [DiaNoLaborableController::class, 'index'])->name('index');
        Route::get('/crear', [DiaNoLaborableController::class, 'create'])->name('create');
        Route::post('/', [DiaNoLaborableController::class, 'store'])->name('store');
        Route::get('/{id}', [DiaNoLaborableController::class, 'show'])->name('show');
        Route::get('/{id}/editar', [DiaNoLaborableController::class, 'edit'])->name('edit');
        Route::put('/{id}', [DiaNoLaborableController::class, 'update'])->name('update');
        Route::delete('/{id}', [DiaNoLaborableController::class, 'destroy'])->name('destroy');

        Route::get('/proximos', [DiaNoLaborableController::class, 'proximos'])->name('proximos');
        Route::get('/del-mes', [DiaNoLaborableController::class, 'delMes'])->name('del-mes');
        Route::get('/laborables', [DiaNoLaborableController::class, 'diasLaborables'])->name('laborables');
        Route::get('/motivos', [DiaNoLaborableController::class, 'motivos'])->name('motivos');
    });
});

// Rutas de Admin
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/dashboard-data', [AdminController::class, 'getDashboardData'])->name('dashboard.data');

        Route::prefix('usuarios')->name('usuarios.')->group(function () {
            Route::get('/', [AdminController::class, 'usuarios'])->name('index');
            Route::post('/', [AdminController::class, 'storeUsuario'])->name('store');
            Route::get('/all', [AdminController::class, 'getAllUsers'])->name('all');
            Route::put('/{usuario}', [AdminController::class, 'update'])->name('update');
            Route::delete('/{usuario}', [AdminController::class, 'destroy'])->name('destroy');
            Route::get('/{usuario}/registros', [AdminController::class, 'getUserRecords'])->name('registros');
            Route::get('/check-email', [AdminController::class, 'checkEmail'])->name('check-email');

            // Rutas para acciones masivas
            Route::post('/bulk-activate', [AdminController::class, 'bulkActivate'])->name('bulk-activate');
            Route::post('/bulk-deactivate', [AdminController::class, 'bulkDeactivate'])->name('bulk-deactivate');
            Route::delete('/bulk-delete', [AdminController::class, 'bulkDelete'])->name('bulk-delete');
        });

        Route::prefix('citas')->name('citas.')->group(function () {
            Route::get('/create', [AdminController::class, 'createCita'])->name('create');
            Route::post('/', [AdminController::class, 'storeCita'])->name('store');
        });

        Route::get('/reportes', [AdminController::class, 'reportes'])->name('reportes');

        Route::prefix('servicios')->name('servicios.')->group(function () {
            Route::get('/', [ServicioController::class, 'adminIndex'])->name('index');
            Route::get('/crear', [ServicioController::class, 'create'])->name('create');
            Route::post('/', [ServicioController::class, 'store'])->name('store');
            Route::get('/{id}/editar', [ServicioController::class, 'edit'])->name('edit');
            Route::put('/{id}', [ServicioController::class, 'update'])->name('update');
            Route::delete('/{id}', [ServicioController::class, 'destroy'])->name('destroy');
        });

        //Rutas para horarios
        Route::resource('horarios', \App\Http\Controllers\HorarioController::class);
    });

// Rutas de Empleado
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':empleado'])->prefix('empleado')->name('empleado.')->group(function () {
    Route::get('/dashboard', [EmpleadoController::class, 'dashboard'])->name('dashboard');
    Route::get('/citas', [EmpleadoController::class, 'citas'])->name('citas');
    Route::get('/servicios', [ServicioController::class, 'empleadoIndex'])->name('servicios.index');
});

// Rutas de Cliente
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':cliente'])
    ->prefix('cliente')
    ->name('cliente.')
    ->group(function () {
        // Dashboard y vistas principales
        Route::get('/dashboard', [ClienteController::class, 'dashboard'])->name('dashboard');
        Route::get('/vehiculos', [ClienteController::class, 'vehiculos'])->name('vehiculos');
        Route::get('/citas', [ClienteController::class, 'citas'])->name('citas');

        // Datos AJAX
        Route::get('/mis-vehiculos', [ClienteController::class, 'misVehiculosAjax'])->name('mis-vehiculos-ajax');
        Route::get('/check-status', [ClienteController::class, 'checkStatus'])->name('check-status');

        // Gestión de citas
        Route::post('/citas', [ClienteController::class, 'storeCita'])->name('citas.store');
        Route::post('/citas/{cita}/cancelar', [ClienteController::class, 'cancelarCita'])->name('citas.cancelar');

        // Datos para formularios
        Route::get('/horarios-disponibles', function () {
            return response()->json(
                App\Models\Horario::activos()->get()->map(function ($horario) {
                    return [
                        'dia_semana' => $horario->dia_semana,
                        'hora_inicio' => $horario->hora_inicio->format('H:i:s'), // Asegurar formato completo
                        'hora_fin' => $horario->hora_fin->format('H:i:s')
                    ];
                })
            );
        })->name('horarios-disponibles');

        // En tu ruta /servicios-disponibles
        Route::get('/servicios-disponibles', function () {
            return response()->json(
                App\Models\Servicio::activos()
                    ->get()
                    ->groupBy('categoria')
            );
        });

        Route::get('/dias-no-laborables', function () {
            return response()->json(
                App\Models\DiaNoLaborable::futuros()
                    ->orderBy('fecha')
                    ->get()
                    ->map(function ($dia) {
                        return [
                            'fecha' => $dia->fecha->format('Y-m-d'),
                            'motivo' => $dia->motivo
                        ];
                    })
            );
        })->name('dias-no-laborables');

        // Servicios
        Route::get('/servicios', [ServicioController::class, 'index'])->name('servicios.index');
    });

// Rutas de perfil
Route::middleware('auth')->prefix('perfil')->name('perfil.')->group(function () {
    Route::get('/', [PerfilController::class, 'edit'])->name('edit');
    Route::post('/actualizar', [PerfilController::class, 'update'])->name('update');
    Route::post('/actualizar-ajax', [PerfilController::class, 'updateAjax'])->name('update-ajax');
});

// Rutas de configuración
Route::middleware('auth')->prefix('configuracion')->name('configuracion.')->group(function () {
    Route::get('/', [PerfilController::class, 'configuracion'])->name('index');
    Route::post('/actualizar-email', [PerfilController::class, 'updateEmail'])->name('update-email');
    Route::post('/actualizar-password', [PerfilController::class, 'updatePassword'])->name('update-password');
});

// Rutas de prueba
Route::get('/debug', function () {
    $user = App\Models\Usuario::first();
    return [
        'user' => $user->toArray(),
        'vehiculos' => $user->vehiculos->toArray(),
        'citas' => $user->citas->toArray()
    ];
});

Route::get('/debug-session', function () {
    return [
        'session_id' => session()->getId(),
        'session_data' => session()->all(),
        'user' => Auth::user(),
        'session_config' => [
            'driver' => config('session.driver'),
            'cookie' => config('session.cookie'),
            'secure' => config('session.secure')
        ]
    ];
});

Route::get('/test-middleware', function () {
    return response()->json([
        'message' => 'Middleware test passed',
        'user' => Auth::user(),
        'role' => Auth::user()->rol ?? null
    ]);
})->middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':cliente']);

// Ruta para verificar horarios
Route::get('/debug/horarios', function () {
    $horarios = App\Models\Horario::activos()->get();

    if ($horarios->isEmpty()) {
        return response()->json([
            'status' => 'error',
            'message' => 'No hay horarios configurados',
            'data' => []
        ]);
    }

    return response()->json([
        'status' => 'success',
        'count' => $horarios->count(),
        'data' => $horarios->map(function ($h) {
            return [
                'id' => $h->id,
                'dia_semana' => $h->dia_semana,
                'hora_inicio' => $h->hora_inicio,
                'hora_fin' => $h->hora_fin,
                'activo' => $h->activo
            ];
        })
    ]);
});

// Ruta para verificar servicios
Route::get('/debug/servicios', function () {
    $servicios = App\Models\Servicio::activos()->get();

    if ($servicios->isEmpty()) {
        return response()->json([
            'status' => 'error',
            'message' => 'No hay servicios configurados',
            'data' => []
        ]);
    }

    return response()->json([
        'status' => 'success',
        'count' => $servicios->count(),
        'data' => $servicios->map(function ($s) {
            return [
                'id' => $s->id,
                'nombre' => $s->nombre,
                'categoria' => $s->categoria,
                'activo' => $s->activo
            ];
        })
    ]);
});
