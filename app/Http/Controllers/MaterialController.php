<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Subcategoria;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = Material::with('subcategoria.categoria');

        // Búsqueda
        if ($request->has('buscar') && $request->buscar != '') {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('codigo', 'like', "%{$buscar}%")
                    ->orWhere('nombre', 'like', "%{$buscar}%")
                    ->orWhere('descripcion', 'like', "%{$buscar}%")
                    ->orWhereHas('subcategoria', function ($subQ) use ($buscar) {
                        $subQ->where('nombre', 'like', "%{$buscar}%");
                    })
                    ->orWhereHas('subcategoria.categoria', function ($catQ) use ($buscar) {
                        $catQ->where('nombre', 'like', "%{$buscar}%");
                    });
            });
        }

        $materiales = $query->orderBy('created_at', 'desc')->paginate(5);

        return view('materiales.index', compact('materiales'));
    }

    public function create()
    {
        $subcategorias = Subcategoria::with('categoria')->get();
        return view('materiales.create', compact('subcategorias'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:50|unique:materiales,codigo',
            'nombre' => 'required|string|max:200',
            'descripcion' => 'nullable|string',
            'subcategoria_id' => 'required|exists:subcategorias,id',
            'unidad_medida' => 'required|string|max:50',
            'cantidad_actual' => 'required|integer|min:0',
            'cantidad_minima' => 'required|integer|min:0',
            'precio_unitario' => 'required|numeric|min:0',
            'estado' => 'required|in:activo,inactivo'
        ]);

        Material::create($validated);

        return redirect()->route('materiales.index')
            ->with('success', 'Material creado exitosamente');
    }

    public function show(Material $material)
    {
        $material->load('subcategoria.categoria');
        return view('materiales.show', compact('material'));
    }

    public function edit(Material $material)
    {
        $subcategorias = Subcategoria::with('categoria')->get();
        return view('materiales.edit', compact('material', 'subcategorias'));
    }

    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:50|unique:materiales,codigo,' . $material->id,
            'nombre' => 'required|string|max:200',
            'descripcion' => 'nullable|string',
            'subcategoria_id' => 'required|exists:subcategorias,id',
            'unidad_medida' => 'required|string|max:50',
            'cantidad_actual' => 'required|integer|min:0',
            'cantidad_minima' => 'required|integer|min:0',
            'precio_unitario' => 'required|numeric|min:0',
            'estado' => 'required|in:activo,inactivo'
        ]);

        $material->update($validated);

        return redirect()->route('materiales.index')
            ->with('success', 'Material actualizado exitosamente');
    }

    public function destroy(Material $material)
    {
        try {
            $material->delete();
            return redirect()->route('materiales.index')
                ->with('success', 'Material eliminado exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('materiales.index')
                ->with('error', 'No se puede eliminar el material porque tiene registros relacionados');
        }
    }
}