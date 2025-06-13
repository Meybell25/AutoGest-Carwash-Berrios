<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'password',
        'rol',
        'estado',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'estado' => 'boolean',
    ];

    // Roles del sistema
    const ROL_ADMIN = 'admin';
    const ROL_EMPLEADO = 'empleado';
    const ROL_CLIENTE = 'cliente';

    /**
     * Verificar si el usuario es admin
     */
    public function isAdmin(): bool
    {
        return $this->rol === self::ROL_ADMIN;
    }

    /**
     * Verificar si el usuario es empleado
     */
    public function isEmpleado(): bool
    {
        return $this->rol === self::ROL_EMPLEADO;
    }

    /**
     * Verificar si el usuario es cliente
     */
    public function isCliente(): bool
    {
        return $this->rol === self::ROL_CLIENTE;
    }

    /**
     * Verificar si el usuario tiene un rol específico
     */
    public function hasRole(string $rol): bool
    {
        return $this->rol === $rol;
    }

    /**
     * Verificar si el usuario puede ser eliminado
     */
    public function canBeDeleted(): bool
    {
        return !$this->isAdmin();
    }

    /**
     * Scope para usuarios activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', true);
    }

    /**
     * Scope por rol
     */
    public function scopeByRol($query, string $rol)
    {
        return $query->where('rol', $rol);
    }

    // Relaciones
    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class, 'usuario_id');
    }

    public function citas()
    {
        return $this->hasMany(Cita::class, 'usuario_id');
    }

    public function gastos()
    {
        return $this->hasMany(Gasto::class, 'usuario_id');
    }

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class, 'usuario_id');
    }

    public function bitacoras()
    {
        return $this->hasMany(Bitacora::class, 'usuario_id');
    }
}