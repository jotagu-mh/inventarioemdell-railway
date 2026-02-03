<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = User::orderBy('created_at', 'desc')->paginate(10);
        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Rol::where('activo', true)->orderBy('nombre')->get();
        return view('usuarios.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'ci' => ['required', 'string', 'max:20', 'unique:users,ci'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'rol_id' => ['required', 'exists:roles,id'],
        ], [
            'name.required' => 'El nombre es obligatorio',
            'apellido.required' => 'El apellido es obligatorio',
            'ci.required' => 'El CI es obligatorio',
            'ci.unique' => 'Este CI ya está registrado',
            'email.required' => 'El email es obligatorio',
            'email.email' => 'Debe ser un email válido',
            'email.unique' => 'Este email ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'rol_id.required' => 'Debe seleccionar un rol',
            'rol_id.exists' => 'El rol seleccionado no es válido',
        ]);

        // Crear usuario
        $usuario = User::create([
            'name' => $validated['name'],
            'apellido' => $validated['apellido'],
            'ci' => $validated['ci'],
            'telefono' => $validated['telefono'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'rol_id' => $validated['rol_id'],
        ]);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $usuario)
    {
        // Cargar movimientos y contar
        $totalMovimientos = $usuario->movimientos()->count();
        $totalEntradas = $usuario->movimientos()->where('tipo', 'entrada')->count();
        $totalSalidas = $usuario->movimientos()->where('tipo', 'salida')->count();
        $ultimoMovimiento = $usuario->movimientos()->latest()->first();

        return view('usuarios.show', compact('usuario', 'totalMovimientos', 'totalEntradas', 'totalSalidas', 'ultimoMovimiento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $usuario)
    {
        $roles = Rol::where('activo', true)->orderBy('nombre')->get();
        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $usuario)
    {
        // Validación
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'ci' => ['required', 'string', 'max:20', 'unique:users,ci,' . $usuario->id],
            'telefono' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $usuario->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'rol_id' => ['required', 'exists:roles,id'],
        ], [
            'name.required' => 'El nombre es obligatorio',
            'apellido.required' => 'El apellido es obligatorio',
            'ci.required' => 'El CI es obligatorio',
            'ci.unique' => 'Este CI ya está registrado',
            'email.required' => 'El email es obligatorio',
            'email.email' => 'Debe ser un email válido',
            'email.unique' => 'Este email ya está registrado',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'rol_id.required' => 'Debe seleccionar un rol',
            'rol_id.exists' => 'El rol seleccionado no es válido',
        ]);

        // Actualizar datos
        $usuario->name = $validated['name'];
        $usuario->apellido = $validated['apellido'];
        $usuario->ci = $validated['ci'];
        $usuario->telefono = $validated['telefono'];
        $usuario->email = $validated['email'];
        $usuario->rol_id = $validated['rol_id']; // ← CAMBIO AQUÍ (estaba 'rol')

        // Solo actualizar contraseña si se proporcionó una nueva
        if ($request->filled('password')) {
            $usuario->password = Hash::make($validated['password']);
        }

        $usuario->save();

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $usuario)
    {
        // Prevenir que el usuario se elimine a sí mismo
        if ($usuario->id === auth()->id()) {
            return redirect()
                ->route('usuarios.index')
                ->with('error', 'No puedes eliminar tu propio usuario');
        }

        $usuario->delete();

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario eliminado exitosamente');
    }
}