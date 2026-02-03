<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'categoria_id'
    ];

    // Relación: Una subcategoría pertenece a una categoría
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    // Relación: Una subcategoría tiene muchos materiales
    public function materiales()
    {
        return $this->hasMany(Material::class);
    }
}