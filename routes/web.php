<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\GastoController;
use App\Http\Controllers\PagoController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\DiaNoLaborableController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

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
});

// Rutas de Admin 
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard y otras rutas existentes...
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/dashboard-data', [AdminController::class, 'getDashboardData'])->name('dashboard.data');

        // Rutas de usuarios
        Route::prefix('usuarios')->name('usuarios.')->group(function () {
            Route::get('/', [AdminController::class, 'usuarios'])->name('index');
            Route::post('/', [AdminController::class, 'storeUsuario'])->name('store');
            Route::get('/all', [AdminController::class, 'getAllUsers'])->name('all');
            Route::put('/{usuario}', [AdminController::class, 'update'])->name('update');
            Route::delete('/{usuario}', [AdminController::class, 'destroy'])->name('destroy');
            Route::get('/{usuario}/registros', [AdminController::class, 'getUserRecords'])->name('registros');
            Route::get('/check-email', [AdminController::class, 'checkEmail'])->name('check-email');

            // Acciones masivas
            Route::post('/bulk-activate', [AdminController::class, 'bulkActivate'])->name('bulk-activate');
            Route::post('/bulk-deactivate', [AdminController::class, 'bulkDeactivate'])->name('bulk-deactivate');
            Route::delete('/bulk-delete', [AdminController::class, 'bulkDelete'])->name('bulk-delete');
        });

        // Rutas de administración de citas
        Route::prefix('citasadmin')->name('citasadmin.')->group(function () {
            Route::get('/', [AdminController::class, 'citasAdmin'])->name('index');
            Route::put('/{cita}/actualizar-estado', [AdminController::class, 'actualizarEstadoCita'])->name('actualizar-estado');
            Route::get('/{cita}/detalles', [AdminController::class, 'getCitaDetalles'])->name('detalles');
        });

        // Rutas de PAGOS 
        Route::prefix('pagos')->name('pagos.')->group(function () {
            // Rutas principales
            Route::get('/{cita}/modal', [PagoController::class, 'showPagoModal'])->name('modal');
            Route::post('/{cita}/registrar', [PagoController::class, 'registrarPago'])->name('registrar');
            Route::get('/{cita}/info', [PagoController::class, 'getInfoPago'])->name('info');
            Route::post('/{cita}/reembolsar', [PagoController::class, 'reembolsarPago'])->name('reembolsar');
            Route::get('/{cita}/verificar-pago', [AdminController::class, 'verificarPagoCita'])->name('verificar-pago');

            // Rutas adicionales para mejor funcionalidad
            Route::get('/{cita}/historial', [PagoController::class, 'historialPagos'])->name('historial');

            // Rutas para reportes y estadísticas de pagos
            Route::get('/reporte-diario', [PagoController::class, 'reporteDiario'])->name('reporte-diario');
            Route::get('/reporte-mensual', [PagoController::class, 'reporteMensual'])->name('reporte-mensual');
            Route::get('/estadisticas', [PagoController::class, 'estadisticasPagos'])->name('estadisticas');
        });

        // Rutas de servicios
        Route::prefix('servicios')->name('servicios.')->group(function () {
            Route::get('/', [ServicioController::class, 'adminIndex'])->name('index');
            Route::get('/all', [AdminController::class, 'getAllServicios'])->name('all');
            Route::post('/', [AdminController::class, 'storeServicio'])->name('store');
            Route::get('/{id}', [AdminController::class, 'showServicio'])->name('show');
            Route::put('/{id}', [AdminController::class, 'updateServicio'])->name('update');
            Route::delete('/{id}', [AdminController::class, 'deleteServicio'])->name('destroy');
            Route::post('/{id}/toggle-status', [AdminController::class, 'toggleServicioStatus'])->name('toggle-status');
        });

        // Rutas de gastos
        Route::prefix('gastos')->name('gastos.')->group(function () {
            Route::get('/', [GastoController::class, 'index'])->name('index');
            Route::get('/crear', [GastoController::class, 'create'])->name('create');
            Route::post('/', [GastoController::class, 'store'])->name('store');
            Route::get('/{id}', [GastoController::class, 'show'])->name('show');
            Route::get('/{id}/editar', [GastoController::class, 'edit'])->name('edit');
            Route::put('/{id}', [GastoController::class, 'update'])->name('update');
            Route::delete('/{id}', [GastoController::class, 'destroy'])->name('destroy');

            Route::get('/tipo/{tipo}', [GastoController::class, 'filtrarPorTipo'])->name('tipo');
            Route::post('/filtrar-fechas', [GastoController::class, 'filtrarPorFechas'])->name('filtrar-fechas');
            Route::get('/estadisticas/resumen', [GastoController::class, 'resumen'])->name('resumen');
            Route::get('/estadisticas/por-tipo', [GastoController::class, 'estadisticasPorTipo'])->name('estadisticas-tipo');
        });

        // Otras rutas existentes
        Route::get('/reportes', [AdminController::class, 'reportes'])->name('reportes');
        Route::get('/bitacora', [BitacoraController::class, 'index'])->name('bitacora.index');
        Route::get('/bitacora/export-excel', [BitacoraController::class, 'exportExcel'])->name('bitacora.export-excel');
        Route::get('/bitacora/export-pdf', [BitacoraController::class, 'exportPdf'])->name('bitacora.export-pdf');

        // Rutas para días no laborables
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

        // Rutas para horarios
        Route::resource('horarios', \App\Http\Controllers\HorarioController::class);
    });
// Rutas de Empleado
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':empleado'])->prefix('empleado')->name('empleado.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [EmpleadoController::class, 'dashboard'])->name('dashboard');

    // Gestión de Citas
    Route::get('/citas', [EmpleadoController::class, 'citas'])->name('citas');

    // Rutas actualizadas para evitar conflictos
    Route::post('/citas/{id}/cambiar-estado', [EmpleadoController::class, 'cambiarEstadoCita'])->name('citas.cambiar-estado');
    Route::post('/citas/{id}/agregar-observaciones', [EmpleadoController::class, 'agregarObservaciones'])->name('citas.agregar-observaciones');
    Route::post('/citas/{id}/finalizar-completa', [EmpleadoController::class, 'finalizarCitaCompleta'])->name('citas.finalizar-completa');
    Route::post('/citas/finalizar-simple', [EmpleadoController::class, 'finalizarCitaSimple'])->name('citas.finalizar-simple');
    Route::get('/citas/{id}/detalles', [EmpleadoController::class, 'getCitaDetalles'])->name('citas.detalles');

    // Historial y Reportes
    Route::get('/historial', [EmpleadoController::class, 'historial'])->name('historial');
    Route::get('/bitacora', [EmpleadoController::class, 'bitacora'])->name('bitacora');

    // Configuración de Cuenta
    Route::post('/perfil/actualizar', [EmpleadoController::class, 'actualizarPerfil'])->name('perfil.actualizar');
    Route::post('/perfil/cambiar-password', [EmpleadoController::class, 'cambiarPassword'])->name('perfil.cambiar-password');

    // Servicios
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
        Route::get('/citas/historial', [ClienteController::class, 'historial'])->name('citas.historial');

        // Datos AJAX
        Route::get('/mis-vehiculos', [ClienteController::class, 'misVehiculosAjax'])->name('mis-vehiculos-ajax');
        Route::get('/servicios/all', [ClienteController::class, 'getAllServicios'])->name('servicios.all');
        Route::get('/check-status', [ClienteController::class, 'checkStatus'])->name('check-status');
        Route::get('/verificar-dia-no-laborable', [ClienteController::class, 'verificarDiaNoLaborable'])
            ->name('verificar-dia-no-laborable');

        // Gestión de citas - ORDEN IMPORTANTE
        // Rutas específicas PRIMERO (antes de las rutas con parámetros)
        Route::get('/citas/horarios-ocupados', [ClienteController::class, 'getHorariosOcupados'])
            ->name('citas.horarios-ocupados');

        // Rutas con parámetros DESPUÉS
        Route::get('/citas/{cita}/edit', [ClienteController::class, 'edit'])->name('citas.edit');
        Route::put('/citas/{cita}', [ClienteController::class, 'updateCita'])->name('citas.update');
        Route::post('/citas/{cita}/cancelar', [ClienteController::class, 'cancelarCita'])->name('citas.cancelar');

        // Ruta de creación al final
        Route::post('/citas', [ClienteController::class, 'storeCita'])->name('citas.store');

        // Datos para el dashboard
        Route::get('/dashboard-data', [ClienteController::class, 'getDashboardData'])->name('dashboard.data');

        // Datos para formularios 
        Route::get('/horarios-disponibles/{fecha}', [ClienteController::class, 'getHorariosDisponiblesPorFecha'])
            ->name('horarios-disponibles.fecha');

        // Datos para formularios
        Route::get('/horarios-disponibles', function () {
            return response()->json(
                App\Models\Horario::activos()->get()->map(function ($horario) {
                    return [
                        'dia_semana' => $horario->dia_semana,
                        'hora_inicio' => $horario->hora_inicio->format('H:i:s'),
                        'hora_fin' => $horario->hora_fin->format('H:i:s')
                    ];
                })
            );
        })->name('horarios-disponibles');

        Route::get('/servicios-disponibles', function () {
            return response()->json(
                App\Models\Servicio::activos()
                    ->get()
                    ->groupBy('categoria')
            );
        })->name('servicios-disponibles');

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

        // Rutas para facturas del cliente
        Route::get('/facturas', [ClienteController::class, 'facturas'])->name('facturas');
        Route::get('/facturas/{cita}', [ClienteController::class, 'verFactura'])->name('facturas.ver');
        Route::get('/facturas/{cita}/descargar', [ClienteController::class, 'descargarFactura'])->name('facturas.descargar');
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

Route::get('/debug-fechas', [ClienteController::class, 'debugFechas'])->name('debug-fechas');

/// Ruta para debug de citas por usuario (JSON)
Route::get('/debug/citas-usuario/{usuarioId}', [ClienteController::class, 'debugCitasUsuarioJson'])
    ->middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':admin'])
    ->name('debug.citas-usuario-json');

Route::get('/check-timezone', function () {
    // Verificar configuración de la base de datos
    $dbTime = DB::select(DB::raw("SELECT @@global.time_zone, @@session.time_zone, NOW() as current_time"));

    return response()->json([
        'app_timezone' => config('app.timezone'),
        'db_timezone' => $dbTime[0],
        'php_time' => now()->format('Y-m-d H:i:s'),
        'db_time' => $dbTime[0]->current_time
    ]);
});

// Toggle de horarios (mantiene mismo middleware/políticas del dashboard)
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':admin'])
    ->patch('/admin/horarios/{horario}/toggle', [\App\Http\Controllers\HorarioController::class, 'toggle'])
    ->name('admin.horarios.toggle');

// routes/web.php
Route::post('/cliente/debug-horarios', [ClienteController::class, 'debugHorarios'])
    ->name('cliente.debug-horarios')
    ->middleware('auth');

// Ruta temporal de debug para citas (SIN CSRF para testing)
Route::post('/debug/test-cita', function (Request $request) {
    return response()->json([
        'received_data' => $request->all(),
        'headers' => $request->headers->all(),
        'user_authenticated' => Auth::check(),
        'user_id' => Auth::id(),
        'csrf_token_present' => $request->header('X-CSRF-TOKEN') ? true : false,
        'method' => $request->method()
    ]);
})->name('debug.test-cita')->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// Ruta temporal para crear citas SIN validación de conflictos
Route::post('/cliente/citas/store-simple', [ClienteController::class, 'storeCitaSimple'])
    ->name('cliente.citas.store-simple')
    ->middleware('auth');
