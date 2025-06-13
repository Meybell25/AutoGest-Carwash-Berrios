<?php
// app/Models/Bitacora.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth; // ← Agregar esta línea

class Bitacora extends Model
{
    use HasFactory;

    protected $table = 'bitacora';

    protected $fillable = [
        'usuario_id',
        'accion',
        'ip',
        'fecha',
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    // Desactivar timestamps automáticos ya que usamos 'fecha'
    public $timestamps = false;

    // Tipos de acciones comunes
    const ACCION_LOGIN = 'login';
    const ACCION_LOGOUT = 'logout';
    const ACCION_CREAR_CITA = 'crear_cita';
    const ACCION_ACTUALIZAR_CITA = 'actualizar_cita';
    const ACCION_CANCELAR_CITA = 'cancelar_cita';
    const ACCION_REGISTRAR_VEHICULO = 'registrar_vehiculo';
    const ACCION_PROCESAR_PAGO = 'procesar_pago';
    const ACCION_CREAR_USUARIO = 'crear_usuario';
    const ACCION_ACTUALIZAR_USUARIO = 'actualizar_usuario';
    const ACCION_ELIMINAR_USUARIO = 'eliminar_usuario';

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    // Scopes
    public function scopeByUsuario($query, $usuarioId)
    {
        return $query->where('usuario_id', $usuarioId);
    }

    public function scopeByAccion($query, $accion)
    {
        return $query->where('accion', $accion);
    }

    public function scopeRecientes($query, $dias = 30)
    {
        return $query->where('fecha', '>=', now()->subDays($dias));
    }

    public function scopeHoy($query)
    {
        return $query->whereDate('fecha', today());
    }

    public function scopeRangoFechas($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha', [$fechaInicio, $fechaFin]);
    }

    // Métodos estáticos para crear registros de bitácora
    public static function registrar(string $accion, int $usuarioId = null, string $ip = null): self
    {
        return self::create([
            'usuario_id' => $usuarioId ?? Auth::id(), // ← Cambiar auth()->id() por Auth::id()
            'accion' => $accion,
            'ip' => $ip ?? request()->ip(),
            'fecha' => now(),
        ]);
    }

    public static function login(int $usuarioId, string $ip = null): self
    {
        return self::registrar(self::ACCION_LOGIN, $usuarioId, $ip);
    }

    public static function logout(int $usuarioId, string $ip = null): self
    {
        return self::registrar(self::ACCION_LOGOUT, $usuarioId, $ip);
    }
}