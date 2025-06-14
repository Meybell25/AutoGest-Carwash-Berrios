<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ClienteController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function() {
    return "Login page";
})->name('login');

Route::get('/home', function() {
    return "Home page";
})->name('home');

// Rutas protegidas por rol
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
});

Route::middleware(['auth', 'empleado'])->prefix('empleado')->group(function () {
    Route::get('/dashboard', [EmpleadoController::class, 'dashboard'])->name('empleado.dashboard');
    Route::get('/citas', [EmpleadoController::class, 'citas'])->name('empleado.citas');
});

Route::middleware(['auth', 'cliente'])->prefix('cliente')->group(function () {
    Route::get('/dashboard', [ClienteController::class, 'dashboard'])->name('cliente.dashboard');
    Route::get('/vehiculos', [ClienteController::class, 'vehiculos'])->name('cliente.vehiculos');
    Route::get('/citas', [ClienteController::class, 'citas'])->name('cliente.citas');
});