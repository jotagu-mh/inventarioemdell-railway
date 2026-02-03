<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MovimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Movimiento::with('material');

        // Filtros
        if ($request->filled('material_id')) {
            $query->where('material_id', $request->material_id);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->porFechas($request->fecha_inicio, $request->fecha_fin);
        }

        $movimientos = $query->orderBy('fecha', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $materiales = Material::orderBy('nombre')->get();

        return view('movimientos.index', compact('movimientos', 'materiales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $tipo = $request->query('tipo', 'entrada'); // Por defecto entrada
        $materiales = Material::where('estado', 'activo')->orderBy('nombre')->get();

        return view('movimientos.create', compact('materiales', 'tipo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validación base
        $rules = [
            'material_id' => 'required|exists:materiales,id',
            'tipo' => 'required|in:entrada,salida',
            'fecha' => 'required|date',
            'cantidad' => 'required|integer|min:1',
            'costo_unitario' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string|max:1000'
        ];

        // Validación específica para ENTRADA
        if ($request->tipo === 'entrada') {
            $rules['numero_factura'] = 'nullable|string|max:100';
            $rules['numero_ingreso'] = 'required|string|max:100';
            $rules['proveedor'] = 'required|string|max:200';
        }

        // Validación específica para SALIDA
        if ($request->tipo === 'salida') {
            $rules['numero_salida'] = 'required|string|max:100';
            $rules['solicitante'] = 'required|string|max:200';
            $rules['motivo'] = 'required|string|max:1000';
        }

        $validated = $request->validate($rules);

        try {
            DB::beginTransaction();

            // Obtener el material
            $material = Material::findOrFail($validated['material_id']);

            // Obtener el último saldo del material
            $ultimoMovimiento = Movimiento::where('material_id', $material->id)
                ->orderBy('fecha', 'desc')
                ->orderBy('created_at', 'desc')
                ->first();

            $saldoAnteriorCantidad = $ultimoMovimiento ? $ultimoMovimiento->saldo_cantidad : 0;
            $saldoAnteriorTotal = $ultimoMovimiento ? $ultimoMovimiento->saldo_total : 0;

            // Calcular total del movimiento actual
            $totalMovimiento = $validated['cantidad'] * $validated['costo_unitario'];

            // Calcular nuevo saldo según el tipo de movimiento
            if ($validated['tipo'] === 'entrada') {
                $nuevoSaldoCantidad = $saldoAnteriorCantidad + $validated['cantidad'];
                $nuevoSaldoTotal = $saldoAnteriorTotal + $totalMovimiento;
            } else { // salida
                // Verificar que haya suficiente stock
                if ($saldoAnteriorCantidad < $validated['cantidad']) {
                    DB::rollBack();
                    return back()->withErrors([
                        'cantidad' => 'No hay suficiente stock. Stock actual: ' . $saldoAnteriorCantidad
                    ])->withInput();
                }

                $nuevoSaldoCantidad = $saldoAnteriorCantidad - $validated['cantidad'];
                $nuevoSaldoTotal = $saldoAnteriorTotal - $totalMovimiento;
            }

            // Crear el movimiento
            $movimiento = new Movimiento($validated);
            $movimiento->user_id = auth()->id(); // ← AGREGAR ESTA LÍNEA
            $movimiento->total = $totalMovimiento; // ← AGREGAR ESTA LÍNEA (para guardar el total del movimiento)
            $movimiento->saldo_cantidad = $nuevoSaldoCantidad;
            $movimiento->saldo_total = $nuevoSaldoTotal;
            $movimiento->save();

            // Actualizar el stock actual del material
            $material->cantidad_actual = $nuevoSaldoCantidad;
            $material->save();

            DB::commit();

            return redirect()->route('movimientos.index')
                ->with('success', 'Movimiento registrado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al registrar el movimiento: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Movimiento $movimiento): View
    {
        $movimiento->load('material');
        return view('movimientos.show', compact('movimiento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movimiento $movimiento): View
    {
        $materiales = Material::where('estado', 'activo')->orderBy('nombre')->get();
        return view('movimientos.edit', compact('movimiento', 'materiales'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Movimiento $movimiento): RedirectResponse
    {
        // Validación base
        $rules = [
            'fecha' => 'required|date',
            'costo_unitario' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string|max:1000'
        ];

        // Validación específica según tipo
        if ($movimiento->tipo === 'entrada') {
            $rules['numero_factura'] = 'nullable|string|max:100';
            $rules['numero_ingreso'] = 'required|string|max:100';
            $rules['proveedor'] = 'required|string|max:200';
        } else {
            $rules['numero_salida'] = 'required|string|max:100';
            $rules['solicitante'] = 'required|string|max:200';
            $rules['motivo'] = 'required|string|max:1000';
        }

        $validated = $request->validate($rules);

        try {
            DB::beginTransaction();

            // Actualizar solo campos permitidos (no cantidad ni material)
            $movimiento->update($validated);

            // Recalcular saldos si cambió el costo unitario
            $this->recalcularSaldos($movimiento->material_id);

            DB::commit();

            return redirect()->route('movimientos.index')
                ->with('success', 'Movimiento actualizado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al actualizar: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movimiento $movimiento): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $materialId = $movimiento->material_id;

            // Verificar si es el último movimiento
            $ultimoMovimiento = Movimiento::where('material_id', $materialId)
                ->orderBy('fecha', 'desc')
                ->orderBy('created_at', 'desc')
                ->first();

            if ($ultimoMovimiento && $ultimoMovimiento->id !== $movimiento->id) {
                DB::rollBack();
                return back()->withErrors([
                    'error' => 'Solo se puede eliminar el último movimiento registrado'
                ]);
            }

            $movimiento->delete();

            // Recalcular saldos
            $this->recalcularSaldos($materialId);

            DB::commit();

            return redirect()->route('movimientos.index')
                ->with('success', 'Movimiento eliminado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al eliminar: ' . $e->getMessage()]);
        }
    }

    /**
     * Recalcular saldos de todos los movimientos de un material
     */
    private function recalcularSaldos(int $materialId): void
    {
        $movimientos = Movimiento::where('material_id', $materialId)
            ->orderBy('fecha', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        $saldoCantidad = 0;
        $saldoTotal = 0.0;

        foreach ($movimientos as $mov) {
            if ($mov->tipo === 'entrada') {
                $saldoCantidad += $mov->cantidad;
                $saldoTotal += ($mov->cantidad * $mov->costo_unitario);
            } else {
                $saldoCantidad -= $mov->cantidad;
                $saldoTotal -= ($mov->cantidad * $mov->costo_unitario);
            }

            $mov->setAttribute('saldo_cantidad', $saldoCantidad);
            $mov->setAttribute('saldo_total', $saldoTotal);
            $mov->save();
        }

        // Actualizar stock del material
        $material = Material::find($materialId);
        if ($material) {
            $material->cantidad_actual = $saldoCantidad;
            $material->save();
        }
    }

    /**
     * Reporte de movimientos por material
     */
    public function reportePorMaterial(Request $request, int $materialId): View
    {
        $material = Material::findOrFail($materialId);

        $query = Movimiento::where('material_id', $materialId);

        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->porFechas($request->fecha_inicio, $request->fecha_fin);
        }

        $movimientos = $query->orderBy('fecha', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('movimientos.reporte', compact('material', 'movimientos'));
    }
}