<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\SubcategoriaController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\RolController;

// Ruta pública - redirige al login si no está autenticado
Route::get('/', function () {
    return redirect()->route('login');
});

// ⭐ RUTA PARA LIMPIAR CACHÉ ⭐
Route::get('/limpiar', function() {
    \Artisan::call('route:clear');
    \Artisan::call('config:clear');
    \Artisan::call('cache:clear');
    return 'Cache limpiado - ahora prueba /crear-admin-urgente';
});

// ⭐ RUTA TEMPORAL PARA CREAR ADMIN - FUERA DEL MIDDLEWARE ⭐
Route::get('/crear-admin-urgente', function () {
    // Verificar si ya existe
    $existe = \App\Models\User::where('email', 'admin@ejemplo.com')->first();

    if ($existe) {
        // Si existe, solo actualizar la contraseña
        $existe->password = bcrypt('admin123');
        $existe->save();
        return 'Contraseña actualizada correctamente';
    }

    // Si no existe, crear nuevo usuario
    \App\Models\User::create([
        'name' => 'Administrador',
        'apellido' => 'Sistema',
        'ci' => '1234',
        'telefono' => '765432',
        'email' => 'admin@ejemplo.com',
        'password' => bcrypt('admin123'),
        'rol_id' => 1,
    ]);

    return 'Usuario administrador creado exitosamente';
});

// Rutas protegidas - Solo usuarios autenticados pueden acceder
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rutas de Categorías
    Route::resource('categorias', CategoriaController::class);
    Route::get('categorias/{categoria}/subcategorias', [CategoriaController::class, 'subcategorias'])->name('categorias.subcategorias');

    // Rutas de Subcategorías
    Route::get('subcategorias/create/{categoria_id}', [SubcategoriaController::class, 'create'])->name('subcategorias.create');
    Route::post('subcategorias', [SubcategoriaController::class, 'store'])->name('subcategorias.store');
    Route::get('subcategorias/{subcategoria}/edit', [SubcategoriaController::class, 'edit'])->name('subcategorias.edit');
    Route::put('subcategorias/{subcategoria}', [SubcategoriaController::class, 'update'])->name('subcategorias.update');
    Route::delete('subcategorias/{subcategoria}', [SubcategoriaController::class, 'destroy'])->name('subcategorias.destroy');

    // Rutas de Materiales
    Route::get('materiales', [MaterialController::class, 'index'])->name('materiales.index');
    Route::get('materiales/create', [MaterialController::class, 'create'])->name('materiales.create');
    Route::post('materiales', [MaterialController::class, 'store'])->name('materiales.store');
    Route::get('materiales/{material}', [MaterialController::class, 'show'])->name('materiales.show');
    Route::get('materiales/{material}/edit', [MaterialController::class, 'edit'])->name('materiales.edit');
    Route::put('materiales/{material}', [MaterialController::class, 'update'])->name('materiales.update');
    Route::delete('materiales/{material}', [MaterialController::class, 'destroy'])->name('materiales.destroy');

    // Rutas de Movimientos
    Route::get('movimientos', [MovimientoController::class, 'index'])->name('movimientos.index');
    Route::get('movimientos/create', [MovimientoController::class, 'create'])->name('movimientos.create');
    Route::post('movimientos', [MovimientoController::class, 'store'])->name('movimientos.store');
    Route::get('movimientos/{movimiento}', [MovimientoController::class, 'show'])->name('movimientos.show');
    Route::get('movimientos/{movimiento}/edit', [MovimientoController::class, 'edit'])->name('movimientos.edit');
    Route::put('movimientos/{movimiento}', [MovimientoController::class, 'update'])->name('movimientos.update');
    Route::delete('movimientos/{movimiento}', [MovimientoController::class, 'destroy'])->name('movimientos.destroy');
    Route::get('movimientos/reporte/material/{material}', [MovimientoController::class, 'reportePorMaterial'])->name('movimientos.reporte.material');

    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas de Usuarios
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/create', [UsuarioController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/{usuario}', [UsuarioController::class, 'show'])->name('usuarios.show');
    Route::get('/usuarios/{usuario}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

    // Rutas de Roles
    Route::get('/roles', [RolController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RolController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RolController::class, 'store'])->name('roles.store');
    Route::get('/roles/{rol}/edit', [RolController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{rol}', [RolController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{rol}', [RolController::class, 'destroy'])->name('roles.destroy');
    Route::put('/roles/{rol}/activar', [RolController::class, 'activar'])->name('roles.activar');
});

// Rutas de autenticación (login, register, etc.)
require __DIR__ . '/auth.php';