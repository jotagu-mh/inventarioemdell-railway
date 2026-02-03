<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Categoria;
use App\Models\Subcategoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Estadísticas generales
        $totalMateriales = Material::count();
        $totalCategorias = Categoria::count();
        $totalSubcategorias = Subcategoria::count();
        $materialesActivos = Material::where('estado', 'activo')->count();
        $materialesStockBajo = Material::whereColumn('cantidad_actual', '<=', 'cantidad_minima')->count();
        
        // Valor total del inventario
        $valorInventario = Material::sum(DB::raw('cantidad_actual * precio_unitario'));
        
        // Materiales con stock bajo
        $materialesAlerta = Material::with('subcategoria.categoria')
                                    ->whereColumn('cantidad_actual', '<=', 'cantidad_minima')
                                    ->limit(5)
                                    ->get();
        
        // Materiales más recientes
        $materialesRecientes = Material::with('subcategoria.categoria')
                                       ->latest()
                                       ->limit(5)
                                       ->get();

        return view('dashboard', compact(
            'totalMateriales',
            'totalCategorias',
            'totalSubcategorias',
            'materialesActivos',
            'materialesStockBajo',
            'valorInventario',
            'materialesAlerta',
            'materialesRecientes'
        ));
    }
}