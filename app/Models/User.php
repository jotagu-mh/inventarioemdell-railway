<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'apellido',
        'ci',
        'telefono',
        'email',
        'password',
        'rol_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Accesor para obtener el nombre completo
     */
    public function getNombreCompletoAttribute()
    {
        return "{$this->name} {$this->apellido}";
    }

    /**
     * Relación con rol
     */
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    /**
     * Relación con movimientos
     */
    public function movimientos()
    {
        return $this->hasMany(Movimiento::class, 'user_id');
    }

    /**
     * Scope para filtrar por rol
     */
    public function scopeRole($query, $rolId)
    {
        return $query->where('rol_id', $rolId);
    }

    /**
     * Verificar si el usuario es administrador
     */
    public function esAdministrador()
    {
        return $this->rol && $this->rol->nombre === 'Administrador';
    }

    /**
     * Verificar si el usuario es supervisor
     */
    public function esSupervisor()
    {
        return $this->rol && $this->rol->nombre === 'Supervisor';
    }

    /**
     * Verificar si el usuario es usuario normal
     */
    public function esUsuario()
    {
        return $this->rol && $this->rol->nombre === 'Usuario';
    }
}