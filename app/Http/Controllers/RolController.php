<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolController extends Controller
{
    /**
     * Mostrar lista de roles
     */
    public function index()
    {
        $roles = Rol::orderBy('id', 'desc')->get();
        return view('roles.index', compact('roles'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Guardar nuevo rol
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:roles,nombre',
            'descripcion' => 'nullable|string|max:500',
            'activo' => 'nullable|boolean',
        ], [
            'nombre.required' => 'El nombre del rol es obligatorio',
            'nombre.unique' => 'Ya existe un rol con este nombre',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres',
        ]);

        try {
            Rol::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'activo' => $request->has('activo') ? true : false,
            ]);

            return redirect()->route('roles.index')
                ->with('success', 'Rol creado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el rol: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $rol = Rol::findOrFail($id);
        return view('roles.edit', compact('rol'));
    }

    /**
     * Actualizar rol existente
     */
    public function update(Request $request, $id)
    {
        $rol = Rol::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100|unique:roles,nombre,' . $id,
            'descripcion' => 'nullable|string|max:500',
            'activo' => 'nullable|boolean',
        ], [
            'nombre.required' => 'El nombre del rol es obligatorio',
            'nombre.unique' => 'Ya existe un rol con este nombre',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres',
        ]);

        try {
            $rol->update([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'activo' => $request->has('activo') ? true : false,
            ]);

            return redirect()->route('roles.index')
                ->with('success', 'Rol actualizado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el rol: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar rol (cambiar estado a inactivo)
     */
    public function destroy($id)
    {
        try {
            $rol = Rol::findOrFail($id);
            
            // Verificar si el rol tiene usuarios asociados
            if ($rol->usuarios()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'No se puede eliminar el rol porque tiene usuarios asociados');
            }

            // Opción 1: Borrado lógico (cambiar estado)
            $rol->update(['activo' => false]);
            
            // Opción 2: Borrado físico (descomentar si prefieres esto)
            // $rol->delete();

            return redirect()->route('roles.index')
                ->with('success', 'Rol desactivado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar el rol: ' . $e->getMessage());
        }
    }

    /**
     * Activar un rol desactivado (opcional)
     */
    public function activar($id)
    {
        try {
            $rol = Rol::findOrFail($id);
            $rol->update(['activo' => true]);

            return redirect()->route('roles.index')
                ->with('success', 'Rol activado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al activar el rol: ' . $e->getMessage());
        }
    }
}