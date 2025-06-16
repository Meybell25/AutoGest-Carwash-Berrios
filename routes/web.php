<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ClienteController;
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
    
    // Dashboard general (redirecciona según rol)
    Route::get('/dashboard', function () {
        $user = Auth::user(); // ← Cambiar auth()->user() por Auth::user()
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
});

// Rutas de Admin (solo administradores)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('usuarios');
    
    // Aquí puedes agregar más rutas de admin:
    // Route::resource('servicios', ServicioController::class);
    // Route::resource('horarios', HorarioController::class);
    // Route::get('/reportes', [AdminController::class, 'reportes'])->name('reportes');
});

// Rutas de Empleado (solo empleados)
Route::middleware(['auth', 'role:empleado'])->prefix('empleado')->name('empleado.')->group(function () {
    Route::get('/dashboard', [EmpleadoController::class, 'dashboard'])->name('dashboard');
    Route::get('/citas', [EmpleadoController::class, 'citas'])->name('citas');
    
    // Aquí puedes agregar más rutas de empleado:
    // Route::put('/citas/{cita}/actualizar', [EmpleadoController::class, 'actualizarCita'])->name('citas.actualizar');
    // Route::get('/calendario', [EmpleadoController::class, 'calendario'])->name('calendario');
});

// Rutas de Cliente (solo clientes)
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':cliente'])->prefix('cliente')->name('cliente.')->group(function () {
    Route::get('/dashboard', [ClienteController::class, 'dashboard'])->name('dashboard');
    Route::get('/vehiculos', [ClienteController::class, 'vehiculos'])->name('vehiculos');
    Route::get('/citas', [ClienteController::class, 'citas'])->name('citas');
});

// Rutas que requieren autenticación pero sin restricción de rol específico
Route::middleware('auth')->group(function () {
    Route::get('/perfil', function () {
        return view('perfil');
    })->name('perfil');
    
    // Aquí puedes agregar rutas generales para usuarios autenticados
});
Route::get('/debug', function() {
    $user = App\Models\Usuario::first();
    
    return [
        'user' => $user->toArray(),
        'vehiculos' => $user->vehiculos->toArray(),
        'citas' => $user->citas->toArray()
    ];
});

Route::get('/debug-session', function() {
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

// Ruta de prueba modificada
Route::get('/test-middleware', function() {
    return response()->json([
        'message' => 'Middleware test passed',
        'user' => Auth::user(),
        'role' => Auth::user()->rol ?? null
    ]);
})->middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':cliente']);