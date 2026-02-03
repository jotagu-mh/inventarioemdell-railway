<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Movimiento extends Model
{
    use HasFactory;

    protected $table = 'movimientos';

    protected $fillable = [
        'user_id',
        'material_id',
        'tipo',
        'fecha',
        'cantidad',
        'costo_unitario',
        'total',
        'numero_factura',
        'numero_ingreso',
        'proveedor',
        'numero_salida',
        'solicitante',
        'motivo',
        'saldo_cantidad',
        'saldo_total',
        'observaciones'
    ];

    protected $casts = [
        'fecha' => 'date',
        'cantidad' => 'integer',
        'costo_unitario' => 'decimal:2',
        'total' => 'decimal:2',
        'saldo_cantidad' => 'integer',
        'saldo_total' => 'decimal:2'
    ];


    /**
     * Relación con el usuario que registró el movimiento
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación con Material
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    /**
     * Accessor para tipo formateado
     */
    public function getTipoFormateadoAttribute(): string
    {
        return $this->tipo === 'entrada' ? 'ENTRADA' : 'SALIDA';
    }

    /**
     * Scope para entradas
     */
    public function scopeEntradas($query)
    {
        return $query->where('tipo', 'entrada');
    }

    /**
     * Scope para salidas
     */
    public function scopeSalidas($query)
    {
        return $query->where('tipo', 'salida');
    }

    /**
     * Scope por material
     */
    public function scopePorMaterial($query, $materialId)
    {
        return $query->where('material_id', $materialId);
    }

    /**
     * Scope por rango de fechas
     */
    public function scopePorFechas($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha', [$fechaInicio, $fechaFin]);
    }

    /**
     * Método para calcular el total automáticamente
     */
    public function calcularTotal(): void
    {
        $this->attributes['total'] = $this->cantidad * $this->costo_unitario;
    }

    /**
     * Boot method para eventos del modelo
     */
    protected static function boot(): void
    {
        parent::boot();

        // Antes de crear, calcular el total
        static::creating(function ($movimiento) {
            $movimiento->calcularTotal();
        });

        // Antes de actualizar, recalcular el total
        static::updating(function ($movimiento) {
            $movimiento->calcularTotal();
        });
    }
}