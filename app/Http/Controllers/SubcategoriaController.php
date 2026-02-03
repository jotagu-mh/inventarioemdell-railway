<?php

namespace App\Http\Controllers;

use App\Models\Subcategoria;
use App\Models\Categoria;
use Illuminate\Http\Request;

class SubcategoriaController extends Controller
{
    // Mostrar formulario de crear
    public function create($categoria_id)
    {
        $categoria = Categoria::findOrFail($categoria_id);
        return view('subcategorias.create', compact('categoria'));
    }

    // Guardar nueva subcategoría
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:100',
            'descripcion' => 'nullable',
            'categoria_id' => 'required|exists:categorias,id'
        ]);

        Subcategoria::create($request->all());

        return redirect()->route('categorias.subcategorias', $request->categoria_id)
            ->with('success', 'Subcategoría creada exitosamente');
    }

    // Mostrar formulario de editar
    public function edit(Subcategoria $subcategoria)
    {
        return view('subcategorias.edit', compact('subcategoria'));
    }

    // Actualizar subcategoría
    public function update(Request $request, Subcategoria $subcategoria)
    {
        $request->validate([
            'nombre' => 'required|max:100',
            'descripcion' => 'nullable'
        ]);

        $subcategoria->update($request->all());

        return redirect()->route('categorias.subcategorias', $subcategoria->categoria_id)
            ->with('success', 'Subcategoría actualizada exitosamente');
    }

    // Eliminar subcategoría
    public function destroy(Subcategoria $subcategoria)
    {
        $categoria_id = $subcategoria->categoria_id;
        $subcategoria->delete();
        
        return redirect()->route('categorias.subcategorias', $categoria_id)
            ->with('success', 'Subcategoría eliminada exitosamente');
    }
}