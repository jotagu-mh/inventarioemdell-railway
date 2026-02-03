<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    // Mostrar todas las categorías
    public function index()
    {
        $categorias = Categoria::withCount('subcategorias')->get();
        return view('categorias.index', compact('categorias'));
    }

    // Mostrar formulario de crear
    public function create()
    {
        return view('categorias.create');
    }

    // Guardar nueva categoría
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:100',
            'descripcion' => 'nullable'
        ]);

        Categoria::create($request->all());

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría creada exitosamente');
    }

    // Mostrar formulario de editar
    public function edit(Categoria $categoria)
    {
        return view('categorias.edit', compact('categoria'));
    }

    // Actualizar categoría
    public function update(Request $request, Categoria $categoria)
    {
        $request->validate([
            'nombre' => 'required|max:100',
            'descripcion' => 'nullable'
        ]);

        $categoria->update($request->all());

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría actualizada exitosamente');
    }

    // Eliminar categoría
    public function destroy(Categoria $categoria)
    {
        $categoria->delete();

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría eliminada exitosamente');
    }

    // Ver subcategorías de una categoría específica
    public function subcategorias(Categoria $categoria)
    {
        $subcategorias = $categoria->subcategorias;
        return view('categorias.subcategorias', compact('categoria', 'subcategorias'));
    }
}

