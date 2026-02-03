<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $table = 'materiales';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'subcategoria_id',
        'unidad_medida',
        'cantidad_actual',
        'cantidad_minima',
        'precio_unitario',
        'estado'
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'cantidad_actual' => 'integer',
        'cantidad_minima' => 'integer',
    ];

    // Relación con Subcategoría
    public function subcategoria()
    {
        return $this->belongsTo(Subcategoria::class);
    }

    // Scope para materiales activos
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    // Scope para stock bajo
    public function scopeStockBajo($query)
    {
        return $query->whereColumn('cantidad_actual', '<=', 'cantidad_minima');
    }

    // Accessor para verificar si hay stock bajo
    public function getStockBajoAttribute()
    {
        return $this->cantidad_actual <= $this->cantidad_minima;
    }
}